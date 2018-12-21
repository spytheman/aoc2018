#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/time.h>
#include <set>
// -*- mode: c++-mode -*-

long getMicrosecond()
{
     struct timeval time;
     gettimeofday(&time, NULL);
     return ((unsigned long long)time.tv_sec * 1000000) + time.tv_usec;
}

struct Instruction {
     char cmd[5]={'n','o','n','e',0};
     int  icode=0;
     int  a1=0;
     int  a2=0;
     int  a3=0;     
     int  n=0;
};

char _insbuf[100];
char *instruction2string(const Instruction *ci)
{
     sprintf(_insbuf, "N:%-3d|CMD: %4s %8d %8d %8d", ci->n, ci->cmd, ci->a1, ci->a2, ci->a3);
     return _insbuf;
}

constexpr int cmd2icode(const char *cmd)
{
     int icode = 0;
     icode = (icode << 8) | (cmd[0] - 'a');
     icode = (icode << 8) | (cmd[1] - 'a');
     icode = (icode << 8) | (cmd[2] - 'a');
     icode = (icode << 8) | (cmd[3] - 'a');
     return icode;
}

enum InstructionCode {
     ADDR = cmd2icode("addr"),
     ADDI = cmd2icode("addi"),
     MULR = cmd2icode("mulr"),
     MULI = cmd2icode("muli"),
     BANR = cmd2icode("banr"),
     BANI = cmd2icode("bani"),
     BORR = cmd2icode("borr"),
     BORI = cmd2icode("bori"),
     SETR = cmd2icode("setr"),
     SETI = cmd2icode("seti"),
     GTIR = cmd2icode("gtir"),
     GTRI = cmd2icode("gtri"),
     GTRR = cmd2icode("gtrr"),
     EQIR = cmd2icode("eqir"),
     EQRI = cmd2icode("eqri"),
     EQRR = cmd2icode("eqrr"),
};

char _statebuf[100];
char * state2string(const int *cpustate){
     sprintf(_statebuf, "[%9d,%9d,%9d,%9d,%9d,%9d]", cpustate[0],cpustate[1],cpustate[2],cpustate[3],cpustate[4],cpustate[5]);
     return _statebuf;
}

int main(int argc, char **argv)
{
     char line[1024];
     char inputfile[1024] = {"input"};
     int initial_reg0 = 0;
     int batchsize = 1000;
     long maxsteps = 999999999999999L;
     Instruction instructions[1000];
     Instruction *ci;
     char cmd[32];
     int a1, a2, a3;
     if( argc > 1 ) {
          for(int i=0;i<argc;i++) printf("Arg: %d = '%s'\n",i, argv[i]);
          strcpy(inputfile, argv[1]);
          if( argc > 2 ) initial_reg0 = atoi( argv[2] );
          if( argc > 3 ) batchsize    = atoi( argv[3] );
          if( argc > 4 ) maxsteps     = atol( argv[4] );
     }
     printf("Input file: '%s' | initial register 0: %d .\n",inputfile, initial_reg0);
     ////////////////////////////////////////////////////////////////////////////////
     int i=0;
     int ipidx=0;
     int psize=0;
     FILE *f = fopen(inputfile, "r");
     while (fgets(line, 1024, f)){
          ci = &instructions[i];
          ci->n = i;
          sscanf(line, "%s %d %d %d\n", ci->cmd, &ci->a1, &ci->a2, &ci->a3);
          if('#' == ci->cmd[0] && 'i' == ci->cmd[1] && 'p' == ci->cmd[2] ) {
               //printf("Found #ip at i:%d\n", i);
               ipidx = ci->a1;
               continue;
          }
          ci->icode = cmd2icode( ci->cmd );
          //printf("Instruction %4d | %s\n", i, instruction2string( ci ));
          i++;
     }
     fclose(f);
     psize=i-1;
     printf("ipidx: %2d | psize: %d\n", ipidx,psize);
     ////////////////////////////////////////////////////////////////////////////////
     printf("Start simulation...\n");
     int cpustate[6]={0,0,0,0,0,0};
     cpustate[0] = initial_reg0;
     printf("Batchsize: %d | Maxsteps: %ld \n", batchsize, maxsteps);
     printf("START: ipidx: %2d | CPU state %s \n",  ipidx, state2string(cpustate));
     long c=0; int ip=0; Instruction *ins;
     long time1 = getMicrosecond();
     std::set<int> vals;
     int comparisons=0;
     int first_r3 = 0; int last_r3 = 0;
     while(c<maxsteps){
          //printf("Iteration c: %d\n",c);
          ip = cpustate[ ipidx ];
          if( ip > psize ){
               printf("\n");
               printf("-->CPU at step: %-6ld is %s | IP: %-4d > programsize: %d . Terminating...\n", c, state2string(cpustate), ip, psize);
               break;
          }
          if( 0 == c % batchsize ) {
               long time2 = getMicrosecond();
               printf("CPU at step: %-10ld (elapsed %5ldms) |IP: %-4d |INS: %15s |REGS: %s\n",
                      c, (time2-time1)/1000, ip, instruction2string( &instructions[ip] ), state2string(cpustate) );
               time1 = time2;
               fflush(stdout);
          }
          ins = &instructions[ip];
          switch( ins->icode ){
          case ADDR: cpustate[ins->a3] = cpustate[ ins->a1 ] + cpustate[ ins->a2 ]; break;
          case ADDI: cpustate[ins->a3] = cpustate[ ins->a1 ] + ins->a2;             break;
          case MULR: cpustate[ins->a3] = cpustate[ ins->a1 ] * cpustate[ ins->a2 ]; break;
          case MULI: cpustate[ins->a3] = cpustate[ ins->a1 ] * ins->a2;             break;
          case BANR: cpustate[ins->a3] = cpustate[ ins->a1 ] & cpustate[ ins->a2 ]; break;
          case BANI: cpustate[ins->a3] = cpustate[ ins->a1 ] & ins->a2;             break;
          case BORR: cpustate[ins->a3] = cpustate[ ins->a1 ] | cpustate[ ins->a2 ]; break;
          case BORI: cpustate[ins->a3] = cpustate[ ins->a1 ] | ins->a2;             break;
          case SETR: cpustate[ins->a3] = cpustate[ ins->a1 ];                       break;
          case SETI: cpustate[ins->a3] = ins->a1;                                   break;
          case GTIR: cpustate[ins->a3] = ( ins->a1 > cpustate[ ins->a2 ] ) ? 1 : 0; break;
          case GTRI: cpustate[ins->a3] = ( cpustate[ins->a1] > ins->a2   ) ? 1 : 0; break;
          case GTRR: cpustate[ins->a3] = ( cpustate[ins->a1] > cpustate[ins->a2] ) ? 1:0; break;
          case EQIR: cpustate[ins->a3] = ( ins->a1 == cpustate[ ins->a2 ] ); break;
          case EQRI: cpustate[ins->a3] = ( cpustate[ ins->a1 ] == ins->a2 )?1:0;   break;
          case EQRR: cpustate[ins->a3] = ( cpustate[ ins->a1 ] == cpustate[ins->a2]) ? 1: 0; break;
          }
          if( ins->icode == EQRR ){
              int ip = cpustate[ipidx];
              int r3 = cpustate[3];
              if( comparisons == 0 ) {
                  printf("  >>>>>> First overflow candidate found: %d\n", r3);
                  first_r3 = r3;
              }
              comparisons++;
              if( vals.find(r3) != vals.end() ) break;
              //printf("----- C: %10ld | IP: %3d | Comparisons: %10d | Register 3: %10d ------------\n", c, ip, comparisons, r3);
              last_r3 = r3;
              vals.insert(r3);
          }
          cpustate[ ipidx ]++;
          c++;
     }
     //for (auto v = vals.begin(); v != vals.end(); ++v){   printf("Reg 0 overflow value: %d\n", *v); }
     printf("Total comparisons made: %d\n", comparisons);
     printf("---------------------------------------------------------------\n");
     printf("Part 1 answer (first comparison with r0, which could cause overflow) is: %d\n", first_r3);
     printf("Part 2 answer (overflow value of r0, for which maximum instructions are executed) is: %d\n", last_r3);
     
     exit(0);
}


