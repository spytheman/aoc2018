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
    $program = explode("\n",$input_part2);
    $reg = [0,0,0,0];
    $c=0; foreach($program as $line){
        $instruction = line2digits( $line );
        $fn = $instructionSet[ $op2names[ $instruction[0] ] ];
        $nextreg = $fn( $reg, $instruction[1], $instruction[2], $instruction[3] );
        $reg = $nextreg;
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
        "addr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] + $reg[ $ib ]; return $reg; },
        "addi"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] + $ib;         return $reg; },
        "mulr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] * $reg[ $ib ]; return $reg; },
        "muli"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] * $ib;         return $reg; },
        "banr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] & $reg[ $ib ]; return $reg; },
        "bani"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] & $ib;         return $reg; },
        "borr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] | $reg[ $ib ]; return $reg; },
        "bori"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ] | $ib;         return $reg; },
        "setr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $reg[ $ia ]; return $reg; },
        "seti"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = $ia;         return $reg; },
        "gtir"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($ia        >  $reg[$ib]) ? 1 : 0; return $reg; },
        "gtri"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($reg[$ia]  >  $ib      ) ? 1 : 0; return $reg; },
        "gtrr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($reg[$ia]  >  $reg[$ib]) ? 1 : 0; return $reg; },
        "eqir"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($ia       === $reg[$ib]) ? 1 : 0; return $reg; },
        "eqri"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($reg[$ia] === $ib      ) ? 1 : 0; return $reg; },
        "eqrr"=>function($reg,$ia,$ib,$ic){ $reg[ $ic ] = ($reg[$ia] === $reg[$ib]) ? 1 : 0; return $reg; },
    ];
}
function sample2working(array $before, array $instruction, array $after): array {
    $instructionSet = getInstructionSet();
    $working=[];
    foreach($instructionSet as $iname => $ifun){
        $res = $ifun($before, $instruction[1], $instruction[2], $instruction[3]);
        if($res[0]===$after[0] &&
           $res[1]===$after[1] &&
           $res[2]===$after[2] &&
           $res[3]===$after[3]){
            $working[]=$iname;
        }
    }
    return $working;
}
