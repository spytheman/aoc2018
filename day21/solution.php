#!/usr/bin/env php
<?php 
include("common.php");
chdir(SDIR); system("make");
system("./day21 input 0 100000000");
