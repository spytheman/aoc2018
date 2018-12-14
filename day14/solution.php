#!/usr/bin/env php
<?php 
include("common.php");
$n = (int) join('', read_input());
$nstart = $n;
$howmanywanted = 10;
$elves=[0,1];
$initialrecipes = "37";
printf("N: %d | Initial recipes: %s\n",$n,$initialrecipes);
$recipes = $initialrecipes; $lastrecipe=strlen($recipes);
function showState($i, $elves, $recipes, $nrecipes){
    printf("%4d | Elves: %-10s | Recipes (%5d): %s\n", $i, ve($elves), $nrecipes, $recipes);
}
$i=0; while(true){
    //showState($i, $elves, $recipes, $lastrecipe);
    $er=[]; foreach($elves as $ee) $er[$ee] = (int) $recipes[$ee]; $s = Asum($er);
    $recipes.=$s;$lastrecipe+=strlen("{$s}");
    foreach($elves as &$e){
        $eforward = $recipes[$e] + 1;
        $e = ($e + $eforward) % $lastrecipe;
    }
    if($lastrecipe > $nstart + $howmanywanted) break;
    if(0 === $i % 100000)printf("Iteration: %8d | lastrecipe: %10d\n",$i, $lastrecipe);
    $i++;
}
//showState($i, $elves, $recipes, $lastrecipe);
$wanted = substr($recipes, $nstart, $howmanywanted);
printf("Wanted %d after %d: %s\n", $howmanywanted, $nstart, $wanted);
