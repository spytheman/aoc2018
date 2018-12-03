#!/usr/bin/env php
<?php 
include("common.php");
$numbers = read_input();
$flen = count( $numbers ); $cf = Asum($numbers);
printf("Current frequency after all input: %d | input size: %d\n", $cf, $flen);
////////////////////////////////////////////////////////////////////////////////////////////
$encounters = [0=>1]; $f = 0;
Arepeat( $numbers, function($n, $c) use (&$encounters, &$f){
    $f += $n;
    if(Acontains_key($encounters, $f)){
        printf("First repeat frequency: %d : c=%d\n", $f, $c);
        return false;
    }
    $encounters[$f]=1;
});
