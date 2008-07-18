<?php
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0

 * @date 2008-02-17
 */
class LBoxConfigItemStructure extends LBoxConfigItemComponent
{
	protected $nodeName 			= "page";
	protected $classNameIterator	= "LBoxIteratorStructure";


	/**
	 * Vraci jestli jde o Home page
	 * @return bool
	 */
	public function isHomePage() {
		return ($this->__get("url") == "/");
	}

	/**
	 * Vraci nazev sablony z parametru, nebo system config default
	 * @return string
	 * @throws LBoxException
	 */
	public function getTemplateFileName() {
		try {
			if (strlen($value = $this->__get($this->attNames["template"])) > 0) {
				return $value;
			}
			else {
				$default = LBoxConfigSystem::getInstance()->getParamByPath("pages/templates/default");
				if (strlen($default) < 1) {
					throw new LBoxExceptionStructure(LBoxExceptionStructure::MSG_TEMPLATE_DEFAULT_NOTFOUND, LBoxExceptionStructure::CODE_TEMPLATE_DEFAULT_NOTFOUND);
				}
				return $default;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci nazev tridy z parametru, nebo system config default
	 * @return string
	 * @throws LBoxException
	 */
	public function getClassName() {
		try {
			if (strlen($value = $this->__get($this->attNames["class"])) > 0) {
				return $value;
			}
			else {
				$default = LBoxConfigSystem::getInstance()->getParamByPath("pages/classes/default");
				if (strlen($default) < 1) {
					throw new LBoxExceptionConfigStructure(LBoxExceptionConfigStructure::MSG_CLASS_DEFAULT_NOTFOUND, LBoxExceptionConfigStructure::CODE_CLASS_DEFAULT_NOTFOUND);
				}
				return $default;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci jestli ma alespon jednoho potomka v menu
	 * @return bool
	 */
	public function hasChildrenInMenu() {
		try {
			if (!parent::hasChildren()) return false;
			foreach ($this->getChildNodesIterator() as $child) {
				if ($child->in_menu) {
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
	 * Vraci jestli je prvni v menu
	 * @return bool
	 */
	public function isFirstInMenu() {
		try {
			// listujeme sourozence
			$siblingNode	= $this->node;
			while ($siblingNode = $siblingNode->previousSibling) {
				if (!($siblingNode instanceof DOMElement)) {
					continue;
				}
				$className	= get_class($this);
				$sibling	= new $className;
				$sibling->setNode($siblingNode);
				if (!$this->outputFilter instanceof LBoxOutputFilter) {
					throw new LBoxExceptionConfigStructure(LBoxExceptionConfigStructure::MSG_NEEDED_OUTPUTFILTER_NOT_DEFINED, LBoxExceptionConfigStructure::CODE_NEEDED_OUTPUTFILTER_NOT_DEFINED);
				}
				$ofClassName	= get_class($this->outputFilter);
				$sibling->setOutputFilter(new $ofClassName($sibling));
				// pokud je sourozenec v menu, vracime false
				if ($sibling->in_menu) {
					return false;
				}
			}
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci jestli je posledni v menu
	 * @return bool
	 */
	public function isLastInMenu() {
		try {
			// listujeme sourozence
			$siblingNode	= $this->node;
			while ($siblingNode = $siblingNode->nextSibling) {
				if (!($siblingNode instanceof DOMElement)) {
					continue;
				}
				$className	= get_class($this);
				$sibling	= new $className;
				$sibling->setNode($siblingNode);
				if (!$this->outputFilter instanceof LBoxOutputFilter) {
					throw new LBoxExceptionConfigStructure(LBoxExceptionConfigStructure::MSG_NEEDED_OUTPUTFILTER_NOT_DEFINED, LBoxExceptionConfigStructure::CODE_NEEDED_OUTPUTFILTER_NOT_DEFINED);
				}
				$ofClassName	= get_class($this->outputFilter);
				$sibling->setOutputFilter(new $ofClassName($sibling));
				// pokud je sourozenec v menu, vracime false
				if ($sibling->in_menu) {
					return false;
				}
			}
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>