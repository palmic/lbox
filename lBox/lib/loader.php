<?php
// PHP 5.3 relevant
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set(@date_default_timezone_get());
}

// terminal
define("CLI", false);
// IIS / Apache
define("IIS", (array_key_exists("INSTANCE_ID", $_SERVER)));
// WIN / Unix
if (IIS) 	{ define("WIN", true); }
else 		{ define("WIN", (array_key_exists("WINDIR", $_SERVER))); }
define("SLASH", (WIN) ? '\\' : '/');
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
	GLOBAL $paths, $pathsIgnore;
	try {
		$debug = false;

		LBoxLoader::getInstance($paths, $pathsIgnore)->debug = $debug;
		LBoxLoader::getInstance()->load($className);
	}
	catch (Exception $e) {
		echo "<hr />";
		echo "Exception code:  <font style='color:blue'>". $e->getCode() ."</font>";
		echo "<br />";
		echo "Exception message: <font style='color:blue'>". nl2br($e->getMessage()) ."</font>";
		echo "<br />";
		echo "Thrown by: '". $e->getFile() ."'";
		echo "<br />";
		echo "on line: '". $e->getLine() ."'.";
		echo "<br />";
		echo "<br />";
		echo "Stack trace:";
		echo "<br />";
		echo nl2br($e->getTraceAsString());
		echo "<hr />";
	}
}

// define URL parts
$requestURI	= IIS ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER['REQUEST_URI'];
$scheme 	= array_key_exists('HTTPS', $_SERVER) ? 'https' : 'http';
$url = $scheme.'://'.$_SERVER['HTTP_HOST'].$requestURI;
$urlArray = parse_url($url);
// oddelime casti za :
$urlArrayParts = explode(":", $urlArray["path"]);

  // emulace GET
  $urlArray["query"] = array_key_exists("query", $urlArray) ? $urlArray["query"] : "";
  $urlGetQueryIn  = explode("&", str_replace("&amp;", "&", urldecode($urlArray["query"])));
  foreach ($urlGetQueryIn as $urlGetVarsLevel1) {
  	if (is_numeric(strpos($urlGetVarsLevel1, "["))) {
	    $getVarName   = reset(explode("[", $urlGetVarsLevel1));
    	$getVarKey    = substr(next(explode("[", $urlGetVarsLevel1)), 0, stripos(next(explode("[", $urlGetVarsLevel1)), "]"));
     	$getVarValue  = next(explode("=", $urlGetVarsLevel1));
      	$_GET[$getVarName][$getVarKey]  = $getVarValue;
    }
    else {
    	$getVarName   = reset(explode("=", $urlGetVarsLevel1));
     	$getVarValue  = next(explode("=", $urlGetVarsLevel1));
       	$_GET[$getVarName]              = $getVarValue;
    }
  }
  unset($_GET["{QUERY_STRING}"]);

try {
	DEFINE("LBOX_REQUEST_URL", 				$url);
	DEFINE("LBOX_REQUEST_URL_VIRTUAL", 		LBoxUtil::fixURLSlash($urlArrayParts[0]));
	DEFINE("LBOX_REQUEST_URL_PARAMS", 		array_key_exists(1, $urlArrayParts) ? $urlArrayParts[1] : "");
	DEFINE("LBOX_REQUEST_URL_QUERY", 		urldecode($urlArray["query"]));
	DEFINE("LBOX_REQUEST_URL_PATH", 		strlen(LBOX_REQUEST_URL_PARAMS) > 0 ? LBOX_REQUEST_URL_VIRTUAL .":". LBOX_REQUEST_URL_PARAMS : LBOX_REQUEST_URL_VIRTUAL); // virtual:params
	DEFINE("LBOX_REQUEST_URL_SCHEME", 		$scheme);
	DEFINE("LBOX_REQUEST_URL_HOST", 		array_key_exists("HTTP_HOST", $_SERVER) ? $_SERVER['HTTP_HOST'] : "");

	DEFINE("LBOX_REQUEST_IP", 				array_key_exists("HTTP_HOST", $_SERVER) ? 	$_SERVER["REMOTE_ADDR"] : "");
	DEFINE("LBOX_REQUEST_IP_MY",			array_key_exists("SERVER_ADDR", $_SERVER) ? 	$_SERVER["SERVER_ADDR"] : "");
	DEFINE("LBOX_REQUEST_AGENT",			array_key_exists("HTTP_USER_AGENT", $_SERVER) ? $_SERVER["HTTP_USER_AGENT"] : "");
	DEFINE("LBOX_REQUEST_REFERER",			array_key_exists("HTTP_REFERER", $_SERVER) ? $_SERVER["HTTP_REFERER"] : "");
	DEFINE("LBOX_REQUEST_REQUEST_TIME",		array_key_exists("REQUEST_TIME", $_SERVER) ? $_SERVER["REQUEST_TIME"] : "");

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
	echo "<hr />";
	echo "Exception code:  <font style='color:blue'>". $e->getCode() ."</font>";
	echo "<br />";
	echo "Exception message: <font style='color:blue'>". nl2br($e->getMessage()) ."</font>";
	echo "<br />";
	echo "Thrown by: '". $e->getFile() ."'";
	echo "<br />";
	echo "on line: '". $e->getLine() ."'.";
	echo "<br />";
	echo "<br />";
	echo "Stack trace:";
	echo "<br />";
	echo nl2br($e->getTraceAsString());
	echo "<hr />";

	exit;
}

?>