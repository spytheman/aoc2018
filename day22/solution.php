#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input(); $depth  = 0; $tx=0; $ty=0;
foreach($lines as $line){
    if(strpos($line, 'depth:')===0) sscanf($line, 'depth: %d', $depth);
    if(strpos($line, 'target:')===0) sscanf($line, 'target: %d, %d', $tx, $ty);
}
printf("Depth: %4d, Target: x:%4d y:%4d\n", $depth, $tx, $ty);
$g = A2Dnew($tx,$ty); showGridZone($g,0,0,$tx+1,$ty+1,1);
