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
    $c++;
}

$maxm = 1000000000; $ghashes=[]; $totals=[]; $repetitionFound = false; $repetitionStart=0; $repetitionPeriod = $maxm;
$m=0;while(($m<=$maxm)&&(!$repetitionFound)){
    $h=getNewH(); for($y=0;$y<$maxy;$y++) for($x=0;$x<$maxx;$x++) @$h[$g[$y][$x]]++;
    $t = $h['|'] * $h['#'];
    if($m===10)printf("Part 1 answer (after 10 iterations): %8d\n", $t);
    $hg = md5(ve($g));
    if(0 === $m % 100) printf("Minute:%-4d | ghash: %32s | H of zone: %-35s | T: %6d\n", $m, $hg, ve($h), $t);
    if(isset($ghashes[$hg])){
        printf("---> The same grid (hash:%32s) at M: %d has been already seen at M: %d\n", $hg, $m, $ghashes[$hg]);
        $repetitionFound  = true;
        $repetitionStart  = $ghashes[$hg];
        $repetitionPeriod = $m - $ghashes[$hg];
    }else{
        $ghashes[ $hg ] = $m;
    }
    $totals[ $m ] = $t;
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
    $g = $ng;
    $m++;
}
printf("Part 2 answer (after 1000000000 iterations): %8d\n", $totals[ $repetitionStart + (($maxm-$repetitionStart) % $repetitionPeriod) ] );
