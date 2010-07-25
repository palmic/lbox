<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2008-08-15
*/
class LBoxI18NDataItem extends LBoxConfigItem
{
	protected $nodeName 			= "text";
	protected $classNameIterator	= "LBoxI18NDataIterator";
	protected $idAttributeName		= "id";
	
	public function setNode(DOMNode $node) {
		$this->node = $node;
		if (strlen($this->nodeName) < 1) {
			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_ABSTRACT_NODENAME_NOT_DEFINED, LBoxExceptionConfig::CODE_ABSTRACT_NODENAME_NOT_DEFINED);
		}
		if (!$this->node instanceof DOMElement) {
			throw new LBoxExceptionConfig("Given param is not DOMElement.",
			LBoxExceptionConfig::CODE_BAD_PARAM);
		}
	}
	
	/**
	 * Vrati tagName sveho nodu
	 * @return string
	 */
	public function getNodeName() {
		return $this->node->tagName;
	}
	
	/**
	 * Vrati dedicny node podle tagName (prvni takovy)
	 * @param string $name
	 * @return LBoxXMLImportDataItem
	 * @throws LBoxExceptionConfig
	 */
	public function getChildByName($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
			}
			foreach ($this->getChildNodesIterator() as $child) {
				if ($child->getNodeName() == $name) {
					return $child;
				}
			}
			throw new LBoxExceptionConfig("Child node was not found!", -1002);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>