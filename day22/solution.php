#!/usr/bin/env php
<?php 
include("common.php");
ini_set('xdebug.max_nesting_level', 2012);
ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);
$lines = read_input(); $depth  = 0; $tx=0; $ty=0;
foreach($lines as $line){
    if(strpos($line, 'depth:')===0) sscanf($line, 'depth: %d', $depth);
    if(strpos($line, 'target:')===0) sscanf($line, 'target: %d, %d', $tx, $ty);
}
printf("Depth: %4d, Target: x:%4d y:%4d\n", $depth, $tx, $ty);

// part 1
[$mx, $my] = [ (int) ($tx + 5), (int) ($ty + 5)];
$ag  = A2Dnew($mx,$my); $agi = A2Dnew($mx,$my); $age = A2Dnew($mx,$my);
$sumrisk = 0;
for($y=0;$y<=$my;$y++){
    for($x=0;$x<=$mx;$x++){
        if( ($tx === $x && $ty === $y) || (0 === $x && 0 === $y) ) $gi = 0;
        else if(0 === $y )$gi = $x * 16807;
        else if(0 === $x )$gi = $y * 48271;
        else $gi = $age[$y][$x-1] * $age[$y-1][$x];
        $ge = ($gi + $depth) % 20183;
        $ag[$y][$x]  = $t = [0=>'.', 1=>'=', 2=>'|'][ $risk = $ge % 3 ];
        $agi[$y][$x] = $gi;
        $age[$y][$x] = $ge;
        if($y <= $ty && $x <= $tx) $sumrisk += $risk;
    }
}
//$ag[0][0]='M'; $ag[$ty][$tx]='T';
showGridZone($ag, 0,0, $mx+1, $my+1, 1);
printf("Total risk for the area: %10d\n", $sumrisk);

// part 2
$movecosts = ['U'=>1,'D'=>1,'L'=>1,'R'=>'1',   'T'=>7, 'C'=>7, ' '=>7]; 
$maxmoves = PHP_INT_MAX; $maxminutes = PHP_INT_MAX;
$mg  = A2Dnew($mx,$my,SPACE_EMPTY);
$distances = A2Dnew($mx,$my,0); for($y=0;$y<=$my;$y++) for($x=0;$x<=$mx;$x++) $distances[$y][$x] = (int) (abs($y-$ty) + abs($x-$tx));

$visited = [];
$mweight = PHP_INT_MAX;
$cpositions=0;
$positionsQ = new Ds\PriorityQueue();
$positionsQ->push(['cx'=>0,'cy'=>0,'ct'=>'T','cm'=>0,'cd'=>$mweight,'cw'=>$mweight, 'pm'=>'z', 'pmoves'=>[], ], $mweight);
while(!$positionsQ->isEmpty()){
    $cstate = $positionsQ->pop();
    if(!$cstate)break;
    $cpositions++;
    
    $cx     = (int) $cstate['cx']; 
    $cy     = (int) $cstate['cy']; 
    $cm     = (int) $cstate['cm'];
    $ct     = $cstate['ct'];
    $pm     = $cstate['pm'];
    $pmoves = $cstate['pmoves'];
    
    if( isset($visited[$cy][$cx][$ct]) ) {
        // Already visited this coordinate, equiped with this same tool ... 
        // Cut moving in circles by skipping and going to the next queued position
        continue;
    }
    $visited[$cy][$cx][$ct] = true;
        
    if(0 === $cpositions % 10000){
        printf("cpositions so far: %8d | lpositionsQ: %5d | cx: %4d | cy: %4d | ct: %1s | cm: %4d | pm: %1s \n", 
               $cpositions, 
               $positionsQ->count(), 
               $cx, $cy, $ct, $cm, $pm );
        showMoves($pmoves);
    }
    
    if($cx === $tx && $cy === $ty && $ct === 'T'){
        if( $maxminutes > $cm ) $maxminutes = $cm;
        printf("--- found route minutes: %4d \n", $cm);
        printf("cpositions: %8d | cx: %4d | cy: %4d | ct: %1s | cm: %4d | pm: %1s \n", 
               $cpositions, 
               $cx, $cy, $ct, $cm, $pm );
        showMoves($pmoves);
        printf("--- Found new route, minutes: %4d | maxminutes: %4d | path.length: %4d | path: '%s'\n", $cm, $maxminutes, count($pmoves), join('', $pmoves));
        exit();
    }
    
    $ck = $ag[$cy][$cx];
    //if( $cm > $maxminutes ) { /*echo "Cut: cm={$cm} > maxminutes={$maxminutes}\n";*/ continue; }

    $cmoves = $movecosts;
    unset($cmoves[ $ct ]); // no point to change to the already selected tool
    
    if('.' === $ck) unset($cmoves[' ']);
    if('=' === $ck) unset($cmoves['T']);
    if('|' === $ck) unset($cmoves['C']);
    
    if($cx <=  0)   unset($cmoves['L']);     if($mx <=  $cx) unset($cmoves['R']);
    if($cy <=  0)   unset($cmoves['U']);     if($my <=  $cy) unset($cmoves['D']);
    
    if($cx === $tx && $cy === $ty && $ct !== 'T') {
        // arrived at the target. No need to move anymore. Just schedule a tool change...
        unset($cmoves['U']); unset($cmoves['D']); unset($cmoves['L']); unset($cmoves['R']); 
    }
    
    $isPrevMoveAToolChange = in_array($pm, ['T','C',' ']);
    if( $isPrevMoveAToolChange ){
        // There is no point to change a tool twice in a row, staying in the same position.
        unset($cmoves['T']); unset($cmoves['C']); unset($cmoves[' ']);
    }
    
    foreach($cmoves as $move=>$mcost){
        $ny = (int) $cy; $nx = (int) $cx; $nt = $ct;
        switch($move){
         case 'U':$ny--;  break;         case 'D':$ny++;  break;
         case 'L':$nx--;  break;         case 'R':$nx++;  break;
         case 'T':; case 'C':; case ' ': { $nt=$move; break; }
        }
        if( ($nx<0||$nx>$mx) || ($ny<0||$ny>$my) )continue;        
        $nck = $ag[$ny][$nx];
        if('.' === $nck && $nt === ' '){ continue; }
        if('=' === $nck && $nt === 'T'){ continue; }
        if('|' === $nck && $nt === 'C'){ continue; }        
        if($nx === $tx && $ny === $ty && $nt !== 'T' ){ continue; }
        /////////////////////////////////////////////////////////////
        $ncd = $distances[$ny][$nx];
        $mcost = (int) $mcost;
        $ncm = $cm + $mcost;
        $ncw = $mweight - $ncm - $ncd;
        $positionsQ->push( ['cx'=>(int)$nx,  'cy'=>(int)$ny, 'ct'=>$nt,
                            'cm'=>(int)$ncm, 'cd'=>$ncd,     'cw'=>$ncw, 
                            'pm'=>$move, 'pmoves'=>array_merge($pmoves, [$move]), ], 
                           $ncw );
    }    
}

function showMoves($pmoves){
    global $mx, $my;
    global $mg;
    global $ag;
    $zg = $mg; $zx=0;$zy=0;$zt='T'; $mzx=0;$mzy=0;
    $zg[$zy][$zx]='X';
    foreach($pmoves as $m){
        switch($m){
         case 'U':$zy--;  break; 
         case 'D':$zy++;  break;
         case 'L':$zx--;  break; 
         case 'R':$zx++;  break;
         case 'T':
         case 'C': 
         case ' ':{ 
             $zt=$m; 
             break; 
         }
        }
        if($zx>$mzx)$mzx=min($mx, $zx);
        if($zy>$mzy)$mzy=min($my, $zy);
        $zg[$zy][$zx]=$zt;
    }
    showGridZone($zg, 0,0, $mzx+2, $mzy+2, 1);
}
