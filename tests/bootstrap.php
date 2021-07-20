<?php

/**
 * spoustec testu
 *
 * $ vendor/bin/tester tests
 */
require __DIR__ . '/../vendor/autoload.php';

use Tester\Dumper;
use Tester\Environment;

$temp = __DIR__."/../temp";
if(!is_dir($temp)) {
	mkdir($temp, 0777, true);
}

Environment::setup();
Dumper::$dumpDir = $temp."/output";
date_default_timezone_set("Europe/Prague");

return require_once __DIR__."/../app/bootstrap.php";
