#include <stdio.h>
#include <stdlib.h>
#include <string.h>

struct Instruction {
     char cmd[5]={'n','o','n','e',0};
     int  a1=0;
     int  a2=0;
     int  a3=0;
     int  n=0;
};

void showInstruction(Instruction *ci)
{
     printf("N: %4d | cmd: %4s %2d %2d %2d\n", ci->n, ci->cmd, ci->a1, ci->a2, ci->a3);
}

int main(int argc, char **argv)
{
     char line[1024];
     char inputfile[1024] = {"input"};
     int initial_reg0 = 0;
     Instruction instructions[1000];
     Instruction *ci;
     char cmd[32];
     int a1, a2, a3;
     if( argc > 1 ) {
          for(int i=0;i<argc;i++) printf("Arg: %d = '%s'\n",i, argv[i]);
          strcpy(inputfile, argv[1]);
          if( argc > 2 ){
               initial_reg0 = atoi( argv[2] );
          }
     }
     printf("Input file: '%s' | initial register 0: %d .\n",inputfile, initial_reg0);
     ////////////////////////////////////////////////////////////////////////////////
     int i=0;
     FILE *f = fopen(inputfile, "r"); while (fgets(line, 1024, f)){
          ci = &instructions[i];
          ci->n = i;
          sscanf(line, "%s %d %d %d\n",
                 ci->cmd,
                 &ci->a1,
                 &ci->a2,
                 &ci->a3);
          showInstruction(ci);
          i++;
     }
     fclose(f);
     exit(0);
}
