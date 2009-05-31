<?php
require("lBox/lib/loader.php");

ob_start();

$acces = AccesRecord::getInstance();
$acces->store();

$files		= new FilesRecords(array("id" => LBOX_REQUEST_URL_PARAMS));
if ($files->count() < 1) {
	header("HTTP/1.1 404 Not Found");
	die;
}
$file	= $files->current();

// zapocist stazeni
if (file_exists($file->getFilePath())) {
	$downloaded = new FilesDownloadedRecord();
	$downloaded->ref_file	= $file->id;
	$downloaded->countIn();

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Content-type: Content-type");
	header("Content-Disposition: attachment; filename=". str_replace (" ", "_", $file->getFileName()));
	header("Content-Transfer-Encoding: binary");
	$readed = @readfile($file->getFilePath());
}

ob_end_flush();
?>