#!/usr/bin/env php
<?php
include("common.php");
$lines = read_input();
$allgroups = ['imune'=>[], 'infection'=>[]];
$cindex = '';
$c=0;
foreach($lines as $line){ $c++;
    if(strlen($line)===0)continue;
    if( ':' === $line[-1] ){ $cindex = ['Immune System:'=>'imune', 'Infection:'=>'infection'][ $line ] ?? ''; continue; }
    $allgroups[$cindex][$c] = new Group($c,$cindex,$line);
}
Group::show($allgroups);

class Group {
    var $id=0;
    var $kind='';
    var $units=0;
    var $hp=0;
    var $weakto=[];
    var $strongto=[];
    var $adamage=0;
    var $atype='';
    var $initiative=0;
    function __construct(int $id, string $kind, string $line){  $this->id = $id;  $this->kind = $kind;  $this->extractInfoFromLine($line);  }
    function __toString(): string {
        return sprintf("Group{ id: %2d, kind: %-9s, units: %4d, hp: %5d, adamage: %3d, atype: %11s, initiative: %2d, weakto: [%-24s], strongto: [%-24s]}",
                       $this->id, $this->kind, $this->units, $this->hp, $this->adamage, $this->atype, $this->initiative, join(', ', $this->weakto), join(', ', $this->strongto));
    }
    static function show(array $allgroups){ 
        foreach($allgroups as $gkind=>$groups){  printf("%s groups:\n", $gkind);  foreach($groups as $g) printf("%s\n", $g);  }
        printf("\n");
    }
    function extractInfoFromLine(string $line){
        //printf("group id: %5d : kind: %10s | line: %s\n", $this->id, $this->kind, $line);
        sscanf($line, "%d units each with %d hit points", $this->units, $this->hp);
        if(strpos($line, '(')>0){
            $_skip = strtok($line, '()');  $options = strtok('()');   $_skip = strtok('()');
            $aoptions = explode('; ', $options);
            foreach($aoptions as $a){
                if(strpos($a, $x='weak to')===0)   $this->weakto   = explode(',',str_replace(' ','',str_replace($x,'',$a)));
                if(strpos($a, $x='immune to')===0) $this->strongto = explode(',',str_replace(' ','',str_replace($x,'',$a)));
            }
        }
        if(preg_match_all("/with an attack that does (\d+) (.+) damage at initiative (\d+)$/", $line, $b)){
            [ $this->adamage, $this->atype, $this->initiative ] = [ (int) $b[1][0], $b[2][0], (int) $b[3][0] ];
        }
    }
}
