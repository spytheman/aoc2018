#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
foreach($lines as $line){
    [$nPlayers, $nMarbles] = line2digits($line);
    game("Part 1", $nPlayers, $nMarbles);
    game("Part 2", $nPlayers, 100 * $nMarbles);
}
function game($label='', int $nPlayers, int $nMarbles){
    $player = 0;
    $scores = [];
    $circle = new \Ds\Deque([0]);
    foreach(range(1, $nMarbles) as $m){
        if(0===$m%23){
            $circle->rotate(-7);
            $points = $circle->pop() + $m;
            @$scores[$player] += $points;
            $circle->rotate(1);
        }else{
            $circle->rotate(1);
            $circle->push($m);
        }
        $player = ($player+1) % $nPlayers;
    }
    printf("%s Highscore (for %5d players and %8d marbles) is: %18d \n", $label, $nPlayers, $nMarbles, Amax($scores));               
}
