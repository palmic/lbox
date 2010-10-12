<?php
define("WIN", false);
define("SLASH", '/');
define("LBOX_DIRNAME_PROJECT", "project");
define("LBOX_DIRNAME_PLUGINS", "plugins");

$slash	= SLASH;

$dirArr 			= explode($slash, dirname(__FILE__));
unset($dirArr[count($dirArr)-1]);
$lBoxDirName		= end($dirArr);
$projectRootArr		= $dirArr;
unset($projectRootArr[count($projectRootArr)-1]);
$projectRootPath	= implode($slash, $projectRootArr);
$paths 		= array(
$projectRootPath . $slash . $lBoxDirName,
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT,
$projectRootPath . $slash . LBOX_DIRNAME_PLUGINS,
);
$pathsIgnore = array(
$projectRootPath . $slash ."$lBoxDirName". $slash ."TAL",
$projectRootPath . $slash ."$lBoxDirName". $slash ."dev",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."dev",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."css",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."js",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."documents",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."img",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."filespace",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash .".cache",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash .".tal_compiled",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."wsw",
);
$pathsConfig = array(
$projectRootPath . $slash . "$lBoxDirName". $slash ."config",
$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."config",
);

define("LBOX_PATH_INSTANCE_ROOT", 		$projectRootPath);
define("LBOX_PATH_CORE", 				LBOX_PATH_INSTANCE_ROOT 	 . $slash . "lBox");
define("LBOX_PATH_CORE_CLASSES", 		LBOX_PATH_CORE 				. $slash ."lib". $slash ."classes");
define("LBOX_PATH_PROJECT", 			LBOX_PATH_INSTANCE_ROOT 	. $slash . LBOX_DIRNAME_PROJECT);
define("LBOX_PATH_CACHE", 				LBOX_PATH_INSTANCE_ROOT		. $slash . LBOX_DIRNAME_PROJECT. $slash . ".cache");
define("LBOX_PATH_PLUGINS", 			LBOX_PATH_INSTANCE_ROOT		. $slash . LBOX_DIRNAME_PLUGINS);

// explicitni load
require(LBOX_PATH_CORE_CLASSES 	. $slash ."loading". $slash ."class.LBoxLoader.php");

function __autoload ($className)
{
	try {
		
		$debug = false;

		$slash				= SLASH;
		$dirArr 			= explode($slash, dirname(__FILE__));
		unset($dirArr[count($dirArr)-1]);
		$lBoxDirName		= end($dirArr);
		$projectRootArr		= $dirArr;
		unset($projectRootArr[count($projectRootArr)-1]);
		$projectRootPath	= implode($slash, $projectRootArr);
		$paths 		= array(
		$projectRootPath . $slash . $lBoxDirName,
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT,
		$projectRootPath . $slash . LBOX_DIRNAME_PLUGINS,
		);
		$pathsIgnore = array(
		$projectRootPath . $slash ."$lBoxDirName". $slash ."TAL",
		$projectRootPath . $slash ."$lBoxDirName". $slash ."dev",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."dev",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."css",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."js",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."documents",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."img",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."filespace",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash .".cache",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash .".tal_compiled",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."wsw",
		);
		$pathsConfig = array(
		$projectRootPath . $slash . "$lBoxDirName". $slash ."config",
		$projectRootPath . $slash . LBOX_DIRNAME_PROJECT . $slash ."config",
		);

		/*$lime_output	= new lime_output();
		$lime_output->info('looking for '. $className);*/
		
		LBoxLoader::getInstance($paths, $pathsIgnore)->debug = ($debug ? 'terminal' : false);
		LBoxLoader::getInstance()->load($className);
	}
	catch (Exception $e) {
		$lime	= new lime_output();
		$lime->error($e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
	}
}
spl_autoload_register('__autoload');

// define URL parts
$requestURI	= IIS ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER['REQUEST_URI'];
$scheme 	= array_key_exists('HTTPS', $_SERVER) ? 'https' : 'http';
$url = $scheme.'://'.$_SERVER['HTTP_HOST'].$requestURI;

try {
/*	DEFINE("LBOX_REQUEST_URL", 				$url);
	DEFINE("LBOX_REQUEST_URL_VIRTUAL", 		LBoxUtil::fixURLSlash($urlArrayParts[0]));
	DEFINE("LBOX_REQUEST_URL_PARAMS", 		array_key_exists(1, $urlArrayParts) ? $urlArrayParts[1] : "");
	DEFINE("LBOX_REQUEST_URL_QUERY", 		urldecode($urlArray["query"]));
	DEFINE("LBOX_REQUEST_URL_PATH", 		strlen(LBOX_REQUEST_URL_PARAMS) > 0 ? LBOX_REQUEST_URL_VIRTUAL .":". LBOX_REQUEST_URL_PARAMS : LBOX_REQUEST_URL_VIRTUAL); // virtual:params
	DEFINE("LBOX_REQUEST_URL_SCHEME", 		$scheme);
	DEFINE("LBOX_REQUEST_URL_HOST", 		array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER['HTTP_HOST'] : "");*/

	DEFINE("LBOX_REQUEST_IP", 				array_key_exists("HTTP_HOST", $_SERVER) ? 	$_SERVER["REMOTE_ADDR"] : "0.0.0.0");
	DEFINE("LBOX_REQUEST_IP_MY",			array_key_exists("SERVER_ADDR", $_SERVER) ? 	$_SERVER["SERVER_ADDR"] : "127.0.0.1");
	DEFINE("LBOX_REQUEST_AGENT",			"terminal");
	DEFINE("LBOX_REQUEST_REFERER",			array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "");
	DEFINE("LBOX_REQUEST_REQUEST_TIME",		date('Y-m-d H:i:s'));

	// nacist config loader s nadefinovanymi cestami
	LBoxLoaderConfig::getInstance($pathsConfig);
	// nastavit DbControl config file cestu
	DbCfg::$cfgFilepath = LBoxLoaderConfig::getInstance()->getPathOf("db");

	// pokud nemame pearovsky firePHP pouzivame lokani LBOXovy
	@include("FirePHPCore/fb.php");
	if (!class_exists("FirePHP")) {
		require(LBOX_PATH_CORE 			. $slash ."firephp". $slash ."0.3.1". $slash ."lib" . $slash ."FirePHPCore" . $slash ."fb.php");
	}
	// disable firePHP on remote mashines (enabled on localhost only!!!)
	if (LBOX_REQUEST_IP != LBOX_REQUEST_IP_MY) {
		FirePHP::getInstance(true)->setEnabled(false);
		FB::setEnabled(false);
	}
	$firePHPOptions = array('maxObjectDepth' => 10,
                 			'maxArrayDepth' => 20,
               				'useNativeJsonEncode' => true,
                 			'includeLineNumbers' => true);
	FirePHP::getInstance(true)->getOptions();
	FirePHP::getInstance(true)->setOptions($firePHPOptions);
	FB::setOptions($firePHPOptions);
	// log exclude of:
	/*FirePHP::getInstance(true)->setObjectFilter('ClassName',
	                         					  array('MemberName'));*/
	// TAL load
	// pokud nemame pearovsky PHPTAL pouzivame lokani LBOXovy
	@include("PHPTAL.php");
	if (!@constant("PHPTAL_VERSION")) {
		define("LBOX_PATH_PHPTAL", LBOX_PATH_CORE . $slash ."TAL". $slash ."PHPTAL-1.2.1");
		define("LBOX_PATH_PHPTAL_GETTEXTTRANSLATOR", LBOX_PATH_PHPTAL . $slash ."PHPTAL");

		require(LBOX_PATH_PHPTAL . $slash ."PHPTAL.php");
	}
	else {
		define("LBOX_PATH_PHPTAL", "PHPTAL");
		define("LBOX_PATH_PHPTAL_GETTEXTTRANSLATOR", LBOX_PATH_PHPTAL);
	}
	// TAL translator service to extend the standard
	require (LBOX_PATH_PHPTAL_GETTEXTTRANSLATOR . $slash ."GetTextTranslator.php");
	require("lbox.phptal.php");

	//LBoxUtil::createDirByPath(PHPTAL_PHP_CODE_DESTINATION);

	// cache lite load
	define("LBOX_PATH_CHACHELITE", LBOX_PATH_CORE . $slash ."cachelite" . $slash ."Cache_Lite-1.7.8");
	require(LBOX_PATH_CHACHELITE . $slash . "Lite.php");
}
catch (Exception $e) {
	$lime	= new lime_output();
	$lime->error($e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
	
	exit;
}

?>