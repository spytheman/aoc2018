#!/usr/bin/env php
<?php
ini_set('memory_limit', '512M');
include("common.php");
$lines = read_input();
$map=[]; $cids=[];
foreach($lines as $line){
    list($cid, $topx,$topy, $w,$h) = line2digits($line);
    $cids[$cid]=0;
    rectangleEach($topx, $topy, $w, $h, function($x,$y) use (&$cids, &$map, &$cid){
        $coord="{$x}x{$y}";
        $map[$coord][]=$cid;
        $tclaimed = $map[$coord];
        if(count($tclaimed)>1) foreach($tclaimed as $alreadyclaimed)unset($cids[$alreadyclaimed]);
    });
}
$omap = ACountAtLeastX($map, 1);
printf("Overlapping tiles of the map: %d\n",count($omap));
printf("Claims with no overlap: %s\n",json_encode(array_keys($cids)));
