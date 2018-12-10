#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$points = []; foreach($lines as $line) $points[]=line2digits($line);

$minwh = 999999; $grid = [];
foreach(range(0,11000) as $step){
    $xpoints = []; $ypoints = [];
    foreach($points as $p) {
        $xpoints[]= $p[0] + $step*$p[2]; 
        $ypoints[]= $p[1] + $step*$p[3];
    }
    $xmin = Amin($xpoints)-2; $xmax = Amax($xpoints)+2;
    $ymin = Amin($ypoints)-2; $ymax = Amax($ypoints)+2;
    $w = ($xmax - $xmin); $h = ($ymax - $ymin);
    $wh = $w*$h;
    if($w>80 || $h>80) continue;
    if($wh>$minwh)break;    
    $minwh = $wh;
    printf("Seconds: %5d , width: %d , height: %d , xmin: %d, ymin: %d, xmax: %d, ymax: %d, wh: %d\n", 
           $step, $w, $h, $xmin, $ymin, $xmax, $ymax, $wh);
    $grid = array_fill(0,$h+1,[]); for($y=0;$y<=$h;$y++) for($x=0;$x<=$w;$x++) @$grid[$y][$x]='.';
    foreach($xpoints as $k=>$x) $grid[ $ypoints[$k] - $ymin ] [ $x - $xmin ] = '#';
}
foreach($grid as $gx) echo join('',$gx)."\n";
