#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input(); 
$allConstelations=Amap($lines, function($line){ return [line2digits($line)]; });
$comparisons = 0; while(true){
    $allConstelations = array_values($allConstelations); $nc = count($allConstelations);
    if(0 === $comparisons || 0===$nc%50) printf("comparisons so far: %10d | nConstelations: %10d \n", $comparisons, $nc);
    for($i=0;$i<$nc;$i++){
        $c1=@$allConstelations[$i];
        if(!$c1)continue;
        for($j=$i+1;$j<$nc;$j++){
            $c2=@$allConstelations[$j];
            if(!$c2)continue;
            $comparisons++;
            if( canConstelationsMerge( $c1, $c2 ) ){
                unset($allConstelations[$i],$allConstelations[$j]);
                $allConstelations[]=array_merge($c1, $c2);
                continue 3;
            }
        }
    }
    printf(">>>>>> Final comparisons: %10d | nConstelations: %10d \n", $comparisons, $nc);
    break;
}
$allConstelations = array_values($allConstelations);
foreach($allConstelations as $k=>$c) printf("Constelation %5d = %s\n", $k, ve($c));
printf("Part 1 answer (number of constelations) is: %d\n", count($allConstelations));
/////////////////////////////////////////////////////////////////////
function manh($p1,$p2){return abs($p1[0]-$p2[0])+abs($p1[1]-$p2[1])+abs($p1[2]-$p2[2])+abs($p1[3]-$p2[3]);}
function pointInConstelation(array $p, array $c){
    if(count($c)===0) return true; 
    foreach($c as $cp) if(manh($p,$cp)<=3) return true; 
}
function canConstelationsMerge(array $c1, array $c2){
    static $canMergeCache = [];
    $k = ve([$c1, $c2]);
    if(!isset($canMergeCache[$k])) $canMergeCache[$k]=_canConstelationsMerge($c1,$c2);
    return $canMergeCache[$k];
}
function _canConstelationsMerge(array $c1, array $c2){ 
    foreach($c1 as $p1) if(pointInConstelation($p1, $c2)) return true; 
    return false; 
}
