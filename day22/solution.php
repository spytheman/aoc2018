#!/usr/bin/env php
<?php 
include("common.php");

$lines = read_input(); $depth  = 0; $tx=0; $ty=0;
foreach($lines as $line){
    if(strpos($line, 'depth:')===0) sscanf($line, 'depth: %d', $depth);
    if(strpos($line, 'target:')===0) sscanf($line, 'target: %d, %d', $tx, $ty);
}
printf("Depth: %4d, Target: x:%4d y:%4d\n", $depth, $tx, $ty);

// part 1
$d = 15; // need some slack, so that the path finding in part 2 could try to surround the target
[$mx, $my] = [ (int) ($tx + $d), (int) ($ty + $d)];
$ag  = A2Dnew($mx,$my); $agi = A2Dnew($mx,$my); $age = A2Dnew($mx,$my);
$distances = A2Dnew($mx,$my,0);  
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
        $distances[$y][$x] = (int) (abs($y-$ty) + abs($x-$tx));
    }
}
//$ag[0][0]='M'; $ag[$ty][$tx]='T';
showGridZone($ag, 0,0, $mx+1, $my+1, 1);
printf("Total risk for the area: %10d\n", $sumrisk);

// part 2
$visited = []; 
$cpositions=0; $spositions = 0;
$toolcosts = ['T'=>7,'C'=>7,'N'=>7,];
$possibletools = [ '.'=>['T','C'], '='=>['C','N'], '|'=>['T','N'], ];
$positionsQ = new Ds\PriorityQueue(); 
$positionsQ->push(['cx'=>0,'cy'=>0,'ck'=>$ag[0][0],'ct'=>'T','cm'=>0,'cd'=>$mx*$my,'cw'=>0, 'pm'=>'z', 'pmoves'=>[], ], 0);
while(!$positionsQ->isEmpty() && $cstate = $positionsQ->pop() ){
    $cpositions++;
    ['cx'=>$cx, 'cy'=>$cy, 'ck'=>$ck, 'ct'=>$ct, 'cm'=>$cm, 'cd'=>$cd, 'cw'=>$cw, 'pm'=>$pm, 'pmoves'=>$pmoves]=$cstate;    

    if(0 === $cpositions % 200000){
        printf("cpos: %5d | spos: %5d | lpositionsQ: %5d | cx: %4d | cy: %4d | ct: %1s | cm: %4d | pm: %1s | cw: %4d | ck: %1s \n",
               $cpositions, $spositions,
               $positionsQ->count(),
               $cx, $cy, $ct, $cm, $pm, $cw, $ck );
        showMoves($pmoves);
    }        
    
    if( isset($visited[$cy][$cx][$ct]) ) {
        // Already visited this coordinate, equiped with this same tool ... 
        // Cut moving in circles by skipping and going to the next queued position
        $spositions++; 
        continue;
    }
    $visited[$cy][$cx][$ct] = $cm;

    if($cx === $tx && $cy === $ty ){
        printf("--- found route minutes: %4d \n", $cm);
        showMoves($pmoves);
        printf("--- Found new route, minutes: %4d | path.length: %4d | path: '%s'\n", $cm, count($pmoves), join('', $pmoves));
        printf("cpos: %8d | spos: %5d | cx: %4d | cy: %4d | ct: %1s | cm: %4d | pm: %1s \n",
               $cpositions, $spositions,
               $cx, $cy, $ct, $cm, $pm );
        //continue;
        exit();
    }    
    $moves = [ 
              ['R', $cx+1, $cy,   $ct, 1], 
              ['D', $cx,   $cy+1, $ct, 1], 
              ['U', $cx,   $cy-1, $ct, 1],  
              ['L', $cx-1, $cy,   $ct, 1], 
              ];
    foreach($moves as [$move, $nx, $ny, $nt, $mcost]){
        if( ($nx<0||$nx>$mx) || ($ny<0||$ny>$my) ) continue; // moving is restricted to the grid size
        $nck = $ag[$ny][$nx];
        $ncd = $distances[$ny][$nx];
        
        $mcost += ($nx === $tx && $ny === $ty && $ct !== 'T') ? $toolcosts['T'] : 0;
        
        $newmoves = [];        
        if($ck !== $nck ){
            // going to another type of terrain ...
            $availabletools = array_intersect( $possibletools[ $ck ], $possibletools[ $nck ] );
            $nt = array_shift($availabletools);
            if($nt !== $ct ){
                // we have to change the current tool
                $mcost += $toolcosts[ $nt ];
                $newmoves[] = $nt;
            }            
        }
        $newmoves[] = $move;
        
        $nmoves = array_merge($pmoves, $newmoves);
        $ncm = $cm + $mcost;
        $ncw = 0 - $ncm - $ncd;
        $positionsQ->push( ['cx'=>(int)$nx,  'cy'=>(int)$ny, 'ck'=>$nck, 'ct'=>$nt,
                            'cm'=>(int)$ncm, 'cd'=>$ncd,     'cw'=>$ncw, 
                            'pm'=>$move, 'pmoves'=>$nmoves, ],
                           $ncw );        
    }    
}
printf("Total cpos: %8d , spos: %8d \n", $cpositions, $spositions);

function showMoves($pmoves){
    global $mx, $my;
    $zg = A2Dnew($mx,$my,SPACE_EMPTY); $zx=0;$zy=0;$zt='T'; $mzx=0;$mzy=0;
    $zg[$zy][$zx]='X';
    foreach($pmoves as $m){
        switch($m){
         case 'U':$zy--; break; 
         case 'D':$zy++; break; 
         case 'L':$zx--; break; 
         case 'R':$zx++; break;
         case 'T': case 'C': case 'N':{ $zt=$m; break; }
        }
        if($zx>$mzx)$mzx=min($mx, $zx); if($zy>$mzy)$mzy=min($my, $zy);
        $zg[$zy][$zx]=$zt;
    }
    showGridZone($zg, 0,0, $mzx+2, $mzy+2, 1);
}
