<?php
//require_once '../lBox/lib/loader_phpunit.php';
require_once(dirname(__FILE__) .'/../../lime/lime.php');
require_once('PHPUnit/Autoload.php');

/**
 * runs all defined tests
 * if tests directory contains coverage folder, it will generate coverage results there
 */
function run_tests() {
	// read test directory and run all tests
	$path					= dirname(__FILE__) . '/tests';
	$dir					= dir($path);
	$lime_output	= new lime_output();
	$coverage			= $coveragePath = '';
	while (($entry = $dir->read()) !== false) {
		if($entry == '.' || $entry == '..') continue;
		if (is_dir("$path/$entry")) {
			if ($entry == 'coverage') {
				$coveragePath	= $path .'/'. $entry;
				$coverage 		= ' --coverage-html '. $coveragePath;
			}
			continue;
		}
		if (is_test($entry)) {
			include "$path/$entry";
			//$lime_output->info("phpunit $path/$entry --colors $coverage");
			$lime_output->green_bar($entry);
			system("phpunit $path/$entry --colors $coverage");
		}
	}
	if (strlen($coverage) > 0) {
		$lime_output->info("You can find coverage results in $coveragePath");
	}
	$dir->close();
}

/**
 * retuns true if given filename looks like test class
 * @param string $filename
 * @return bool
 */
function is_test($filename) {
	$pattern_test	= '(.+)Test$';
	$parts				= explode('.', $filename);
	foreach ($parts as $part) {
		if (preg_match("/$pattern_test/", $part)) {
			return true;
		}
	}
	return false;
}
