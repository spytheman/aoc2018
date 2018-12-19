#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$program = []; $ipidx = 'dip'; $cpustate = [0,0,0, 0,0,0,  'dip'=>0, 'ipidx'=>0];
$c=0;
$instructions = getInstructionSet();
foreach($lines as $line){
    //printf("%04d: %s\n", $c, $line);
    [$cmd,$_rest] = explode(' ', $line);
    $args = array_pad( line2digits($line), 3, 0);
    if($cmd === '#ip'){
        $cpustate['ipidx'] = $ipidx = $args[0];
        continue;
    }
    $program[$c] = array_merge([$cmd], $args);
    $c++;
}
$programsize = count($program)-1; 
for($i=0;$i<=$programsize;$i++)printf("I: %-3d CMD: %s\n",$i, state2string($program[$i]));

$c=0; while(true){
    $ip  = $cpustate[ $ipidx ];
    if( $ip > $programsize ) {
        printf("--->CPU at step: %-3d %s | IP: %-4d \n", $c, state2string($cpustate), $ip);
        printf("--->IP > programsize: %d . Terminating...\n", $programsize);
        break;
    }
    $ins = $program[ $ip ];
    printf("CPU at step: %-3d %s | IP: %-4d | INS: %15s\n", $c, state2string($cpustate), $ip, ve($ins));
    $ncpustate = $instructions[ $ins[0] ]( $cpustate, $ins[1], $ins[2], $ins[3] );
    $cpustate = $ncpustate;
    $cpustate[ $ipidx  ]++;
    if($c>100)break;
    $c++;
}

function state2string($state){
    $res = [];
    foreach( $state as $rname=>$rval) $res[]=sprintf("%1s: %-3s", $rname, $rval);
    return '['.join(',', $res).']';
}

function getInstructionSet(): array {
    return [        
        "addr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] + $reg[ $ib ];         return $o; },
        "addi"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] + $ib;                 return $o; },
        "mulr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] * $reg[ $ib ];         return $o; },
        "muli"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] * $ib;                 return $o; },
        "banr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] & $reg[ $ib ];         return $o; },
        "bani"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] & $ib;                 return $o; },
        "borr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] | $reg[ $ib ];         return $o; },
        "bori"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ] | $ib;                 return $o; },
        "setr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $reg[ $ia ];                       return $o; },
        "seti"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = $ia;                               return $o; },
        "gtir"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($ia        >  $reg[$ib]) ? 1 : 0; return $o; },
        "gtri"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($reg[$ia]  >  $ib      ) ? 1 : 0; return $o; },
        "gtrr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($reg[$ia]  >  $reg[$ib]) ? 1 : 0; return $o; },
        "eqir"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($ia       === $reg[$ib]) ? 1 : 0; return $o; },
        "eqri"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($reg[$ia] === $ib      ) ? 1 : 0; return $o; },
        "eqrr"=>function($reg,$ia,$ib,$ic){ $o=$reg; $o[$ic] = ($reg[$ia] === $reg[$ib]) ? 1 : 0; return $o; },
        "#ip" =>function($reg,$ia,$ib,$ic){ $o=$reg; $o['#ip'] = $ia;                             return $o; },
    ];
}
