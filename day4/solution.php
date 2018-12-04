#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$history = []; $cguards = [];
foreach($lines as $line){
    @list($y,$mon,$d, $h,$m, $cguard) = line2digits($line);
    $cdate = sprintf("%04d/%02d/%02d %02d:%02d", $y,$mon,$d,$h,$m);
    $ctime = strtotime($cdate);
    $history[ $ctime ] = [$line, Acast2ints([$y,$mon,$d, $h,$m, $cguard])];
    if( $cguard > 0 ) $cguards[ $cguard ] = 1;
}
asort($history); $cguards = Akeys($cguards);
$cguard = 0;
$guards=[]; $awakes = []; $asleeps = []; $timetable = []; $ttminutes = [];
foreach($cguards as $c){ foreach(range(0, 59) as $m){ $timetable[ $c ][$m] = []; } }
foreach(range(0, 59) as $m){ foreach($cguards as $c){ $ttminutes[ $m ][$c] = 0; } }
$old_ctime=0; $cstate = 1;
foreach($history as $ctime=>list($line, list($y,$mon,$d, $h,$m, $cg))){
    //printf("ctime: %d | %02d:%02d | %6d->%6d | line: %s \n", $ctime, $h,$m, $cg, $cguard, $line);
    if($cg!==0){
        $cguard = $cg;
        $old_ctime = $ctime;
        $cstate = 1;
    }else{
        if(strpos($line, 'falls asleep')!==false) {
            $cstate = 0;
            $guards[ $cguard ] = 0;
            @$awakes[ $cguard ] += ( $ctime - $old_ctime );
        }
        if(strpos($line, 'wakes up')!==false) {
            $cstate = 1;
            $guards[ $cguard ] = 1;
            @$asleeps[ $cguard ] += ( $ctime - $old_ctime );
            //printf("start at %s - end at %s\n", date("c", $old_ctime), date("c", $ctime));
            $asleepminutes=[];
            foreach(@range($old_ctime, $ctime, 60) as $t){
                $xm = (int) date("i", $t);
                $xd = date("m-d", $t);
                $asleepminutes[] = $xm;
                //printf("sleep at %s - %s\n", $xd, $xm);
            }            
            array_pop($asleepminutes);
            foreach($asleepminutes as $m) {
                $timetable[ $cguard ][ $m ][] = $xd;
                $ttminutes[ $m ][$cguard] += 1;
            }
            printf("         #%d | day: %s | asleep minutes: %s\n", $cguard, $xd, ve($asleepminutes));
        }
        $old_ctime = $ctime;
    }
}
Arsort($awakes);
Arsort($asleeps);
//printf("Awakes: %s\n",ve($awakes));
//printf("Asleeps: %s\n",ve($asleeps));
$mostAsleepGuard = Akeys($asleeps)[0];
printf("Most asleep guard: %d\n", $mostAsleepGuard);
$asleepdays=0; $asleepdays_index = 0;
foreach($timetable[ $mostAsleepGuard ] as $km=>$kx){
    //printf("km: %s => km: %s\n", $km, ve($kx));
    $asleeps = Alen($kx);
    if( $asleepdays <= $asleeps ){
        $asleepdays = $asleeps;
        $asleepdays_index = $km;
    }
}
printf("Maximum asleeps for guard %d at minute: %d = %d\n", $mostAsleepGuard, $asleepdays_index, $asleepdays );
printf("Part 1 answer is: %d\n", $mostAsleepGuard * $asleepdays_index);

$mostasleeptimes_per_minute = 0; $mostasleepguard_per_minute = 0; $mostasleepminute = 0;
foreach(range(0,59) as $m){
    $mguards = $ttminutes[ $m ];
    arsort($mguards);
    $mguard = Akeys($mguards)[0];
    $mtimes = Avals($mguards)[0];
    if($mtimes>$mostasleeptimes_per_minute){
        $mostasleeptimes_per_minute = $mtimes;
        $mostasleepguard_per_minute = $mguard;
        $mostasleepminute = $m;
    }
    printf("m: %d | mguard: %5d | $mtimes: %3d | guards: %s\n",$m, $mguard, $mtimes, ve($mguards));
}
printf("Most asleep minute: %2d | most asleep guard: %5d | most asleep times: %3d\n", $mostasleepminute, $mostasleepguard_per_minute, $mostasleeptimes_per_minute);
printf("Part 2 answer is: %d\n", $mostasleepguard_per_minute * $mostasleepminute);
