<?php
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2007-12-08
 */
abstract class LBoxConfigItem implements OutputItem
{
	/**
	 * relevantni DOMNode
	 * @var DOMNode
	 */
	protected $node;

	/**
	 * nodeName elementu jednotky (napriklad component u komponent)
	 * @var string
	 */
	protected $nodeName;

	/**
	 * nazev tridy konkretniho items iteratoru
	 * @var string
	 */
	protected $classNameIterator;

	/**
	 * set OutputFilters
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;

	/**
	 * prijima DOMNode s nodeName == $this->nodeName, jinak vyhodi vyjimku
	 * @param DOMNode $node
	 * @throws LBoxExceptionConfig
	 */
	public function setNode(DOMNode $node) {
		$this->node = $node;
		if (strlen($this->nodeName) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
		}
		if ($this->node->nodeName != $this->nodeName) {
			throw new LBoxExceptionConfig("\$node ".	LBoxExceptionConfig::MSG_PARAM_NOT_NODENAME ." ". $this->node->nodeName ." given.",
			LBoxExceptionConfig::CODE_BAD_PARAM);
		}
	}

	/**
	 * Vraci attributy
	 * @param string $name
	 * @throws LBoxExceptionConfig
	 */
	public function __get($name = "") {
		switch ($name) {
			default:
				$value	= $this->getParamDirect($name);
				if ($this->outputFilter instanceof LBoxOutputFilter) {
					return $this->outputFilter->prepare($name, $value);
				}
				else {
					return $value;
				}
		}
	}

	public function getParamDirect($name = "") {
		try {
			if ($this->node->hasAttributes()) {
				foreach ($this->node->attributes as $attribute) {
					if ($attribute->name == $name) {
						return $attribute->value;
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter for output filter
	 * @param LBoxOutputFilter $outputFilter
	 */
	public function setOutputFilter(LBoxOutputFilter $outputFilter) {
		$this->outputFilter = $outputFilter;
	}

	/**
	 * getter of output filter
	 * @return LBoxOutputFilter
	 */
	public function getOutputFilter() {
		return $this->outputFilter;
	}

	/**
	 * Vraci obsah nodu (pokud nejaky je)
	 * @return string
	 */
	public function getContent() {
		try {
			if (!is_string($value = $this->node->nodeValue)) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_NODE_CONTENT_ISNOT_STRING, LBoxExceptionConfig::CODE_NODE_CONTENT_ISNOT_STRING);
			}
			return trim($value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function __toString() {
		$msg = "Object of type ". get_class($this) ."\ntagName:\n	". $this->node->tagName ."\n";
		$msg .= "attributes:\n";
		foreach ($this->node->attributes as $attribute) {
			$attributesString .= strlen($attributesString) > 0 ? "\n" : "";
			$attributesString .= "	". $attribute->name ." => ". $attribute->value;
		}
		return $msg . $attributesString ."\n";
	}

	/**
	 * Vraci jestli ma potomky ve strukture
	 * @return bool
	 */
	public function hasChildren() {
		return (bool) $this->node->hasChildNodes();
	}

	/**
	 * Vraci jestli ma potomky ve strukture
	 * @return bool
	 */
	public function hasParent() {
		return ($this->node->parentNode->nodeName == $this->nodeName);
	}

	/**
	 * Vraci iterator potomku ve strukture
	 * @return LBoxIteratorConfig
	 */
	public function getChildNodesIterator() {
		try {
			if ($this->hasChildren()) {
				if (strlen($classNameIterator = $this->classNameIterator) < 1) {
					throw new LBoxExceptionConfig("classNameIterator: ". LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
				}
				$iterator = new $classNameIterator;
				$iterator->setParent($this->node);
				if ($this->outputFilter instanceof LBoxOutputFilter) {
					$iterator->setOutputFilterItemsClass(get_class($this->outputFilter));
				}
				return $iterator;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci parenta ve strukture
	 * @return LBoxConfigItem
	 */
	public function getParent() {
		try {
			if ($this->hasParent()) {
				$className	= get_class($this);
				$parent		= new $className;
				$parent->setNode($this->node->parentNode);
				if ($this->outputFilter instanceof LBoxOutputFilter) {
					$ofClassName	= get_class($this->outputFilter);
					$parent->setOutputFilter(new $ofClassName($parent));
				}
				return $parent;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>