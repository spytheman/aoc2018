<?php
define('SFILE', realpath($_SERVER['PHP_SELF']));
define('SDIR', dirname(SFILE));
ini_set('memory_limit', '512M');

function Azeros(int $howmany, int $n=0): array { return array_fill(0,$howmany,$n); }
function Afirst(array $a){ return reset($a); }
function Alast(array $a){  return end($a); }
function Afirstkv(array $a){ $v=reset($a); $k=key($a); return [$k,$v]; }
function Alastkv(array $a){  $v=end($a);   $k=key($a); return [$k,$v]; }
function Amap(array $a, $f): array { return array_map($f, $a); }
function Amapkv(array $a, $f): array { return array_map($f, array_keys($a), $a); }
function Afilter(array $a, $f): array { return array_filter($a, $f); }
function Areduce(array $a, $f, $init){ return array_reduce($a, $f, $init); }
function Amax(array $a){ $m=reset($a); foreach($a as $e)if($m<$e)$m=$e; return $m;}
function Amin(array $a){ $m=reset($a); foreach($a as $e)if($m>$e)$m=$e; return $m;}
function Amaxkv(array $a){ $mk=key($a);$mv=reset($a); foreach($a as $k=>$e)if($mv<$e){$mv=$e; $mk=$k;} return [$mk,$mv]; }
function Aminkv(array $a){ $mk=key($a);$mv=reset($a); foreach($a as $k=>$e)if($mv>$e){$mv=$e; $mk=$k;} return [$mk,$mv]; }
function Amax_by_key(array $a){ $mk=key($a);$mv=reset($a); foreach($a as $k=>$v) if($mk<$k){$mk=$k;$mv=$v;} return $mv; }
function Amin_by_key(array $a){ $mk=key($a);$mv=reset($a); foreach($a as $k=>$v) if($mk>$k){$mk=$k;$mv=$v;} return $mv; }
function Asum(array $a){ return array_sum($a); }
function Aprod(array $a){ return array_product($a); }
function ACountAtLeastX(array $a, int $x): array { $y=[]; foreach($a as $aa)if(count($aa)>$x)$y[]=$aa; return $y;}
function Akeys(array $a){ return array_keys($a); }
function Avals(array $a){ return array_values($a); }
function Apart(array $a, int $start, int $len=0){ return array_slice($a, $start, $len); }
function Acount(array $a, $what){ $c=0; foreach($a as $e) if($e===$what)$c++; return $c; }
function Alen(array $a): int { return count($a); }
function Ahas(array $a, $what): bool { return in_array($what, $a, true); }
function Ahas_key(array $a, $what): bool { return array_key_exists($what, $a); }
function Akeyof(array $a, $what){ return array_search($what, $a); }
function Azip2(array $a1, array $a2): array { return array_map(function($a,$b){ return [$a,$b]; }, $a1, $a2); }
function Azip3(array $a1, array $a2, array $a3): array { return array_map(function($a,$b,$c){ return [$a,$b,$c]; }, $a1, $a2, $a3); }
function Areverse(array $a): array { return array_reverse($a, true); }
function Acolumn(array $a, $colname='id',$colindexname=null): array { return array_column($a, $colname,$colindexname); }
function Aareall(array $a, $f): bool {
    $res = true; foreach($a as $e)if(!$f($e)){ $res = false; break; }
    return $res;
}
function Afirstmatching(array $a, $f){
    foreach($a as $k=>$e)if($f($e,$k)){ return $e; }
    return false;
}
function Afirstmatchingkv(array $a, $f): array {
    foreach($a as $k=>$e)if($f($e,$k)){ return [$k,$e]; }
    return false;
}
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
function Aunsetkeys(array &$a, array $keys){  foreach($keys as $k)unset($a[$k]); return $a;}
function Acast2ints(array $a): array { return array_map('intval', $a); }
function Ahistogram(array $a): array { return array_count_values($a); }
function Ahistogram_update(array &$a, array $newvals){ foreach($newvals as $nv){ @$a[$nv]++; } }
function line2array(string $line, int $chunksize=1): array {   return str_split($line, $chunksize); }
function line2digits(string $line): array { $res = []; if(preg_match_all("/\d+/",$line,$b)) $res = Acast2ints(Afirst($b));  return $res; }
function line2histogram(string $line): array {  return Ahistogram(line2array($line)); }
function histogramMostCommon(array $histogram, int $n=1): array {
    arsort($histogram);
    $k = array_keys( $histogram ); $v = array_values( $histogram );
    return array_combine( Apart($k, 0, $n), Apart($v, 0, $n));
}
function Agetkv(array $a, int $i=0): array { return [ array_keys($a)[$i], array_values($a)[$i] ]; }
function Agetkvmostfrequent(array $a): array { return Agetkv(histogramMostCommon($a)); }
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
    if($_SERVER['argc']>1) $filename=$_SERVER['argv'][1];
    return file($filename, FILE_IGNORE_NEW_LINES);
}
