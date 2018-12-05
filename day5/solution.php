#!/usr/bin/env php
<?php 
include("common.php");
$input = join('', read_input());
$small = range('a','z'); $BIG = range('A','Z');
$reactionpairs = Amap(array_merge(Azip2($small,$BIG), Azip2($BIG,$small)), function($x){ return join('', $x); });
function fullReaction(string $input, array $reactionpairs): array {
    $c=0; while(true){
        $c++;
        $oldinput = $input;
        $input = str_replace($reactionpairs, '', $oldinput);
        if(strlen($input) === strlen($oldinput)) break;
    }
    return [$c, $input];
}
$part1 = fullReaction($input, $reactionpairs);
printf("Part 1: reactions: %d | remaining units = answer = %d\n", $part1[0], strlen($part1[1]));

$part2results = [];
foreach($reactionpairs as $pair){
    $cleanedinput = str_replace(line2array($pair), '', $input);
    //printf("pair: %s, ilen: %d, cleanedlen: %d\n", $pair, strlen($input),strlen($cleanedinput));
    [$c, $reactresult] = fullReaction($cleanedinput, $reactionpairs);
    $part2results[$pair] = strlen($reactresult);
}
$part2 = Amin($part2results);
$thepair = Akeyof($part2results, $part2);
printf("Part 2: the length of the shortest polymer after removing all units of exactly one type: '%s' is: %d\n", $thepair, $part2);
