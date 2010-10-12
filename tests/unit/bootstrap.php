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
