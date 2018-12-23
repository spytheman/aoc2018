#!/usr/bin/env php
<?php 
include("common.php");

$robots = Amap(read_input(), function($line){ return line2digits($line); });
//printRobots("Ordinary", $robots);

$sortedrobots = $robots;
usort($sortedrobots, function($a, $b){ return $b[3]<=>$a[3]; });

$otherrobots = $sortedrobots;
$maxrobot = array_shift($otherrobots);
//printRobots("Maximum", [$maxrobot]);

$inrangerobots = Afilter($robots, function($x) use ( $maxrobot ):bool { return manhatanDistance($x, $maxrobot) <= $maxrobot[3]; });
//printRobots("Inrange",$inrangerobots);
printf("Part 1 answer(robots in range) is: %d\n", count($inrangerobots));

function printRobots(string $name, array $robots){ Amap($robots, function($x) use ($name) { printf("%s robot: %s\n", $name, ve($x)); }); }
function manhatanDistance(array $a, array $b): int { return abs($a[0]-$b[0]) + abs($a[1]-$b[1]) + abs($a[2]-$b[2]) ; }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$clines=[]; 
$clines[] = "
(declare-const x Int)
(declare-const y Int)
(declare-const z Int)

(define-fun dist ((x1 Int) (y1 Int) (z1 Int) (x2 Int) (y2 Int) (z2 Int)) Int
  (+ (abs (- x2 x1))
     (abs (- y2 y1))
     (abs (- z2 z1))))       
";

$clines[] = "(define-fun inrange ((x Int) (y Int) (z Int)) Int (+ \n";
foreach($robots as $r) $clines[] = sprintf("  (if (<= (dist x y z %s %s %s) %s) 1 0) \n", $r[0],$r[1],$r[2],$r[3] );
$clines[] = "  ))\n";

$clines[] = "
(maximize (inrange x y z))
(minimize (dist 0 0 0 x y z))
(check-sat)
(get-objectives)
(get-model)
";

$z3filename = get_input_filename().'.z3';
file_put_contents($z3filename, join('', $clines));
printf("Written Z3 file %s .\n", $z3filename);
printf("Now run 'z3 %s' and get the answer.\n", $z3filename);
system(sprintf("time z3 %s", $z3filename));
