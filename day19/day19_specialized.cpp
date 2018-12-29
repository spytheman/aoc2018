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

///  IP === s1 
///  ^^^^^^^^^
int r0=0,r1=0,r2=0,r3=0,r4=0,r5=0;
#define _LOADREGS { s0=r0; s1=r1; s2=r2; s3=r3; s4=r4; s5=r5; }
#define _SAVEREGS { r0=s0; r1=s1; r2=s2; r3=s3; r4=s4; r5=s5; }

char _regsbuffer[255];
char * __attribute__((noinline))  Elf_regs2string(){  sprintf(_regsbuffer, "R:[%9d,%9d,%9d,%9d,%9d,%9d]", r0,r1,r2,r3,r4,r5);  return _regsbuffer; }

void * __attribute__((noinline)) badJump(long int c, int line, int xIP){
    printf("Long jump made at line %d . C: %12ld, IP was: %d. %s .\n", (line), (c), (xIP), Elf_regs2string() );
    abort();
}
#define BEND {if(c>=maxCount)goto lBatchFinished;}
#define IEND { ++s1, ++c; BEND; }
#pragma GCC diagnostic push
#pragma GCC diagnostic ignored "-Wpedantic"
bool Elf_emulate(long maxCount, long *actualIterationCount)
{
  static void *glabels[] = { &&l0, &&l1, &&l2, &&l3, &&l4, &&l5, &&l6, &&l7, &&l8, &&l9, &&l10, &&l11, &&l12, &&l13, &&l14, &&l15, &&l16, &&l17, &&l18, &&l19, &&l20, &&l21, &&l22, &&l23, &&l24, &&l25, &&l26, &&l27, &&l28, &&l29, &&l30, &&l31, &&l32, &&l33, &&l34, &&l35, &&l36, &&l37 };
  long c=0;

#pragma GCC diagnostic push
#pragma GCC diagnostic ignored "-Wdeprecated-register"
  register int s0=0,s1=0,s2=0,s3=0,s4=0,s5=0; 
  _LOADREGS;
#pragma GCC diagnostic pop

  int *ip = &s1; 

  goto *glabels[ s1 ]; 

      l0: /*["addi",1,16,1]       */  s1=s1+16;             IEND; if(s1>36){_SAVEREGS;badJump(c,0,s1);} else goto *glabels[ s1 ]; 
      l1: /*["seti",1,2,5]        */  s5=1;                 IEND;
      l2: /*["seti",1,2,2]        */  s2=1;                 IEND;
      l3: /*["mulr",5,2,3]        */  s3=s5*s2;             IEND;
      l4: /*["eqrr",3,4,3]        */  s3=(s3==s4)?1:0;      IEND;
      l5: /*["addr",3,1,1]        */                        ++c;s1++;if(s3==1)s1++;BEND;if(s3==0){goto l6;} else if(s3==1){goto l7;} else {_SAVEREGS;badJump(c,5,s3+s1);}
      l6: /*["addi",1,1,1]        */                        ++c;s1=8;BEND;goto l8;
      l7: /*["addr",5,0,0]        */  s0=s5+s0;             IEND;
      l8: /*["addi",2,1,2]        */  s2=s2+1;              IEND;
      l9: /*["gtrr",2,4,3]        */  s3=(s2>s4)?1:0;       IEND;
     l10: /*["addr",1,3,1]        */  s1=s1+s3;             IEND; if(s1>36){_SAVEREGS;badJump(c,10,s1);} else goto *glabels[ s1 ]; 
     l11: /*["seti",2,8,1]        */                        ++c;s1=3;BEND;goto l3;
     l12: /*["addi",5,1,5]        */  s5=s5+1;              IEND;
     l13: /*["gtrr",5,4,3]        */  s3=(s5>s4)?1:0;       IEND;
     l14: /*["addr",3,1,1]        */                        ++c;s1++;if(s3==1)s1++;BEND;if(s3==0){goto l15;} else if(s3==1){goto l16;} else {_SAVEREGS;badJump(c,14,s3+s1);}
     l15: /*["seti",1,1,1]        */                        ++c;s1=2;BEND;goto l2;
     l16: /*["mulr",1,1,1]        */  s1=s1*s1;             IEND; if(s1>36){_SAVEREGS;badJump(c,16,s1);} else goto *glabels[ s1 ]; 
     l17: /*["addi",4,2,4]        */  s4=s4+2;              IEND;
     l18: /*["mulr",4,4,4]        */  s4=s4*s4;             IEND;
     l19: /*["mulr",1,4,4]        */  s4=s1*s4;             IEND;
     l20: /*["muli",4,11,4]       */  s4=s4*11;             IEND;
     l21: /*["addi",3,3,3]        */  s3=s3+3;              IEND;
     l22: /*["mulr",3,1,3]        */  s3=s3*s1;             IEND;
     l23: /*["addi",3,4,3]        */  s3=s3+4;              IEND;
     l24: /*["addr",4,3,4]        */  s4=s4+s3;             IEND;
     l25: /*["addr",1,0,1]        */  s1=s1+s0;             IEND; if(s1>36){_SAVEREGS;badJump(c,25,s1);} else goto *glabels[ s1 ]; 
     l26: /*["seti",0,0,1]        */                        ++c;s1=1;BEND;goto l1;
     l27: /*["setr",1,5,3]        */  s3=s1;                IEND;
     l28: /*["mulr",3,1,3]        */  s3=s3*s1;             IEND;
     l29: /*["addr",1,3,3]        */  s3=s1+s3;             IEND;
     l30: /*["mulr",1,3,3]        */  s3=s1*s3;             IEND;
     l31: /*["muli",3,14,3]       */  s3=s3*14;             IEND;
     l32: /*["mulr",3,1,3]        */  s3=s3*s1;             IEND;
     l33: /*["addr",4,3,4]        */  s4=s4+s3;             IEND;
     l34: /*["seti",0,0,0]        */  s0=0;                 IEND;
     l35: /*["seti",0,1,1]        */                        ++c;s1=1;BEND;goto l1;

     l36: ;
     l37: ;
          printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, *ip ); 
          *actualIterationCount += c;
          _SAVEREGS;
          return false;

     lBatchFinished: 
       *actualIterationCount += c;
       _SAVEREGS;
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
