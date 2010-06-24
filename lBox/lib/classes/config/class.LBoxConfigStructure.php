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
	protected $classNameItem		= "LBoxConfigItemStructure";
	protected $classNameManager		= "LBoxConfigManagerStructure";
	protected $nodeName				= "page";
	
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

	public function resetInstance() {
		try {
			self::$instance	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
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
	public function getDOM() {
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
				throw new LBoxExceptionConfigComponent("$id: ". LBoxExceptionConfigComponent::MSG_NODE_BYID_NOT_FOUND, LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND);
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
	
	/**
	 * pretizeno o nastaveni povinnych hodnot
	 * @param string $url
	 * @param string $id
	 * @return LBoxConfigItem
	 */
	public function getCreateItem($url = "", $id = "") {
		try {
			if (strlen($url) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			if (strlen($id) < 1) {
				$ids	= array_keys($this->cacheNodes);
				arsort($ids);
				$id	= (current($ids))+1;
			}
			try {
				if (LBoxConfigManagerStructure::getInstance()->getPageById($id)) {
					throw new LBoxExceptionConfig("Page with this id already exists!");
				}
			}
			catch (Exception $e) {
				switch ($e->getCode()) {
					case LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND:
					case LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND:
						break;
					default:
						throw $e;
				}
			}
			try {
				if (LBoxConfigManagerStructure::getInstance()->getPageByUrl($url)) {
					throw new LBoxExceptionConfig("Page with this url already exists!");
				}
			}
			catch (Exception $e) {
				switch ($e->getCode()) {
					case LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND:
					case LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND:
						break;
					default:
						throw $e;
				}
			}
			$instance		= parent::getCreateItem();
			$instance->id	= $id;
			$instance->url	= $url;
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 
	 * vytvori novou item pod predaneho parenta
	 * @param LBoxConfigItemStructure $parent
	 * @param string $urlPart
	 */
	public function getCreateChild(LBoxConfigItemStructure $parent, $urlPart = "") {
		try {
			if (strlen($urlPart) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			$url	= $parent->url ."/". $urlPart ."/";
			$url	= preg_replace("/(\/+)/", "/", $url);
			if ($parent->hasChildren()) {
				foreach ($parent->getChildNodesIterator() as $child) {
					$id	= $child->id+1;
				}
			}
			else {
				$id	= (int)(((string)$parent->id) . "001");
			}
			$child	= $this->getCreateItem($url, $id);
			$parent->appendChild($child);
			return $child;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function store() {
		try {
			$this->cacheNodes		= array();
			$this->cacheNodesByUrl	= array();
			parent::store();
			$this->load();
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