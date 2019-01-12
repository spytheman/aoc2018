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
  static void *glabels[] = { &&l0, &&l1, &&l2, &&l3, &&l4, &&l5, &&l6, &&l7, &&l8, &&l9, &&l10, &&l11, &&l12, &&l13, &&l14, &&l15, &&l16, &&l17, &&l18, &&l19, &&l20, &&l21, &&l22, &&l23, &&l24, &&l25, &&l26, &&l27, &&l28, &&l29, &&l30, &&l31, &&l32 };
  long c=0;

#pragma GCC diagnostic push
#pragma GCC diagnostic ignored "-Wdeprecated-register"
  register int s0=0,s1=0,s2=0,s3=0,s4=0,s5=0; 
  _LOADREGS;
#pragma GCC diagnostic pop

  int *ip = &s1; 

  goto *glabels[ s1 ]; 

      l0: /*["seti",123,0,3]      */  s3=123;               IEND;
      l1: /*["bani",3,456,3]      */  s3=s3&456;            IEND;
      l2: /*["eqri",3,72,3]       */  s3=(s3==72)?1:0;      IEND;
      l3: /*["addr",3,1,1]        */                        ++c;s1++;if(s3==1)s1++;BEND;if(s3==0){goto l4;} else if(s3==1){goto l5;} else {_SAVEREGS;badJump(c,3,s3+s1);}
      l4: /*["seti",0,0,1]        */                        ++c;s1=1;BEND;goto l1;
      l5: /*["seti",0,9,3]        */  s3=0;                 IEND;
      l6: /*["bori",3,65536,5]    */  s5=s3|65536;          IEND;
      l7: /*["seti",15028787,4,3] */  s3=15028787;          IEND;
      l8: /*["bani",5,255,2]      */  s2=s5&255;            IEND;
      l9: /*["addr",3,2,3]        */  s3=s3+s2;             IEND;
     l10: /*["bani",3,16777215,3] */  s3=s3&16777215;       IEND;
     l11: /*["muli",3,65899,3]    */  s3=s3*65899;          IEND;
     l12: /*["bani",3,16777215,3] */  s3=s3&16777215;       IEND;
     l13: /*["gtir",256,5,2]      */  s2=(256>s5)?1:0;      IEND;
     l14: /*["addr",2,1,1]        */                        ++c;s1++;if(s2==1)s1++;BEND;if(s2==0){goto l15;} else if(s2==1){goto l16;} else {_SAVEREGS;badJump(c,14,s2+s1);}
     l15: /*["addi",1,1,1]        */                        ++c;s1=17;BEND;goto l17;
     l16: /*["seti",27,3,1]       */                        ++c;s1=28;BEND;goto l28;
     l17: /*["seti",0,9,2]        */  s2=0;                 IEND;
     l18: /*["addi",2,1,4]        */  s4=s2+1;              IEND;
     l19: /*["muli",4,256,4]      */  s4=s4*256;            IEND;
     l20: /*["gtrr",4,5,4]        */  s4=(s4>s5)?1:0;       IEND;
     l21: /*["addr",4,1,1]        */                        ++c;s1++;if(s4==1)s1++;BEND;if(s4==0){goto l22;} else if(s4==1){goto l23;} else {_SAVEREGS;badJump(c,21,s4+s1);}
     l22: /*["addi",1,1,1]        */                        ++c;s1=24;BEND;goto l24;
     l23: /*["seti",25,1,1]       */                        ++c;s1=26;BEND;goto l26;
     l24: /*["addi",2,1,2]        */  s2=s2+1;              IEND;
     l25: /*["seti",17,8,1]       */                        ++c;s1=18;BEND;goto l18;
     l26: /*["setr",2,4,5]        */  s5=s2;                IEND;
     l27: /*["seti",7,3,1]        */                        ++c;s1=8;BEND;goto l8;
     l28: /*["eqrr",3,0,2]        */  s2=(s3==s0)?1:0;      IEND;
     l29: /*["addr",2,1,1]        */                        ++c;s1++;if(s2==1)s1++;BEND;if(s2==0){goto l30;} else if(s2==1){goto l31;} else {_SAVEREGS;badJump(c,29,s2+s1);}
     l30: /*["seti",5,3,1]        */                        ++c;s1=6;BEND;goto l6;

     l31: ;
     l32: ;
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

   int batchsize = 2147483647; // ~1s realtime
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
