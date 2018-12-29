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
int r0=0,r1=0,r2=0,r3=0,r4=0,r5=0;
char _regsbuffer[255];
char * Elf_regs2string(){  sprintf(_regsbuffer, "R:[%9d,%9d,%9d,%9d,%9d,%9d]", r0,r1,r2,r3,r4,r5);  return _regsbuffer; }
bool Elf_emulate(long maxCount, long *actualIterationCount)
{
  long c=0;
  int ip=0;
  while(c<maxCount){ 
    ip = r1;
    switch(ip){  
          case    0:  r1 = r1 + 16;          break; // addi 1         16        1        
          case    1:  r5 = 1;                break; // seti 1         2         5        
          case    2:  r2 = 1;                break; // seti 1         2         2        
          case    3:  r3 = r5 * r2;          break; // mulr 5         2         3        
          case    4:  r3 = (r3 == r4)?1:0;   break; // eqrr 3         4         3        
          case    5:  r1 = r3 + r1;          break; // addr 3         1         1        
          case    6:  r1 = r1 + 1;           break; // addi 1         1         1        
          case    7:  r0 = r5 + r0;          break; // addr 5         0         0        
          case    8:  r2 = r2 + 1;           break; // addi 2         1         2        
          case    9:  r3 = (r2 > r4)?1:0;    break; // gtrr 2         4         3        
          case   10:  r1 = r1 + r3;          break; // addr 1         3         1        
          case   11:  r1 = 2;                break; // seti 2         8         1        
          case   12:  r5 = r5 + 1;           break; // addi 5         1         5        
          case   13:  r3 = (r5 > r4)?1:0;    break; // gtrr 5         4         3        
          case   14:  r1 = r3 + r1;          break; // addr 3         1         1        
          case   15:  r1 = 1;                break; // seti 1         1         1        
          case   16:  r1 = r1 * r1;          break; // mulr 1         1         1        
          case   17:  r4 = r4 + 2;           break; // addi 4         2         4        
          case   18:  r4 = r4 * r4;          break; // mulr 4         4         4        
          case   19:  r4 = r1 * r4;          break; // mulr 1         4         4        
          case   20:  r4 = r4 * 11;          break; // muli 4         11        4        
          case   21:  r3 = r3 + 3;           break; // addi 3         3         3        
          case   22:  r3 = r3 * r1;          break; // mulr 3         1         3        
          case   23:  r3 = r3 + 4;           break; // addi 3         4         3        
          case   24:  r4 = r4 + r3;          break; // addr 4         3         4        
          case   25:  r1 = r1 + r0;          break; // addr 1         0         1        
          case   26:  r1 = 0;                break; // seti 0         0         1        
          case   27:  r3 = r1;               break; // setr 1         5         3        
          case   28:  r3 = r3 * r1;          break; // mulr 3         1         3        
          case   29:  r3 = r1 + r3;          break; // addr 1         3         3        
          case   30:  r3 = r1 * r3;          break; // mulr 1         3         3        
          case   31:  r3 = r3 * 14;          break; // muli 3         14        3        
          case   32:  r3 = r3 * r1;          break; // mulr 3         1         3        
          case   33:  r4 = r4 + r3;          break; // addr 4         3         4        
          case   34:  r0 = 0;                break; // seti 0         0         0        
          case   35:  r1 = 0;                break; // seti 0         1         1        
          default: { 
              printf("--------------------------------------------------------------------------------------------------------\n");
              printf("        Terminating ... Elf_emulate C: %12ld | IP: %3d \n", c, ip ); 
              *actualIterationCount += c;
              return false;
          }
    }
    r1++;
    ip = r1;
    c++;
  }
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
