<?php

require 'vendor/autoload.php';

spl_autoload_register(function ($class) {

	$blacklist=['^setasign'];

	foreach($blacklist as $b)
		if(preg_match("/$b/",$class)) return false;

	$pName=__DIR__.'/classes/'.preg_replace('/\\\\/','/',$class).'.php';

	include $pName;
});