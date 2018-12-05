#!/usr/bin/env php
<?php 
include("common.php");
$input = join('', read_input());
$small = range('a','z'); $BIG = range('A','Z');
$reactionpairs = Amap(array_merge(Azip2($small,$BIG), Azip2($BIG,$small)), function($x){ return join('', $x); });
$c=0; while(true){
    $c++;
    $oldinput = $input;
    $input = str_replace($reactionpairs, '', $oldinput);
    if(strlen($input) === strlen($oldinput)) break;
}
printf("Part 1: reactions: %d | remaining units = answer = %d\n", $c, strlen($input));
