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
$maxmoves = PHP_INT_MAX; $maxminutes = PHP_INT_MAX;
$mg  = A2Dnew($mx,$my,SPACE_EMPTY);
$distances = A2Dnew($mx,$my,0); for($y=0;$y<=$my;$y++) for($x=0;$x<=$mx;$x++) $distances[$y][$x] = (int) (abs($y-$ty) + abs($x-$tx));
//$walked = A2Dnew($mx,$my,[]);
$foundroutes = [];
$minutes = findRoutes($mg, ['cx'=>0,'cy'=>0,'ct'=>'T']);
usort($foundroutes, function($a,$b){ return $b[0]<=>$a[0]; });
foreach($foundroutes as [$cminutes, $route]) printf("Found route lasting %5d minutes: %s\n", $cminutes, joint('',$route));
printf("Minimum minutes: %d\n", $minutes);
exit();

function findRoutes($mg, $cstate, $previousmoves=[], $isPrevAChange=false, $cminutes=0, $nmoves=0 ){
    static $calls=0; $calls++; 
    global $ag, $distances, $tx, $ty, $mx, $my, $movecosts, $maxmoves, $maxminutes, $foundroutes;
    assert( $cminutes >= 0 , 'findRoutes called with negative cminutes!');
    if( $nmoves   > $maxmoves   ) { echo "Cut: nmoves   > maxmoves\n"; return -1; }
    if( $cminutes > $maxminutes ) { echo "Cut: cminutes > maxminutes\n"; return -1; }
        
    $spad = str_pad(' ', $nmoves, '>');
    $cx = (int) $cstate['cx']; $cy = (int) $cstate['cy']; $ct = $cstate['ct'];
    if( $cx<0   ){ echo "Cut: cx<0  \n"; return -1; }
    if( $cy<0   ){ echo "Cut: cy<0  \n"; return -1; }
    if( $cx>$mx ){ echo "Cut: cx>mx \n"; return -1; }
    if( $cy>$my ){ echo "Cut: cy>my \n"; return -1; }
    
    //if( isset( $walked[$cy][$cx][$ct] ) ) { echo "\n"; return -1; }
    //$walked[$cy][$cx][$ct] = 1;
    
    $ck = $ag[$cy][$cx];
    $cd = $distances[$cy][$cx];
    
    if(0 === $calls % 1){
        printf("calls %9d - findRoutes target:{%2dx%2d} | maxminutes: %4d | cminutes:%4d | cd:%3d | maxmoves:%4d | nmoves:%4d | cstate: {cx:%3d,cy:%3d,ct:%1s} | pmove:'%1s'\n",
               $calls,
               $tx, $ty,
               $maxminutes, $cminutes, $cd,
               $maxmoves, $nmoves, 
               $cx, $cy, $ct, 
               join('',$previousmoves)
               );
    }

    $mgc = $mg[$cy][$cx];
    if(in_array($mgc, ['U','D','L','R'])) { echo "Cut: mgc in UDLR \n"; return -1; }
    if((int) $mgc > 1) { echo "Cut: mgc > 1 \n"; return -1; }
    
    $cmoves = $movecosts;
    unset($cmoves[ $ct ]); // no point to change to the already selected tool    
    
    //$failreason = sprintf("{$spad} -1 since ck='%1s' and ct='%1s'\n", $ck, $ct);
    if('.' === $ck && $ct === ' '){ echo "Cut: ck='.' && ct=' '\n"; return -1;}
    if('=' === $ck && $ct === 'T'){ echo "Cut: ck='=' && ct='T'\n"; return -1;}
    if('|' === $ck && $ct === 'C'){ echo "Cut: ck='|' && ct='C'\n"; return -1;}    
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
            printf("--- Found new route, minutes: %4d | path: %s\n", $cminutes, join('', $previousmoves));
            $dg = $mg; $dg[$cy][$cx]='X'; $dg[$ty][$tx]='T';
            showGridZone($mg, 0,0, $mx+1, $my+1, 1);
            // cut slower routes:
            $maxminutes = min($maxminutes, $cminutes   );
            $maxmoves   = min($maxmoves,   $nmoves + 8);
            $foundroutes[] = [$cminutes, $previousmoves];
            return $cminutes;
        }
    }

    if( $isPrevAChange ){
        // no point in changing tools several times for the same place
        unset($cmoves['T']);
        unset($cmoves['C']);
        unset($cmoves[' ']);
    }
        
    //Try all the remaining moves:
    $nstates = [];
    foreach($cmoves as $move=>$mcost){
        $ny = 0; $nx = 0; $nt ='';
        [$ny, $nx, $nt] = [$cy, $cx, $ct];
        switch($move){
         case 'U':$ny--;  break; case 'D':$ny++;  break;
         case 'L':$nx--;  break; case 'R':$nx++;  break;
         case 'T':; case 'C':; case ' ': { $nt=$move; break; }
        }
        //if( isset($walked[$ny][$nx][$nt]) ) continue;
        $nxtst = ['cx'=>(int)$nx,'cy'=>(int)$ny,'ct'=>$ct];
        $nstates[] = [ 0=>(int) $distances[$ny][$nx], 1=>(int) $mcost, 2=>$move, 3=>$nxtst ];
    }
    usort( $nstates, function($a, $b){  return 10*($a[0] <=> $b[0])  +  ($a[1] <=> $a[1]); });
    printf("{$spad} cstate: {cx:%2d, cy:%2d, ct: '%1s', ck:'%1s'} | nstates: \n", $cx, $cy, $ct, $ck);
    foreach($nstates as $n) printf("{$spad}         nstate: %s\n", ve($n));
    
    $routes=[];
    foreach($nstates as [$ndistance, $mcost, $move, $znstate ]){
        $ndistance = (int) $ndistance; $mcost = (int) $mcost;
        $nmg = $mg; $achanges = false;
        switch($move){
         case 'U':; case 'D':; case 'L':;  case 'R': {
             $achanges = false;
             $nmg[$cy][$cx]=$move; 
             break;
         }
         case 'T':; case 'C':; case ' ':;  {
             $achanges = true;
             if(  $nmg[$cy][$cx] === SPACE_EMPTY )  $nmg[$cy][$cx] = 0;
             $nmg[$cy][$cx]++;
             break;
         }
        }
        $nminutes = (int) $cminutes + (int) $mcost;
        if($nminutes >= $maxminutes)continue;
        if($nminutes + $ndistance >= $maxminutes)continue;
        
        $pmoves = $previousmoves; $pmoves[] = $move;
        $neededminutes = findRoutes($nmg, $znstate, $pmoves, $achanges, $cminutes + $mcost, $nmoves+1 );
        printf("{$spad} Needed minutes for move: %s: %d\n", $move, $neededminutes);
        if($neededminutes === -1) continue;
        $routes[ $move ] = $neededminutes;
    }    
    if(!count($routes)) { echo "Cut: no routes.\n"; return -1; }
    
    printf("{$spad} Routes : %s\n", ve($routes));    
    return Amin($routes);
}
