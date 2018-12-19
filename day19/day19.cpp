#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc, char **argv)
{
     char line[1024];
     char inputfile[1024] = {"input"};
     int initial_reg0 = 0;
     if( argc > 1 ) {
          for(int i=0;i<argc;i++) printf("Arg: %d = '%s'\n",i, argv[i]);
          strcpy(inputfile, argv[1]);
          if( argc > 2 ){
               initial_reg0 = atoi( argv[2] );
          }
     }
     printf("Input file: '%s' | initial register 0: %d .\n",inputfile, initial_reg0);
     FILE *f = fopen(inputfile, "r"); while (fgets(line, 1024, f)){
          printf("LINE: %s",line);
     }
     fclose(f);
     exit(0);
}
