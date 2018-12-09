#include <stdio.h>
#include <stdlib.h>

typedef struct place{
   int m;
   struct place *prev;
   struct place *next;
}PLACE;

PLACE *insertAfter(PLACE * x, PLACE * nPlace){
   PLACE *y = x->next;
   nPlace->prev = x; nPlace->next = y;
   x->next = nPlace; y->prev = nPlace;
   return nPlace;
}
PLACE * unlinkPlace(PLACE *removed){
   removed->prev->next = removed->next; 
   removed->next->prev = removed->prev; 
   return removed;
}
PLACE *newPlace(int v){
   PLACE *p = malloc(sizeof(PLACE));
   p->m = v; p->next = p->prev = p;
   return p;
}

long game(char *label, int np, int nm){
   long *players = malloc(np * sizeof(long));
   PLACE *places = newPlace(0);
   PLACE *cp = places;
   int  p = 0;
   int  c = 0;
   int  nc = 0;
   int  placesLength = 1;
   for (int m = 1; m <= nm; m++){
      PLACE *nPlace = newPlace(m);
      if (0 == m % 23){
         PLACE *removed = unlinkPlace(cp->prev->prev->prev->prev->prev->prev->prev);
         players[p] += (m + removed->m);
         cp = removed->next;
         placesLength--;
      }else{
         nc = (c + 2) % placesLength;
         cp = insertAfter(cp->next, nPlace);
         c = nc;
         placesLength++;
      }
      p = (p + 1 ) % np;
   }
   long maxp=players[0]; for(long i=0;i<np;i++) if(maxp<players[i])maxp=players[i];
   printf("%s Highscore (for %5d players and %8d marbles) is: %18ld\n", label, np, nm, maxp);
   return maxp;
}

int main(){
   char line[1024];
   int np, nm;
   while (fgets(line, 1024, stdin)){
      sscanf(line, "%d players; last marble is worth %d points\n", &np, &nm);
      game("Part 1", np, nm);
      game("Part 2", np, 100*nm);
   }
}
