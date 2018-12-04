#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$history = []; $cguards = []; $allminutes = range(0,59);
foreach($lines as $line){
    @[$y,$mon,$d, $h,$m, $cguard] = line2digits($line);
    $cdate = sprintf("%04d/%02d/%02d %02d:%02d", $y,$mon,$d,$h,$m); $ctime = strtotime($cdate);
    $history[ $ctime ] = [$line, Acast2ints([$y,$mon,$d, $h,$m, $cguard])];
    if( $cguard > 0 ) $cguards[ $cguard ] = $cguard;
}
asort($history);
$asleeps = []; $timetable = []; $ttminutes = []; $old_ctime=0;
foreach($cguards as $c){ foreach($allminutes as $m){ $timetable[ $c ][$m] = []; } } 
foreach($allminutes as $m){ foreach($cguards as $c){ $ttminutes[ $m ][$c] = 0; } }
foreach($history as $ctime=>[$line, [$y,$mon,$d, $h,$m, $cg]]){
    //printf("ctime: %d | %02d:%02d | %6d->%6d | line: %s \n", $ctime, $h,$m, $cg, $cguard, $line);
    if($cg!==0){   $cguard = $cg;   $old_ctime = $ctime; } else{
        if(strpos($line, 'wakes up')!==false) {
            @$asleeps[ $cguard ] += ( $ctime - $old_ctime ); 
            //printf("start at %s - end at %s\n", date("c", $old_ctime), date("c", $ctime));
            $asleepminutes=[];
            foreach(@range($old_ctime, $ctime, 60) as $t){
                $xd = date("m-d", $t); 
                $xm = (int) date("i", $t);
                $asleepminutes[] = $xm;
                //printf("sleep at %s - %s\n", $xd, $xm);
            }            
            array_pop($asleepminutes);
            foreach($asleepminutes as $m) {
                $timetable[ $cguard ][ $m ][] = $xd;
                $ttminutes[ $m ][$cguard] += 1;
            }
            //printf("         #%d | day: %s | asleep minutes: %s\n", $cguard, $xd, ve($asleepminutes));
        }
        $old_ctime = $ctime;
    }
}
[$mostAsleepGuard,] = Akvmostfrequent($asleeps);
printf("Most asleep guard: %d\n", $mostAsleepGuard);
$minimum = [0,0]; foreach($timetable[ $mostAsleepGuard ] as $km=>$kx){
    //printf("km: %s => km: %s\n", $km, ve($kx));
    $asleeps = Alen($kx); 
    if( $minimum[0] <= $asleeps ) $minimum = [$asleeps, $km];
}
printf("Maximum asleeps for guard %d at minute: %d = %d\n", $mostAsleepGuard, $minimum[1], $minimum[0]);
printf("Part 1 answer is: %d\n", $mostAsleepGuard * $minimum[1]);

$minimum=[0,0,0]; foreach($allminutes as $m){
    $mguards = $ttminutes[ $m ];
    [$mguard,$mtimes]=Akvmostfrequent($mguards);
    if($mtimes>$minimum[0]) $minimum = [ $mtimes, $mguard, $m ];
    //printf("m: %d | mguard: %5d | $mtimes: %3d | guards: %s\n",$m, $mguard, $mtimes, ve($mguards));
}
printf("Most asleep minute: %2d | most asleep guard: %5d | most asleep times: %3d\n", $minimum[2], $minimum[1], $minimum[0]);
printf("Part 2 answer is: %d\n", $minimum[1] * $minimum[2]);
