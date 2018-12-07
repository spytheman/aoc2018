#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
define('TASK_WORKER_COUNT', 5); // should be 5 in production
define('TASK_MIN_DURATION', 60); // should be 60 in production

$c=0;
$nodeIDs = []; $edges = [];
foreach($lines as $line){
    //printf("%04d: %s\n", $c, $line);
    if(preg_match_all("/Step (.) must be finished before step (.) can begin/", $line, $b)){
        [$s,$e]=[$b[1][0], $b[2][0]];
        //printf("%s %s\n", $s, $e);
        $nodeIDs[]=$s;
        $nodeIDs[]=$e;
        $edges[]=[$s,$e];
    }
    $c++;
}
$nodeIDs=array_unique($nodeIDs); sort($nodeIDs);
printf("nodeIDs: %s\n", ve($nodeIDs));
printf("edges: %s\n", ve($edges));
[$tasks,$taskBlocks]=topological_sort($nodeIDs,$edges);
printf("Part 1 task order should be %s\n", join("", $tasks));

$times = array_flip($nodeIDs);
foreach($times as $k=>&$v)$v+=TASK_MIN_DURATION;

$nWorkers = TASK_WORKER_COUNT; $workers=[]; for($i=0; $i<$nWorkers; $i++) $workers[$i]=['.', 0];

function Aprintv(array $a, string $aname='A'){ printf("| %s: [%s] ", $aname, join(',', $a)); }
function newline(){ echo "\n";}

$t=-1; $done=[]; $doing = []; $uncompleted = $tasks;

while(true){
    if(count($uncompleted)===0)break;
    if($t>99999)break;
    $t++;
    $runningWorkers = 0;
    foreach($workers as $wi=>&$worker){
        $unblockedTasks = array_diff(Akeys(array_filter($taskBlocks, function($x){ return count($x)===0; })), $doing); $remaining = $unblockedTasks; $nRemainingTasks = count($remaining);
        //printf("worker %3d | unblockedTasks: %2d, [%s] .\n", $wi, count($unblockedTasks), join("",$unblockedTasks));

        $cTask = $worker[0];
        if($worker[1]===0){
            if($cTask!=='.'){
                //printf(">>> W%d FINISHED task %s .\n", $wi, $cTask);
                unset($taskBlocks[ $cTask ]); foreach($taskBlocks as &$tlist) $tlist = array_diff($tlist, [$cTask]);
                $done["t{$t}w{$wi}"] = $cTask;
                $doing = array_filter($doing, function($e) use($cTask) { return ($cTask !== $e);} );
                $uncompleted = array_filter($uncompleted, function($e) use ($cTask) { return $cTask !== $e; } );
                $worker = ['.', 0];
                $unblockedTasks = array_diff(Akeys(array_filter($taskBlocks, function($x){ return count($x)===0; })), $doing); $remaining = $unblockedTasks; $nRemainingTasks = count($remaining);
            }
            if($nRemainingTasks>0){
                $cTask = array_shift($remaining); $nRemainingTasks--;
                //printf(">>> W%d STARTED task %s .\n", $wi, $cTask);
                $worker[0] = $cTask;
                $worker[1] = $times[$cTask];
                $doing["w{$wi}"]=$cTask;
                $runningWorkers++;
            }else{
                $worker = ['.', 0];
            }
        }else{
            $worker[1]--;
            $runningWorkers++;
        }
    }
    /*
    printf("t: %3d | RW: %d | workers: [%18s] | doing: [%25s] | done: [%20s]| uncompleted: [%10s] | unblocked: [%s] \n",
        $t, $runningWorkers, ve($workers), ve($doing), ve($done), join('', $uncompleted), ve($unblockedTasks));
    */
    printf("t: %3d | RW: %d | workers: [ %6s ] | done: [ %-10s ]\n", $t, $runningWorkers, join('', Acolumn($workers,0)), join('',Avals($done)));
}
printf("Part 2 total time: %d\n",$t);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Uses topological_sort/2 from https://stackoverflow.com/questions/11953021/topological-sorting-in-php

function topological_sort(array $nodeIDs, array $edges): array {
    $L = $S = $nodes = array();

    // remove duplicate nodes
    $nodeIDs= array_unique($nodeIDs);
    // remove duplicate edges
    $hashes = array(); foreach($edges as $k=>$e) {
        $hash = md5(serialize($e));
        if (in_array($hash, $hashes)) { unset($edges[$k]); }
        else { $hashes[] = $hash; };
    }

    foreach($nodeIDs as $id) {
        $nodes[$id] = array('in'=>array(), 'out'=>array());
        foreach($edges as $e) {
            if ($id==$e[1]) { $nodes[$id]['in'][]=$e[0]; }
            if ($id==$e[0]) { $nodes[$id]['out'][]=$e[1]; }
        }
    }
    function blockedBy($nodes, $nid){
        $in = $input = $nodes[$nid]['in'];
        if(count($in) === 0) return [];
        $result = $in; foreach( $in as $pid ) {
            $result = array_merge($result, blockedBy($nodes, $pid));
        }
        return array_unique($result);
    }
    $blocks = []; foreach($nodeIDs as $id) $blocks[ $id ] = blockedBy($nodes, $id);
    foreach ($nodes as $id=>$n) { if (empty($n['in'])) $S[]=$id; }
    while (!empty($S)) {
        sort($S);
        $L[] = $id = array_shift($S);
        foreach($nodes[$id]['out'] as $m) {
            $nodes[$m]['in'] = array_diff($nodes[$m]['in'], array($id));
            if (empty($nodes[$m]['in'])) { $S[] = $m; }
        }
        $nodes[$id]['out'] = array();
    }
    foreach($nodes as $n) {
        if (!empty($n['in']) or !empty($n['out'])) {
            return null; // not sortable as graph is cyclic
        }
    }
    return [$L, $blocks];
}
