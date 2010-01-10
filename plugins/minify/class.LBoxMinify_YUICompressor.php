<?php
require_once '2.1.3/min/lib/Minify/YUICompressor.php';

/**
 * only if server is capable of executing JAR files!!!
 */
class LBoxMinify_YUICompressor extends Minify_YUICompressor
{
}

/* set jar file path */
Minify_YUICompressor::$jarFile	= "";
Minify_YUICompressor::$tempDir	= LBOX_PATH_CACHE ."/minify";

?>