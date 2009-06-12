<?php
DEFINE("XT_GROUP", 1);
require("../../../../lBox/lib/loader.php");
session_start();

// check xt session
if (!LBoxXTProject::isLoggedAdmin(XT_GROUP)) {
	header("HTTP/1.1 404 Not Found");die;
}

// firePHP debug
//LBoxFirePHP::table($_POST, 'POST data debug');

try {
	//////////////////////////////////////////////////////////////////////
	//	saving data
	//////////////////////////////////////////////////////////////////////

	if (count($_POST) > 1) {
		throw new LBoxException("API awaits array with only one node!");
	}

	foreach ($_POST as $k => $postData) {
		switch($k) {
			case "style":
					$styleString	= "";
					foreach ($postData as $propName => $propValue) {
						$styleString .= "";
					}
					$returned	= saveMetanodeStylePropertiesByPostData($postData);
				break;
			default:
					$returned	= saveMetanodeContentByPostData($postData);
				break;
		}
		echo(json_encode($returned));
	}
}
catch (Exception $e) {
		throwExceptionToFirePHP($e);
		$ret 						= new stdclass(); // PHP base class
		$ret->Exception				= new stdclass();
		$ret->Exception->code	 	= $e->getCode();
		$ret->Exception->message 	= $e->getMessage();
		die(json_encode($ret));
}

function throwExceptionToFirePHP(Exception $e) {
	LBoxFirePHP::throwException($e);
}

/**
 * ulozi obsah metanode podle predanych dat a vrati zpet jeji vysledny content
 * @param $data
 * @return stdclass
 */
function saveMetanodeContentByPostData($data = array()) {
	try {
		$contentRaw			= $data["content"];
		$contentProcessed	= $contentRaw;

		$node	= getMetanodeByPostData($data);
		$node->setContent($contentProcessed);
		$node->store();
		$contentProcessed	= $node->getContent();
	
	
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

/**
 * ulozi style metanodu podle predanych dat a vrati zpet jeji vysledny content
 * @param $data
 * @return stdclass
 */
function saveMetanodeStylePropertiesByPostData($data = array()) {
	try {
		$contentRaw			= $data["content"];
		// parsing style properties from css string into clean array for metanode setter
		$contentRawParts		= explode(";", $contentRaw);
		$contentProcessedParts	= array();
		foreach ($contentRawParts as $k => $contentRawPart) {
			if (strlen(trim($contentRawPart)) < 1) continue;
			$contentProcessedParts[reset(explode(":", $contentRawPart))]	= end(explode(":", $contentRawPart));
		}

//LBoxFirePHP::table($contentProcessedParts, 'im about to setting metanode styles data:');

		$node	= getMetanodeByPostData($data);
		$node->setStyles($contentProcessedParts);
		$node->store();

		$contentProcessed	= $node->getStyles();

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

/**
 * getter na motanodes podle predanych dat
 * @param array $data
 * @return LBoxMetanode
 */
function getMetanodeByPostData($data = array()) {
	try {
		if (count($data) < 1) {
			throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
		}

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
		return $node;
	}
	catch(Exception $e) {
		throw $e;
	}
}
?>