<?
/**
 * Structure config manager
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigManagerStructure extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerStructure
	 */
	protected static $instance;
	
	protected $classNameConfig 				= "LBoxConfigStructure";

	/**
	 * @return LBoxConfigManagerStructure
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
	 * getter configu konkretni stranky podle url
	 * @param string $url
	 * @param string $outputFilterClass
	 * @return LBoxConfigItemStructure
	 * @throws LBoxException
	 */
	public function getPageByUrl($url = "", $outputFilterClass = "") {
		try {
			$instance	= self::getInstance()->getConfigInstance()->getNodeByUrl($url);
			if (strlen($outputFilterClass) > 0) {
				$of			= new $outputFilterClass($instance);
				if (!$of instanceof LBoxOutputFilter) {
					throw new LBoxExceptionConfig("Non OutputFilter compatible OutputFilter given!", LBoxExceptionConfig::CODE_BAD_PARAM);
				}
				$instance	->setOutputFilter($of);
			}
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter configu konkretni stranky podle id (vyuziva nacachovane pole stranek misto iterace nodu)
	 * @param int $id
	 * @param string $outputFilterClass
	 * @return LBoxConfigItemStructure
	 * @throws LBoxException
	 */
	public function getPageById($id = "", $outputFilterClass = "") {
		try {
			$instance	= self::getInstance()->getConfigInstance()->getNodeById($id);
			if (strlen($outputFilterClass) > 0) {
				$of			= new $outputFilterClass($instance);
				if (!$of instanceof LBoxOutputFilter) {
					throw new LBoxExceptionConfig("Non OutputFilter compatible OutputFilter given!", LBoxExceptionConfig::CODE_BAD_PARAM);
				}
				$instance	->setOutputFilter($of);
			}
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter configu home stranky
	 * @return LBoxConfigItemStructure
	 * @throws LBoxException
	 */
	public function getHomePage() {
		try {
			return self::getInstance()->getPageByUrl("/");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>