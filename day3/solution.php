#!/usr/bin/env php
<?php
ini_set('memory_limit', '512M');
include("common.php");
$lines = read_input();
$map=[]; $cids=[];
foreach($lines as $line){
    if(preg_match_all("/\d+/",$line,$b)){
        list($cid, $topx,$topy, $w,$h) = $b[0];
        $cids[$cid]=0;
        for($x=$topx;$x<$topx+$w;$x++){
            for($y=$topy;$y<$topy+$h;$y++){
                $coord="{$x}x{$y}";
                $map[$coord][]=$cid;
                if(count($map[$coord])>1){
                    foreach($map[$coord] as $alreadyclaimed)unset($cids[$alreadyclaimed]);
                }
            }
        }
    }
}
$omap = array_filter($map, function($cids){ return count($cids)>1; });
printf("Overlapping tiles of the map: %d\n",count($omap));
printf("Claims with no overlap: %s\n",json_encode($cids));
