#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$maxx = Amax(Amap($lines, function($l){ return strlen($l); })); $maxy = count($lines);
printf("Longest: x: %d y: %d\n",$maxx, $maxy);
$cars=[];
$g=array_fill(0,$maxy,[]);for($y=0;$y<$maxy;$y++)$g[$y]=array_fill(0,$maxx,' ');
$y=0;
for($y=0;$y<$maxy;$y++){
    $line = $lines[$y]; $nline = strlen($line);
    for($x=0;$x<$nline;$x++) {
        $c = $line[$x];
        if($c==='>'||$c==='<'||$c==='v'||$c==='^') $cars[]=[$c,[$x,$y],0];
        $g[$y][$x]=$c;
    }
}
$tracks=array_fill(0,$maxy,[]); 
for($y=0;$y<$maxy;$y++) for($x=0;$x<$maxx;$x++) $tracks[$y][$x] = str_replace(['<','>','v','^'], '.', $g[$y][$x]);
//showGridZone($g,0,0,$maxx, $maxy,1);
//showGridZone($tracks,0,0,$maxx, $maxy,1);

function move(array $tracks, array &$cars, int $maxx, int $maxy): bool  {
    static $move=0; $move++;
    
    $c2v=['>'=>[1,0],'<'=>[-1,0],'v'=>[0,1],'^'=>[0,-1]];
    $cturns=['/^'=>'>','/<'=>'v','/>'=>'^','/v'=>'<','\\^'=>'<','\\<'=>'^','\\>'=>'v','\\v'=>'>'];
    $cturnsLeft =['>'=>'^','^'=>'<','<'=>'v','v'=>'>'];
    $cturnsRight=['>'=>'v','v'=>'<','<'=>'^','^'=>'>'];

    $g=$tracks;
    $positions=[];
    foreach($cars as $k=>&$c){
        [$cx, $cy] = $c[1]; [$vx, $vy] = $c2v[$c[0]]; [$nx, $ny] = [$cx+$vx, $cy+$vy]; $c[1] = [$nx,$ny]; // update car position
        $mnx = ($nx>=$maxx) ? $maxx-1 : $nx; if($mnx<0)$mnx=0;
        $mny = ($ny>=$maxy) ? $maxy-1 : $ny; if($mny<0)$mny=0;
        $np = $tracks[$mny][$mnx];
        if($np==='+'){
            // on intersections
            switch($c[2] % 3){
             case 0: $c[0]=$cturnsLeft[$c[0]]; break;
             case 2: $c[0]=$cturnsRight[$c[0]]; break;
            }
            $c[2]++;
        }else{
            $nc = "{$np}{$c[0]}"; 
            // update car direction if on a corner
            if(isset($cturns[$nc])) $c[0] = $cturns[$nc]; 
        }
        $scoords = sprintf("%3d,%3d", $mnx, $mny);
        $positions[$scoords][] = $k;
        $g[$mny][$mnx]=$c[0];
        //printf("car %2d: %s\n", $k, ve($c));
    }
    printf("Move: %6d | Positions: %s\n", $move, ve($positions));
    foreach($positions as $p=>$pcis){
        if(count($pcis)<2)continue;
        printf("Crash at: %s, between: \n", $p);
        foreach($pcis as $ci) printf("  crashed car %d: %s\n", $ci, ve($cars[$ci]));
        return false;
    }    
    showGridZone($g,0,0, $maxx,$maxy, 1);
    return true;
}
while(move($tracks, $cars, $maxx, $maxy));
