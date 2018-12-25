#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input(); 
$points = Amap($lines, function($line){ return line2digits($line); });
$allConstelations = Constelation::points2Constelations( $points );
//foreach($allConstelations as $k=>$c) printf("Constelation %5d = %s\n", $k, $c);
printf("Total constelations created: %10d\n", Constelation::getTotalConstelationsCreated());
printf("Part 1 answer (number of constelations) is: %d\n", count($allConstelations));

////////////////////////////////////////////////////////////////////////////////////////////////////////////

class Constelation {
    static $totalconstelations = 0;
    var $npoints = 0;
    var $points = [];
    var $ckey = "";
    function __construct(array $points){
        $this->points = $points;
        $this->npoints = count($points);
        $this->ckey = ve($points);
        Constelation::$totalconstelations++;
    }
    function __toString(): string {
        return sprintf("Constelation{npoints: %3d, points: %-20s}",
                       $this->npoints, ve($this->points)); 
    }
    function isPointInside(array $p1): bool {
        if(0===$this->npoints) return true;
        foreach($this->points as $p2) {
            if( 
                abs($p1[0]-$p2[0])+
                abs($p1[1]-$p2[1])+
                abs($p1[2]-$p2[2])+
                abs($p1[3]-$p2[3]) <= 3
                ) return true;
        }
        return false;
    }
    static function getTotalConstelationsCreated(): int {
        return Constelation::$totalconstelations;
    }
    static function merge(Constelation $c1, Constelation $c2): Constelation {
        return new Constelation( array_merge($c1->points, $c2->points) );
    }
    static function canMerge(Constelation $c1, Constelation $c2): bool {
        static $canMergeCache = [];
        if(!isset($canMergeCache[$c1->ckey][$c2->ckey])) {
            $canMergeCache[$c1->ckey][$c2->ckey] = Constelation::_canConstelationsMerge($c1, $c2);
        }
        return $canMergeCache[$c1->ckey][$c2->ckey];
    }
    private static function _canConstelationsMerge(Constelation $c1, Constelation $c2): bool {
        if( $c1->npoints >= $c2->npoints ) {
            foreach($c2->points as $p)  if($c1->isPointInside($p)) return true;
        }else{
            foreach($c1->points as $p)  if($c2->isPointInside($p)) return true;
        }
        return false;
    }
    static function points2Constelations(array $points): array {
        $allConstelations = Amap($points, function($point){ return new Constelation([ $point ]); });
        $nc = count($allConstelations);
        $comparisons = 0;
        while(true){
            $allConstelations = array_values($allConstelations); 
            if(0 === $comparisons || 0===$nc%50) printf("comparisons so far: %10d | nConstelations: %10d \n", $comparisons, $nc);
            for($i=$nc-1;$i>0;$i--){ 
                $c1=@$allConstelations[$i]; if(!$c1) continue;
                for($j=0;$j<$i;$j++){ 
                    $c2=@$allConstelations[$j]; if(!$c2) continue;
                    $comparisons++;
                    if( Constelation::canMerge($c1,$c2) ){
                        unset($allConstelations[$i],$allConstelations[$j]);
                        $allConstelations[] = Constelation::merge($c1,$c2);
                        $nc--;
                        continue 3;
                    }
                }
            }
            printf(">>>>>> Final comparisons: %10d | nConstelations: %10d \n", $comparisons, $nc);
            break;
        }
        $allConstelations = array_values($allConstelations);
        return $allConstelations;
    }
}
