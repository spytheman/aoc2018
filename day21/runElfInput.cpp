#include <stdlib.h>
#include "elfProgram.cpp"

int main(int argc, char **argv){
   int batchsize = 10000000;
   if( argc > 1 ) r0 = atoi(argv[1]);
   if( argc > 2 ) batchsize = atoi(argv[2]);
   long c=0; bool stillRuns=true;
   do{
      printf("CPU at step: %12ld | BSize: %10d. elfVM regs: %s\n", c, batchsize, Elf_regs2string()); fflush(stdout);
      stillRuns = Elf_emulate(batchsize,&c);
      if( stillRuns )continue;
      printf("CPU at step: %12ld | BSize: %10d. Final run of elfVM | regs: %s\n", c, batchsize, Elf_regs2string());
   }while(stillRuns);
   printf("Goodbye.\n");
   exit(0);
}
