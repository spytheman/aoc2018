#!/usr/bin/env php
<?php 
include("common.php");
$regexp = str_replace(['^', '$'], '', join('', read_input())); 
$nregexp = strlen($regexp); 
printf("Regexp length: %d\n", $nregexp);
$routes = generateRoutesGivenRegexp($regexp);
$c = 0; foreach($routes as $r){
    printf("Route: %5d is: '%s' .\n", $c, $r);
    $c++;
}

function generateRoutesGivenRegexp(string $regexp, $startindex=0, $level=0): array {
    $levelpad = str_pad('', $level*10, ' ');
    printf("{$levelpad} generateRoutesGivenRegexp startindex: %d , level: %d, regexp: %s \n", $startindex, $level, $regexp);
    $routes = [];
    $chars = str_split($regexp, 1); $endIndex = count($chars);
    $curbranch = '';
    for($i=$startindex;$i<$endIndex;$i++){
        $c = $chars[$i]; 
        $nc = ''; if($i + 1 < $endIndex) $nc = $chars[$i+1];
        printf("{$levelpad} Char %5d: %s\n", $i, $c);
        switch($c){
         case '(':{
             $foundb=0; $j=$i; while(true){
                 if($j>$endIndex){
                     printf("{$levelpad} Level: %d | Startindex: %d | Syntax error for ( at %d - this bracket has no matching ) .\n", $level, $startindex, $i);
                     printf("{$levelpad} Regexp: %s \n", $regexp);
                     exit(0); 
                 }
                 if($chars[$j] === '(')$foundb++; if($chars[$j] === ')')$foundb--; 
                 if($foundb===0) break;
                 $j++;
             }
             $subregexp = substr($regexp, $i+1, $j - 1 - $i );
             $subroutes = generateRoutesGivenRegexp($subregexp, 0, $level+1);
             printf("{$levelpad} Subroutes at level %d for subregexp: '%s' | curbranch:'%s'\n", $level, $subregexp, $curbranch);
             foreach($subroutes as $r) {
                 printf("{$levelpad}   route: '%s'\n", $r);
                 $routes[]= $curbranch . $r;
             }
             $curbranch='';
             $i=$j;
             break;
         }
         case ')':break;
         case '|':{
             printf("I: $i , c: '$c' , nc: '$nc' , curbranch: '%s'\n", $curbranch);
             $routes[]=$curbranch; $curbranch='';
             if($nc === '') {
                 $routes[] = '';
             }
             break;
         }
         default:{
             $curbranch.=$c;
             break;
         }
        }
    }
    if($curbranch!=='')$routes[]=$curbranch;
    return $routes;
}
