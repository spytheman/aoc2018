#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$c=0; $state = []; $fsm=array_fill(0,32,0);
foreach($lines as $line){
    $line = trim($line); if(strlen($line)===0)continue;
    $line = str_replace(['.','#',], ['0','1'], $line);
    switch($line[0]){
     case 'i':
        sscanf($line, "initial state: %s", $state);
        $state = Acast2ints(line2array($state));
        break;
     default:
        sscanf($line, "%1d%1d%1d%1d%1d => %1d", $d4,$d3,$d2,$d1,$d0, $x);
        $fsmn = 16*$d4 + 8*$d3 + 4*$d2 + 2*$d1 + 1*$d0;
        $fsm[ $fsmn ] = $x;
        break;
    }
    $c++;
}
$nstate = count($state); 
$g=array_fill(0,20+1,array_fill(0,3*$nstate,0)); $glen = count($g[0]);
for($i=0;$i<$nstate;$i++)$g[0][$i+$nstate] = $state[$i];
printf("FSM   0:%3d, 1:%3d                : %32s\n", Acount($fsm,0), Acount($fsm,1), join('',$fsm));
printf("Initial state len: %3d            : %s\n",$nstate, join('',$state));
for($y=1;$y<=20;$y++) for($x=2;$x<$glen-2;$x++){
    $os = 16*$g[$y-1][$x-2] + 8*$g[$y-1][$x-1] + 4*$g[$y-1][$x] + 2*$g[$y-1][$x+1] + 1*$g[$y-1][$x+2];
    $g[$y][$x] = $fsm[ $os ];
}
$pots=[]; for($x=0;$x<$glen;$x++){ if($g[20][$x]) $pots[]=$x-$nstate; }
printf("Part 1 answer: %d\n", Asum($pots));
