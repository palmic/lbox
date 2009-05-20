<?php
require("lBox/lib/loader.php");

ob_start();

$acces = AccesRecord::getInstance();
$acces->store();

$files		= new FilesRecords(array("id" => LBOX_REQUEST_URL_PARAMS));
if (!($file = $files->current()) instanceof FilesRecord) {
	header("HTTP/1.1 404 Not Found");
	die;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Content-type: Content-type");
header("Content-Disposition: attachment; filename=". str_replace (" ", "_", $file->getFileName()));
header("Content-Transfer-Encoding: binary");

// zapocist stazeni
if ($readed = @readfile($file->getFilePath())) {
	$downloaded = new FilesDownloadedRecord();
	$downloaded->ref_file	= $file->id;
	$downloaded->countIn();
}

ob_end_flush();
?>