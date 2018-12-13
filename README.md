# December 2018: Advent of Code Solutions
Delyan Angelov

I am doing the [Advent of Code](https://adventofcode.com) puzzles for 2018.
This repo contains my solutions of the challenges, as I solve them.

I will push my solutions *ONLY AFTER* the leaderboard for the day is already full.

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
[Day 11: Chronal Charge](https://adventofcode.com/2018/day/11) | [day11/solution.php](day11/solution.php) | Using [a precalculated integral image](https://en.wikipedia.org/wiki/Summed-area_table) is the key to speeding up part 2 (or recoding in C ... or both).
[Day 12: Subterranean Sustainability](https://adventofcode.com/2018/day/12) | [day12/solution.php](day12/solution.php) | A cellular (pot) automaton simulator. Did not solve part 2 in code, just eyeballed the output for a 1000 iterations, and derived a formula for the end result, given an unlimited grid.
