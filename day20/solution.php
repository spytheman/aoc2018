#!/usr/bin/env php
<?php 
include("common.php");
$regexp = str_replace(['^', '$'], '', join('', read_input()));
$hregexp = md5($regexp);
$nregexp = strlen($regexp); 
printf("Regexp length: %d hash: %32s\n", $nregexp, $hregexp);
$lexems = regexp2lexems($regexp);
$ptree = lexems2parsetree($lexems);
printf("Ptree: %s\n", ve($ptree));
printTree($ptree);
function printTree(array $ptree, $level=0){
    $levelpad = str_pad(' ', 4*$level, ' ');
    foreach($ptree as $n){
        if(is_array($n)){
            printf("{$levelpad} [\n");
            printTree($n, $level+1);
            printf("{$levelpad} ]\n");
        }else{
            printf("{$levelpad} %s \n", $n);
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////
function regexp2lexems(string $regexp=''): array {
    //printf("regexp2lexems regexp: %s\n", $regexp);
    $res = [];
    $nregexp=strlen($regexp);
    $token = ''; $blevel = 0;
    for($i=0;$i<$nregexp;$i++){
        $c = $regexp[$i];
        switch($c){
         case 'N':;
         case 'E':;
         case 'S':;
         case 'W':{
             $token.=$c;
             break;
         }
         case '(':{
             if($token!=='')$res[]="_{$token}"; 
             $res[]="{$c}{$blevel}";
             $token='';
             $blevel++;
             break;
         }
         case '|':{
             if($token!=='')$res[]="_{$token}"; 
             $res[]=$c; 
             $token=''; 
             break;
         }
         case ')':{
             $blevel--;
             if($token!=='')$res[]="_{$token}"; 
             $res[]="{$c}{$blevel}";
             $token='';
             break;             
         }
        }
    }
    if($token!=='')$res[]="_{$token}";
    return $res;
}
function lexems2parsetree(array $lexems, $level=0): array {
    $levelpad = str_pad(' ', $level*5, ' ');
    //printf("{$levelpad} lexems2parsetree level: %2d, lexems: %s\n", $level, ve($lexems));
    $res = [];
    $nlexems = count($lexems);
    for($i=0;$i<$nlexems;$i++){
        $lexem = $lexems[$i];        
        switch($lexem[0]){
         case '_':{
             $res[]=$lexem;
             break;
         }
         case '|':{
             if( $i+1 == $nlexems) {
                 $res[]='e';
             }
             break;
         }
         case '(':{
             $found = false; $needed=$lexem; $needed[0]=')'; $optionlexems=[];
             for($j=$i+1;$j<$nlexems;$j++){
                 $olexem = $lexems[$j];
                 if( $lexems[$j] === $needed ){
                     $found = true;
                     break;
                 }
                 $optionlexems[]=$olexem;
             }
             if(!$found){
                 printf("{$levelpad} Syntax error after '%s' . The bracket was not properly closed.\n", ve($res));
                 exit(1);
             }
             $res[]=lexems2parsetree( $optionlexems, $level+1 );
             $i=$j;
             break;
         }
         case ')':{
             printf("{$levelpad} found end of options\n");
             break;
         }
        }
    }
    return $res;
}
