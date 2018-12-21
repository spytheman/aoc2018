#!/usr/bin/env php
<?php
include("common.php");
define('TEMPLATEFOLDER', realpath(__DIR__).'/_elfasm2c_templates');

$lines = read_input();
$program = []; $ipidx =0; $cpustate = [0,0,0, 0,0,0];
if(isset($argv[2]))$cpustate[0]=(int)$argv[2];
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

include(TEMPLATEFOLDER.'/header.php');

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

include(TEMPLATEFOLDER.'/footer.php');

exit(0);
