<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-08-15
*/
class LBoxI18NData extends LBoxConfig
{
	protected static 	$instance;
	protected		 	$configName 			= "translation";
	protected 			$classNameIterator		= "LBoxI18NDataIterator";
	protected 			$classNameItem			= "LBoxI18NDataItem";
	protected 			$nodeName				= "text";
	
	public 				$filePath;

	/**
	 * defines unicate ID attribute name to check if is unique and index by it
	 * @var string
	 */
	protected $idAttributeName		= "id";

	/**
	 * contents all loaded and validated nodes indexed by theirs id values
	 */
	protected $cacheNodes			= array();
	
	protected $loaded				= false;

	public function resetInstance() {
		try {
			self::$instance	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter configu konkretniho lang textu podle id
	 * @param int $id
	 * @return LBoxI18NDataItem
	 * @throws LBoxExceptionConfigComponent
	 */
	public function getNodeById($id = 0) {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfigComponent::CODE_BAD_PARAM);
			}
			$this->load();
			if (!array_key_exists($id, $this->cacheNodes)) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_NODE_BYID_NOT_FOUND, LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND);
			}
			return $this->cacheNodes[$id];
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
			// cache vypnuta kvuli opakovanemu prochazeni jinych files
//			if (!self::$instance instanceof $className) {
				self::$instance = new $className;
//			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function getDOM() {
		if (strlen($this->filePath) < 1) {
			throw new  LBoxExceptionConfig(LBoxExceptionConfig::MSG_CFG_FILE_NOT_DEFINED, LBoxExceptionConfig::CODE_CFG_FILE_NOT_DEFINED);
		}
		try {
			if (!file_exists($this->filePath)) {
				throw new  LBoxExceptionConfig("'". $this->filePath ."' ". LBoxExceptionConfig::MSG_TYPE_NOT_FOUND, LBoxExceptionConfig::CODE_TYPE_NOT_FOUND);
			}
			$domDocument = new DOMDocument;
			if (!@$domDocument->load($this->filePath)) {
				throw new  LBoxExceptionConfig("'$this->filePath'". LBoxExceptionConfig::MSG_INVALID_PATH, LBoxExceptionConfig::CODE_INVALID_PATH);
			}
			return $domDocument;
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
			if (!$this->loaded) {
				$this->loadRecursive($this->getRootIterator());
			}
			$this->loaded	= true;
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
				if (strlen($item->id) < 1) {
					$exStr = "id = '". $item->id ."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_EMPTY, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_EMPTY);
				}
				if (array_key_exists($item->id, $this->cacheNodes)) {
					$exStr = "url = '". $item->id."'";
					throw new LBoxExceptionConfigStructure("$exStr: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE);
				}
				$this->cacheNodes[$item->$idName] 	= $item;
				$this->cacheNodes[$item->id] 	= $item;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>