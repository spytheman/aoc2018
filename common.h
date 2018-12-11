
void showGridZone(int topx, int topy, int w, int h, int Grows, int Gcols, int grid[Grows][Gcols]){
   printf("\n");
   printf("# Grid zone {topx,topy}={%d,%d}, {w,h}={%d,%d}\n", topx, topy, w, h);
   printf("# --------------------------------------------------\n#");
   int v=0;
   long ysums=0; 
   long yproducts=1;
   long xs=0; 
   long xp=1;
   for(int y=topy;y<h+topy;y++){
      printf("# y: %5d | ", y);
      xs=0; xp=1;
      for(int x=topx;x<w+topx;x++){         
         v=grid[y][x]; 
         xs+=v; xp*=v;
         printf("%3d ", v);
      }
      printf(" | lsum: %8ld | lproduct: %8ld\n#", xs, xp);
      ysums+=xs; yproducts*=xp;
   }
   printf(" --------------------------------------------------\n");
   printf("# Total sum: %8ld\n", ysums);
   printf("# Total product: %10ld\n", yproducts);
   printf("\n");
}
