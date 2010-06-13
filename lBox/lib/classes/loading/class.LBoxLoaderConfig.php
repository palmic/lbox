<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxLoaderConfig extends LBoxLoader
{
	/**
	 * @var Config
	 */
	protected static $instance;
	
	/**
	 * @var string
	 */
	protected $configFileName;

	/**
	 * implicit file prefixes
	 * @var array
	 */
	protected $prefixes = array("");

	/**
	 * file postfix
	 * @var string
	 */
	protected $postfix = "xml";
	

	/**
	 * vraci absolutni cestu k nalezenemu config souboru podle vzoru $type.$this->postfix 
	 */
	public function getPathOf($type = "") {
		try {
			// prohledat zadane cesty
			foreach ($this->paths as $path) {
				if (strlen($found = $this->getPathOfType($type, $path, NULL, "<type>(\.\w{2})?\.".$this->postfix)) > 0) {
					break;
				}
			}
			return $found;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * neaktivni
	 */
	public function load() {}

	/**
	 * getter singleton instance
	 * @param string 	$type type to load
	 * @param array 	$paths paths where to search
	 * @param array 	$prefixes explicit file prefixes
	 * @return LBoxLoaderConfig
	 * @throws LBoxExceptionLoader
	 */
	public static function getInstance($paths = array(), $pathsIgnore = array(), $prefixes = array()) {
		$className 	= __CLASS__;
		try {
			if (!isset(self::$instance)) {
				self::$instance = new $className($paths, $pathsIgnore, $prefixes);
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>