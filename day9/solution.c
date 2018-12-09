#include <stdio.h>
#include <stdlib.h>

typedef struct place{
   int m;
   struct place *prev;
   struct place *next;
}PLACE;

PLACE *insertAfter(PLACE * z, PLACE * nPlace){
   PLACE *x = z;
   PLACE *y = z->next;
   nPlace->prev = x; nPlace->next = y;
   x->next = nPlace; y->prev = nPlace;
   return nPlace;
}

PLACE *newPlace(int v){
   PLACE *p = malloc(sizeof(PLACE));
   p->m = v; p->next = p->prev = p;
   return p;
}

long game(int np, int nm){
   long *players = malloc(np * sizeof(long));
   PLACE *places = newPlace(0);
   PLACE *cp = places;
   long p = 1;
   int  c = 0;
   int  nc = 0;
   int  placesLength = 1;
   for (int m = 1; m <= nm; m++){
      PLACE *nPlace = newPlace(m);
      if (0 == m % 23){
         PLACE *removed = cp->prev->prev->prev->prev->prev->prev->prev;
         players[p] += m;
         players[p] += removed->m;
         removed->prev->next = removed->next; removed->next->prev = removed->prev; cp = removed->next;
         placesLength--;
      }else{
         nc = (c + 2) % placesLength;
         cp = insertAfter(cp->next, nPlace);
         c = nc;
         placesLength++;
      }
      p = (p + 1 > np) ? 1 : p + 1;
   }
   long maxp=players[0]; for(long i=0;i<np;i++) if(maxp<players[i])maxp=players[i];
   return maxp;
}

int main(){
   char line[1024];
   int np, nm;
   while (fgets(line, 1024, stdin)){
      sscanf(line, "%d players; last marble is worth %d points\n", &np, &nm);
      printf("Highscore (for %3d players and %5d marbles) is: %10ld\n", np, nm, game(np, nm));
   }
}
