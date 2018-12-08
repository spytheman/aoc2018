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
printf("Part 1 answer (overlapping tiles of the map) is: %d\n",count($omap));
printf("Part 2 answer (claims with no overlap): %s\n",Afirst(Akeys($cids)));
