#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
/// Uses topological_sort/2 from https://stackoverflow.com/questions/11953021/topological-sorting-in-php
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
$result=join("", topological_sort($nodeIDs,$edges));
printf("Part 1 task order should be %s\n", $result);

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
    return $L;
}
