<?
/**
 * config manager protocol
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
abstract class LBoxConfigManager
{
	/**
	 * trida iteratoru config nodu - musi byt definovana v podtride!
	 * @var string
	 */
	protected $classNameConfig;

	/**
	 * cache config nodu indexovanych podle parametru
	 * @var array
	 */
	protected $nodesByParams	= array();
	
	/**
	 * cache var
	 * @var LBoxConfig
	 */
	protected $configInstance;
	
	protected function __construct() {
	}

	/**
	 * getter for config element by some identification parameter
	 * @param string $url
	 * @return LBoxConfigItem
	 * @throws LBoxExceptionConfig
	 */
	protected function getNodeByParam($paramName = "", $paramValue = "") {
		try {
			if ($this->nodesByParams[$paramName][$paramValue] instanceof LBoxConfigItem) {
				return $this->nodesByParams[$paramName][$paramValue];
			}
			if (strlen($paramName) < 1) {
				throw new LBoxExceptionConfig("Bad param \$paramName, ". LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
				LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			if (strlen($paramValue) < 1) {
				throw new LBoxExceptionConfig("Bad param \$paramValue, ". LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
				LBoxExceptionConfig::CODE_BAD_PARAM);
			}

			return $this->nodesByParams[$paramName][$paramValue]	= 
				$this->findNodeByParam($paramName, $paramValue, $this->getConfigInstance()->getRootIterator(), true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * recursive function to read structure tree and searching for page by url param
	 * @param string $url
	 * @param LBoxIteratorConfig $in - where to search
	 * @param bool $cache - do cache or not
	 * @return LBoxConfigItem
	 * @throws LBoxExceptionConfig
	 */
	protected function findNodeByParam($paramName = "", $paramValue = "", LBoxIteratorConfig $in, $cache = false) {
		try {
			if (strlen($paramName) < 1) {
				throw new LBoxExceptionConfig("Bad param \$paramName, ". LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
				LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			if (strlen($paramValue) < 1) {
				throw new LBoxExceptionConfig("Bad param \$paramValue, ". LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
				LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			foreach ($in as $node) {
				if ($node->hasChildren()) {
					if (($found = self::findNodeByParam($paramName, $paramValue, $node->getChildNodesIterator(), $cache)) instanceof LBoxConfigItem) {
						return $found;
					}
				}
				if ($cache) {
					$this->nodesByParams[$paramName][$node->$paramName]	= $node;
				}
				if ($node->$paramName == $paramValue) {
					return $node;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * recursive function to read config dom and searching for node by its content
	 * @param string $content
	 * @param LBoxIteratorConfig $in - where to search
	 * @param bool $cache - do cache or not
	 * @return LBoxConfigItem
	 * @throws LBoxExceptionConfig
	 */
	protected function findNodeByContent($content = "", LBoxIteratorConfig $in, $cache = false) {
		try {
			if (strlen($content) < 1) {
				throw new LBoxExceptionConfig("Bad param \$content, ". LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL,
				LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			foreach ($in as $node) {
				if ($node->hasChildren()) {
					if (($found = self::findNodeByContent($content, $node->getChildNodesIterator(), $cache)) instanceof LBoxConfigItem) {
						return $found;
					}
				}
				if ($cache) {
					$this->nodesByParams[$paramName][$node->$paramName]	= $node;
				}
				if ($node->getContent() == $content) {
					return $node;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	abstract public static function getInstance();

	/**
	 * vraci root iterator config nodu
	 * @return LBoxIteratorConfig
	 * @throws Exception
	 */
	public function getIterator() {
		try {
			return $this->getConfigInstance()->getRootIterator();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci instanci konkretni LBoxConfig tridy
	 * @return LBoxConfig
	 * @throws LBoxExceptionConfig
	 */
	protected function getConfigInstance() {
		if ($this->configInstance instanceof LBoxConfig) {
			return $this->configInstance;
		}
		if (strlen($className = $this->classNameConfig) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_CLASSNAME_NOT_DEFINED,
			LBoxExceptionConfig::CODE_ABSTRACT_CLASSNAME_NOT_DEFINED);
		}
		$instance	= eval("return $className::getInstance();");
		if (!$instance instanceof LBoxConfig) {
			throw new LBoxExceptionConfig("$className class ". LBoxExceptionConfig::MSG_CLASS_NOT_CONFIG, LBoxExceptionConfig::CODE_CLASS_NOT_CONFIG);
		}
		return $this->configInstance = $instance;
	}
	
	/**
	 * preparseruje hodnotu na pouzite standardni metaelementy
	 * @param string $value
	 * @return string
	 */
	protected static function getValueParsed($value = "") {
		try {
			$value	= str_replace("\$project", 	LBOX_PATH_PROJECT, $value);
			//$value	= str_replace("\$system", 	LBOX_PATH_CORE, $value);
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>