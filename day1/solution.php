<?php
//$numbers = explode(", ", "+1, -1");                // 0
//$numbers = explode(", ", "+1, -2, +3, +1");        // 2
//$numbers = explode(", ", "+3, +3, +4, -2, -4");    // 10
//$numbers = explode(", ", "-6, +3, +8, +5, -6");    // 5
//$numbers = explode(", ", "+7, +7, -2, -7, -4");    // 14
$numbers = explode("\n", file_get_contents("day1/input"));
$clean_numbers = []; foreach($numbers as $n){ $n = (int) $n; if($n===0)continue; $clean_numbers[] = $n;} $numbers = $clean_numbers;
$flen = count( $numbers ); $cf = 0; $frequencies = []; foreach($numbers as $n){  $cf += $n;    $frequencies[] = $cf; }
printf("Current frequency after all input: %d | input size: %d\n", $cf, $flen);
////////////////////////////////////////////////////////////////////////////////////////////
$encounters = [0=>1]; $c=0; $f = 0;
while(true){
    $i = $c % $flen;
    $f += $numbers[ $i ];
    $number = $numbers[ $i ];
    printf("   %6d   %6d   %6d  f: %6d  \n", $c, $i, $number, $f);
    if( !array_key_exists($f, $encounters) ){
        $encounters[$f]=1;
    }else{
        printf("First repeat frequency: %d : c=%d\n", $f, $c);
        break;
    }
    $c++;
}
