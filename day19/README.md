The brute force PHP solution.php, runs at ~1030280 instructions/s .
Timed with:

    ./solution.php input 1 | kstimer

... and waiting :-) . It is slightly faster than an Apple ][ 6502 lol.


The brute force C solution in day19.cpp is *much* faster ...
It runs at around ~148 000 000 instructions/s .


However the emulated program runs a quadratic algo, and N is ~10551306 .
So the instructions needed are proportional to N^2 = 111330058305636 .
So even the C solution will need at least *~209 hours* to finish it ...

This gives enough time to do a proper analysis of the algo, and create
a more efficient algorithm giving the same answer as the quadratic one.