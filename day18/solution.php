#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$llen = strlen($lines[0]); $border = 1; $maxx = $maxy = 2*$border + $llen; $sx = $sy = $border;
$g = A2Dnew($maxx, $maxy, ' '); for($y=0;$y<$llen;$y++) for($x=0;$x<$llen;$x++) $g[$sy+$y][$sx+$x] = $lines[$y][$x];

$maxm = 1000000000; $ghashes=[]; $totals=[]; $repetitionFound = false; $repetitionStart=0; $repetitionPeriod = $maxm;
function getNewH(){  return [' '=>0, '.'=>0, '|'=>0, '#'=>0]; }
$m=0;while(($m<=$maxm)&&(!$repetitionFound)){
    $h=getNewH(); for($y=0;$y<$maxy;$y++) for($x=0;$x<$maxx;$x++) @$h[$g[$y][$x]]++;
    $t = $h['|'] * $h['#'];
    if($m===10)printf("Part 1 answer (after 10 iterations): %8d\n", $t);
    $hg = md5(serialize($g));
    if(0 === $m % 100) printf("Minute:%-4d | ghash: %32s | H of zone: %-35s | T: %6d\n", $m, $hg, ve($h), $t);
    if(isset($ghashes[$hg])){
        $repetitionFound = true; $repetitionStart = $ghashes[$hg]; $repetitionPeriod = $m - $repetitionStart;
        printf("---> The same grid (hash:%32s) at M: %d has been already seen at M: %d\n", $hg, $m, $ghashes[$hg]);
    }else{
        $ghashes[ $hg ] = $m;
    }
    $totals[ $m ] = $t;
    $ng = $g;
    for($y=0;$y<$maxy;$y++){
        for($x=0;$x<$maxx;$x++){
            $nearh=getNewH();
            @$nearh[$g[$y-1][$x-1]]++; @$nearh[$g[$y-1][$x]]++;  @$nearh[$g[$y-1][$x+1]]++;
            @$nearh[$g[$y  ][$x-1]]++;                           @$nearh[$g[$y  ][$x+1]]++;
            @$nearh[$g[$y+1][$x-1]]++; @$nearh[$g[$y+1][$x]]++;  @$nearh[$g[$y+1][$x+1]]++;
            $c=$g[$y][$x]; $nc = $c;
            switch($c){
             case '.': if( $nearh['|']>=3 ) $nc = '|'; break;
             case '|': if( $nearh['#']>=3 ) $nc = '#'; break;
             case '#': if( !($nearh['#']>=1 && $nearh['|']>=1) ) $nc='.'; break;
            }
            $ng[$y][$x]=$nc;
        }
    }
    $g = $ng;
    $m++;
}
printf("Part 2 answer (after 1000000000 iterations): %8d\n", $totals[ $repetitionStart + (($maxm-$repetitionStart) % $repetitionPeriod) ] );
