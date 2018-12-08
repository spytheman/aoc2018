#!/usr/bin/env php
<?php 
include("common.php");
$digits = line2digits(join(' ', read_input()));
printf("Digits: [ %s ]\n", join(',', $digits));
global $nodes; $nodes = [];
function newNode(){
    static $name='A';
    return [
            'name'=>$name++,
            'nChildren'=>0,
            'nMeta'=>0,
            'children'=>[],
            'metadata'=>[],
            'index'=>0,
            'size'=>0,
    ];
}
function readNode(array $digits, int $i){
    global $nodes;
    $o=0;
    $node = newNode();
    $node['nChildren']= $nChildren = $digits[$i+$o]; $o++;
    $node['nMeta']    = $metaSize  = $digits[$i+$o]; $o++;
    $children=[];
    for($c=0;$c<$nChildren;$c++){
        $cNode = readNode($digits, $i+$o);
        $children[$c]=$cNode;
        $o+=$cNode['size'];
    }
    $metadata = array_slice($digits,$i+$o, $metaSize); $o+=$metaSize;
    $node['index']=$i;
    $node['size']=$o;
    $node['children'] = $children;
    $node['metadata'] = $metadata;
    $nodes[]=$node;
    return $node;
}
$root = readNode($digits, 0);
foreach($nodes as $n){
    printf("Node %s , starts at: %d , size: %d\n", $n['name'], $n['index'], $n['size']);
}
$metadataSum = Asum(Amap($nodes, function($n){ return Asum($n['metadata']);}));
printf("Part 1 answer (the sum of all metadata entries) is: %d\n", $metadataSum);