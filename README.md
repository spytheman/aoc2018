# December 2018: Advent of Code Solutions
Delyan Angelov

I am doing the [Advent of Code](https://adventofcode.com) puzzles for 2018.
This repo contains my solutions of the challenges, as I solve them.

Contents:
=======================================

./cookie - NB: **You should create this file yourself**. It is **NOT** included in this repo. 
The file contains a single line like this:  session=74506ff802....
You can get it by logging into the https://adventofcode.com website, then seeing your cookies.

./prepare_for_day - A helper script, that prepares a new folder for a given day. 
It will make a draft in folder day2/ and download the input for this day for you.
All that remains for you to do then, is the fun part - solving the problem.

        Usage: ./prepare_for_day 2                  

./common.php - This file contains utility functions used by all solutions.

./day*/ - The folders contain the solutions for a given AOC problem.


How to procede:
=======================================
        1. ./prepare_for_day 17
        2. edit day3/solution.php
        3. run day3/solution.php 
        4. Cycle through steps 2 and 3, until solution works.
        5. Submit your answer to the site.

Misc.
=======================================

You can run all the solutions so far like that:

        for i in day*/solution.php; do echo "$i : "; time $i; echo ;done
