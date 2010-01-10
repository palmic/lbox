<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigProperties extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "properties";
	protected $classNameIterator	= "LBoxIteratorProperties";

	/**
	 * @return LBoxConfigProperties
	 * @throws Exception
	 */
	public static function getInstance() {
		$className 	= __CLASS__;
		try {
			if (!self::$instance instanceof $className) {
				self::$instance = new $className;
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o kontrolu vzhledem k multilang
	 */
	protected function getDOM() {
		try {
			if ($this->dom instanceof DOMDocument) {
				return $this->dom;
			}
			$configNameBase	= $this->configName;
			try {
				$this->configName .= ".". LBoxFront::getDisplayLanguage();
				return parent::getDOM();
			}
			catch(Exception $e) {
				try {
					$this->configName	= $configNameBase;
					return parent::getDOM();
				}
				catch (Exception $e) {
					if ($e->getCode() == LBoxExceptionConfig::CODE_TYPE_NOT_FOUND) {
						die($this->configName .".xml not found due to langdomain did not recognized!");
					}
				}
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>