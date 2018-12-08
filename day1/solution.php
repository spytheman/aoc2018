#!/usr/bin/env php
<?php 
include("common.php");
$numbers = read_input(); printf("Part 1 answer (current frequency after all input) is: %d\n", Asum($numbers));
$seen = [0=>1]; $f = 0;
Arepeat( $numbers, function($n, $c) use (&$seen, &$f){
    $f += $n; if(!Ahas_key($seen, $f)){ $seen[$f]=1; return; }
    printf("Part 2 answer (first repeat frequency) is: %d\n", $f); exit();
});
