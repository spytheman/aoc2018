#!/usr/bin/env php
<?php include("common.php");
$lines = read_input();
$nline = strlen($lines[0]);
$c2=0; $c3=0;
foreach($lines as $line){
    $hline = line2histogram($line,$nline);
    $c2 += Acontains(Avals($hline),2)>0 ? 1 : 0;
    $c3 += Acontains(Avals($hline),3)>0 ? 1 : 0;
}
printf("Twos: %d | Threes: %d | Checksum: %d\n", $c2, $c3, $c2*$c3);
foreach($lines as $l1){
    foreach($lines as $l2){
        $sames=""; for($i=0;$i<$nline;$i++) if($l1[$i]===$l2[$i])$sames.=$l1[$i];
        if(strlen($sames)===$nline-1){ printf("Found mismatched: %s %s . Sames: %s\n", $l1, $l2, $sames); exit(0); }
    }
}
