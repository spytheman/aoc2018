#!/usr/bin/env php
<?php 
include("common.php");
$lines =  read_input();
foreach($lines as $line) line2Highscore($line);
function line2Highscore($line){
    [$nPlayers, $nMarbles] = line2digits($line);
    $placed = [0];
    $players = Azeros($nPlayers+1);
    $marbles = range(1, $nMarbles);
    $c = 0; $p = 1;
    foreach ($marbles as $m) {
        if(0 === ($m % 1000))printf("Placing marble %d.\n", $m);
        $placedLength = count($placed);
        if (0 === ($m % 23)) {
            $removedIndex = (($placedLength + $c - 7) % $placedLength) + 1;
            $removed = $placed[$removedIndex];
            //printf("------> M: %d ; Removing %d at index %d\n", $m, $removed, $removedIndex);
            array_splice($placed, $removedIndex, 1);
            $players[$p] += ($m + $removed);
            $c = $removedIndex - 1;
        } else {
            $newC = ($c + 2) % $placedLength;
            array_splice($placed, $newC + 1, 0, $m);
            $c = $newC;
        }
        //printf("p: %2d , c: %2d, m: %2d, placed: %s\n", $p, $c, $m, join(' ', $placed));
        $p++;
        if ($p > $nPlayers) $p = 1;
    }
    //Aprintv($players, "players"); endl();
    $pv = Amax($players);
    printf("Part 1 answer (winning score) is: %d, for line: '%s'\n", $pv, $line);
    return $pv;
}
