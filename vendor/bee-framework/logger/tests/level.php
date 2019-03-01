<?php

$seasLog = new SeasLog;

$t = get_extension_funcs('seaslog');

SeasLog::setBasePath(__DIR__);

$aMessages = array('test log from array abc {website}','test log from array def {action}');
$aContent = array('website' => 'github.com','action' => 'rboot');

SeasLog::debug($aMessages,$aContent);