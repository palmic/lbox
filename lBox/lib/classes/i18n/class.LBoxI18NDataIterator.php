<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-08-15
*/
class LBoxI18NDataIterator extends LBoxIteratorConfig
{
	protected $nodeName 		= "text";
	protected $classNameItem	= "LBoxI18NDataItem";
	
	/**
	 * loadAll flag
	 * @var bool
	 */
	protected $loadedAll		= false;

	/**
	 * @param DOMNode $parentElement - root element iteratoru
	 * @throws LBoxExceptionConfig
	 */
	public function setParent(DOMNode $parentElement) {
		if (strlen($this->nodeName) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
		}
		$this->parentElement = $parentElement;
		// nacteme first child
		if ($this->parentElement->hasChildNodes()) {
			$firstChild = $this->parentElement->firstChild;
			// preskakovani irelevantnich nodu (#TEXT, #COMMENT etc..)
			while (!$firstChild instanceof DOMElement) {
				if (!($firstChild->nextSibling instanceof DOMNode)) break;
				$firstChild = $firstChild->nextSibling;
			}
			$this->items[] = $firstChild;
		}
	}

	public function current() {
		try {
			if ($this->valid()) {
				if (strlen($className = $this->classNameItem) < 1) {
					throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_CLASSNAME_NOT_DEFINED,
					LBoxExceptionConfig::CODE_ABSTRACT_CLASSNAME_NOT_DEFINED);
				}
				$instance	= eval("return new $className;");
				if (!$instance instanceof LBoxConfigItem) {
					throw new LBoxExceptionConfig("$className class ". LBoxExceptionConfig::MSG_CLASS_NOT_CONFIG_ITEM, LBoxExceptionConfig::CODE_CLASS_NOT_CONFIG_ITEM);
				}
				$instance->setNode($this->items[$this->key()]);
				// output filters pokud je nastavena class
				if (strlen($this->outputFilterClass) > 0) {
					$class = $this->outputFilterClass;
					$filter = new $class($instance);
					$instance->setOutputFilter($filter);
				}
				return $instance;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * prepsano kvuli obecnemu pouziti na XML
	 */
	public function next() {
		try {
			// pokud parentElement nema potomky, neni co iterovat - ukoncime iteraci
			if ($this->key() > 0)
			if (!$this->current() instanceof LBoxConfigItem) {
				return;
			}
			parent::next();
			if (!$this->items[$this->key()] instanceof DOMNode) {
				// preskakovani irelevantnich nodu (#TEXT, #COMMENT etc..)
				$nextSibling = $this->items[$this->key()-1]->nextSibling;
				while(!$nextSibling instanceof DOMElement) {
					if (!$nextSibling instanceof DOMNode) break;
					$nextSibling = $nextSibling->nextSibling;
				}
				$this->items[$this->key()] = $nextSibling;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function valid() {
		return ($this->items[$this->key()] instanceof DOMElement);
	}

	/**
	 * Vraci celkovy pocet jednotek iterace
	 * @return int
	 * @throws LBoxException
	 */
	public function count() {
		try {
			$this->loadAll();
			if (!$this->items[count($this->items)-1] instanceof DOMElement) {
				unset($this->items[count($this->items)-1]);
			}
			return count($this->items);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * nacte vsechny nody iterace - jen kvuli metode count()
	 * @throws LBoxException
	 */
	protected function loadAll() {
		try {
			if ($this->loadedAll) return;
			$currentKey	= $this->key();
			while ($this->valid()) {
				$this->next();
			}
			$this->key	= $currentKey;
			$this->loadedAll	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>