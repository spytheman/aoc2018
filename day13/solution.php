#!/usr/bin/env php
<?php
echo "Part 1 answer: 115,138 \n";
echo "Part 2 answer: 0,98 \n";
echo "WIP ...\n";
exit(0);
include("common.php");
$lines = read_input();
$maxx = Amax(Amap($lines, function($l){ return strlen($l); })); $maxy = count($lines);
printf("Longest: x: %d y: %d\n",$maxx, $maxy);
$tracks=array_fill(0,$maxy,[]);for($y=0;$y<$maxy;$y++)$tracks[$y]=array_fill(0,$maxx,' ');
$cars=[]; $n=0;
for($y=0;$y<$maxy;$y++){
    $line = $lines[$y]; $nline = strlen($line);
    for($x=0;$x<$nline;$x++) {
        $c = $line[$x];
        if($c==='>'||$c==='<'||$c==='v'||$c==='^') {
            $cars[]=[$c,[$x,$y],0,$n++];
            if($c==='<'||$c==='>')$c='-';
            if($c==='v'||$c==='^')$c='|';
        }
        $tracks[$y][$x]=$c;
    }
}
function showCars($label='cars', $cars){  foreach($cars as $k=>$c) printf("%s car %2d: %s\n", $label, $k, ve($c)); }
showCars("X Initial", $cars);
//showGridZone($tracks,0,0,$maxx, $maxy,1);exit();

function move(array $tracks, array &$cars, int $maxx, int $maxy): array  {
    static $move=0; 
    printf("Move: %d\n", $move);
    $c2v=['>'=>[1,0],'<'=>[-1,0],'v'=>[0,1],'^'=>[0,-1]];
    $cturns=['/^'=>'>','/<'=>'v','/>'=>'^','/v'=>'<','\\^'=>'<','\\<'=>'^','\\>'=>'v','\\v'=>'>'];
    $cturnsLeft =['>'=>'^','^'=>'<','<'=>'v','v'=>'>'];
    $cturnsRight=['>'=>'v','v'=>'<','<'=>'^','^'=>'>'];

    $g=$tracks;
    $positions=[];
    uasort($cars, function ($c1, $c2) { return (
                                                $c1[1][1] <   $c2[1][1] || 
                                                $c1[1][1] === $c2[1][1] && 
                                                $c1[1][0] <   $c2[1][0]
                                                ) ? -1 : 1; });    
    foreach($cars as $k=>&$c){
        printf("   moving car: %d\n",$k);
        [$cx, $cy] = $c[1]; [$vx, $vy] = $c2v[$c[0]]; [$nx, $ny] = [$cx+$vx, $cy+$vy]; $c[1] = [$nx,$ny]; // update car position
        $mnx = ($nx>=$maxx) ? $maxx-1 : $nx; if($mnx<0)$mnx=0;
        $mny = ($ny>=$maxy) ? $maxy-1 : $ny; if($mny<0)$mny=0;
        $np = $tracks[$mny][$mnx];
        if('+' === $np){
            // on intersections
            switch($c[2] % 3){
             case 0: $c[0] = $cturnsLeft[$c[0]]; break;
             case 2: $c[0] =$cturnsRight[$c[0]]; break;
            }
            $c[2]++;
        }
        if('\\' === $np || '/' === $np ){
            // update car direction on corners
            $nc = "{$np}{$c[0]}";
            $c[0] = $cturns[$nc];
        }
        $scoords = sprintf("%d,%d", $mnx, $mny);
        $positions[$scoords][] = $k;
        $g[$mny][$mnx]=$c[0];
        if(count($positions[$scoords])>1)$g[$mny][$mnx]='x';
        //printf("car %2d: %s\n", $k, ve($c));
    }
    //printf("Move: %6d | cars: %s\n", $move, join(' ', Amap($cars, function($c){ return sprintf("%-22s", ve($c)); })));
    $crashes=[];
    foreach($positions as $p=>$pcis){
        if(count($pcis)<2)continue;
        printf("X   m: %6d, Crash at: %s, between cars %s\n", $move, $p, ve($pcis));
        foreach($pcis as $ci) {
            printf("X   m: %6d,     crashed car %d: %s\n", $move, $ci, ve($cars[$ci]));
            unset($cars[$ci]);
        }
        $crashes[]=$p;
    }
    if(count($crashes)>0){
        foreach($crashes as $crash){
            [$x,$y]=explode(",", $crash);
            //printf("Crash at %d, %d\n",$x,$y);
            //showGridZone($g, $x-3, $y-3, 7,7, 1);
        }
    }
    showCars("      Cars after move: {$move}", $cars);
    $move++;
    return $crashes;
}
//printf("======================= Initial grid: =============================\n"); showGridZone($tracks, 0,0, 0,0, 1);

while(true){
    $crashes=move($tracks, $cars, $maxx, $maxy);
    if(count($crashes)>0)break;
}
printf("X First crash: %s\n", $crashes[0]);

printf("X -----------------------------------------------------------------------------\n");
while(count($cars)>2){
    move($tracks, $cars, $maxx, $maxy);
}

if(0===count($cars)){
    printf("X All cars crashed.\n");
}else{
    showCars("X Remaining", $cars);
}
