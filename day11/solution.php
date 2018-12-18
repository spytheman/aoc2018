#!/usr/bin/env php
<?php 
include("common.php");
$serial = (int) join('', read_input());
if($argc>2) $serial = (int) $argv[2];
$grid = A2Dnew(300,300,0);
for($y=1;$y<=300;$y++){
    for($x=1;$x<=300;$x++){
        $rid = $x + 10;
        $power = ((($rid * $y) + $serial) * $rid); 
        $d = 0; if($power>100) $d = (int) strrev("{$power}")[2];
        $grid[$y][$x]=$d - 5;
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////
// NB: This solution now uses a precalculated partial sums/integrated image.
//     This speeds up the calculation of areas in the grid2tops function below.
//     The technique is described at https://en.wikipedia.org/wiki/Summed-area_table.
//     The precalculation here is O(maxsize*maxsize), but is done just *once* for the whole grid 
//     The time cost is ~50ms in pure PHP, for a grid 300x300. The speedup is over 20x ...
/////////////////////////////////////////////////////////////////////////////////////////////////
printf("Precalculating isums for serial: %d ...\n", $serial);
$isums = $grid; for($y=1;$y<=300;$y++) for($x=1;$x<=300;$x++) $isums[$y][$x] = $isums[$y][$x] + $isums[$y-1][$x] + $isums[$y][$x-1] - $isums[$y-1][$x-1];
printf("Precalculating isums for serial: %d ... done\n", $serial);

function grid2tops($grid, $maxsize=3){
    global $isums;
    $tops=[0,0,0,0,0];
    foreach(range(1,$maxsize) as $size){
        for($y=1;$y<=300-$size;$y++){
            $ys=$y+$size; 
            for($x=1;$x<=300-$size;$x++){
                $xs=$x+$size;
                
                //  Using the precalculated integrated image here is O(constant) :-) ...
                $s = $isums[$ys][$xs] - $isums[$y][$xs] - $isums[$ys][$x] + $isums[$y][$x];
                
                // The naive solution commented below, produces the same, but is O(size*size):
                // $s=0; for($j=0;$j<$size;$j++) for($k=0;$k<$size;$k++) $s += $grid[$y + $j ][$x + $k];
                
                if($tops[0]<$s){
                    $tops[0]=$s;
                    $tops[1]=$x+1;
                    $tops[2]=$y+1;
                    $tops[3]=$size;
                    $tops[4]=$grid[$y][$x];
                }
            }
        }
        if( $size % 50 === 0 ) printf("size: %3d, tops: %s\n", $size, ve($tops));
    }
    return $tops;
}

$tops = grid2tops($grid, 3);
printf("Part 1 answer: %d,%d\n", $tops[1], $tops[2]);
//showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
//printf("\n");

$tops = grid2tops($grid, 300);
printf("Part 2 answer: %d,%d,%d\n", $tops[1], $tops[2], $tops[3]);
//showGridZone($grid, $tops[1], $tops[2], $tops[3], $tops[3]);
//printf("\n");
