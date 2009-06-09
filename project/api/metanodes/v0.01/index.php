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
FirePHP::getInstance(true)->table('POST data debug', $table);
*/

try {
	//////////////////////////////////////////////////////////////////////
	//	saving data
	//////////////////////////////////////////////////////////////////////

	$contentRaw			= $_POST["content"];
	$contentProcessed	= $contentRaw;
	
	// page metanode
	if ($_POST["caller_type"] == "page") {
		$callerConfig		= LBoxConfigManagerStructure::getInstance()->getPageById($_POST["caller_id"]);
		$callerClassName	= $callerConfig->class;
		$caller				= new $callerClassName($callerConfig);
	}
	// component metanode
	else {
		$callerConfig	= LBoxConfigManagerComponents::getInstance()->getComponentById($_POST["caller_id"]);
		$caller			= new LBoxComponent($callerConfig, LBoxFront::getPage());
	}
	$node	= LBoxMetanodeManager::getNode(		$_POST["type"],
												(int)$_POST["seq"],
												$caller,
												$_POST["lng"]);
	$node->setContent($contentRaw);


	//////////////////////////////////////////////////////////////////////
	//	return filtered data
	//////////////////////////////////////////////////////////////////////
	
	$data = new stdclass(); // PHP base class
	$data->Results = new stdclass();
	$data->Results->content_raw = $contentRaw; // raw_data
	$data->Results->caller_type = $_POST["caller_type"];
	$data->Results->caller_id = $_POST["caller_id"];
	$data->Results->type = $_POST["type"];
	$data->Results->seq = $_POST["seq"];
	$data->Results->lng = $_POST["lng"];
	$data->Results->status = 'OK';
	$data->Results->content = $contentProcessed; // content
	
	echo(json_encode($data));
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

?>