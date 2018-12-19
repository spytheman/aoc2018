<?php
$n = $argv[1]; printf("REG: %d\n", $n);
$sqrN = (int) sqrt($n);
$result = 0;
for ($i = 1; $i <= $sqrN; $i++) {
    if ($n % $i === 0) {
        $x = $i + $n / $i;
        printf("i: %d | X: %d\n", $i, $x);
        $result += $x;
    }
}
printf("Result: %10d\n", $result );
