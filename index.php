<?php
require("lBox/lib/loader.php");

session_start();
ob_start();

//DbControl::$debug = true;

//LBoxLoaderConfig::getInstance()->debug = true;

DEFINE("LBOX_PATH_TEMPLATES_PAGES",			LBoxConfigSystem::getInstance()->getParamByPath("pages/templates/path"));
DEFINE("LBOX_PATH_TEMPLATES_COMPONENTS",	LBoxConfigSystem::getInstance()->getParamByPath("components/templates/path"));
DEFINE("LBOX_PATH_TEMPLATES_LAYOUTS",		LBoxConfigSystem::getInstance()->getParamByPath("layouts/templates/path"));
DEFINE("LBOX_PATH_FILES_I18N",				LBoxConfigSystem::getInstance()->getParamByPath("i18n/globalfiles/path"));

try {
	LBoxFront::run();
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

//die("zobrazuju aspon neco in: ". __FILE__ ." at line:  ". __LINE__);
ob_end_flush();
?>