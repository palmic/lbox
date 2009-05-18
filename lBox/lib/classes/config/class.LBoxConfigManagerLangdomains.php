<?
/**
 * Structure config manager
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2009-05-18
*/
class LBoxConfigManagerLangdomains extends LBoxConfigManager
{
	/**
	 * @var LBoxConfigManagerProperties
	 */
	protected static $instance;
	
	protected $classNameConfig 		= "LBoxConfigLangdomains";
	
	/**
	 * cache var
	 * @var array
	 */
	protected $langsDomains	=	array();

	/**
	 * @return LBoxConfigManagerLangdomains
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
	 * getter jazyka podle domeny
	 * @return string
	 * @throws LBoxException
	 */
	public function getLangByDomain($domain = "") {
		try{
			if (strlen($domain) < 1) {
				throw new LBoxExceptionProperty(LBoxExceptionProperty::MSG_PARAM_STRING_NOTNULL, LBoxExceptionProperty::CODE_BAD_PARAM);
			}
			if (!$node = $this->findNodeByContent(trim($domain), $this->getConfigInstance()->getRootIterator(), true)) {
				throw new LBoxExceptionProperty(LBoxExceptionProperty::MSG_PROPERTY_LANGDOMAIN_NOT_FOUND ." Tryed to find name = '$name'", LBoxExceptionProperty::CODE_PROPERTY_LANGDOMAIN_NOT_FOUND);
			}
			return $node->lang;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter jazyka podle domeny
	 * @return string
	 * @throws LBoxException
	 */
	public function getLangsDomains() {
		try{
			if (count($this->langsDomains) > 0) {
				return $this->langsDomains;
			}
			foreach ($this->getConfigInstance()->getRootIterator() as $node) {
				$this->langsDomains[trim($node->lang)] = trim($node->getContent());
			}
			return $this->langsDomains;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>