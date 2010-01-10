<?php
/**
 * combines and save JS files
* @package LBox
* @version 1.0
* @date 2010-01-10
*/
class LBoxFilesCombineJS extends LBoxFilesCombine
{
	protected $destination			= "<project>/js/combined";
	protected static $extOut		= "js";

	protected static $instance;
	
	public function compress($input) {
		try {
			/* odstraneno kvuli x problemum s timto kompresorem
			return LBoxJSMinPlus::minify($input);*/
			return LBoxJSMin::minify($input);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return LBoxFilesCombineJS
	 * @throws Exception
	 */
	public static function getInstance() {
		try {
			if (!self::$instance instanceof LBoxFilesCombineJS) {
				self::$instance = new LBoxFilesCombineJS;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>