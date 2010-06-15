<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxConfigComponents extends LBoxConfig
{
	protected static $instance;
	protected $configName 			= "components";
	protected $classNameIterator	= "LBoxIteratorComponents";
	protected $classNameItem		= "LBoxConfigItemComponent";
	protected $nodeName				= "component";
	
	/**
	 * defines unicate ID attribute name to check if is unique and index by it
	 * @var string
	 */
	protected $idAttributeName		= "id";

	/**
	 * contents all loaded and validated nodes indexed by theirs id values
	 */
	protected $cacheNodes			= array();

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
	 * getter configu konkretni stranky podle id
	 * @param int $id
	 * @return LBoxConfigItemStructure
	 * @throws LBoxExceptionConfigComponent
	 */
	public function getNodeById($id = 0) {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionConfigComponent(LBoxExceptionConfigComponent::MSG_PARAM_INT_NOTNULL, LBoxExceptionConfigComponent::CODE_BAD_PARAM);
			}
			if (!array_key_exists($id, $this->cacheNodes)) {
				throw new LBoxExceptionComponent(LBoxExceptionComponent::MSG_COMPONENT_NOT_FOUND ." Tryed to find id = '$id'", LBoxExceptionComponent::CODE_COMPONENT_NOT_FOUND);
			}
			return $this->cacheNodes[$id];
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
					throw new LBoxExceptionConfigComponent("$exStr: ". LBoxExceptionConfigComponent::MSG_ATTRIBUTE_UNIQUE_EMPTY, LBoxExceptionConfigComponent::CODE_ATTRIBUTE_UNIQUE_EMPTY);
				}
				if (array_key_exists($item->$idName, $this->cacheNodes)) {
					$exStr = "$idName = '". $item->$idName ."'";
					throw new LBoxExceptionConfigComponent("$exStr: ". LBoxExceptionConfigComponent::MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE, LBoxExceptionConfigComponent::CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE);
				}
				$this->cacheNodes[$item->$idName] = $item;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			$this->cacheNodes		= array();
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