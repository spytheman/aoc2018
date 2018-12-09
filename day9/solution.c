#include <stdio.h>
#include <stdlib.h>
typedef struct place{
   long m;
   struct place *prev;
   struct place *next;
}PLACE;

PLACE *insertAfter(PLACE * z, PLACE * newPlace){
   PLACE *x = z;
   PLACE *y = z->next;
   //printf("insertAfter x:%p[%d] {NEW:%p[%d]} y:%p[%d]\n", x, x->m, newPlace, newPlace->m,  y,y->m);
   newPlace->prev = x; newPlace->next = y;
   x->next = newPlace; y->prev = newPlace;
   return newPlace;
}

PLACE *new_place(long v){
   PLACE *p = malloc(sizeof(PLACE));
   p->m = v; p->next = 0; p->prev = 0;
   return p;
}

long game(int np, int nm){
   //printf("      Starting game with %d players and %d marbles ...\n", np, nm);
   long *players = malloc(np * sizeof(long));
   PLACE *places = new_place(0);
   PLACE *cp = places;
   long p = 1;
   long c = 0;
   long nc = 0;
   long placesLength = 1;
   //long checkpoint = 1; while(1){ if(checkpoint<nm){ checkpoint*=10;}else{ checkpoint/=10; break; } }
   cp->next = cp->prev = cp;
   for (int m = 1; m <= nm; m++){
      PLACE *newPlace = new_place(m);
      //if (0 == m % checkpoint){  printf("Placing marble %d.\n", m);  }
      if (0 == m % 23){
         PLACE *removed = cp->prev->prev->prev->prev->prev->prev->prev;
         players[p] += m;
         players[p] += removed->m;
         removed->prev->next = removed->next; removed->next->prev = removed->prev; cp = removed->next; free(removed);
         placesLength--;
      }else{
         nc = (c + 2) % placesLength;
         //printf("c: %d | NC: %d\n", c, nc);
         cp = insertAfter(cp->next, newPlace);
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
      printf("  --------> np: %3d ; nm: %5d ... Highscore: %10ld\n", np, nm, game(np, nm));
   }
}
