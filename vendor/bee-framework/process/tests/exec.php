<?php

require __DIR__ . '/../vendor/autoload.php';


//exec("ps -N -o uname,pid,ppid,pmem,pcpu,stat,comm,cmd | grep -E 'php|%MEM'", $reslut);

//$data = [];
//
//foreach ($menu as $row) {
//    $line = [];
//
//}
//
//foreach ($reslut as $row) {
//    $line = [];
//    $flag = 0;
//
//    $vars = explode(' ', $row);
//    foreach ($vars as $key => $var) {
//        if (empty($var)) {
//            continue;
//        }
//
//        $line[$flag++ . 'fuck'] = $var;
//
//    }
//
//    $data[] = $line;
//}

//print_r($reslut);

//(new \Ahc\Cli\Output\Writer)->table($data);

require 'process.php';

//(new p([]))->reload();
(new p([]))->status();
