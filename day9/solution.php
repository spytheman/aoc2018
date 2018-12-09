#!/usr/bin/env php
<?php 
include("common.php");
$lines =  read_input();
$nPlayers=0; $nMarbles=0;
foreach($lines as $line) {
    [$nPlayers, $nMarbles] = line2digits($line);
    printf("Part 1 ");game("Part 1", $nPlayers, $nMarbles);
    printf("Part 2 ");game("Part 2", $nPlayers, $nMarbles*100);
}
function game(string $label, int $nPlayers, int $nMarbles): int {
    if($nMarbles>9000){
        printf("!!!!!!! marbles: %d are over 9000. Try instead 'solution.deque.php' or the C based 'solution' .\n",$nMarbles);
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
    printf("%s Highscore (for %5d players and %8d marbles) is: %18d \n", $label, $nPlayers, $nMarbles, $pv);
    return $pv;
}
