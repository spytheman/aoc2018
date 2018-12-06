#!/usr/bin/env php
<?php
include("common.php");
$lines = read_input();
$x=[];$y=[];
foreach($lines as $line) sscanf($line, "%d, %d", $x[], $y[]);
$maxx = Amax($x); $maxy = Amax($y); $n=count($x); $coords = range(0,$n-1);
$d=[0=>[]]; $g=[0=>[]]; $areas=array_fill(0,$n,0);
foreach(range(0,$maxx) as $i){
    foreach(range(0,$maxy) as $j){
        @$g[$i][$j]=0;
        $distances = [];
        foreach($coords as $z) $distances[$z] = abs($i-$x[$z]) + abs($j-$y[$z]);
        [$mk,$m] = Aminkv($distances);
        @$d[$i][$j]=Asum($distances);
        $mn = Ahistogram($distances)[$m];
        //printf("i,j=$i,$j | m:$m | mk:$mk | mn:$mn | distances: %s\n",ve($distances));
        if($mn>1)continue;
        @$g[$i][$j]=$mk;
        $areas[$mk]++;
    }
}
$borderareas=[];
foreach(range(0,$maxx) as $i) {  $borderareas[]= $g[$i][0];   $borderareas[]= $g[$i][$maxy]; }
foreach(range(0,$maxy) as $j) {  $borderareas[]= $g[0][$j];   $borderareas[]= $g[$maxx][$j]; }
Aunsetkeys($areas, array_unique($borderareas));
asort($areas);
printf("Part 1 answer: the size of the largest area that is not infinite is: %d\n", Amax($areas));

$size=0; foreach(range(0,$maxx) as $i) foreach(range(0,$maxy) as $j) if($d[$i][$j]<10000) $size++;
printf("Part 2 answer: the size of the region containing all locations which have a total distance to all given coordinates of less than 10000 is: %d \n", $size);
