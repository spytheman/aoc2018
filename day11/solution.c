#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include "../common.h"

const int GSIZE=300;
#define G( x ) GSIZE, GSIZE, x

typedef struct { long sum; int x; int y; int v; int size; } Tops;

void grid2tops(int maxsize, Tops *tops, GP(int,grid) ){
   long osum=0;
   long s=0;
   for(int size=1;size<=maxsize;size++){ 
      for(int y=1; y<=GSIZE-size; y++){
         for(int x=1; x<=GSIZE-size; x++){
            s=0;
            for(int j=0;j<size;j++) {
               for(int k=0;k<size;k++) {
                  s += grid[y+j][x+k];
               }
            }
            if(tops->sum < s){
               tops->sum = s;
               tops->x = x;
               tops->y = y;
               tops->v = grid[y][x];
               tops->size = size;
            }
         }
      }      
      //printf("Try size: %3d; tops: sum: %8ld , x: %8d, y: %8d, v: %8d, size: %8d\n", size, tops->sum, tops->x, tops->y, tops->v, tops->size); fflush(stdout);
      if(osum>=tops->sum)break;
      osum = tops->sum;
   }
}

int main(int argc, char **argv){
   char buf[64];
   int grid[GSIZE][GSIZE];
   int serial = atoi(fgets(buf,64,stdin));
   int rid, power, d;
   int power_mod_1000;
   for(int y=0; y<GSIZE; y++) for(int x=0; x<GSIZE; x++){
      rid = x + 10;
      power = (((rid * y) + serial) * rid); 
      d = 0;
      power_mod_1000 = power % 1000;
      sprintf(buf, "%03d", power_mod_1000);
      d = buf[0] - '0';
      grid[y][x] = d - 5;
   }
   Tops tops1={0,0,0,0,0};
   grid2tops(3, &tops1, G(grid) );
   //showGridZone(tops1.x, tops1.y, tops1.size, tops1.size, G(grid));
   printf("Part 1 answer: %d,%d\n", tops1.x, tops1.y);
     
   Tops tops={0,0,0,0,0};
   grid2tops(GSIZE, &tops, G(grid) );
   //showGridZone(tops.x, tops.y, tops.size, tops.size, G(grid));
   printf("Part 2 answer: %d,%d,%d\n", tops.x, tops.y, tops.size);
   return 0;
}
