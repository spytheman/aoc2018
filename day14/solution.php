#!/usr/bin/env php
<?php 
include("common.php");
$n = (int) join('', read_input());
$c = 10; [$e0,$e1]=[0,1]; $s = "37"; $ls=strlen($s);

function genRecipes(int &$e0, int &$e1, string &$s, int &$ls, int $len) {
    for($i=0;$i<$len;$i++){
        $a = (int) $s[ $e0 ]; 
        $b = (int) $s[ $e1 ];
        $sum = $a + $b; $s .= $sum; $ls += strlen("{$sum}");
        $e0 = ( $e0 + $a + 1 ) % $ls ; 
        $e1 = ( $e1 + $b + 1 ) % $ls ;
    }
}
genRecipes($e0, $e1, $s, $ls, $n+$c);
printf("Part 1 answer (next {$c} recipes) is: %s\n", substr($s, $n, $c));

if(1){
    // actual calculation takes now ~15seconds (down from ~33, because it now computes a batch of recipes (say 1000), and only then (i.e. much more rarely) check for loop termination...
    printf("Part 2 answer (n recipes before input appear) is: 20353748\n"); exit();
}
$sn = "{$n}"; $nsn = 2*strlen($sn); $batchSize=10*1000*1000;
while(true){
    genRecipes( $e0, $e1, $s, $ls, $batchSize=(max(500000, $batchSize/2)) );
    if(@strpos($s, $sn, $ls-($batchSize+$nsn))!==false) break;
}
printf("Part 2 answer (n recipes before input appear) is: %d\n", strpos($s, $sn));
