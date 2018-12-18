<?php
define('SFILE', realpath($_SERVER['PHP_SELF']));
define('SDIR', dirname(SFILE));
ini_set('memory_limit', '512M');

define('SPACE_EMPTY', '░'); define('SPACE_FILLED', '▓');

function endl(){ echo "\n";}
function Aprintv(array $a, string $aname='A'){ printf("| %s: [%s] ", $aname, join(',', $a)); }
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
function Aall(array $a, $f): bool {
    $res = true; foreach($a as $e)if(!$f($e)){ $res = false; break; }
    return $res;
}
function Afirst_matching(array $a, $f){
    foreach($a as $k=>$e)if($f($e,$k)){ return $e; }
    return false;
}
function Afirst_matchingkv(array $a, $f): array {
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
function A2Dnew(int $maxx, int $maxy, $dflt=0): array {  return array_fill(0, $maxy+1, array_fill(0, $maxx+1, $dflt)); }
function A1Deach(array $a, $f){ foreach($a as $x) if(false===$f($x,$y))return; }
function A2Deach(array $a, $f){ foreach($a as $x) foreach($a as $y) if(false===$f($x,$y))return; }
function A3Deach(array $a, $f){ foreach($a as $x) foreach($a as $y) foreach($a as $z) if(false===$f($x,$y,$z))return; }
function Aflatten(array $array): array {   return iterator_to_array( new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array))); }
function Aunsetkeys(array &$a, array $keys){  foreach($keys as $k)unset($a[$k]); return $a;}
function Acast2ints(array $a): array { return array_map('intval', $a); }
function Ahistogram(array $a): array { return array_count_values($a); }
function Ahistogram_update(array &$a, array $newvals){ foreach($newvals as $nv){ @$a[$nv]++; } }
function line2array(string $line, int $chunksize=1): array {   return str_split($line, $chunksize); }
function line2digits(string $line, $withsigns=true): array { $res = []; $regexp = ($withsigns) ? "/([+-]?\d+)/" : "/(\d+)/"; if(preg_match_all($regexp,$line,$b)) $res = Acast2ints(Afirst($b)); return $res; }
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
define('SEPARATORLINE', "#------------------------------------------------------------------------------\n");
function ilimit($x, $xmax=0, $xmin=0){ return min($xmax, max($xmin, $x)); }
function showGridZone($grid, $topx=0, $topy=0, $w=0, $h=0,$ordinarysize=2, $showproduct=false){
    $gmaxx = count($grid[0]); $gmaxy = count($grid);    
    $xstart = $topx; $ystart = $topy;
    if($topx>$gmaxx)printf("# GE: topx %d > gmaxx %d\n", $topx, $gmaxx); $xstart=ilimit($xstart, $gmaxx); 
    if($topy>$gmaxy)printf("# GE: topy %d > gmaxy %d\n", $topy, $gmaxy); $ystart=ilimit($ystart, $gmaxy);
    $xend = ilimit($topx+(($w === 0 ) ? $gmaxx : $w), 999999, $xstart);     
    $yend = ilimit($topy+(($h === 0 ) ? $gmaxy : $h), 999999, $ystart);
    if($xend>$gmaxx)printf("# GE: xend %d > gmaxx %d\n", $xend, $gmaxx); $xend=ilimit($xend, $gmaxx, $xstart);
    if($yend>$gmaxy)printf("# GE: yend %d > gmaxy %d\n", $yend, $gmaxy); $yend=ilimit($yend, $gmaxy, $ystart);
    
    $ordinarysize2 = 2 * $ordinarysize + 2; $ordinarysize3 = 3 * $ordinarysize + 3;
    printf(SEPARATORLINE);
    printf("# Grid zone top xy={%d,%d}, wh={%d,%d} | cellsize: %d | start %d,%d | end %d,%d\n", $topx, $topy, $w, $h, $ordinarysize, $xstart, $ystart, $xend,$yend);
    printf(SEPARATORLINE); 
    $yzeros=0; $ysums=0; $yproducts = 1;
    for($y=$ystart;$y<$yend;$y++){
        $xs=0; $xp=1; $xzeros=0; $cells=[]; 
        for($x=$xstart;$x<$xend;$x++) {
            $g = $grid[$y][$x];
            $v = (int) $g;
            $xs += $v;
            $xp *= $v;
            if($v==0)$xzeros++;
            $cells[]=sprintf("%{$ordinarysize}s", $g);
        }
        printf("#y: %3d |z: %5d |s: %{$ordinarysize2}d |", $y, $xzeros, $xs);
        if($showproduct)printf("p: %{$ordinarysize2}d |", $xp);
        printf("%s\n", join('', $cells));
        $ysums+=$xs; $yproducts*=$xp; $yzeros+=$xzeros;
    }
    printf(SEPARATORLINE);
    printf("# Area ZEROS: %-6d TSUM: %-{$ordinarysize3}d TPRODUCT: %-{$ordinarysize3}d\n", $yzeros, $ysums, $yproducts);
    printf(SEPARATORLINE);
}

function ve($x){ return json_encode($x); }
function noSpace($line, $what=' '){ return str_replace($what, '', $line); }
function read_input($filename=''){
    if($filename==='')$filename=SDIR."/input";
    if($_SERVER['argc']>1) $filename=$_SERVER['argv'][1];
    return file($filename, FILE_IGNORE_NEW_LINES);
}
