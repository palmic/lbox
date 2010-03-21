<?php
DEFINE("XT_GROUP", 1);
require("../../../../lBox/lib/loader.php");
session_start();

// check xt session
if ((!LBoxXTDBFree::isLogged(XT_GROUP)) && (!LBoxXTProject::isLoggedAdmin(XT_GROUP))) {
	header("HTTP/1.1 404 Not Found");die;
}

$post			= LBoxFront::getDataPost();
$postFormData	= current($post);

// firePHP debug
//LBoxFirePHP::table($post, 'POST data debug');

try {
	//////////////////////////////////////////////////////////////////////
	//	saving data
	//////////////////////////////////////////////////////////////////////


	foreach ($post as $formID => $data) {
		$typeRecord		= $data["type"];
		$idColname		= eval("return $typeRecord::\$idColName;");
		$record			= strlen($postFormData[$idColname]) < 1 ? new $typeRecord : new $typeRecord($postFormData[$idColname]);
		$form	= LBoxMetaRecordsManager::getMetaRecord($record)->getForm();
		$form->setDoNotReload(true);
		$form->__toString();
		
		$ret 						= new stdclass(); // PHP base class
		
		// check controls validations errors
		$exceptions	= array();
		foreach ($form->getControls() as $control) {
//LBoxFirePHP::log("Control: ". $control->getName());
			foreach ($control->getExceptionsValidations() as $e) {
				$exceptions[$control->getName()]["invalidations"][$e->getCode()]= $e->getMessage();
			}
		}
		if (count($exceptions) > 0) {
			$ret->type				= $typeRecord;
			$ret->id				= $data[$idColname];
			$ret->invalidControls	= $exceptions;
			$ret->status 			= "FAILED";
			header("HTTP/1.1 200 OK");
			die(json_encode($ret));
		}

		// sending data back
		$ret->Results 	= new stdclass();
		$ret->Data		= new stdclass();
		$ret->Results->status = 'OK';
		foreach ($form->getControls() as $control) {
			$ctrlName	= $control->getName();
			$ret->Data->$ctrlName	= $control->getValue();
		}
		header("HTTP/1.1 200 OK");
		die(json_encode($ret));
	}
}
catch (Exception $e) {
		LBoxFirePHP::throwException($e);
		$ret 						= new stdclass(); // PHP base class
		
		$ret->Exception				= new stdclass();
		$ret->Exception->code	 	= $e->getCode();
		$ret->Exception->message 	= $e->getMessage();
		header("HTTP/1.1 200 OK");
		die(json_encode($ret));
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
?>