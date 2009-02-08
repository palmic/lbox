<?php

//DEFINE("PATH_MAKE_FONT", '/project/classes/utils/PDML/0.9alpha/src/font/makefont/');
DEFINE("PATH_MAKE_FONT", '');
include PATH_MAKE_FONT .'makefont.php';

if (strlen($font = $_GET["font"]) < 1) {
	die("please specify font name into get - for instance ?font=arial");
}
if (strlen($enc = $_GET["enc"]) < 1) {
	$enc	= "cp1250";
}

MakeFont(PATH_MAKE_FONT ."ttf/$font.ttf", "ttf/$font.afm", $enc);

?>