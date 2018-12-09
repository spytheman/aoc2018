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
    // The code here is a nearly direct port of the wonderful Python deque solution at:
    // https://www.reddit.com/r/adventofcode/comments/a4i97s/2018_day_9_solutions/ebepyc7/
    $player = 0;
    $scores = [];
    $circle = new \Ds\Deque([0]);
    foreach(range(1, $nMarbles) as $m){
        if(0===$m%23){
            $circle->rotate(7);
            $points = $circle->shift() + $m;
            @$scores[$player] += $points;
            $circle->rotate(-1);
        }else{
            $circle->rotate(-1);
            $circle->unshift($m);
        }
        $player = ($player+1) % $nPlayers;
    }
    printf("%s Highscore (for %5d players and %8d marbles) is: %18d \n", $label, $nPlayers, $nMarbles, Amax($scores));               
}
