#!/usr/bin/env php
<?php
include("common.php");
define('TEMPLATEFOLDER', realpath(__DIR__).'/_elfasm2c_templates');

// CODEGEN_SWITCH - It works and achieves ~500 000 000 iterations/s .
// It generates a giant while()( switch(){} } with a loop per 1 elf assembly instruction.
define('CODEGEN_SWITCH', 1); 

// CODEGEN_DIRECT - EXPERIMENTAL !!! Should achieve nearly 1:1 mapping to native instructions.
// Should be several times faster than CODEGEN_GOTOS . It should produce code that can be 
// further optimized by the C compiler down the line.
define('CODEGEN_DIRECT',  2); 

$cpustate = [0,0,0, 0,0,0];
$showheader = $showfooter = true;
$codegenkind = CODEGEN_SWITCH;
for($i=1;$i<$argc;$i++) {
    if($argv[$i]==='--help'){
        echo "Usage: elfasm2c.php input.elf [OPTION] > input.c \n";
        echo "        ... where OPTION can be: \n";
        echo "        --help          -  Shows this help .\n";
        echo "        --hideheader    -  no header boilerplate in output. \n";
        echo "        --hidefooter    -  no footer boilerplate in output. \n";
        echo "        --reg Ridx=Rval -  Set initial register Ridx to value Rval. \n";
        echo "                 Can be given multiple times. Example: --reg 0=1231  --reg 5=771 \n";
        echo "                 ... sets both reg0=1231 and reg5=771 . \n";
        echo "\n";
        echo "        --codegenswitch -  Use the CODEGEN_SWITCH kind of generation . \n";
        echo "                           This is the default mode. Moderately fast. \n";
        echo "\n";
        echo "        --codegendirect -  Use the CODEGEN_DIRECT kind of generation (with gotos and stuff). \n";
        echo "                           Experimental and more complex, but the generated code should be \n";
        echo "                           Super Duper Insanely Fast (tm). \n";
        echo "\n";
        exit(0);
    }
    if($argv[$i]==='--hideheader') $showheader = false;
    if($argv[$i]==='--hidefooter') $showfooter = false;
    if($argv[$i]==='--reg'){
        if($i+1>=$argc) die("Argument --reg need parameter of the form Ridx=Rval .\n");
        [$ri, $rv] = explode('=', $argv[$i+1]);
        $ri = (int) $ri; $rv = (int) $rv;
        if( $ri < 0 || $ri > 5 ) die("Option --reg Ridx=Rval should have 0 <= Ridx <= 5 . Actual Ridx passed: {$ri} \n");
        $cpustate[$ri] = $rv;        
    }
}

$program = []; $ipidx =0; 
$lines = read_input();
$c=0;
foreach($lines as $line){
    [$cmd,$_rest] = explode(' ', $line);
    $args = array_pad( line2digits($line), 3, 0);
    if($cmd === '#ip'){ $ipidx = $args[0]; continue; }
    $program[$c] = array_merge([$cmd], $args);
    $c++;
}

function commentSeparatorLine(){ printf("////////////////////////////////////////////////////////////////////////////\n"); }
function stdoutMinusLine(){ return 'printf("--------------------------------------------------------------------------------------------------------\n");'; }

if($showheader) include(TEMPLATEFOLDER.'/header.php');

if($codegenkind === CODEGEN_SWITCH){
    $regs = []; $regnames = []; $regpercents = [];
    foreach($cpustate as $k=>$v) {
        $regnames[] = "r{$k}";
        $regpercents[] = "%9d";
        $regs[] = sprintf("r{$k}={$v}"); 
    }
    printf("int %s;\n", join(',',$regs));
    printf("char _regsbuffer[255];\n");
    printf("char * Elf_regs2string(){");
    echo  ('  sprintf(_regsbuffer, "R:['.join(',',$regpercents).']", '.join(',',$regnames).");");
    printf("  return _regsbuffer; ");
    printf("}\n");
    printf("bool Elf_emulate(long maxCount, long *actualIterationCount)\n");
    printf("{\n");
    printf("  long c=0;\n");
    printf("  int ip=0;\n");
    printf("  while(c<maxCount){ \n");
    printf("    ip = r{$ipidx};\n");
    printf("    switch(ip){  \n");
    $programsize = count($program); for($i=0;$i<$programsize;$i++){
        $ins = $program[$i];
        $cop = ";";    
        switch($ins[0]){
         case "addr": $cop = " r{$ins[3]} = r{$ins[1]} + r{$ins[2]}; "; break;
         case "addi": $cop = " r{$ins[3]} = r{$ins[1]} + {$ins[2]}; "; break;
         case "mulr": $cop = " r{$ins[3]} = r{$ins[1]} * r{$ins[2]}; "; break;
         case "muli": $cop = " r{$ins[3]} = r{$ins[1]} * {$ins[2]}; "; break;
         case "banr": $cop = " r{$ins[3]} = r{$ins[1]} & r{$ins[2]}; "; break;
         case "bani": $cop = " r{$ins[3]} = r{$ins[1]} & {$ins[2]}; "; break;
         case "borr": $cop = " r{$ins[3]} = r{$ins[1]} | r{$ins[2]}; "; break;
         case "bori": $cop = " r{$ins[3]} = r{$ins[1]} | {$ins[2]}; "; break;
         case "setr": $cop = " r{$ins[3]} = r{$ins[1]}; "; break;
         case "seti": $cop = " r{$ins[3]} = {$ins[1]}; "; break;
         case "gtir": $cop = " r{$ins[3]} = ({$ins[1]} > r{$ins[2]})?1:0; "; break;
         case "gtri": $cop = " r{$ins[3]} = (r{$ins[1]} > {$ins[2]})?1:0; "; break;
         case "gtrr": $cop = " r{$ins[3]} = (r{$ins[1]} > r{$ins[2]})?1:0; "; break;
         case "eqir": $cop = " r{$ins[3]} = ({$ins[1]} == r{$ins[2]})?1:0; "; break;
         case "eqri": $cop = " r{$ins[3]} = (r{$ins[1]} == {$ins[2]})?1:0; "; break;
         case "eqrr": $cop = " r{$ins[3]} = (r{$ins[1]} == r{$ins[2]})?1:0; "; break;
        }
        printf("          case %4d: %-23s break; // %s %-9d %-9d %-9d\n", $i, $cop, $ins[0], $ins[1],$ins[2],$ins[3]);
    }
    printf("          default: { \n");
    echo  ('              '.stdoutMinusLine()."\n");
    echo  ('              printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, ip ); '."\n");
    printf("              *actualIterationCount += c;\n");
    printf("              return false;\n");
    printf("          }\n");
    printf("    }\n");
    printf("    r{$ipidx}++;\n");
    printf("    ip = r{$ipidx};\n");
    printf("    c++;\n");
    printf("  }\n");
    printf("  *actualIterationCount += c;\n");
    printf("  return true;\n");
    printf("}\n");
}


if($showfooter) include(TEMPLATEFOLDER.'/footer.php');

exit(0);
