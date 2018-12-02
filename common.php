<?php

function read_input($filename){
    $clean_lines = []; 
    $lines = explode("\n", file_get_contents("input"));
    foreach($lines as $line){
        if($line==='')continue;
        $clean_lines[] = $line;
    }
    return $clean_lines;
}
