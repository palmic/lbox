<?php
/**
 * Structure config manager
* @author Michal Palma <michal.palma@gmail.com>
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
	 * destroys singleton instance from cache
	 */
	public static function resetInstance() {
		try {
			self::$instance = NULL;
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
				throw new LBoxExceptionProperty(LBoxExceptionProperty::MSG_PROPERTY_LANGDOMAIN_NOT_FOUND ." Tryed to find domain = '$domain'", LBoxExceptionProperty::CODE_PROPERTY_LANGDOMAIN_NOT_FOUND);
			}
			// find prior lng
			if (count($lngs = explode(",", $node->lang)) > 0) {
				foreach ($this->getClientLanguages() as $clientLanguage) {
					foreach ($lngs as $lng) {
						if (strtolower(trim($lng)) == strtolower(trim($clientLanguage))) {
//LBoxFirePHP::log("Language by client browser choosed: ". strtolower(trim($lng)));
							return strtolower(trim($lng));
						}
					}
				}
				// prior lng not found - choose first as default
				return strtolower(trim(reset($lngs)));
			}
			else {
				return trim($node->lang);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci accepted jazyku prohlizece podle jeho nastaveni
	 * @return array
	 */
	protected function getClientLanguages() {
		try {
			$out	= array();
			foreach (explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $lngAccepted) {
				$lng			= trim(reset(explode(";", $lngAccepted)));
				$out[$lng]		= $lng;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na vsechny langdomains
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