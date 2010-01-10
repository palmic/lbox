<?php
/**
 * combines and save JS files
* @package LBox
* @version 1.0
* @date 2010-01-10
*/
class LBoxFilesCombineCSS extends LBoxFilesCombine
{
	protected $destination			= "<project>/css/combined";
	protected static $extOut		= "css";

	protected static $instance;
	
	public function compress($input) {
		try {
			return LBoxMinify_CSS_Compressor::process($input);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return LBoxFilesCombineCSS
	 * @throws Exception
	 */
	public static function getInstance() {
		try {
			if (!self::$instance instanceof LBoxFilesCombineCSS) {
				self::$instance = new LBoxFilesCombineCSS;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>