<?php
define('SFILE', realpath($_SERVER['PHP_SELF']));
define('SDIR', dirname(SFILE));

function read_input($filename=''){
    if($filename==='')$filename=SDIR."/input";
    $clean_lines = []; 
    $lines = explode("\n", file_get_contents($filename));
    foreach($lines as $line){
        if($line==='')continue;
        $clean_lines[] = $line;
    }
    return $clean_lines;
}

function Amap(array $a, $f): array { return array_map($f, $a); }
function Afilter(array $a, $f): array { return array_filter($a, $f); }
function Areduce(array $a, $f, $init){ return array_reduce($a, $f, $init); }
function Amax(array $a){ return Areduce($a, max, $a[0]); }
function Amin(array $a){ return Areduce($a, min, $a[0]); }
function Asum(array $a){ return Areduce($a, function($a,$b){ return $a+$b;}, 0); }
function Aprod(array $a){ return Areduce($a, function($a,$b){ return $a*$b;}, 1); }
function ACountAtLeastX(array $a, int $x): array { return Afilter($a, function($aa) use ($x) { return count($aa)>$x; }); }
function Akeys(array $a){ return array_keys($a); }
function Avals(array $a){ return array_values($a); }

function line2digits(string $line): array { $res = []; if(preg_match_all("/\d+/",$line,$b)) $res = $b[0];  return $res; }
function line2histogram(string $line, int $nline=0): array {
    $hline = [];
    if($nline===0)$nline=strlen($line);
    for($i=0;$i<$nline;$i++){
        $b = $line[$i];
        @$hline[$b]++;
    }
    return $hline;
}
