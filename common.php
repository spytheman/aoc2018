<?php
define('SFILE', realpath($_SERVER['PHP_SELF']));
define('SDIR', dirname(SFILE));
ini_set('memory_limit', '512M');

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
function Apart(array $a, int $start, int $len=0){ return array_slice($a, $start, $len); }
function Acount(array $a, $what){ return count(Afilter($a, function($v) use ($what) { return $v === $what; })); }
function Alen(array $a): int { return count($a); }
function Ahas(array $a, $what): bool { return in_array($what, $a, true); }
function Ahas_key(array $a, $what): bool { return array_key_exists($what, $a); }
function Arepeat(array $a, $f){
    // Infinite loop over array $a, calling $f on each element, passing to $f the element value $v, the loop iteration $i, and the current array position $imod
    $alen = count($a); if($alen===0)return;
    $i=0;
    while(true){
        $imod = $i % $alen;
        $v = $a[$imod];
        if(FALSE === $f($v, $i, $imod))break;
        $i++;
    }
}
function A1Deach(array $a, $f){ foreach($a as $x) if(false===$f($x,$y))return; }
function A2Deach(array $a, $f){ foreach($a as $x) foreach($a as $y) if(false===$f($x,$y))return; }
function A3Deach(array $a, $f){ foreach($a as $x) foreach($a as $y) foreach($a as $z) if(false===$f($x,$y,$z))return; }
function Aflatten(array $array): array {   return iterator_to_array( new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array))); }
function Aunsetkeys(array &$a, array $keys){  foreach($keys as $k)unset($a[$k]); }
function Acast2ints(array $a): array { return Amap($a, function($e){ return (int) $e; }); }
function Ahistogram(array $a): array { $h = []; foreach($a as $e) @$h[$e]++; return $h; }
function Ahistogram_update(array &$a, array $newvals){ foreach($newvals as $nv){ @$a[$nv]++; } }
function line2array(string $line, int $chunksize=1): array {   return str_split($line, $chunksize); }
function line2digits(string $line): array { $res = []; if(preg_match_all("/\d+/",$line,$b)) $res = Acast2ints($b[0]);  return $res; }
function line2histogram(string $line): array {  return Ahistogram(line2array($line)); }
function histogramMostCommon(array $histogram, int $n=1): array {
    arsort($histogram);
    $k = Akeys( $histogram ); $v = Avals( $histogram );
    return array_combine( Apart($k, 0, $n), Apart($v, 0, $n));
}
function Akv(array $a, int $index=0): array { return [ Akeys($a)[$index], Avals($a)[$index] ]; }
function Akvmostfrequent(array $a): array { return Akv(histogramMostCommon($a)); }
function line2maskedlines($line,$maskchar=' '){
    $res = [];
    $letters = line2array($line);
    foreach($letters as $k=>$b){
        $mline = $letters; 
        $mline[$k]=$maskchar; 
        $mline = join('', $mline);
        $res[]=$mline;
    }
    return $res;
}
function rectangleEach($topx, $topy, $w, $h, $f){
    $maxx = $topx + $w;
    $maxy = $topy + $h;
    for($x=$topx;$x<$maxx;$x++){
        for($y=$topy;$y<$maxy;$y++){
            if(false === $f($x,$y))return;
        }
    }     
}
function ve($x){ return json_encode($x); }
function noSpace($line, $what=' '){ return str_replace($what, '', $line); }
function read_input($filename=''){
    if($filename==='')$filename=SDIR."/input";
    return Afilter( explode("\n", file_get_contents($filename)), function($line){ return $line!==''; });
}
