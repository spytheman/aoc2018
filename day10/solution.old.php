#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$s=(@$argv[2])>0?$argv[2]:10003;
$points = [];
$c=0;
foreach($lines as $line) $points[]=line2digits($line);
/// the constants here are produced by tweaking, just so that the apropriate area of the grid is shown
/// I knew what to seek for, by abusing a spreadsheet as a ploter :-)
/// See https://docs.google.com/spreadsheets/d/1orToWeVnJ4X8fV4riD4lkM0gjJgEQerQ6bQK0tEE1G0/edit?usp=sharing
$gsize = 600; $mx = 500; $my = 200; $opx = 420; $opy = 50;
$hgsize = $gsize/2; $sx=$gsize; $sy=$gsize; $cx=$sx/$mx; $cy=$sy/$my;
while(true){
    printf("s: %5d | mx: %d, my: %d | cx:%f | cy:%f\n", $s, $mx,$my, $cx,$cy);
    $grid=Azeros($sx*$sy);
    foreach($points as &$p){
        $px = $p[0] + $s*$p[2]; $py = $p[1] + $s*$p[3];
        $spx = round($hgsize+$cx*($px - $opx));
        $spy = round($hgsize+$cy*($py - $opy));
        $grid[$spy*$sx + $spx]=1;
    }
    $slines = [];
    for($y=0;$y<$sy;$y++){
        $sline = []; $slinefiled = false; 
        for($x=0;$x<$sx;$x++){
            $c=$grid[$y*$sx+$x] ? '#' : ' ';
            $sline[]=$c; 
            if($c==='#')$slinefiled=true;
        }
        if($slinefiled){
            $slines[$y]=$sline;
            $line = rtrim(join('', $sline));
            printf("sy: %5d | line: %s\n", $y, $line);
        }
    }
    $s++;
    exit();
}
