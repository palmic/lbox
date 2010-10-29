<?php
DEFINE("XT_GROUP", 1);
require("../../../../../lBox/lib/loader.php");
session_start();

LBoxCacheManagerFront::getInstance()->switchListeningOff();

// check xt session
if ((!LBoxXTDBFree::isLogged(XT_GROUP)) && (!LBoxXTProject::isLoggedAdmin(XT_GROUP))) {
	header("HTTP/1.1 404 Not Found");die;
}

// firePHP debug
//LBoxFirePHP::log(LBoxConfigSystem::getInstance()->getParamByPath("metanodes/images/path"));
//LBoxFirePHP::table($_FILES['image'], "uploaded image data");

try {
	//////////////////////////////////////////////////////////////////////
	//	saving data
	//////////////////////////////////////////////////////////////////////

	if (strlen($tmpPath = $_FILES['image']['tmp_name']) > 0) {
		$imgName		= $_FILES["image"]["name"];
		$userRecord		= LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord() : LBoxXTDBFree::getUserXTRecord();
		$dirTarget		= LBoxUtil::fixPathSlashes(LBoxConfigSystem::getInstance()->getParamByPath("metanodes/images/path") . SLASH . $userRecord->nick . SLASH . date("Ym"));
		$imgNameTarget	= date("YmdHis") .".". LBoxUtil::getExtByFilename($imgName);
		$imageURL		= /*LBOX_REQUEST_URL_SCHEME ."://". LBOX_REQUEST_URL_HOST ."/". */str_replace('\\', '/', LBoxUtil::fixPathSlashes(str_replace(LBOX_PATH_PROJECT, "", "$dirTarget/$imgNameTarget")));
		LBoxUtil::createDirByPath($dirTarget);
		if (!move_uploaded_file($tmpPath, "$dirTarget". SLASH ."$imgNameTarget")) {
			throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_UPLOAD_ERROR, LBoxExceptionFilesystem::CODE_FILE_UPLOAD_ERROR);
		}
		$ret 						= new stdclass(); // PHP base class
		$ret->status				= "UPLOADED";
		$ret->image_url				= $imageURL;
		header("HTTP/1.1 200 OK");
		header("content-type: text/html");
		die(json_encode($ret));
	}
}
catch (Exception $e) {
		throwExceptionToFirePHP($e);
		$ret 						= new stdclass(); // PHP base class
		$ret->Exception				= new stdclass();
		$ret->Exception->code	 	= $e->getCode();
		$ret->Exception->message 	= $e->getMessage();
		$ret->Exception->trace	 	= $e->getTraceAsString();
		header("HTTP/1.1 200 OK");
		header("content-type: text/html");
		die(json_encode($ret));
}

function throwExceptionToFirePHP(Exception $e) {
	LBoxFirePHP::throwException($e);
}
?>