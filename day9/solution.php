#!/usr/bin/env php
<?php 
include("common.php");
$lines =  read_input();
$nPlayers=0; $nMarbles=0;
foreach($lines as $line) {
    [$nPlayers, $nMarbles] = line2digits($line);
    if($nMarbles>10000)system(sprintf("echo '%s' | %s/solution", $line, SDIR));
    else line2Highscore($nPlayers, $nMarbles);
}
function line2Highscore($nPlayers, $nMarbles){
    //printf("Calculating highscore for %3d players and %5d marbles ...\n", $nPlayers, $nMarbles);
    $placed = [0];
    $players = Azeros($nPlayers+1);
    $marbles = range(1, $nMarbles);
    $c = 0; $p = 1; $placedLength = count($placed);
    foreach ($marbles as $m) {
        if(0 === ($m % 10000))printf("Placing marble %d.\n", $m);
        if (0 === ($m % 23)) {
            $removedIndex = (($placedLength + $c - 7) % $placedLength) + 1;
            $removed = $placed[$removedIndex];
            //printf("------> M: %d ; Removing %d at index %d\n", $m, $removed, $removedIndex);
            array_splice($placed, $removedIndex, 1);
            $players[$p] += ($m + $removed);
            $c = $removedIndex - 1;
            $placedLength--;
        } else {
            $newC = ($c + 2) % $placedLength;
            array_splice($placed, $newC + 1, 0, $m);
            $c = $newC;
            $placedLength++;
        }
        //printf("p: %2d , c: %2d, m: %2d, placed: %s\n", $p, $c, $m, join(' ', $placed));
        $p++;
        if ($p > $nPlayers) $p = 1;
    }
    //Aprintv($players, "players"); endl();
    $pv = Amax($players);
    printf("Part 1 answer (winning score) is: %d, for nplayers: %d and nmarbles: %d .\n", $pv, $nPlayers, $nMarbles);
    return $pv;
}
