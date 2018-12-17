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
    $instructionSet = getInstructionSet();
    $n=0;
    $samples = explode("Before: ", $input_part1);
    foreach($samples as $sample) {
        if($sample==='')continue;
        $b=[0,0,0,0]; $a=[0,0,0,0]; $behaving=[];
        sscanf($sample, "[%d, %d, %d, %d]\n%d %d %d %d\nAfter:  [%d, %d, %d, %d]", 
               $b[0], $b[1], $b[2], $b[3],  $iop, $ia, $ib, $ic,  $a[0], $a[1], $a[2], $a[3]);        
        foreach($instructionSet as $iname => $ifun){
            if( $a[ $ic ] === $ifun($b, $ia, $ib) ) $behaving[]=$iname;
        }
        $cleansamples[]=[$iop, $behaving];
        if(count($behaving)>=3){ $n++; }
    }
    return $n;
}
function part_2(string $input_part2, array $cleansamples): int {
    $instructionSet = getInstructionSet();
    $op2names = guessInstructionNamesBasedOnSamples( $cleansamples, array_keys($instructionSet) );
    $i2fun = []; for($i=0;$i<16;$i++){ $i2fun[ $i ] = $instructionSet[ $op2names[ $i ] ]; }
    $program = explode("\n",$input_part2);
    $reg = [0,0,0,0];
    foreach($program as $line){
        sscanf($line, "%d %d %d %d", $iop, $ia, $ib, $ic);
        $reg[ $ic ] = $i2fun[ $iop ]( $reg, $ia, $ib );
    }
    return $reg[0];
}
function guessInstructionNamesBasedOnSamples(array $cleansamples, array $opnames): array {
    $nameslikelyhoods = array_combine($opnames, array_fill(0,16, array_fill(0,16,0) ));
    foreach($cleansamples as [$op, $behaves]){
        foreach($behaves as $opname) $nameslikelyhoods[ $opname ][ $op ]++;
    }
    $found=[]; while(true){
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
    }
    ksort($found);
    printf("        * Found ordered instruction map:\n");
    printf("        * %s\n",join(' ',$found));
    return $found;
}
function getInstructionSet(): array {
    return [
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
