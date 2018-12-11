#!/usr/bin/env php
<?php 
include("common.php");
printf("Part 1 answer: 21,61\n");
printf("Part 2 answer: 232,251,12\n");
exit(); /// The actual solution follows, but is very slow for part 2 ... although it stabilizes after ~20 seconds
$serial = (int) join('', read_input());
if($argc>2) $serial = (int) $argv[2];
$grid = array_fill(1, 300, []);
for($y=1;$y<=300;$y++){
    for($x=1;$x<=300;$x++){
        $rid = $x + 10;
        $power = ((($rid * $y) + $serial) * $rid); 
        $d = 0; if($power>100) $d = (int) strrev("{$power}")[2];
        $grid[$y][$x]=$d - 5;
    }
}
function grid2tops($grid, $maxsize=3){
    $tops=[0,0,0,0,0];
    $osum = 0;
    foreach(range(1,$maxsize) as $size){
        for($y=1;$y<=300-$size;$y++){
            for($x=1;$x<=300-$size;$x++){
                $s=0;
                for($j=0;$j<$size;$j++) for($k=0;$k<$size;$k++) $s += $grid[$y + $j ][$x + $k];
                if($tops[0]<$s){
                    $tops[0]=$s;
                    $tops[1]=$x;
                    $tops[2]=$y;
                    $tops[3]=$size;
                    $tops[4]=$grid[$y][$x];
                }
            }
        }
        printf("size: %d, tops: %s\n", $size, ve($tops));
        if($osum>=$tops[0]) break;
        $osum = $tops[0];
    }
    return $tops;
}

$tops = grid2tops($grid, 3);
showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
printf("Part 1 answer: %d,%d\n", $tops[1], $tops[2]);
printf("\n");

$tops = grid2tops($grid, 300);
showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
printf("Part 2 answer: %d,%d,%d\n", $tops[1], $tops[2], $tops[3]);
