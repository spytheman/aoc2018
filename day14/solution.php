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
$i=0; while(true){
    $a = (int) $recipes[ $elves[0] ]; 
    $b = (int) $recipes[ $elves[1] ];
    $s = $a + $b; $recipes .= $s; $lastrecipe += strlen("{$s}");
    $elves[0] = ( $elves[0] + $a + 1 ) % $lastrecipe ;
    $elves[1] = ( $elves[1] + $b + 1 ) % $lastrecipe ;
    if ( $lastrecipe > $nstart + $howmanywanted ) break;
    $i++;
}
$wanted = substr($recipes, $nstart, $howmanywanted);
printf("Wanted %d after %d: %s\n", $howmanywanted, $nstart, $wanted);
