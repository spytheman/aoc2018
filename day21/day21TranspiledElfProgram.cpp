#include <stdlib.h>
#include <stdio.h>

////////////////////////////////////////////////////////////////////////////
/// This is a generated file. Edit it on your risk.
/// This was produced by running: elfasm2c.php input
/// This text should be put into a C++ file, for example elfProgram.cpp 
/// ... then it should be compiled with: 
///     g++ -std=c++14 -g  -c elfProgram.cpp  -o elfProgram.o 
////////////////////////////////////////////////////////////////////////////

/// ipidx: 1 
int r0=0,r1=0,r2=0,r3=0,r4=0,r5=0;

char _regsbuffer[255];
char * Elf_regs2string(){  sprintf(_regsbuffer, "R:[%6d,%6d,%6d,%6d,%6d,%6d]", r0,r1,r2,r3,r4,r5);  return _regsbuffer; }
bool Elf_emulate(long maxCount, long *actualIterationCount)
{
  long c=0;
  int ip=0;
  while(c<maxCount){ 
    ip = r1;
    switch(ip){  
          case    0:  r3 = 123;              break; // seti 123       0         3        
          case    1:  r3 = r3 & 456;         break; // bani 3         456       3        
          case    2:  r3 = (r3 == 72)?1:0;   break; // eqri 3         72        3        
          case    3:  r1 = r3 + r1;          break; // addr 3         1         1        
          case    4:  r1 = 0;                break; // seti 0         0         1        
          case    5:  r3 = 0;                break; // seti 0         9         3        
          case    6:  r5 = r3 | 65536;       break; // bori 3         65536     5        
          case    7:  r3 = 15028787;         break; // seti 15028787  4         3        
          case    8:  r2 = r5 & 255;         break; // bani 5         255       2        
          case    9:  r3 = r3 + r2;          break; // addr 3         2         3        
          case   10:  r3 = r3 & 16777215;    break; // bani 3         16777215  3        
          case   11:  r3 = r3 * 65899;       break; // muli 3         65899     3        
          case   12:  r3 = r3 & 16777215;    break; // bani 3         16777215  3        
          case   13:  r2 = (256 > r5)?1:0;   break; // gtir 256       5         2        
          case   14:  r1 = r2 + r1;          break; // addr 2         1         1        
          case   15:  r1 = r1 + 1;           break; // addi 1         1         1        
          case   16:  r1 = 27;               break; // seti 27        3         1        
          case   17:  r2 = 0;                break; // seti 0         9         2        
          case   18:  r4 = r2 + 1;           break; // addi 2         1         4        
          case   19:  r4 = r4 * 256;         break; // muli 4         256       4        
          case   20:  r4 = (r4 > r5)?1:0;    break; // gtrr 4         5         4        
          case   21:  r1 = r4 + r1;          break; // addr 4         1         1        
          case   22:  r1 = r1 + 1;           break; // addi 1         1         1        
          case   23:  r1 = 25;               break; // seti 25        1         1        
          case   24:  r2 = r2 + 1;           break; // addi 2         1         2        
          case   25:  r1 = 17;               break; // seti 17        8         1        
          case   26:  r5 = r2;               break; // setr 2         4         5        
          case   27:  r1 = 7;                break; // seti 7         3         1        
          case   28:  r2 = (r3 == r0)?1:0;   break; // eqrr 3         0         2        
          case   29:  r1 = r2 + r1;          break; // addr 2         1         1        
          case   30:  r1 = 5;                break; // seti 5         3         1        
          default: { 
              printf("Elf_emulate C: %12ld | IP: %3d | Terminating ...\n", c, ip ); 
              *actualIterationCount += c;
              return false;
          }
    }
    ip = r1;     
    r1++;
    c++;
  }
  *actualIterationCount += c;
  return true;
}


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
