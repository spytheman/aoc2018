#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$c=0;
foreach($lines as $line){
    printf("%04d: %s\n", $c, $line);
    $c++;
}
