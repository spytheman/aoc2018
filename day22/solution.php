#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input(); $depth  = 0; $tx=0; $ty=0;
foreach($lines as $line){
    if(strpos($line, 'depth:')===0) sscanf($line, 'depth: %d', $depth);
    if(strpos($line, 'target:')===0) sscanf($line, 'target: %d, %d', $tx, $ty);
}
printf("Depth: %4d, Target: x:%4d y:%4d\n", $depth, $tx, $ty);
$ag  = A2Dnew($tx,$ty);
$agi = A2Dnew($tx,$ty);
$age = A2Dnew($tx,$ty);
for($y=0;$y<=$ty;$y++){
    for($x=0;$x<=$tx;$x++){
        if( ($tx === $x && $ty === $y) || (0 === $x && 0 === $y) ) $gi = 0;
        else if(0 === $y )$gi = $x * 16807;
        else if(0 === $x )$gi = $y * 48271;
        else $gi = $age[$y][$x-1] * $age[$y-1][$x];
        $ge = ($gi + $depth) % 20183;
        $ag[$y][$x]  = [0=>'.', 1=>'=', 2=>'|'][$ge % 3];
        $agi[$y][$x] = $gi;
        $age[$y][$x] = $ge;
    }
}
$ag[0][0]='M'; $ag[$ty][$tx]='T';
showGridZone($ag, 0,0, $tx+1, $ty+1, 1);
