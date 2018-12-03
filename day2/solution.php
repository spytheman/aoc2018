#!/usr/bin/env php
<?php
include("common.php");
$lines = read_input();
$nline = strlen($lines[0]);
$c2=0; $c3=0;
foreach($lines as $line){
    $hvals=Avals(line2histogram($line));
    $c2+=Ahas($hvals,2)?1:0;
    $c3+=Ahas($hvals,3)?1:0;
}
printf("Twos: %d | Threes: %d | Checksum: %d\n", $c2, $c3, $c2*$c3);
$seen=[];
foreach($lines as $line){
    $mlines = line2maskedlines( $line );
    foreach($mlines as $mline){
        if(Ahas_key($seen, $mline )) { 
            printf("Found mismatched: %s %s . Sames: %s\n", $line, $seen[ $mline ], noSpace($mline));
            exit(0);
        }else{
            $seen[ $mline ] = $line;
        }
    }
}
