<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxIteratorStructure extends LBoxIteratorConfig
{
	protected $nodeName 			= "page";
	protected $classNameItem		= "LBoxConfigItemStructure";
	
	/**
	 * @param DOMNode $parentElement - root element stranek - prvni potomek musi byt home page
	 * @throws LBoxExceptionConfig
	 */
	public function setParent(DOMNode $parentElement) {
		if (strlen($this->nodeName) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
		}
		$this->parentElement = $parentElement;
		// nacteme home page
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
			// nactme rovnou i potomka homepage, protoze chceme mit potomky home page na stejne urovni (pokud nejake dalsi stranky jsou)
			if ($this->current() && $this->current()->isHomePage()) {
				$childDescendant = $this->items[$this->key()]->firstChild;
				// preskakovani irelevantnich nodu (#TEXT, #COMMENT etc..)
				while (($childDescendant) && ($childDescendant->nodeName != $this->nodeName)) {
					if (!$childDescendant->nextSibling instanceof DOMNode) break;
					$childDescendant = $childDescendant->nextSibling;
				}
				if ($childDescendant && ($childDescendant->nodeName == $this->nodeName)) {
					$this->items[] = $childDescendant;
				}
			}
		}
	}
}
?>