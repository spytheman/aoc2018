#!/usr/bin/env php
<?php 
include("common.php");
$numbers = read_input(); $cf = Asum($numbers);
printf("Current frequency after all input: %d \n", $cf);

$seen = [0=>1]; $f = 0;
Arepeat( $numbers, function($n, $c) use (&$seen, &$f){
    $f += $n; if(!Ahas_key($seen, $f)){ $seen[$f]=1; return; }
    printf("First repeat frequency: %d : c=%d\n", $f, $c); exit();
});
