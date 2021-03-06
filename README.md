# December 2018: Advent of Code Solutions
Delyan Angelov

I am doing the [Advent of Code](https://adventofcode.com) puzzles for 2018.
This repo contains my solutions of the challenges, as I solve them.

I will push my solutions *ONLY AFTER* the leaderboard for the day is already full (even though I have not scored so far, and judging by the times of the top 100 performers, it is not likely that I will score at all this year ... most people give their answers before I finish reading and understanding the task, lol).

Contents:
=======================================

./cookie - NB: **You should create this file yourself**. It is **NOT** included in this repo. 
The file contains a single line like this:  session=74506ff802....
You can get it by logging into the https://adventofcode.com website, then seeing your cookies.

./prepare_for_day - A helper script, that prepares a new folder for a given day. 
It will produce a draft solution in folder dayXX/ , then it will download the input for this day for you.
All that remains for you to do then, is the fun part - solving the problem.

        Usage: ./prepare_for_day 2                  

./common.php - This file contains utility functions used by all solutions.

./day*/ - The folders contain the solutions for a given AOC problem.


Common way to do an exercise in Advent of Code:
=======================================
        0. After the start time of the contest, read the task description on the site.
        1. ./prepare_for_day 17
        2. edit day17/solution.php
        3. ./day17/solution.php 
        4. Cycle through steps 2 and 3, until solution works.
        5. Submit your answer to the site.

Misc.
=======================================

You can run all the solutions so far like that:

        for i in day*/solution.php; do echo "$i : "; time $i; echo ;done



Solutions and notes about them so far:
=======================================

Task | Solution | Comment
--- | --- | ---
[Day 1: Chronal Calibration](https://adventofcode.com/2018/day/1) | [day1/solution.php](day1/solution.php) | After implementing Arepeat/2 the rest was very easy.
[Day 2: Inventory Management System](https://adventofcode.com/2018/day/2) | [day2/solution.php](day2/solution.php) | Histograms FTW!
[Day 3: No Matter How You Slice It](https://adventofcode.com/2018/day/3) | [day3/solution.php](day3/solution.php) | Just extract all numbers without worrying about regexpes and clean parsing...
[Day 4: Repose Record](https://adventofcode.com/2018/day/4) | [day4/solution.php](day4/solution.php) | This one took much more time than I expected.
[Day 5: Alchemical Reduction](https://adventofcode.com/2018/day/5) | [day5/solution.php](day5/solution.php) | Using range/2 and Azip2/2 to make the pairs that had to be removed in advance, was easy and quick ... In retrospect, a simple scanner would be quicker though.
[Day 6: Chronal Coordinates](https://adventofcode.com/2018/day/6) | [day6/solution.php](day6/solution.php) | NB: in future I really should iterate and do testing on the given example input *first*, because each run on the full input took >10s. Also 'Manhattan distance' = abs(dx) + abs(dy) , and NOT abs(dx+dy) .
[Day 7: The Sum of Its Parts](https://adventofcode.com/2018/day/7) | [day7/solution.php](day7/solution.php) | NB: topological sorting and graphs in general are NOT my strong suit. Should exercise them more.
[Day 8: Memory Maneuver](https://adventofcode.com/2018/day/8) | [day8/solution.php](day8/solution.php) | Parsing a serialized tree do not always involve an explicit state machine.
[Day 9: Marble Mania](https://adventofcode.com/2018/day/9) | [day9/solution.php](day9/solution.php) | Reimplemented [the whole solution in C](day9/solution.c) for part 2, since PHP array_slice is slow. In C, I used a doubly linked circular buffer to implement the circle, in which the marbles are placed, which made traversing easy and fast.
*Deque for Day 9) | [day9/solution.deque.php](day9/solution.deque.php) | After seeing [a wonderful Python collections.deque based solution](https://www.reddit.com/r/adventofcode/comments/a4i97s/2018_day_9_solutions/ebepyc7/), I ported it to PHP using the [new Data Structures PHP extension](http://docs.php.net/manual/en/ds.installation.php) ([deque](http://docs.php.net/manual/en/class.ds-deque.php) specifically). Result: it works acceptably (i.e. works in linear time. For my input both parts are done in ~13s ... Well it is _51 times slower_ than the C solution :-) )
[Day 10: The Stars Align](https://adventofcode.com/2018/day/10) | [day10/solution.php](day10/solution.php) | NB: PHP is not very good at plotting points, but spreadsheets are.
[Day 11: Chronal Charge](https://adventofcode.com/2018/day/11) | [day11/solution.php](day11/solution.php) | Using [a precalculated integral image](https://en.wikipedia.org/wiki/Summed-area_table) is the key to speeding up part 2 (or recoding in C ... or both). NB: even with the integral image optimization, the PHP solition takes ~5700ms, the C solution takes ~23ms ...
[Day 12: Subterranean Sustainability](https://adventofcode.com/2018/day/12) | [day12/solution.php](day12/solution.php) | A cellular (pot) automaton simulator. Did not solve part 2 in code, just eyeballed the output for a 1000 iterations, and derived a formula for the end result, given an unlimited grid.
[Day 13: Mine Cart Madness](https://adventofcode.com/2018/day/13) | [day13/solution.php](day13/solution.php) | Works for all inputs now. The problem was the order of checking for crashes with the other cars. (it printed different collision coords than the other solutions, and I had no clue why that happened ... I guessed it is something related to the elimination order of the carts ... "Madness" indeed...).
[Day 14: Chocolate Charts](https://adventofcode.com/2018/day/14) | [day14/solution.php](day14/solution.php) | The part 2 calculation takes ~32-33 seconds in PHP. I have no idea for a faster way so far. Update: batching of recipe generation, and then checking for termination once per batch helps very much - runtime reduced to ~17s .
[Day 15: Beverage Bandits](https://adventofcode.com/2018/day/15) | [day15/solution.php](day15/solution.php) | WIP
[Day 16: Chronal Classification](https://adventofcode.com/2018/day/16) | [day16/solution.php](day16/solution.php) | An emulated VM/CPU, although with very limited capabilities :-) . The solution runs in ~50ms .
[Day 17: Reservoir Research](https://adventofcode.com/2018/day/17) | [day17/solution.php](day17/solution.php) | WIP
[Day 18: Settlers of The North Pole](https://adventofcode.com/2018/day/18) | [day18/solution.php](day18/solution.php) | Bruteforcing for part 2 was waaayy too slow (~25ms/1 simulated minute => > estimated *289 days* realtime waiting time :-( ). The grids does seem to repeat ... So the main optimization was to find the cycle of repetition first, then use it to predict the future grid.
[Day 19: Go With The Flow](https://adventofcode.com/2018/day/19) | [day19/solution.php](day19/solution.php) | Reusing the VM instructions from day 16, did not save much, since as always part 2 required heuristcs and actual understanding of the goal of the emulated program. Bruteforcing (even using a fast C emulator it would take ~209 hours for 1 core of my PC ... and it could not be paralelized easily also) was out of the question.
[Day 20: A Regular Map](https://adventofcode.com/2018/day/20) | [day20/solution.php](day20/solution.php) | A neat exercise combining parsing and grid walking. Note to self: most problems here do not need full blown lexer/parser/compiler stages... Simple direct interpreters are fine, since the time is too short to do anything else.
[Day 21: Chronal Conversion](https://adventofcode.com/2018/day/21) | [day21/solution.php](day21/solution.php) | Rank 810/703 ... best so far, but I really should think more and do not immediately jump to brute forcing first... The essence of the task was to find which r0 values could cause the input program to terminate. The problem is that happens usually after many iterations. Thus it was important to actually understand the part of the input responsible for checking for termination (instruction 28), and also to speed up the whole process by eliminating dummy delay loops (thrown in to discourage bruteforcing).
[Day 22: Mode Maze](https://adventofcode.com/2018/day/22) | [day22/solution.php](day22/solution.php) | WIP (runs very slow and after optimization no longer works for all inputs ... NB: need to work on my graph walking and maze path skills).
[Day 23: Experimental Emergency Teleportation](https://adventofcode.com/2018/day/23) | [day23/solution.php](day23/solution.php) | Rank 1059/329 (best for me so far)... Learned about how to use Z3 constraint solver - generate a z3 lisp program, and then run z3 over it. Brute forcing was completely out of the question for this day's part 2.
[Day 24: Immune System Simulator 20XX](https://adventofcode.com/2018/day/24) | [day24/solution.php](day24/solution.php) | WIP (another game simulation).
[Day 25: Four-Dimensional Adventure](https://adventofcode.com/2018/day/25) | [day25/solution.php](day25/solution.php) | A nice little clustering task.
