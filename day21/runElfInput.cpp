#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/time.h>
#include "elfProgram.cpp"

int main(int argc, char **argv){
   int batchsize = 10000000;
   if( argc > 1 ) r0 = atoi(argv[1]);
   if( argc > 2 ) batchsize = atoi(argv[2]);
   int c=0; bool stillRuns=true;
   do{
      printf("BSize: %d. elfVM iterated %d times | regs: %s\n", batchsize, c, Elf_regs2string());
      stillRuns = Elf_emulate(batchsize,&c);
      if( stillRuns )continue;
      printf("BSize: %d. Final run of elfVM lasted %d iterations | regs: %s\n", batchsize, c, Elf_regs2string());
   }while(stillRuns);
   printf("Goodbye.\n");
   exit(0);
}
