#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$x=[];$y=[]; 
foreach($lines as $line) sscanf($line, "%d, %d", $x[], $y[]);
$maxx = Amax($x); $maxy = Amax($y); $n=count($x); $coords = range(0,$n-1); 
function process($x,$y,$maxx,$maxy,$n,$coords,$borderwidth=0){
    $g=[0=>[]]; $points=array_fill(0,$n,0);
    foreach(range(-$borderwidth,$maxx+$borderwidth) as $i){
        foreach(range(-$borderwidth,$maxy+$borderwidth) as $j){
            @$g[$i][$j]=0;
            $distances = []; 
            foreach($coords as $z) $distances[$z] = abs($i-$x[$z]) + abs($j-$y[$z]);
            $m = Amin($distances); $mk = Akeyof($distances,$m);
            $mn = count(Afilter($distances, function($d) use ($m) { return $m === $d; }));
            //printf("i,j=$i,$j | m:$m | mk:$mk | mn:$mn | distances: %s\n",ve($distances));
            if($mn>1)continue;
            @$g[$i][$j]=$mk;
            //printf("%4d ", $mk);
            $points[$mk]++;
        }
        //printf("\n");
    }
    return [$points,$g];
}
[$points,$g] = process($x,$y,$maxx,$maxy,$n,$coords,0);
$borderpoints=[];
foreach(range(0,$maxx) as $i) {  $borderpoints[]= $g[$i][0];   $borderpoints[]= $g[$i][$maxy]; }
foreach(range(0,$maxy) as $j) {  $borderpoints[]= $g[0][$j];   $borderpoints[]= $g[$maxx][$j]; }
$borderpoints = array_unique($borderpoints);
$remainingpoints=Afilter($coords, function($z) use( $borderpoints ) { return !Ahas($borderpoints, $z); });
$areas=[]; foreach($remainingpoints as $c) $areas[] = $points[$c];
asort($areas);
printf("Part 1 answer: the size of the largest area that is not infinite is: %d\n", Amax($areas));
