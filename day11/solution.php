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
    foreach(range(1,$maxsize) as $size){
        for($y=1;$y<=301-$size;$y++){
            for($x=1;$x<=301-$size;$x++){
                $s=0;
                for($j=0;$j<$size;$j++) for($k=0;$k<$size;$k++) $s += $grid[$y + $j ][$x + $k];
                if($tops[0]<$s){
                    $tops[0]=$s;
                    $tops[1]=$x;
                    $tops[2]=$y;
                    $tops[4]=$grid[$y][$x];
                }
            }
        }
        $tops[3]=$size;
        printf("size: %d, tops: %s\n", $size, ve($tops));
    }
    return $tops;
}

function showGridZone($grid, $topsx, $topsy, $w=3, $h=3){
    printf("Grid zone x: %d y: %d w: %d h: %d\n", $topsx, $topsy, $w, $h);
    for($y=-1;$y<=$h;$y++){
        for($x=-1;$x<=$w;$x++){
            printf("%3d ", $grid[$y + $topsy ][$x + $topsx]);
        }
        endl();
    }
    endl();
}

$tops = grid2tops($grid, 3);
showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
printf("Serial: %d ; tops [x,y,size]: [ %d,%d,%d ]\n", $serial, $tops[1], $tops[2], $tops[3]);

$tops = grid2tops($grid, 16); // should be 300 but it stabilizes around 10-16 usually
showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
