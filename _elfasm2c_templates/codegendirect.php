<?php
$programsize = count($program); 
$rip = "r{$ipidx}";
printf("\n");
printf("///  IP === {$rip} \n");
printf("///  ^^^^^^^^^\n");
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
echo("#define badJump(line, xIP) { printf(\"Long jump made at line %d . C: %12ld, IP was: %d. %s .\\n\", (line), (c), (xIP), Elf_regs2string() ); abort(); }\n");
echo("#define BEND {if(c>=maxCount)goto lBatchFinished;}\n");
echo("#define IEND { ++{$rip}, ++c; BEND; }\n");
echo("#pragma GCC diagnostic push\n");
echo("#pragma GCC diagnostic ignored \"-Wpedantic\"\n");    
printf("bool Elf_emulate(long maxCount, long *actualIterationCount)\n");
printf("{\n");
$programEndWithPadding=$programsize+2;
$glabels = []; for($i=0;$i<$programEndWithPadding;$i++){ $glabels[] = "&&l{$i}"; }
printf("  static void *glabels[] = { %s };\n", join(', ', $glabels));
printf("  long c=0;\n");
printf("  int *ip = &{$rip}; \n");
printf("\n");
printf("  goto *glabels[ {$rip} ]; \n"); // allows the continuation of previous batch runs
printf("\n");
for($i=0;$i<$programsize;$i++){
    $label = "l{$i}:";
    $ins = $program[$i];
    $cop = ";";
    $smetainstruction = "IEND;";
    switch($ins[0]){
     case "addr": $cop = " r{$ins[3]}=r{$ins[1]}+r{$ins[2]};"; break;
     case "addi": $cop = " r{$ins[3]}=r{$ins[1]}+{$ins[2]};"; break;
     case "mulr": $cop = " r{$ins[3]}=r{$ins[1]}*r{$ins[2]};"; break;
     case "muli": $cop = " r{$ins[3]}=r{$ins[1]}*{$ins[2]};"; break;
     case "banr": $cop = " r{$ins[3]}=r{$ins[1]}&r{$ins[2]};"; break;
     case "bani": $cop = " r{$ins[3]}=r{$ins[1]}&{$ins[2]};"; break;
     case "borr": $cop = " r{$ins[3]}=r{$ins[1]}|r{$ins[2]};"; break;
     case "bori": $cop = " r{$ins[3]}=r{$ins[1]}|{$ins[2]};"; break;
     case "setr": $cop = " r{$ins[3]}=r{$ins[1]};"; break;
     case "seti": $cop = " r{$ins[3]}={$ins[1]};"; break;
     case "gtir": $cop = " r{$ins[3]}=({$ins[1]}>r{$ins[2]})?1:0;"; break;
     case "gtri": $cop = " r{$ins[3]}=(r{$ins[1]}>{$ins[2]})?1:0;"; break;
     case "gtrr": $cop = " r{$ins[3]}=(r{$ins[1]}>r{$ins[2]})?1:0;"; break;
     case "eqir": $cop = " r{$ins[3]}=({$ins[1]}==r{$ins[2]})?1:0;"; break;
     case "eqri": $cop = " r{$ins[3]}=(r{$ins[1]}=={$ins[2]})?1:0;"; break;
     case "eqrr": $cop = " r{$ins[3]}=(r{$ins[1]}==r{$ins[2]})?1:0;"; break;
    }
    if($ins[3] === $ipidx){
        $smetainstruction .= " if( {$rip} > {$programsize} ) badJump({$i}, {$rip}); goto *glabels[ {$rip} ]; "; // not optimized, but works in every case :-)
        switch($ins[0]){
         case 'seti':{
             $nip = ($ins[1]+1); // +1 since the IP should be incremented *after each* instruction
             $lgoto = "l{$nip}"; 
             $cop = ""; $smetainstruction = "++c;{$rip}={$nip};BEND;goto {$lgoto};";
             break;
         }
         case 'addr':{
             if($ins[2]===$ipidx){
                 $conditionR  = "r{$ins[1]}";
                 $lnext = "l".($i+1);
                 $lskip = "l".($i+2);
                 $cop = ""; $smetainstruction = "++c;{$rip}++;if({$conditionR}==1){$rip}++;BEND;if({$conditionR}==0){goto {$lnext};} else if({$conditionR}==1){goto {$lskip};} else badJump({$i}, r{$ins[1]}+{$rip} );";
             }
             break;
         }
         case 'addi':{
             if($ins[2]===$ipidx){
                 $nip = ($i+$ins[1]+1);
                 $lgoto = "l{$nip}";
                 $cop = ""; $smetainstruction = "++c;{$rip}={$nip};BEND;goto {$lgoto};";
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
printf("          return false;\n");

printf("\n");
printf("     lBatchFinished: \n");
printf("       *actualIterationCount += c;\n");
printf("       return true;\n");
printf("}\n");
echo("#pragma GCC diagnostic pop\n");
