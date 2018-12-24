#!/usr/bin/env php
<?php 
include("common.php");
$lines = read_input();
$allgroups = ['imune'=>[], 'infection'=>[]];
$cindex = '';
$c=0;
foreach($lines as $line){
    if(strlen($line)===0)continue;
    if( ':' === $line[-1] ){
        if($line==='Immune System:') $cindex = 'imune';
        if($line==='Infection:')     $cindex = 'infection';
        continue;
    }
    $side=&$allgroups[$cindex];
    $g = new Group($c,$cindex);
    $g->extractInfoFromLine($line);
    $side[$g->id]=$g;
    $c++;
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
    function __construct(int $id, string $kind){
        $this->id = $id;
        $this->kind = $kind;
    }
    function __toString(): string {
        return sprintf("Group{ id: %2d, kind: %-9s, units: %4d, hp: %5d, adamage: %3d, atype: %11s, initiative: %2d, weakto: [%-24s], strongto: [%-24s]}",
                       $this->id, $this->kind,
                       $this->units, $this->hp,                       
                       $this->adamage, $this->atype, $this->initiative,                       
                       join(', ', $this->weakto), join(', ', $this->strongto)
        );
    }
    static function show(array $allgroups){
        foreach($allgroups as $gkind=>$groups){
            printf("%s groups:\n", $gkind);
            foreach($groups as $g) {
                printf("%s\n", $g);
            }
        }            
        printf("\n");
    }
    static function addOptions(array &$optionlist, string $s, string $starts=''){
        $optionlist = array_merge($optionlist, explode(',', str_replace(' ', '', str_replace($starts,  '', $s)))); 
    }
    function extractOptions(string $line){
        if(strpos($line, '(')>0){
            $weakto=[]; $strongto=[];
            $_skip = strtok($line, '()');  $options = strtok('()');   $_skip = strtok('()');
            $aoptions = explode('; ', $options);
            foreach($aoptions as $a){
                if(strpos($a, $x='weak to')===0)   Group::addOptions($weakto,   $a, $x);
                if(strpos($a, $x='immune to')===0) Group::addOptions($strongto, $a, $x);
            }        
            $this->weakto=$weakto;
            $this->strongto=$strongto;
        }
    }
    function extractUnitsAndHP(string $line){
        sscanf($line, "%d units each with %d hit points", $this->units, $this->hp);
    }
    function extractAttackAndInitiative(string $line){         
        if(preg_match_all("/with an attack that does (\d+) (.+) damage at initiative (\d+)$/", $line, $b)){
            [ $this->adamage, $this->atype, $this->initiative ] = [ (int) $b[1][0], $b[2][0], (int) $b[3][0] ];
        }
    }
    function extractInfoFromLine(string $line){
        //printf("group id: %5d : kind: %10s | line: %s\n", $this->id, $this->kind, $line);
        $this->extractUnitsAndHP($line);
        $this->extractOptions($line);
        $this->extractAttackAndInitiative($line);
    }
}
