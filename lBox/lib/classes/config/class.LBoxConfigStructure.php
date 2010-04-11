<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2007-12-08
*/
class LBoxConfigStructure extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "structure";
	protected $classNameIterator	= "LBoxIteratorStructure";

	/**
	 * defines unicate ID attribute name to check if is unique and index by it
	 * @var string
	 */
	protected $idAttributeName		= "id";

	/**
	 * contents all loaded and validated nodes indexed by theirs id values
	 */
	protected $cacheNodes			= array();

	/**
	 * contents all loaded and validated nodes indexed by theirs url values
	 */
	protected $cacheNodesByUrl		= array();

	/**
	 * @return LBoxConfigStructure
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
				$this->configName	= $configNameBase;
				return parent::getDOM();
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter configu konkretni stranky podle id
	 * @param int $id
	 * @return LBoxConfigItemStructure
	 * @throws LBoxExceptionConfigComponent
	 */
	public function getNodeById($id = 0) {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfigComponent::CODE_BAD_PARAM);
			}
			if (!array_key_exists($id, $this->cacheNodes)) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_NODE_BYID_NOT_FOUND, LBoxExceptionConfigComponent::CODE_NODE_BYID_NOT_FOUND);
			}
			return $this->cacheNodes[$id];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter configu konkretni stranky podle id
	 * @param string $url
	 * @return LBoxConfigItemStructure
	 * @throws LBoxExceptionConfigStructure
	 */
	public function getNodeByUrl($url = "") {
		try {
			if (strlen($url) < 1) {
				throw new LBoxExceptionConfigStructure(LBoxExceptionConfigStructure::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfigStructure::CODE_BAD_PARAM);
			}
			if (!array_key_exists($url, $this->cacheNodesByUrl)) {
				throw new LBoxExceptionConfigStructure(LBoxExceptionConfigStructure::MSG_NODE_BYURL_NOT_FOUND ." '$url'", LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND);
			}
			return $this->cacheNodesByUrl[$url];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * loads and validates all strcture config nodes
	 * @throws LBoxException
	 */
	protected function load() {
		try {
			$this->loadRecursive($this->getRootIterator());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * validates given iterator items recursive by custom config conditions
	 * @param LBoxIteratorConfig $iterator
	 * @throws LBoxExceptionConfigComponent
	 */
	protected function loadRecursive(LBoxIteratorConfig $iterator) {
		try {
			$idName = $this->idAttributeName;
			foreach ($iterator as $item) {
				// if has children run recursive call
				if ($item->hasChildren()) {
					$this->loadRecursive($item->getChildNodesIterator());
				}
				if (strlen($item->$idName) < 1) {
					$exStr = "$idName = '". $item->$idName ."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_EMPTY, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_EMPTY);
				}
				if (array_key_exists($item->$idName, $this->cacheNodes)) {
					$exStr = "$idName = '". $item->$idName ."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE);
				}
				
				if (strlen($item->url) < 1) {
					$exStr = "url = '". $item->url ."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_EMPTY, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_EMPTY);
				}
				if (array_key_exists($item->url, $this->cacheNodesByUrl)) {
					$exStr = "url = '". $item->url."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE);
				}
				$this->cacheNodes[$item->$idName] 	= $item;
				$this->cacheNodesByUrl[$item->url] 	= $item;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {
		try {
			$this->load();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>