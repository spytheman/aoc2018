#include <stdlib.h>
#include <stdio.h>
#include <iostream>     // std::cout, std::endl
#include <iomanip>      // std::setfill, std::setw

////////////////////////////////////////////////////////////////////////////
/// This is a generated file. Edit it on your risk.
/// This was produced by running: elfasm2c.php input
/// This text should be put into a C++ file, for example elfProgram.cpp 
/// ... then it should be compiled with: 
///     g++ -Wall -pedantic -Ofast -march=native -mtune=native -mavx -c elfProgram.cpp  -o elfProgram.o 
////////////////////////////////////////////////////////////////////////////

/// ipidx: 1
//// HIGHLY EXPERIMENTAL!!! Code generated by --codegendirect 
//// TODO 

///  IP === r1 
///  ^^^^^^^^^
int r0=0,r1=0,r2=0,r3=0,r4=0,r5=0;
char _regsbuffer[255];
char * Elf_regs2string(){  sprintf(_regsbuffer, "R:[%9d,%9d,%9d,%9d,%9d,%9d]", r0,r1,r2,r3,r4,r5);  return _regsbuffer; }
#define badJump(line, xIP) { printf("Long jump made at line %d . IP was: %d. %s .\n", (line), (xIP), Elf_regs2string() ); abort(); } 
#define IPOST { r1++; c++; if( c >= maxCount ) goto lBatchFinished; } 
bool Elf_emulate(long maxCount, long *actualIterationCount)
{
  static void *glabels[] = { &&l0, &&l1, &&l2, &&l3, &&l4, &&l5, &&l6, &&l7, &&l8, &&l9, &&l10, &&l11, &&l12, &&l13, &&l14, &&l15, &&l16, &&l17, &&l18, &&l19, &&l20, &&l21, &&l22, &&l23, &&l24, &&l25, &&l26, &&l27, &&l28, &&l29, &&l30, &&l31, &&l32, &&l33, &&l34, &&l35, &&l36, &&l37, &&l38, &&l39, &&l40 };
  long c=0;
  int *ip = &r1; 

  goto *glabels[ r1 ]; 

      l0:   r1 = r1 + 16;             IPOST;  if( r1 > 36 ) badJump(0, r1); goto *glabels[ r1 ];  // ["addi",1,16,1]
      l1:   r5 = 1;                   IPOST;  // ["seti",1,2,5]
      l2:   r2 = 1;                   IPOST;  // ["seti",1,2,2]
      l3:   r3 = r5 * r2;             IPOST;  // ["mulr",5,2,3]
      l4:   r3 = (r3 == r4)?1:0;      IPOST;  // ["eqrr",3,4,3]
      l5:   r1 = r3 + r1;             IPOST;  if( r1 > 36 ) badJump(5, r1); goto *glabels[ r1 ];  // ["addr",3,1,1]
      l6:   r1 = r1 + 1;              IPOST;  if( r1 > 36 ) badJump(6, r1); goto *glabels[ r1 ];  // ["addi",1,1,1]
      l7:   r0 = r5 + r0;             IPOST;  // ["addr",5,0,0]
      l8:   r2 = r2 + 1;              IPOST;  // ["addi",2,1,2]
      l9:   r3 = (r2 > r4)?1:0;       IPOST;  // ["gtrr",2,4,3]
     l10:   r1 = r1 + r3;             IPOST;  if( r1 > 36 ) badJump(10, r1); goto *glabels[ r1 ];  // ["addr",1,3,1]
     l11:   r1 = 2;                   IPOST;  if( r1 > 36 ) badJump(11, r1); goto *glabels[ r1 ];  // ["seti",2,8,1]
     l12:   r5 = r5 + 1;              IPOST;  // ["addi",5,1,5]
     l13:   r3 = (r5 > r4)?1:0;       IPOST;  // ["gtrr",5,4,3]
     l14:   r1 = r3 + r1;             IPOST;  if( r1 > 36 ) badJump(14, r1); goto *glabels[ r1 ];  // ["addr",3,1,1]
     l15:   r1 = 1;                   IPOST;  if( r1 > 36 ) badJump(15, r1); goto *glabels[ r1 ];  // ["seti",1,1,1]
     l16:   r1 = r1 * r1;             IPOST;  if( r1 > 36 ) badJump(16, r1); goto *glabels[ r1 ];  // ["mulr",1,1,1]
     l17:   r4 = r4 + 2;              IPOST;  // ["addi",4,2,4]
     l18:   r4 = r4 * r4;             IPOST;  // ["mulr",4,4,4]
     l19:   r4 = r1 * r4;             IPOST;  // ["mulr",1,4,4]
     l20:   r4 = r4 * 11;             IPOST;  // ["muli",4,11,4]
     l21:   r3 = r3 + 3;              IPOST;  // ["addi",3,3,3]
     l22:   r3 = r3 * r1;             IPOST;  // ["mulr",3,1,3]
     l23:   r3 = r3 + 4;              IPOST;  // ["addi",3,4,3]
     l24:   r4 = r4 + r3;             IPOST;  // ["addr",4,3,4]
     l25:   r1 = r1 + r0;             IPOST;  if( r1 > 36 ) badJump(25, r1); goto *glabels[ r1 ];  // ["addr",1,0,1]
     l26:   r1 = 0;                   IPOST;  if( r1 > 36 ) badJump(26, r1); goto *glabels[ r1 ];  // ["seti",0,0,1]
     l27:   r3 = r1;                  IPOST;  // ["setr",1,5,3]
     l28:   r3 = r3 * r1;             IPOST;  // ["mulr",3,1,3]
     l29:   r3 = r1 + r3;             IPOST;  // ["addr",1,3,3]
     l30:   r3 = r1 * r3;             IPOST;  // ["mulr",1,3,3]
     l31:   r3 = r3 * 14;             IPOST;  // ["muli",3,14,3]
     l32:   r3 = r3 * r1;             IPOST;  // ["mulr",3,1,3]
     l33:   r4 = r4 + r3;             IPOST;  // ["addr",4,3,4]
     l34:   r0 = 0;                   IPOST;  // ["seti",0,0,0]
     l35:   r1 = 0;                   IPOST;  if( r1 > 36 ) badJump(35, r1); goto *glabels[ r1 ];  // ["seti",0,1,1]

     l36: ; // program end padding 
     l37: ; // program end padding 
     l38: ; // program end padding 
     l39: ; // program end padding 
     l40: ; // program end padding 
          printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, *ip ); 
          *actualIterationCount += c;
          return false;

     lBatchFinished: 
       *actualIterationCount += c;
       return true;
}


int main(int argc, char **argv)
{
   setbuf(stdout, NULL);

   int batchsize = 500000000; // ~1s realtime
   if( argc > 1 ) r0 = atoi(argv[1]);
   if( argc > 2 ) batchsize = atoi(argv[2]);

   printf("Batch size: %10d\n", batchsize);
   printf("Initial registers: %21s %s\n", " ", Elf_regs2string());
   printf("--------------------------------------------------------------------------------------------------------\n");
   long c=0; bool stillRuns=true;
   do{
      printf("CPU at step: %12ld | elfVM regs : %s\n", c, Elf_regs2string());
      stillRuns = Elf_emulate(batchsize,&c);
      if( stillRuns )continue;
      printf("CPU at step: %12ld | elfVM final: %s\n", c, Elf_regs2string());
   }while(stillRuns);

   printf("--------------------------------------------------------------------------------------------------------\n");
   printf("Instructions: %ld\n", c);
   exit(0);
}
