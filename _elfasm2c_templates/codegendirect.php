<?php
$programsize = count($program); 
$sip = "s{$ipidx}";
printf("\n");
printf("///  IP === {$sip} \n");
printf("///  ^^^^^^^^^\n");
$regs = []; $regnames = []; $regpercents = [];
foreach($cpustate as $k=>$v) {
    $regnames[] = "r{$k}";
    $regpercents[] = "%9d";
    $regs[] = sprintf("r{$k}={$v}"); 
}
printf("int %s;\n", join(',',$regs));
printf("#define _LOADREGS { s0=r0; s1=r1; s2=r2; s3=r3; s4=r4; s5=r5; }\n");
printf("#define _SAVEREGS { r0=s0; r1=s1; r2=s2; r3=s3; r4=s4; r5=s5; }\n");
echo "\n";
printf("char _regsbuffer[255];\n");
printf("char * __attribute__((noinline))  Elf_regs2string(){");
echo  ('  sprintf(_regsbuffer, "R:['.join(',',$regpercents).']", '.join(',',$regnames).");");
printf("  return _regsbuffer; ");
printf("}\n");
echo "\n";
echo("void * __attribute__((noinline)) badJump(long int c, int line, int xIP){\n");
echo("    printf(\"Long jump made at line %d . C: %12ld, IP was: %d. %s .\\n\", (line), (c), (xIP), Elf_regs2string() );\n");
echo("    abort();\n");
echo("}\n");
echo("#define BEND {if(c>=maxCount)goto lBatchFinished;}\n");
echo("#define IEND { ++{$sip}, ++c; BEND; }\n");
echo("#pragma GCC diagnostic push\n");
echo("#pragma GCC diagnostic ignored \"-Wpedantic\"\n");    
printf("bool Elf_emulate(long maxCount, long *actualIterationCount)\n");
printf("{\n");
$programEndWithPadding=$programsize+2;
$glabels = []; for($i=0;$i<$programEndWithPadding;$i++){ $glabels[] = "&&l{$i}"; }
printf("  static void *glabels[] = { %s };\n", join(', ', $glabels));
printf("  long c=0;\n");
echo "\n";
echo("#pragma GCC diagnostic push\n");
echo("#pragma GCC diagnostic ignored \"-Wdeprecated-register\"\n");
printf("  register int %s; \n", str_replace('r', 's', join(',',$regs)));
printf("  _LOADREGS;\n");
echo("#pragma GCC diagnostic pop\n");
echo "\n";
printf("  int *ip = &{$sip}; \n");
printf("\n");
printf("  goto *glabels[ {$sip} ]; \n"); // allows the continuation of previous batch runs
printf("\n");
for($i=0;$i<$programsize;$i++){
    $label = "l{$i}:";
    $ins = $program[$i];
    $cop = ";";
    $smetainstruction = "IEND;";
    switch($ins[0]){
     case "addr": $cop = " s{$ins[3]}=s{$ins[1]}+s{$ins[2]};"; break;
     case "addi": $cop = " s{$ins[3]}=s{$ins[1]}+{$ins[2]};"; break;
     case "mulr": $cop = " s{$ins[3]}=s{$ins[1]}*s{$ins[2]};"; break;
     case "muli": $cop = " s{$ins[3]}=s{$ins[1]}*{$ins[2]};"; break;
     case "banr": $cop = " s{$ins[3]}=s{$ins[1]}&s{$ins[2]};"; break;
     case "bani": $cop = " s{$ins[3]}=s{$ins[1]}&{$ins[2]};"; break;
     case "borr": $cop = " s{$ins[3]}=s{$ins[1]}|s{$ins[2]};"; break;
     case "bori": $cop = " s{$ins[3]}=s{$ins[1]}|{$ins[2]};"; break;
     case "setr": $cop = " s{$ins[3]}=s{$ins[1]};"; break;
     case "seti": $cop = " s{$ins[3]}={$ins[1]};"; break;
     case "gtir": $cop = " s{$ins[3]}=({$ins[1]}>s{$ins[2]})?1:0;"; break;
     case "gtri": $cop = " s{$ins[3]}=(s{$ins[1]}>{$ins[2]})?1:0;"; break;
     case "gtrr": $cop = " s{$ins[3]}=(s{$ins[1]}>s{$ins[2]})?1:0;"; break;
     case "eqir": $cop = " s{$ins[3]}=({$ins[1]}==s{$ins[2]})?1:0;"; break;
     case "eqri": $cop = " s{$ins[3]}=(s{$ins[1]}=={$ins[2]})?1:0;"; break;
     case "eqrr": $cop = " s{$ins[3]}=(s{$ins[1]}==s{$ins[2]})?1:0;"; break;
    }
    if($ins[3] === $ipidx){
        $smetainstruction .= " if({$sip}>{$programsize}){_SAVEREGS;badJump(c,{$i},{$sip});} else goto *glabels[ {$sip} ]; "; // not optimized, but works in every case :-)
        switch($ins[0]){
         case 'seti':{
             $nip = ($ins[1]+1); // +1 since the IP should be incremented *after each* instruction
             $lgoto = "l{$nip}"; 
             $cop = ""; $smetainstruction = "++c;{$sip}={$nip};BEND;goto {$lgoto};";
             break;
         }
         case 'addr':{
             if($ins[2]===$ipidx){
                 $conditionR  = "s{$ins[1]}";
                 $lnext = "l".($i+1);
                 $lskip = "l".($i+2);
                 $cop = ""; $smetainstruction = "++c;{$sip}++;if({$conditionR}==1){$sip}++;BEND;if({$conditionR}==0){goto {$lnext};} else if({$conditionR}==1){goto {$lskip};} else {_SAVEREGS;badJump(c,{$i},s{$ins[1]}+{$sip});}";
             }
             break;
         }
         case 'addi':{
             if($ins[2]===$ipidx){
                 $nip = ($i+$ins[1]+1);
                 $lgoto = "l{$nip}";
                 $cop = ""; $smetainstruction = "++c;{$sip}={$nip};BEND;goto {$lgoto};";
             }
             break;
         }
        }
    }
    printf("   %6s /*%-22s*/ %-22s %s\n", $label, ve($ins), $cop, $smetainstruction);
}
printf("\n");
for($x=$i;$x<$programEndWithPadding;$x++){   printf("     l{$x}: ;\n"); }
echo  ('          printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, *ip ); '."\n");
printf("          *actualIterationCount += c;\n");
printf("          _SAVEREGS;\n");
printf("          return false;\n");

printf("\n");
printf("     lBatchFinished: \n");
printf("       *actualIterationCount += c;\n");
printf("       _SAVEREGS;\n");
printf("       return true;\n");
printf("}\n");
echo("#pragma GCC diagnostic pop\n");
