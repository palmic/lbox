<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
abstract class LBoxIteratorConfig extends LBoxIterator
{
	/**
	 * config instance zastupujici DOM document
	 * @var LBoxConfig
	 */
	protected $config;

	/**
	 * rodicovsky DOM element, kterym iterujeme
	 * @var DOMNode
	 */
	protected $parentElement;

	/**
	 * item nodeName - musi byt definano v podtride!
	 * @var string
	 */
	protected $nodeName;

	/**
	 * Trida pro spravu jednotlivych iterovanych nodu
	 * @var string
	 */
	protected $classNameItem;

	/**
	 * items OutputFilter class
	 * @var string
	 */
	protected $outputFilterClass;

	/**
	 * Set items OutputFilter class
	 * @param string $outputFilterClass
	 */
	public function setOutputFilterItemsClass($outputFilterClass = "") {
		if (strlen($outputFilterClass) < 1) {
			throw new LBoxExceptionOutputFilter("\$outputFilterClass ". LBoxExceptionOutputFilter::MSG_PARAM_STRING_NOTNULL, LBoxExceptionOutputFilter::CODE_BAD_PARAM);
		}
		$this->outputFilterClass = $outputFilterClass;
	}

	/**
	 * setter na config
	 * @param LBoxConfig $config
	 */
	public function setConfig(LBoxConfig $config) {
		try {
			$this->config	= $config;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

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
			while ($firstChild->nodeName != $this->nodeName) {
				if (!$firstChild->nextSibling instanceof DOMNode) break;
				$firstChild = $firstChild->nextSibling;
			}
			if ($firstChild->nodeName == $this->nodeName) {
				$this->items[] = $firstChild;
			}
		}
	}
	
	public function __toString() {
		$msg = "Object of type ". get_class($this) ."\n";
		$msg .= "Loaded items in cache:\n";
		foreach ($this->items as $key => $item) {
			$items .= strlen($items) > 0 ? ", " : "";
			$items .= "$key => ". $item->nodeName;
		}
		return $msg . $items;
	}

	/**
	 * Vraci current instanci item
	 * @return LBoxConfigItem
	 * @throws LBoxExceptionConfig
	 */
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
				$instance->setConfig($this->config);
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
	 * @throws LBoxExceptionStructure
	 */
	public function next() {
		try {
			// pokud parentElement nema potomky, neni co iterovat - ukoncime iteraci
				
			if ($this->key() > 0)
			if (!$this->current() instanceof LBoxConfigItem) {
				return;
			}
			parent::next();
			if (!array_key_exists($this->key(), $this->items) || (!$this->items[$this->key()] instanceof DOMNode)) {
				// preskakovani irelevantnich nodu (#TEXT, #COMMENT etc..)
				$nextSibling = $this->items[$this->key()-1]->nextSibling;
				while(($nextSibling instanceof DOMNode) && ($nextSibling->nodeName != $this->nodeName)) {
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
		if (	array_key_exists($this->key(), $this->items) &&
				$this->items[$this->key()] &&
				($this->items[$this->key()]->nodeName == $this->nodeName)) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>