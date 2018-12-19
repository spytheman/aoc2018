#!/usr/bin/env php
<?php
include("common.php");
$lines = read_input();
$program = []; $ipidx =0; $cpustate = [0,0,0, 0,0,0];
if(isset($argv[2]))$cpustate[0]=(int)$argv[2];
$c=0;
foreach($lines as $line){
    [$cmd,$_rest] = explode(' ', $line);
    $args = array_pad( line2digits($line), 3, 0);
    if($cmd === '#ip'){
        $ipidx = $args[0];
        continue;
    }
    $program[$c] = array_merge([$cmd], $args);
    $c++;
}
$programsize = count($program)-1;
printf("ipidx: %d\n",$ipidx);
//for($i=0;$i<=$programsize;$i++)printf("I: %-3d CMD: %s\n",$i, state2string($program[$i]));

$c=0; while(true){
    $ip  = $cpustate[ $ipidx ];
    if( $ip > $programsize ) {
        printf("--->CPU at step: %-3d %s | IP: %-4d \n", $c, state2string($cpustate), $ip);
        printf("--->IP > programsize: %d . Terminating...\n", $programsize);
        break;
    }
    $ins = $program[ $ip ];
    //if(0 === $c % 1000000) printf("CPU at step: %-9d %s | IP: %-4d | INS: %15s\n", $c, state2string($cpustate), $ip, ve($ins));
    switch($ins[0]){
    case "addr":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] + $cpustate[ $ins[2] ];    break;
    case "addi":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] + $ins[2];                 break;
    case "mulr":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] * $cpustate[ $ins[2] ];    break;
    case "muli":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] * $ins[2];                 break;
    case "banr":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] & $cpustate[ $ins[2] ];    break;
    case "bani":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] & $ins[2];                 break;
    case "borr":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] | $cpustate[ $ins[2] ];    break;
    case "bori":$cpustate[$ins[3]] = $cpustate[ $ins[1] ] | $ins[2];                 break;
    case "setr":$cpustate[$ins[3]] = $cpustate[ $ins[1] ];                           break;
    case "seti":$cpustate[$ins[3]] = $ins[1];                                        break;
    case "gtir":$cpustate[$ins[3]] = ($ins[1]        >  $cpustate[$ins[2]]) ? 1 : 0; break;
    case "gtri":$cpustate[$ins[3]] = ($cpustate[$ins[1]]  >  $ins[2]      ) ? 1 : 0; break;
    case "gtrr":$cpustate[$ins[3]] = ($cpustate[$ins[1]]  >  $cpustate[$ins[2]]) ? 1 : 0; break;
    case "eqir":$cpustate[$ins[3]] = ($ins[1]       === $cpustate[$ins[2]]) ? 1 : 0; break;
    case "eqri":$cpustate[$ins[3]] = ($cpustate[$ins[1]] === $ins[2]      ) ? 1 : 0; break;
    case "eqrr":$cpustate[$ins[3]] = ($cpustate[$ins[1]] === $cpustate[$ins[2]]) ? 1 : 0; break;
    }
    if( $c > 30 ){
        // Euristic speedup (determined by looking hard at goal and purpose of the input):
        $n = Amax($cpustate);
        printf("After 30 steps, the probable factorized number (biggest register) is: %d\n",$n);
        $sqrN = (int) sqrt($n);
        $result = 0; for ($i = 1; $i <= $sqrN; $i++) if ($n % $i === 0) $result += $i + $n / $i;
        printf("Result of the program should be: %d\n", $result);
        $cpustate[ 0 ] = $result ;
        break;
    }
    $cpustate[ $ipidx  ]++;
    $c++;
}
printf("CPU at step: %-3d %s \n", $c, state2string($cpustate));
printf("Answer (content of register 0, after program termination): %s\n", $cpustate[0]);

function state2string($state){
    $res = [];
    foreach( $state as $rname=>$rval) $res[]=sprintf("%8s",$rval);
    return '['.join(',', $res).']';
}
