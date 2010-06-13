<?php
DEFINE("XT_GROUP", 1);
require("../../../../lBox/lib/loader.php");
session_start();

LBoxCacheManagerFront::getInstance()->switchListeningOff();

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
		$flagEdit		= strlen($postFormData[$idColname]) > 0;
		$record			= strlen($postFormData[$idColname]) < 1 ? new $typeRecord : new $typeRecord($postFormData[$idColname]);
		$form	= LBoxMetaRecordsManager::getMetaRecord($record)->getForm();
		$form->setDoNotReload(true);
		$form->__toString($forceThrow = true);
		
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
		$ret->Insert	= (int)(strlen($postFormData[$idColname]) < 1);
		$ret->Results->status = 'OK';
		$controls		= $form->getControls();
		foreach ($controls as $control) {
			$ctrlName	= $control->getName();
			/*if (array_key_exists("action_reload_on_complete", $controls) && $controls["action_reload_on_complete"]->getValue()) {
				$ret->Data->$ctrlName	= "";
			}
			else {
				switch (true) {
					case ($control instanceof LBoxFormControlFile): $ret->Data->$ctrlName = ""; break;
					default:
						$ret->Data->$ctrlName	= strlen($form->recordProcessed->$ctrlName) > 0 ? $form->recordProcessed->$ctrlName : $control->getValue();
				}
			}*/
		}
		if (array_key_exists("action_reload_on_complete", $controls) && $controls["action_reload_on_complete"]->getValue()) {
			$ret->Data->action_reload_on_complete		= $controls["action_reload_on_complete"]->getValue();
			$ret->Data->type							= $typeRecord;
			$ret->Data->$idColname						= $form->recordProcessed->$idColname;
		}
		else {
			$ret->Data->type							= $typeRecord;
			$ret->Data->$idColname						= $form->recordProcessed->$idColname;
		}
		// node data URL
		$dataURLParams["type"]	= $typeRecord;
		$dataURLParams["id"]	= $form->recordProcessed->$idColname;
		foreach ($dataURLParams as $k => $v) {
			if (preg_match("/content/", $k)) continue;
			$paramsString	.= strlen($paramsString) > 0 ? "&" : "";
			$paramsString	.= "$k=$v";
		}
		$ret->Data->data_url						= LBOX_REQUEST_URL_VIRTUAL ."?$paramsString";
		header("HTTP/1.1 200 OK");
		die(json_encode($ret));
	}
	if (!$_POST && count($_GET) > 0) {
		// get the node data
		$typeRecord		= $_GET["type"];
		$idColname		= eval("return $typeRecord::\$idColName;");
		$record			= new $typeRecord($_GET["id"]);
		$out			= array();
		foreach ($record as $param => $value) {
			$out[$param]	= $value;
		}
		header("HTTP/1.1 200 OK");
		die(json_encode($out));
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
?>