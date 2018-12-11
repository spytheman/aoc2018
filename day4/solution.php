#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$history = []; $cguards = []; $allminutes = range(0,59);
foreach($lines as $line){
    @[$y,$mon,$d, $h,$m, $cguard] = line2digits($line,false);
    $cdate = sprintf("%04d/%02d/%02d %02d:%02d", $y,$mon,$d,$h,$m); $ctime = strtotime($cdate);
    $history[ $ctime ] = [$line, Acast2ints([$y,$mon,$d, $h,$m, $cguard])];
    if( $cguard > 0 ) $cguards[ $cguard ] = $cguard;
}
asort($history);
$asleeps = []; $timetable = []; $ttminutes = []; $old_ctime=0;
foreach($cguards as $c){ foreach($allminutes as $m){ $timetable[ $c ][$m] = []; } } 
foreach($allminutes as $m){ foreach($cguards as $c){ $ttminutes[ $m ][$c] = 0; } }
foreach($history as $ctime=>[$line, [$y,$mon,$d, $h,$m, $cg]]){
    if($cg!==0){   $cguard = $cg;   $old_ctime = $ctime; } else{
        if(strpos($line, 'wakes up')!==false) {
            @$asleeps[ $cguard ] += ( $ctime - $old_ctime ); 
            $asleepminutes=[];
            foreach(@range($old_ctime, $ctime, 60) as $t){
                $xd = date("m-d", $t); 
                $xm = (int) date("i", $t);
                $asleepminutes[] = $xm;
            }            
            array_pop($asleepminutes);
            foreach($asleepminutes as $m) {
                $timetable[ $cguard ][ $m ][] = $xd;
                $ttminutes[ $m ][$cguard] += 1;
            }
        }
        $old_ctime = $ctime;
    }
}
[$mostAsleepGuard,] = Agetkvmostfrequent($asleeps);
$minimum = [0,0]; foreach($timetable[ $mostAsleepGuard ] as $km=>$kx){
    $asleeps = Alen($kx); 
    if( $minimum[0] <= $asleeps ) $minimum = [$asleeps, $km];
}
printf("Part 1 answer (mostAsleepGuard * minute) is: %d\n", $mostAsleepGuard * $minimum[1]);

$minimum=[0,0,0]; foreach($allminutes as $m){
    $mguards = $ttminutes[ $m ];
    [$mguard,$mtimes]=Agetkvmostfrequent($mguards);
    if($mtimes>$minimum[0]) $minimum = [ $mtimes, $mguard, $m ];
}
printf("Part 2 answer (strategy 2) is: %d\n", $minimum[1] * $minimum[2]);
