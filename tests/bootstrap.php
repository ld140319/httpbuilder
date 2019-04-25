<?php

/*
 * This file is part of the HttpBuilder package.
 * (c) Ethan <ethanalog@gmail.com>
 */

// include(__DIR__.'/../Autoloader.php');
// exit();

if (file_exists(__DIR__.'/../../autoload.php')) {
	require __DIR__.'/../../autoload.php';
} elseif(@include(__DIR__.'/../Autoloader.php')){
	Ethansmart\HttpBuilder\Autoloader::register();
} else {
	 die('ERROR: Unable to find a suitable mean to register Ethansmart\HttpBuilder\Autoloader.');
}

require __DIR__.'/HttpRequestTest.php';