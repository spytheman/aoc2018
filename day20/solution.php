#!/usr/bin/env php
<?php
include("common.php");
$regexp = substr(join('', read_input()), 1, -1);
global $grid; $grid = [];
walkOverGridAndDiscoverRoomDoors($regexp, 0, 0);

$rooms = [ new Room(0,0,0) ];
$visitedrooms = []; $maxrooms = []; $lastroom = null;
while($room = array_shift($rooms)){
    $lastroom = $room;
    $roomc = $room->c(); $visitedrooms[$roomc] = 1; if($room->distance >= 1000) $maxrooms[$roomc] = 1;
    $roomdoors = $grid[$room->y][$room->x] ?? [];
    foreach($roomdoors as $d){
        $x=$room->x; $y=$room->y;
        switch($d){ case 'N':$y-=1;break; case 'E':$x+=1;break; case 'S':$y+=1;break; case 'W':$x-=1;break; }
        if(!isset($visitedrooms[Room::sc($x,$y)])) $rooms[]=new Room($x,$y,$room->distance+1);
    }
}
printf("Part 1 answer is: %d\n", $lastroom->distance);
printf("Part 2 answer is: %d\n", count($maxrooms));

function walkOverGridAndDiscoverRoomDoors(string $regexp, int $startX, int $startY){
    global $grid;
    $x = $startX; $y = $startY; $n = strlen($regexp);
    for($i=0;$i<$n;$i++){
        $c = $regexp[$i];
        if(in_array($c, ['N','E','S','W'])) $grid[$y][$x][] = $c;
        if($c === '('){
            $level = 0; for($j=$i;$j<$n;$j++){ if($regexp[$j]==='(')$level++; if($regexp[$j]===')')$level--; if($level === 0) break; }
            if($j<$n) {  walkOverGridAndDiscoverRoomDoors(substr($regexp, $i+1, $j-$i-1), $x, $y); $i=$j; }
        }
        if($c === '|'){ $x=$startX;$y=$startY; }
        switch($c){ case 'N':$y-=1;break; case 'E':$x+=1;break; case 'S':$y+=1;break; case 'W':$x-=1;break; }
    }
}

class Room {
    static function sc($x,$y):string{ return "{$x}x{$y}"; }
    var $x,$y,$distance;
    function __construct(int $x=0, int $y=0, int $distance=0){ $this->x = $x; $this->y = $y; $this->distance = $distance; }
    function c():string{ return Room::sc($this->x, $this->y); }
}
