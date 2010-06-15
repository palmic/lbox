<?php
/**
 * upraveno pro cteni datovych XML Airtoy
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-08-15
*/
class LBoxI18NDataManager extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerComponents
	 */
	protected static $instances	= array();
	
	protected $classNameConfig 		= "LBoxI18NData";
	
	/**
	 * cesta k souboru
	 * @var string
	 */
	protected $filePath;

	/**
	 * getter configu konkretniho lang textu podle id (vyuziva nacachovane pole stranek misto iterace nodu)
	 * @return LBoxI18NDataItem
	 * @throws LBoxException
	 */
	public function getTextById($id = "") {
		try {
			return $this->getConfigInstance()->getNodeById($id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @param string $filePath
	 * @return LBoxI18NDataManager
	 * @throws Exception
	 */
	public static function getInstance($filePath	= "") {
		$className 	= __CLASS__;
		try {
			if (strlen($filePath) < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_STRING_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			if (!	array_key_exists($filePath, self::$instances)
				||	!(self::$instances[$filePath] instanceof $className)) {
						self::$instances[$filePath] = new $className($filePath);
			}
			return self::$instances[$filePath];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @param string $filePath
	 */
	protected function __construct($filePath	= "") {
		$this->filePath	= $filePath;
	}
	
	public function getConfigInstance() {
		try {
			$instance				= parent::getConfigInstance();
			$instance->filePath		= $this->filePath;
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>