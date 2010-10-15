<?php
$dirArr		= explode("/", dirname(__FILE__));
$pathRoot	= "";
foreach ($dirArr as $dir) {
	if ($dir == "tests") break;
	$pathRoot	.= strlen($pathRoot) > 0 ? "/" : "";
	$pathRoot	.= $dir;
}
$pathRoot = "/$pathRoot";
require_once($pathRoot .'/tests/lime/lime.php');
require_once $pathRoot.'/lBox/lib/loader_phpunit.php';
require_once('PHPUnit/Autoload.php');

/**
 * runs all defined tests
 * if tests directory contains coverage folder, it will generate coverage results there
 */
function run_tests() {
	global $argv;
	// read test directory and run all tests
	$path					= dirname(__FILE__) . '/tests';
	$dir					= dir($path);
	$lime_output	= new lime_output();
	$coverage			= $coveragePath = '';
	$tests				= array();
	while (($entry = $dir->read()) !== false) {
		if($entry == '.' || $entry == '..') continue;
		if (is_dir("$path/$entry")) {
			if ($entry == 'coverage') {
				$coveragePath	= $path .'/'. $entry;
			}
			continue;
		}
		if (is_test($entry)) {
			$tests[getTestNameByPath("$path/$entry")]	= "$path/$entry";
		}
	}
	// run concrete test
	if (($testName = $argv[1]) && can_param_be_test($testName)) {
		$testName	.= 'Test';
		if (!array_key_exists($testName, $tests)) {
			$lime_output->error("cannot find test ". $argv[1] . ' in '. $path);
			return;
		}
		if ($coveragePath) {
			$coveragePathConcrete	=	$coveragePath .'/'. $testName;
			LBoxUtil::createDirByPath($coveragePathConcrete);
			$coverage 		= 	'--coverage-html '. $coveragePathConcrete . ' ';
			//$coverage			.=	' --coverage-clover '. $coveragePathConcrete . '/clover.xml ';
		}
		$path	= $tests[$testName];
		$call	= "phpunit $coverage $path";
		//$lime_output->info($call);
		$out	= getCallReturn($call);
		if (preg_match('/OK \((\d+) tests\, (\d+) assertions\)/', $out, $match)) {
			$lime_output->green_bar($testName);
		}
		else {
			$lime_output->red_bar($testName);
		}
		echo $out;
		if (strlen($coverage) > 0) {
			if (!doesContainErrorNotification($out)) {
				$lime_output->info("coverage results in $coveragePath");
			}
		}
		return;
	}
	// run all loaded tests
	if(count($tests) < 1) {
		$lime_output->comment('no test found in '. $path);
		return;
	}
	$testsCountAtomic	= 0;
	foreach($tests as $name => $path) {
		if ($coveragePath) {
			$coveragePathConcrete	=	$coveragePath .'/'. $name;
			LBoxUtil::createDirByPath($coveragePathConcrete);
			$coverage 		= 	'--coverage-html '. $coveragePathConcrete . ' ';
			//$coverage			.=	' --coverage-clover '. $coveragePathConcrete . '/clover.xml ';
		}
		$call	= "phpunit $coverage $path";
		//$lime_output->info($call);
		$out				= getCallReturn($call);
		$space			= '';
		if (preg_match('/OK \((\d+) tests\, (\d+) assertions\)/', $out, $match)) {
			$testsCountAtomic	+= $match[1];
			$outPartName	= $name . ' ('.$match[1].' tests)';
			for($i=0;$i<(68-strlen($outPartName));$i++){$space.=' ';}
			$lime_output->green_bar($outPartName . $space .'OK ');
		}
		else {
			for($i=0;$i<(64-strlen($name));$i++){$space.=' ';}
			$lime_output->red_bar($name . $space .'FAILED ');
			echo $out;
		}
	}
	$lime_output->echoln("$testsCountAtomic tests done", array('fg' => 'green'));
	if (strlen($coverage) > 0) {
		if (!doesContainErrorNotification($out)) {
			$lime_output->info("coverage results in $coveragePath");
		}
	}
	$dir->close();
}

/**
 * retuns test class name by filepath
 * @param string $path
 * @return string
 */
function getTestNameByPath($path) {
	$content	= fread(fopen($path, 'r'), filesize($path));
	if (!preg_match('/class (\w+)/', $content, $match)) {
		throw new Exception('Cannot find test class in '.$path, 1);
	}
	return $match[1];
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

/**
 * check if script exe argument can be test name
 * @param string $param
 * @return bool
 */
function can_param_be_test($param) {
	return !preg_match('/(\-){2}/', $param);
}

/**
 * call system terminal command and returns its returned value
 * @param string $call
 * @return string
 */
function getCallReturn($call) {
	ob_start();
	passthru($call);
	$out	= ob_get_clean();
	return $out;	
}

/**
 * retuns true if given content looks like error message or does contain it
 * @param string $content
 * @return bool
 */
function doesContainErrorNotification($content) {
	return (bool)preg_match('/error/', $content);
}
