# Advent of Code

This is my general purpose repo for Advent of Code: https://adventofcode.com

I didn't create this until 2024, but I might go through some older puzzles at some point. Apparently I earned 19 stars in 2020 but I have no idea where the code is.

## It's PHP!

I'm solving these in PHP because I know the language extremely well and it's just very easy to run PHP using the command line.

## Running

```
php run.php <year> <day>
```

So for example running day 3 of 2024:

```
php run.php 2024 3
```

## Creating New Days

Plop a file into the year named `dayXX.php` where `XX` is the day number (`01`, `02`, `19`, `20`, and so on).

The file should contain a class that implements the `Day` interface from `Day.php` from the root.

