#!/usr/bin/env php
<?php include("common.php");
$lines=read_input();
$d=[];foreach($lines as $line)Ahistogram_update($d,Akeys(Ahistogram(Avals(line2histogram($line)))));
printf("Part 1 answer (checksum) is: %d\n",$d[2]*$d[3]);
$seen=[];foreach($lines as $line){
    $mlines=line2maskedlines($line);
    foreach($mlines as $mline){
        if(Ahas_key($seen,$mline)){printf("Part 2 answer (same letters in single letter mismatch) is: %s\n",$line,$seen[$mline],noSpace($mline));exit(0);}
        $seen[$mline]=$line;}}
