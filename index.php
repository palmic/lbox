<?php
require("lBox/lib/loader.php");

session_start();
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	ob_start();
}
else ob_start();

//DbControl::$debug = true;
//LBoxCache::$debug = true;

//LBoxLoaderConfig::getInstance()->debug = true;

DEFINE("LBOX_PATH_TEMPLATES_PAGES",			LBoxConfigSystem::getInstance()->getParamByPath("pages/templates/path"));
DEFINE("LBOX_PATH_TEMPLATES_COMPONENTS",	LBoxConfigSystem::getInstance()->getParamByPath("components/templates/path"));
DEFINE("LBOX_PATH_TEMPLATES_LAYOUTS",		LBoxConfigSystem::getInstance()->getParamByPath("layouts/templates/path"));
DEFINE("LBOX_PATH_FILES_I18N",				LBoxConfigSystem::getInstance()->getParamByPath("i18n/globalfiles/path"));

try {
	LBoxFront::run();
}
catch (Exception $e) {
	switch (LBoxConfigSystem::getInstance()->getParamByPath("debug/exceptions")) {
		case 1:
			echo getExceptionNotice($e);
			break;
		case -1:
			if (LBOX_REQUEST_IP == "127.0.0.1") {
				echo getExceptionNotice($e);
			}
			else {
				echo $e->getMessage();
			}
			break;
		case 0:
			echo $e->getMessage();
			break;
	}
}

function getExceptionNotice(Exception $e) {
	$out  = "";
    $out .= "<hr />";
    $out .= "Exception code:  <font style='color:blue'>". $e->getCode() ."</font>";
    $out .= "<br />";
    $out .= "Exception message: <font style='color:blue'>". nl2br($e->getMessage()) ."</font>";
    $out .= "<br />";
    $out .= "Thrown by: '". $e->getFile() ."'";
    $out .= "<br />";
    $out .= "on line: '". $e->getLine() ."'.";
    $out .= "<br />";
    $out .= "<br />";
    $out .= "Stack trace:";
    $out .= "<br />";
    $out .= nl2br($e->getTraceAsString());
    $out .= "<hr />";
    
    return $out;
}

//die("zobrazuju aspon neco in: ". __FILE__ ." at line:  ". __LINE__);
ob_end_flush();
?>