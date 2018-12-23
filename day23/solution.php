#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$digits = Amap($lines, function($line){ return line2digits($line); });
Amap($digits, function($x){ printf("digits: %s\n", ve($x)); });
$radiuses = Amap($digits, function($x){ return $x[3];});
$maxradius = Amax($radiuses);
var_dump($radiuses,$maxradius);

