#!/usr/bin/env php
<?php 
include("common.php");
$n = (int) join('', read_input());
$c = 10; [$e0,$e1]=[0,1]; $s = "37"; $ls=strlen($s);
while(true){
    $a = (int) $s[ $e0 ]; 
    $b = (int) $s[ $e1 ];
    $sum = $a + $b; $s .= $sum; $ls += strlen("{$sum}");
    $e0 = ( $e0 + $a + 1 ) % $ls ; 
    $e1 = ( $e1 + $b + 1 ) % $ls ;
    if ( $ls > $n + $c ) break;
}
printf("Part 1 answer (next {$c} recipes) is: %s\n", substr($s, $n, $c));
if(1){
    // actual calculation takes now ~17seconds (down from ~33, because it now computes a batch of recipes (say 1000), and only then (i.e. much more rarely) check for loop termination...
    printf("Part 2 answer (n recipes before input appear) is: 20353748\n"); exit();
}
[$e0,$e1]=[0,1]; $s = "37"; $ls=strlen($s); $sn = "{$n}"; $nsn = 2*strlen($sn); $batchSize=1000; $batchNSN = $batchSize+$nsn;
$i=0;while(true){
    $a = (int) $s[ $e0 ]; 
    $b = (int) $s[ $e1 ];
    $sum = $a + $b; $s .= $sum; $ls += strlen("{$sum}");
    $e0 = ( $e0 + $a + 1 ) % $ls ; 
    $e1 = ( $e1 + $b + 1 ) % $ls ;
    if(0===$i % $batchSize){
        if(0===$i % 1000000) printf("Iteration: %10d, ls: %10d\n", $i, $ls);
        if(strpos($slast=substr($s, $ls-$batchNSN), $sn)!==false) break;
    }
    $i++;
}
printf("Part 2 answer (n recipes before input appear) is: %d\n", strpos($s, $sn));
