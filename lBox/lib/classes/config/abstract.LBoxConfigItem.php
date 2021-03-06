<?php
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @date 2007-12-08
 */
abstract class LBoxConfigItem implements OutputItem
{
	/**
	 * config instance zastupujici DOM document
	 * @var LBoxConfig
	 */
	protected $config;

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
	 * defines unicate ID attribute name to check if is unique and index by it
	 * @var string
	 */
	protected $idAttributeName;
	
	/**
	 * set OutputFilters
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;

	/**
	 * cache var
	 * @var int
	 */
	protected $level;

	public function __construct() {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
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
	public function __set($name = "", $value = "") {
		if (strlen($name) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
		}
		if ($value === NULL) {
			$this->node->removeAttribute($name);
		}
		else {
			if (!$this->node->setAttribute($name, $value)) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ATTRIBUTE_CANNOT_CHANGE, LBoxExceptionConfig::CODE_ATTRIBUTE_CANNOT_CHANGE);
			}
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
	
	/**
	 * getter na DOMNode
	 * @return DOMNode
	 */
	public function getNode() {
		try {
			return $this->node;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getParamDirect($name = "") {
		try {
			if (!$this->node instanceof DOMNode) {
				throw new LBoxExceptionConfig("Cannot get data from destructed config item (after calling config store()). Do get new instance from LBCManager");
			}
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

	/**
	 * nastavi obsah item
	 * @param string $value
	 */
	public function setContent($value) {
		try {
			if (htmlspecialchars($value) == $value && htmlspecialchars_decode($value) == $value) {
				$this->node->nodeValue	= $value;
			}
			else {
				foreach($this->node->childNodes as $nodeChild) {
					$this->node->removeChild($nodeChild);
				}
				$this->node->appendChild($this->config->getDom()->createCDATASection($value));
			}
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

	public function __destruct() {}

	/**
	 * Vraci jestli ma potomky ve strukture
	 * @return bool
	 */
	public function hasChildren() {
		// je treba projet jestli ma element nodes,
		//$this->node->hasChildNodes() vrati true i pokud ma pouze text nodes
		if (!$this->node instanceof DOMNode) {
			throw new LBoxExceptionConfig("Cannot get data from destructed config item (after calling config store()). Do get new instance from LBCManager");
		}
		foreach ($this->node->childNodes as $childNode) {
			if ($childNode instanceof DOMElement) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Vraci jestli ma potomky ve strukture
	 * @return bool
	 */
	public function hasParent() {
		return ($this->node->parentNode->nodeName == $this->nodeName);
	}

	/**
	 * Vraci jestli ma pred sebou sourozence
	 * @return bool
	 */
	public function hasSiblingBefore() {
		try {
			$prev	= $this->node->previousSibling;
			while ($prev && (!$prev instanceof DOMElement)) {
				$prev	= $prev->previousSibling;
			}
			if ($prev) {
				return $prev->nodeName == $this->nodeName;
			}
			else {
				return false;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci jestli ma za sebou sourozence
	 * @return bool
	 */
	public function hasSiblingAfter() {
		try {
			$next	= $this->node->nextSibling;
			while ($next && (!$next instanceof DOMElement)) {
				$next	= $next->nextSibling;
			}
			if ($next) {
				return $next->nodeName == $this->nodeName;
			}
			else {
				return false;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * 
	 * vraci, jestli je predana item jeho primym potomkem
	 * @param LBoxConfigItem $child
	 */
	public function isParentOf(LBoxConfigItem $child) {
		try {
			if (strlen($idAttributeName = $this->idAttributeName) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_INSTANCE_VAR);
			}
			if (!$this->hasChildren()) {
				return false;
			}
			foreach ($this->getChildNodesIterator() as $childItem) {
				if ($childItem->$idAttributeName	== $child->$idAttributeName) {
					return true;
				}
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci iterator potomku ve strukture
	 * @return LBoxIteratorConfig
	 */
	public function getChildNodesIterator() {
		try {
			if (strlen($classNameIterator = $this->classNameIterator) < 1) {
				throw new LBoxExceptionConfig("classNameIterator: ". LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
			}
			$iterator = new $classNameIterator;
			$iterator->setConfig($this->config);
			$iterator->setParent($this->node);
			if ($this->outputFilter instanceof LBoxOutputFilter) {
				$iterator->setOutputFilterItemsClass(get_class($this->outputFilter));
			}
			return $iterator;
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
				$parent->setConfig($this->config);
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
	
	/**
	 * Vraci parenta ve strukture na konkretnim pozadovanem levelu, nebo NULL
	 * @param int $level - wanted parent level
	 * @return LBoxConfigItem
	 */
	public function getParentLevel($level = 1) {
		try {
			if (!is_int($level) || $level < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_INT_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			$branch[]	= $this;
			while (end($branch)->hasParent()) {
				$branch[]	= end($branch)->getParent();
			}
			return $branch[count($branch)-$level];
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci svuj level ve stromu
	 * @return int
	 */
	public function getLevel() {
		try {
			if (is_int($this->level)) {
				return $this->level;
			}
			$node	= $this;
			$i		= 1;
			while ($node->hasParent()) {
				$node	= $node->getParent();
				$i++;
			}
			return $this->level	= $i;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci sourozence pred sebou
	 * @return LBoxConfigItem
	 */
	public function getSiblingBefore() {
		try {
			if ($this->hasSiblingBefore()) {
				$prev	= $this->node->previousSibling;
				while ($prev && (!$prev instanceof DOMElement)) {
					$prev	= $prev->previousSibling;
				}
				$className	= get_class($this);
				$previous	= new $className;
				$previous		->setNode($prev);
				$previous		->setConfig($this->config);
				if ($this->outputFilter instanceof LBoxOutputFilter) {
					$ofClassName	= get_class($this->outputFilter);
					$previous->setOutputFilter(new $ofClassName($previous));
				}
				return $previous;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci sourozence za sebou
	 * @return LBoxConfigItem
	 */
	public function getSiblingAfter() {
		try {
			if ($this->hasSiblingAfter()) {
				$next	= $this->node->nextSibling;
				while ($next && (!$next instanceof DOMElement)) {
					$next	= $next->nextSibling;
				}
				$className	= get_class($this);
				$nextItem	= new $className;
				$nextItem	->setNode($next);
				$nextItem	->setConfig($this->config);
				if ($this->outputFilter instanceof LBoxOutputFilter) {
					$ofClassName	= get_class($this->outputFilter);
					$nextItem->setOutputFilter(new $ofClassName($nextItem));
				}
				return $nextItem;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * vlozi predany LBCI do struktury jako posledniho potomka
	 * @param LBoxConfigItem $child
	 */
	public function appendChild(LBoxConfigItem $child) {
		try {
			$this->node->appendChild($child->getNode());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vlozi predany LBCI do struktury pred sebe na stejnou uroven
	 * @param LBoxConfigItem $child
	 */
	public function insertBefore(LBoxConfigItem $sibling) {
		try {
			$this->node = $this->node->parentNode->insertBefore($sibling->getNode(), $this->node);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vyjme LBCI ze stromu a vlozi jej nakonec
	 */
	public function removeFromTree() {
		try {
			$this->config->getDOM()->documentElement->appendChild($this->getNode());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * odstrani LBCI ze stromu
	 */
	public function delete() {
		try {
			if (!$this->node instanceof DOMNode) {
				throw new LBoxExceptionConfig("Cannot get data from destructed config item (after calling config store()). Do get new instance from LBCManager");
			}
			$content	= $this->node->nodeValue;
			if (!$this->node->parentNode->removeChild($this->node)) {
				throw new LBoxExceptionConfig("Cannot delete node!");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>