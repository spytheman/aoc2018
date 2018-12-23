#include <stdlib.h>
#include <stdio.h>

extern int in_range(int x, int y, int z);

int main(int argc, char**argv)
{
   int t=0;
   int x=0,y=0,z=0;
   int inr=0,maxr=0;
   int mx=0,my=0,mz=0;   
   for(t=1;t<150;t++){
      for(x=-t;x<=t;x++){
         for(y=-t;y<=t;y++){
            for(z=-t;z<=t;z++){
               inr = in_range(x,y,z);
               if( maxr < inr ) { maxr = inr; mx=x; my=y; mz=z; }
            }
         }
      }
   }
   printf("Part 2 (max in range) is: %d , at point {x:%9d,y:%9d,z:%8d}\n", maxr, mx,my,mz);
   return 0;
}
