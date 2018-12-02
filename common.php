<?php
define('SFILE', $_SERVER['PWD'].'/'.$_SERVER['PHP_SELF']);
define('SDIR', dirname(SFILE));

function read_input($filename=''){
    if($filename==='')$filename=SDIR."/input";
    $clean_lines = []; 
    $lines = explode("\n", file_get_contents($filename));
    foreach($lines as $line){
        if($line==='')continue;
        $clean_lines[] = $line;
    }
    return $clean_lines;
}
