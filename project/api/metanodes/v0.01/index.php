<?php
DEFINE("XT_GROUP", 1);
require("../../../../lBox/lib/loader.php");
session_start();

// check xt session
if (!LBoxXTProject::isLoggedAdmin(XT_GROUP)) {
	header("HTTP/1.1 404 Not Found");die;
}

// firePHP debug
/*$table[]   = array("post varname", "value");
foreach ($_POST as $k => $v) {
	$table[] = array($k,$v);
}
FirePHP::getInstance(true)->table('POST data debug', $table);*/


try {
	//////////////////////////////////////////////////////////////////////
	//	saving data
	//////////////////////////////////////////////////////////////////////

	if (count($_POST) > 1) {
		throw new LBoxException("API awaits array with only one node!");
	}

	foreach ($_POST as $k => $postData) {
		$returned	= saveMetanodeByPostData($postData);
		echo(json_encode($returned));
	}
}
catch (Exception $e) {
	/*switch (LBoxConfigSystem::getInstance()->getParamByPath("debug/exceptions")) {
		case 1:
			echo getExceptionNotice($e);
			break;
		case -1:
			if (LBOX_REQUEST_IP == "127.0.0.1") {
				throwExceptionToFirePHP($e);
			}
			else {
				throwExceptionToFirePHP($e);
			}
			break;
		case 0:
			echo $e->getMessage();
			break;
	}*/
	throwExceptionToFirePHP($e);	
}

function throwExceptionToFirePHP(Exception $e) {
	FirePHP::getInstance(true)->error('Exception of code:  '. $e->getCode() .' with message: '. $e->getMessage() . ' thrown');
	FirePHP::getInstance(true)->warn('Thrown by: :  '. $e->getFile());
	FirePHP::getInstance(true)->warn('At line: :  '. $e->getLine());
	$i = 1;
	foreach ($e->getTrace() as $traceStep) {
		$traceLine	= array();
		foreach ($traceStep as $attName => $attValue) {
			$traceParams[]	= $attName;
			$traceLine[]	= $attValue;
		}
		if ($i < 2) { $trace[0]	= $traceParams; }
		$trace[$i]	= $traceLine;
		$i++;
	}
	FirePHP::getInstance(true)->table('Stack trace', $trace);
}

/**
 * ulozi metanode podle predanych dat a vrati zpet jeji vysledny content
 * @param $data
 * @return stdclass
 */
function saveMetanodeByPostData($data = array()) {
	try {
		if (count($data) < 1) {
			throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
		}

		$contentRaw			= $data["content"];
		$contentProcessed	= $contentRaw;
		
		// page metanode
		if ($data["caller_type"] == "page") {
			$callerConfig		= LBoxConfigManagerStructure::getInstance()->getPageById($data["caller_id"]);
			$callerClassName	= $callerConfig->class;
			$caller				= new $callerClassName($callerConfig);
		}
		// component metanode
		else {
			$callerConfig	= LBoxConfigManagerComponents::getInstance()->getComponentById($data["caller_id"]);
			$caller			= new LBoxComponent($callerConfig, LBoxFront::getPage());
		}
		$node	= LBoxMetanodeManager::getNode(		$data["type"],
													(int)$data["seq"],
													$caller,
													$data["lng"]);
		$node->setContent($contentRaw);
	
	
		//////////////////////////////////////////////////////////////////////
		//	return filtered data
		//////////////////////////////////////////////////////////////////////
		
		$ret = new stdclass(); // PHP base class
		$ret->Results = new stdclass();
		$ret->Results->content_raw = $contentRaw; // raw_data
		$ret->Results->caller_type = $data["caller_type"];
		$ret->Results->caller_id = $data["caller_id"];
		$ret->Results->type = $data["type"];
		$ret->Results->seq = $data["seq"];
		$ret->Results->lng = $data["lng"];
		$ret->Results->status = 'OK';
		$ret->Results->content = $contentProcessed; // content
		
		return $ret;
	}
	catch(Exception $e) {
		throw $e;
	}
}

?>