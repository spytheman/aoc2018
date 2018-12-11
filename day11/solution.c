#include <stdlib.h>
#include <stdio.h>
#include <string.h>

int main(int argc, char **argv){
   char buf[64];
   int grid[301][301];
   int serial = atoi(argv[1]);
   int cx     = atoi(argv[2]);
   int cy     = atoi(argv[3]);
   int rid, power, d;
   int power_mod_1000;
   for(int y=0; y<=300; y++) for(int x=0; x<=300; x++){
      rid = x + 10;
      power = (((rid * y) + serial) * rid); 
      d = 0;
      power_mod_1000 = power % 1000;
      sprintf(buf, "%03d", power_mod_1000);
      d = buf[0] - '0';
      grid[y][x] = d - 5;
      if(x==cx && y==cy)
        printf("serial: %d, x: %d, y: %d, rid: %d, power: %d, spower: '%s', d: %d, grid[x][y]: %d\n",
               serial, x, y, rid, power, buf, d, grid[y][x]);
   }
   for(int y=0; y<=300; y++) { printf("line %3d: ", y); for(int x=0; x<=300; x++){ printf("%3d ", grid[y][x]);} printf("\n"); }
   return 0;
}
