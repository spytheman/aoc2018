#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$llen = strlen($lines[0]);
$border = 3;
$maxx = 2*$border + $llen; $sx = $border; $ex = $maxx - $border;
$maxy = 2*$border + $llen; $sy = $border; $ey = $maxy - $border;
function getNewH(){  return [' '=>0, '.'=>0, '|'=>0, '#'=>0]; }
$g = A2Dnew($maxx, $maxy, ' ');
$c=0; foreach($lines as $line){
    for($x=0;$x<$llen;$x++) $g[$sy+$c][$sx+$x] = $line[$x];
    //printf("%04d: %s\n", $c, $line);
    $c++;
}
for($m=0;$m<11;$m++){
    $h=getNewH(); for($y=0;$y<$maxy;$y++) for($x=0;$x<$maxx;$x++) @$h[$g[$y][$x]]++;
    printf("Minute: %4d | H of zone: %20s , Total: %8d\n", $m, ve($h), $h['|'] * $h['#']); 
    //showGridZone($g,0,0, $maxx,$maxy,1);
    $ng = A2Dnew($maxx, $maxy, ' ');
    for($y=0;$y<$maxy;$y++){
        for($x=0;$x<$maxx;$x++){
            $c=$g[$y][$x]; $nc = $c;
            $nearh=getNewH(); for($hy=-1;$hy<=1;$hy++) for($hx=-1;$hx<=1;$hx++) {if($hy===0 && $hx===0)continue; @$nearh[$g[$hy+$y][$hx+$x]]++;}
            switch($c){
             case '.': $nc = ($nearh['|']>=3) ? '|' : '.'; break;
             case '|': $nc = ($nearh['#']>=3) ? '#' : '|'; break;
             case '#': $nc = ($nearh['#']>=1 && $nearh['|']>=1) ? '#' : '.'; break;
            }
            $ng[$y][$x]=$nc;
        }
    }
    $g=$ng;
}
