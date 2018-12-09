#!/usr/bin/env php
<?php 
include("common.php");
$lines =  read_input();
$nPlayers=0; $nMarbles=0;
foreach($lines as $line) {
    [$nPlayers, $nMarbles] = line2digits($line);
    printf("Part 1 answer ");game($nPlayers, $nMarbles);
    printf("Part 2 answer ");game($nPlayers, $nMarbles*100);
}
function game(int $nPlayers, int $nMarbles): int {
    if($nMarbles>10000){
        system(sprintf("echo '%d players; last marble is worth %d points' | %s/solution", $nPlayers, $nMarbles, SDIR));
        return 0;
    }
    $placed = [0];
    $players = Azeros($nPlayers+1);
    $marbles = range(1, $nMarbles);
    $c = 0; $p = 1; $placedLength = count($placed);
    foreach ($marbles as $m) {
        if(0 === ($m % 10000))printf("Placing marble %d.\n", $m);
        if (0 === ($m % 23)) {
            $removedIndex = (($placedLength + $c - 7) % $placedLength) + 1;
            $removed = $placed[$removedIndex];
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
        $p++;
        if ($p > $nPlayers) $p = 1;
    }
    $pv = Amax($players);
    printf("Highscore (for  %d players and %d marbles) is: %d\n", $nPlayers, $nMarbles, $pv);
    return $pv;
}
