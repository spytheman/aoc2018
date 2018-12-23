#!/usr/bin/env php
<?php 
include("common.php");

$robots = Amap(read_input(), function($line){ return line2digits($line); });
printRobots("Ordinary", $robots);

$sortedrobots = $robots;
usort($sortedrobots, function($a, $b){ return $b[3]<=>$a[3]; });

$otherrobots = $sortedrobots;
$maxrobot = array_shift($otherrobots);
printRobots("Maximum", [$maxrobot]);

$inrangerobots = Afilter($robots, function($x) use ( $maxrobot ):bool { return manhatanDistance($x, $maxrobot) <= $maxrobot[3]; });
printRobots("Inrange",$inrangerobots);
printf("Part 1 answer(robots in range) is: %d\n", count($inrangerobots));

function printRobots(string $name, array $robots){ Amap($robots, function($x) use ($name) { printf("%s robot: %s\n", $name, ve($x)); }); }
function manhatanDistance(array $a, array $b): int { return abs($a[0]-$b[0]) + abs($a[1]-$b[1]) + abs($a[2]-$b[2]) ; }
