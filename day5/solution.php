#!/usr/bin/env php
<?php 
include("common.php");
$input = join('', read_input());
$small = range('a','z');
$BIG = range('A','Z');
$reactionunits = Amap(array_merge(Azip2($small,$BIG), Azip2($BIG,$small)), function($x){ return join('', $x); });
$c=0;
while(true){
    $c++;
    $oldinput = $input;
    $input = str_replace($reactionunits, '', $oldinput);
    if(strlen($input) === strlen($oldinput)) break;
}
printf("Reactions: %d | Remaining units: %d\n", $c, strlen($input));
