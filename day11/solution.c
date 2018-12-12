#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <math.h>
#include "../common.h"

#define GSIZE 300
#define G( x ) GSIZE, GSIZE, x

typedef struct { long sum; int x; int y; int v; int size; } Tops;

// isum will contain a precalculated integral image. See https://en.wikipedia.org/wiki/Summed-area_table
int isum[GSIZE+1][GSIZE+1];

void grid2tops(int serial, int maxsize, Tops *tops, GP(int,grid) ){
   long s=0;
   for(int size=1;size<=maxsize;size++){ 
      for(int y=1; y<=GSIZE-size; y++){
         int ys = y + size;
         for(int x=1; x<=GSIZE-size; x++){
            int xs = x + size;
            s = isum[ys][xs] - isum[y][xs] - isum[ys][x] + isum[y][x];            
            if(tops->sum < s){
               tops->sum = s;
               tops->x = x+1;
               tops->y = y+1;
               tops->v = grid[y][x];
               tops->size = size;
            }
         }
      }
      //if(size % 50 == 0) printf("Try serial: %5d, size: %3d; tops: sum: %8ld , x: %8d, y: %8d, v: %8d, size: %8d\n", serial, size, tops->sum, tops->x, tops->y, tops->v, tops->size); fflush(stdout);
   }
}

int main(int argc, char **argv){
   char buf[64];
   int grid[GSIZE+1][GSIZE+1];
   int serial;
   int rid, power, d;
   serial = atoi(fgets(buf,64,stdin));
   for(int i=0; i<=GSIZE; i++) { 
      grid[0][i] = 0; 
      grid[i][0] = 0; 
   }
   for(int y=1; y<=GSIZE; y++) {
      for(int x=1; x<=GSIZE; x++){
         rid = x + 10;
         power = (((rid * y) + serial) * rid);         
         d = ((int)floor(power/100)) % 10;
         grid[y][x] = d - 5;
      }
   }
   
   // Precalculate an integral image to speed up grid2tops:
   memcpy( isum, grid, (GSIZE+1)*(GSIZE+1)*sizeof(int) ); 
   for(int y=1; y<=GSIZE; y++) 
     for(int x=1; x<=GSIZE; x++)
       isum[y][x] = isum[y][x] + isum[y-1][x] + isum[y][x-1] - isum[y-1][x-1];
   //showGridZone(0,0, 10,10, G(isum));

   Tops tops1={0,0,0,0,0};
   grid2tops(serial, 3, &tops1, G(grid) );
   printf("Part 1 answer: %d,%d\n", tops1.x, tops1.y);
   //showGridZone(tops1.x, tops1.y, tops1.size, tops1.size, G(grid));
     
   Tops tops={0,0,0,0,0};
   grid2tops(serial, GSIZE, &tops, G(grid) );
   printf("Part 2 answer: %d,%d,%d\n", tops.x, tops.y, tops.size);
   //showGridZone(tops.x, tops.y, tops.size, tops.size, G(grid));
   return 0;
}
