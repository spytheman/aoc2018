#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
[$input_part1, $input_part2] = explode("\n\n\n\n", join("\n", $lines));
$cleansamples=[];
printf("Part 1 answer (number of samples in puzzle input that behave like three or more opcodes) is: %d\n", part_1($input_part1, $cleansamples));
printf("Part 2 answer (value contained in register 0 after executing the test program) is: %d\n", part_2($input_part2,$cleansamples));
///////////////////////////////////////////////////////////////////////////////////////////////////////
function part_1(string $input_part1, array &$cleansamples): int {
    $n=0;
    $samples = explode("Before: ", $input_part1);
    $c=1; foreach($samples as $sample) {
        $sample = trim($sample);
        if($sample==='')continue;
        $nums = line2digits($sample);
        $before=array_slice($nums, 0, 4); $instruction=array_slice($nums, 4, 4); $after=array_slice($nums, 8, 4);
        $behaving = sample2working($before, $instruction, $after);
        $cleansamples[]=['op'=>$instruction[0], 'behaves'=>$behaving];
        if(count($behaving)>=3){ $n++; }
        $c++;
    }
    return $n;
}
function part_2(string $input_part2, array $cleansamples): int {
    $instructionSet = getInstructionSet();
    $op2names = guessInstructionNamesBasedOnSamples( $cleansamples );
    $i2fun = []; for($i=0;$i<16;$i++){ $i2fun[ $i ] = $instructionSet[ $op2names[ $i ] ]; }
    $program = explode("\n",$input_part2);
    $reg = [0,0,0,0];
    $c=0; foreach($program as $line){
        $instruction = line2digits( $line );
        $reg[ $instruction[3] ] = $i2fun[ $instruction[0] ]( $reg, $instruction[1], $instruction[2]);
        $c++;
    }
    return $reg[0];
}
function guessInstructionNamesBasedOnSamples(array $cleansamples): array {
    $nameslikelyhoods = array_combine(
        array_keys(getInstructionSet()),
        array_fill(0,16, array_fill(0,16,0) )
    );
    foreach($cleansamples as $c) {
        $behaves = $c['behaves'];
        foreach($behaves as $opname) $nameslikelyhoods[ $opname ][ $c['op'] ]++;
    }
    $c=0;$found=[]; while(true){
        if(count($found)>=16)break;
        $mopname = ''; $mop = -1; $mopcount = -1;
        foreach($nameslikelyhoods as $opname=>$v){
            $ac =Acount($v,0);
            if(15 === $ac){ 
                $mopname = $opname; [$mop, $mopcount] = Amaxkv($v); break;
            }
        }
        if($mopname!==''){
            $found[ $mop ] = $mopname;
            unset($nameslikelyhoods[ $mopname ]);
            foreach( $nameslikelyhoods as &$h) $h[ $mop ] = 0;
        }
        $c++;
    }
    ksort($found);
    printf("        * Found ordered instruction map:\n");
    printf("        * %s\n",join(' ',$found));
    return $found;
}
function getInstructionSet(): array {
    return $iset = [
        "addr"=>function($reg,$ia,$ib){ return $reg[ $ia ] + $reg[ $ib ]; },
        "addi"=>function($reg,$ia,$ib){ return $reg[ $ia ] + $ib;         },
        "mulr"=>function($reg,$ia,$ib){ return $reg[ $ia ] * $reg[ $ib ]; },
        "muli"=>function($reg,$ia,$ib){ return $reg[ $ia ] * $ib;         },
        "banr"=>function($reg,$ia,$ib){ return $reg[ $ia ] & $reg[ $ib ]; },
        "bani"=>function($reg,$ia,$ib){ return $reg[ $ia ] & $ib;         },
        "borr"=>function($reg,$ia,$ib){ return $reg[ $ia ] | $reg[ $ib ]; },
        "bori"=>function($reg,$ia,$ib){ return $reg[ $ia ] | $ib;         },
        "setr"=>function($reg,$ia,$ib){ return $reg[ $ia ]; },
        "seti"=>function($reg,$ia,$ib){ return $ia;         },
        "gtir"=>function($reg,$ia,$ib){ return ($ia        >  $reg[$ib]) ? 1 : 0; },
        "gtri"=>function($reg,$ia,$ib){ return ($reg[$ia]  >  $ib      ) ? 1 : 0; },
        "gtrr"=>function($reg,$ia,$ib){ return ($reg[$ia]  >  $reg[$ib]) ? 1 : 0; },
        "eqir"=>function($reg,$ia,$ib){ return ($ia       === $reg[$ib]) ? 1 : 0; },
        "eqri"=>function($reg,$ia,$ib){ return ($reg[$ia] === $ib      ) ? 1 : 0; },
        "eqrr"=>function($reg,$ia,$ib){ return ($reg[$ia] === $reg[$ib]) ? 1 : 0; },
    ];
}
function sample2working(array $before, array $instruction, array $after): array {
    $instructionSet = getInstructionSet();
    $working=[];
    foreach($instructionSet as $iname => $ifun){
        $res = $before; $res[ $instruction[3] ]  = $ifun($before, $instruction[1], $instruction[2]);
        if($res[0]===$after[0] &&
           $res[1]===$after[1] &&
           $res[2]===$after[2] &&
           $res[3]===$after[3]){
            $working[]=$iname;
        }
    }
    return $working;
}
