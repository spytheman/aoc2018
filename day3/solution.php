#!/usr/bin/env php
<?php
include("common.php");
$lines = read_input();
$map=[]; $cids=[];
foreach($lines as $line){
    list($cid,$topx,$topy,$w,$h)=line2digits($line);
    $cids[$cid]=0;
    $maxx = $topx + $w;
    $maxy = $topy + $h;
    foreach(range($topx, $maxx-1) as $x){
        foreach(range($topy, $maxy-1) as $y){
            $coord=2048*$x+$y;
            $map[$coord][]=$cid;
            $tclaimed=$map[$coord];
            if(count($tclaimed)>1)Aunsetkeys($cids,$tclaimed);
        }
    }
}
$omap = ACountAtLeastX($map,1);
printf("Overlapping tiles of the map: %d\n",count($omap));
printf("Claims with no overlap: %s\n",ve(Akeys($cids)));
