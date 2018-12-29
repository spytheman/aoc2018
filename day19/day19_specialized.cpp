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

///  IP === r1 
///  ^^^^^^^^^
int r0=0,r1=0,r2=0,r3=0,r4=0,r5=0;
char _regsbuffer[255];
char * Elf_regs2string(){  sprintf(_regsbuffer, "R:[%9d,%9d,%9d,%9d,%9d,%9d]", r0,r1,r2,r3,r4,r5);  return _regsbuffer; }
#define badJump(line, xIP) { printf("Long jump made at line %d . C: %12ld, IP was: %d. %s .\n", (line), (c), (xIP), Elf_regs2string() ); abort(); }
#define BEND {if(c>=maxCount)goto lBatchFinished;}
#define IEND { ++r1, ++c; BEND; }
#pragma GCC diagnostic push
#pragma GCC diagnostic ignored "-Wpedantic"
bool Elf_emulate(long maxCount, long *actualIterationCount)
{
  static void *glabels[] = { &&l0, &&l1, &&l2, &&l3, &&l4, &&l5, &&l6, &&l7, &&l8, &&l9, &&l10, &&l11, &&l12, &&l13, &&l14, &&l15, &&l16, &&l17, &&l18, &&l19, &&l20, &&l21, &&l22, &&l23, &&l24, &&l25, &&l26, &&l27, &&l28, &&l29, &&l30, &&l31, &&l32, &&l33, &&l34, &&l35, &&l36, &&l37 };
  long c=0;
  int *ip = &r1; 

  goto *glabels[ r1 ]; 

      l0: /*["addi",1,16,1]       */  r1=r1+16;             IEND; if( r1 > 36 ) badJump(0, r1); goto *glabels[ r1 ]; 
      l1: /*["seti",1,2,5]        */  r5=1;                 IEND;
      l2: /*["seti",1,2,2]        */  r2=1;                 IEND;
      l3: /*["mulr",5,2,3]        */  r3=r5*r2;             IEND;
      l4: /*["eqrr",3,4,3]        */  r3=(r3==r4)?1:0;      IEND;
      l5: /*["addr",3,1,1]        */                        ++c;r1++;if(r3==1)r1++;BEND;if(r3==0){goto l6;} else if(r3==1){goto l7;} else badJump(5, r3+r1 );
      l6: /*["addi",1,1,1]        */                        ++c;r1=8;BEND;goto l8;
      l7: /*["addr",5,0,0]        */  r0=r5+r0;             IEND;
      l8: /*["addi",2,1,2]        */  r2=r2+1;              IEND;
      l9: /*["gtrr",2,4,3]        */  r3=(r2>r4)?1:0;       IEND;
     l10: /*["addr",1,3,1]        */  r1=r1+r3;             IEND; if( r1 > 36 ) badJump(10, r1); goto *glabels[ r1 ]; 
     l11: /*["seti",2,8,1]        */                        ++c;r1=3;BEND;goto l3;
     l12: /*["addi",5,1,5]        */  r5=r5+1;              IEND;
     l13: /*["gtrr",5,4,3]        */  r3=(r5>r4)?1:0;       IEND;
     l14: /*["addr",3,1,1]        */                        ++c;r1++;if(r3==1)r1++;BEND;if(r3==0){goto l15;} else if(r3==1){goto l16;} else badJump(14, r3+r1 );
     l15: /*["seti",1,1,1]        */                        ++c;r1=2;BEND;goto l2;
     l16: /*["mulr",1,1,1]        */  r1=r1*r1;             IEND; if( r1 > 36 ) badJump(16, r1); goto *glabels[ r1 ]; 
     l17: /*["addi",4,2,4]        */  r4=r4+2;              IEND;
     l18: /*["mulr",4,4,4]        */  r4=r4*r4;             IEND;
     l19: /*["mulr",1,4,4]        */  r4=r1*r4;             IEND;
     l20: /*["muli",4,11,4]       */  r4=r4*11;             IEND;
     l21: /*["addi",3,3,3]        */  r3=r3+3;              IEND;
     l22: /*["mulr",3,1,3]        */  r3=r3*r1;             IEND;
     l23: /*["addi",3,4,3]        */  r3=r3+4;              IEND;
     l24: /*["addr",4,3,4]        */  r4=r4+r3;             IEND;
     l25: /*["addr",1,0,1]        */  r1=r1+r0;             IEND; if( r1 > 36 ) badJump(25, r1); goto *glabels[ r1 ]; 
     l26: /*["seti",0,0,1]        */                        ++c;r1=1;BEND;goto l1;
     l27: /*["setr",1,5,3]        */  r3=r1;                IEND;
     l28: /*["mulr",3,1,3]        */  r3=r3*r1;             IEND;
     l29: /*["addr",1,3,3]        */  r3=r1+r3;             IEND;
     l30: /*["mulr",1,3,3]        */  r3=r1*r3;             IEND;
     l31: /*["muli",3,14,3]       */  r3=r3*14;             IEND;
     l32: /*["mulr",3,1,3]        */  r3=r3*r1;             IEND;
     l33: /*["addr",4,3,4]        */  r4=r4+r3;             IEND;
     l34: /*["seti",0,0,0]        */  r0=0;                 IEND;
     l35: /*["seti",0,1,1]        */                        ++c;r1=1;BEND;goto l1;

     l36: ;
     l37: ;
          printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, *ip ); 
          *actualIterationCount += c;
          return false;

     lBatchFinished: 
       *actualIterationCount += c;
       return true;
}
#pragma GCC diagnostic pop


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
