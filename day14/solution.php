#!/usr/bin/env php
<?php 
include("common.php");
$n = (int) join('', read_input());
$nstart = $n;
$howmanywanted = 10;
$elves=[0,1];
$initialrecipes = [3,7];
printf("N: %d | Initial recipes: %s\n",$n,ve($initialrecipes));
$recipes = Azeros($n+2*$howmanywanted); $lastrecipe = 0;
foreach($initialrecipes as $k=>$x) { $recipes[ $lastrecipe ] = $x; $lastrecipe++; }
function showState($i, $elves, $recipes, $nrecipes){
    printf("%4d | Elves: %10s | Recipes (%5d): %s\n", $i, ve($elves), $nrecipes, ve($recipes));
}
$i=0; while(true){
    showState($i, $elves, $recipes, $lastrecipe);
    $er=[]; foreach($elves as $ee) $er[$ee] = $recipes[$ee];
    $esum = Acast2ints(line2array("".Asum($er)));
    foreach($esum as $d){  $recipes[ $lastrecipe ] = $d; $lastrecipe++; }
    foreach($elves as &$e){
        $eforward = $recipes[$e] + 1;
        $e = ($e + $eforward) % $lastrecipe;
    }
    if($lastrecipe > $nstart + $howmanywanted) break;
    if(0 === $i % 100000)printf("Iteration: %8d | lastrecipe: %10d\n",$i, $lastrecipe);
    $i++;
}
//showState($i, $elves, $recipes, $lastrecipe);
$wanted = Apart($recipes, $nstart, $howmanywanted);
printf("Wanted %d after %d: %s\n", $howmanywanted, $nstart, join('',$wanted));
