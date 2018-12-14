#!/usr/bin/env php
<?php 
include("common.php");
$n = (int) join('', read_input());
$c = 10; $e=[0,1]; $s = "37"; $ls=strlen($s);
while(true){
    $a = (int) $s[ $e[0] ]; 
    $b = (int) $s[ $e[1] ];
    $sum = $a + $b; $s .= $sum; $ls += strlen("{$sum}");
    $e[0] = ( $e[0] + $a + 1 ) % $ls ; 
    $e[1] = ( $e[1] + $b + 1 ) % $ls ;
    if ( $ls > $n + $c ) break;
}
printf("Part 1 answer (next {$c} recipes) is: %s\n", substr($s, $n, $c));
