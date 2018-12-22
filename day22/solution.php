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
//showGridZone($ag, 0,0, $mx+1, $my+1, 1);
printf("Total risk for the area: %10d\n", $sumrisk);

// part 2
$movecosts = ['U'=>1,'D'=>1,'L'=>1,'R'=>'1',   'T'=>7, 'C'=>7, ' '=>7];
$allpossiblemoves = array_keys($movecosts);
$cstate = ['ct' => 'T', 'cx'=>0, 'cy'=>0];
$mg  = A2Dnew($mx,$my,SPACE_EMPTY);
$minutes = findRoutes($mg, $cstate);
printf("Minimum minutes: %d\n", $minutes);
exit();
function findRoutes($mg, $cstate, $cminutes=0, $nmoves=0, $previousmove='z' ){
    global $ag, $tx, $ty, $mx, $my, $allpossiblemoves, $movecosts;
    assert( $nmoves < 200, 'too many moves');
    assert( $cminutes >= 0 , 'findRoutes called with negative cminutes!');
    $spad = str_pad(' ', $nmoves, '>');
    
    $cx = (int) $cstate['cx']; $cy = (int) $cstate['cy']; $ct = $cstate['ct'];
    if( $cx<0   ){ echo "{$spad} cx <  0\n"; return -1; }
    if( $cx>$my ){ echo "{$spad} cx > mx\n"; return -1; }
    if( $cy<0   ){ echo "{$spad} cy <  0\n"; return -1; }
    if( $cy>$my ){ echo "{$spad} cy > my\n"; return -1; }
    $ck = $ag[$cy][$cx];
    $cd = abs($cy-$ty) + abs($cx-$tx);

    printf("{$spad} findRoutes target:{%2dx%2d} | cd:%3d | pmove:'%1s' | nmoves:%4d | cminutes:%4d | cstate:%20s \n", $tx, $ty, $cd, $previousmove, $nmoves, $cminutes, ve($cstate));    
    $dg = $mg; $dg[$cy][$cx]='X'; $dg[$ty][$tx]='T'; showGridZone($dg, 0,0, $mx, $my, 1);

    $cmoves = $movecosts;
    unset($cmoves[ $ct ]); // no point to change to the already selected tool
        
    $failreason = sprintf("{$spad} -1 since ck='%1s' and ct='%1s'\n", $ck, $ct);
    if('.' === $ck && $ct === ' '){ echo $failreason; return -1;}
    if('=' === $ck && $ct === 'T'){ echo $failreason; return -1;}
    if('|' === $ck && $ct === 'C'){ echo $failreason; return -1;}
    if('.' === $ck)unset($cmoves[' ']);
    if('=' === $ck)unset($cmoves['T']);
    if('|' === $ck)unset($cmoves['C']);
    if(0 === $cx)unset($cmoves['L']);  if($mx <= $cx)unset($cmoves['R']);
    if(0 === $cy)unset($cmoves['U']);  if($my <= $cy)unset($cmoves['D']);
    
    if($cx === $tx && $cy === $ty ){
        // arrived, so no need to move anymore
        unset($cmoves['U']);
        unset($cmoves['D']);
        unset($cmoves['L']);
        unset($cmoves['R']);        
        if( $ct === 'T' ) {
            printf("{$spad} Found new possible route with minutes: %d\n", $cminutes);
            showGridZone($mg, 0,0, $mx+1, $my+1, 1);
            return $cminutes;
        }
    }else{
        if(SPACE_EMPTY !== $mg[$cy][$cx]){ echo "{$spad} -1 since it was passed already\n"; return -1; }
    }
    
    //Try all the remaining moves:
    $nstates = [];
    foreach($cmoves as $move=>$mcost){
        $nstate = ['cx'=>(int)$cx, 'cy'=>(int)$cy, 'ct'=> $ct];
        switch($move){
         case 'U':$nstate['cy']--;  break;
         case 'D':$nstate['cy']++;  break;
         case 'L':$nstate['cx']--;  break;
         case 'R':$nstate['cx']++;  break;
         case 'T':$nstate['ct']='T';break;
         case 'C':$nstate['ct']='C';break;
         case ' ':$nstate['ct']=' ';break;
        }
        $ndistance = abs($nstate['cy']-$ty) + abs($nstate['cx']-$tx);
        $nstates[] = [(int) $ndistance, (int) $mcost, $move, $nstate ];
    }
    usort( $nstates, function($a, $b){  return 10*($a[0] <=> $b[0])  +  ($a[1] <=> $a[1]); });
    printf("{$spad} cstate: {cx:%2d, cy:%2d, ct: '%1s', ck:'%1s'} | nstates: \n", $cx, $cy, $ct, $ck);
    foreach($nstates as $n) printf("{$spad}         nstate: %s\n", ve($n));
    printf("\n\n");
    
    $routes=[];
    foreach($nstates as [$ndistance, $mcost, $move, $znstate ]){
        $nmg = $mg; 
        $znx = $znstate['cx']; $zny = $znstate['cy'];
        switch($move){
         case 'U':; case 'D':; case 'L':;  case 'R': {  $nmg[$cy][$cx]=$move; break; }
         case 'T':; case 'C':; case ' ':{
             //if($znx === $tx && $zny === $ty) {
             //$nmg[$cy][$cx]='F';
             //}
             break;
         }
        }
        $neededminutes = findRoutes($nmg, $znstate, $cminutes + $mcost, $nmoves+1, $move);
        //printf("{$spad} %1s needed minutes: %s\n", $move, ve($neededminutes));
        if($neededminutes === -1) continue;
        $routes[ $move ] = $neededminutes;
        //printf("{$spad} routes so far: %s\n", ve($routes));
    }
    
    if(!count($routes)) { echo "{$spad} -1 since no valid routes found\n"; return -1;}
    
    return Amin($routes);
}
