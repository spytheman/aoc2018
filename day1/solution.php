#!/usr/bin/env php
<?php include("common.php");
$numbers = read_input();
//$numbers = explode(", ", "+1, -1");                // 0
//$numbers = explode(", ", "+1, -2, +3, +1");        // 2
//$numbers = explode(", ", "+3, +3, +4, -2, -4");    // 10
//$numbers = explode(", ", "-6, +3, +8, +5, -6");    // 5
//$numbers = explode(", ", "+7, +7, -2, -7, -4");    // 14
$flen = count( $numbers ); $cf = Asum($numbers);
printf("Current frequency after all input: %d | input size: %d\n", $cf, $flen);
////////////////////////////////////////////////////////////////////////////////////////////
$encounters = [0=>1]; $f = 0;
Aloopover( $numbers, function($n, $c) use (&$encounters, &$f){
    $f += $n;
    //printf("   c:%6d   n:%6d  f: %6d  \n", $c, $n, $f);
    if( !array_key_exists($f, $encounters) ){
        $encounters[$f]=1;
    }else{
        printf("First repeat frequency: %d : c=%d\n", $f, $c);
        return false;
    }
});
