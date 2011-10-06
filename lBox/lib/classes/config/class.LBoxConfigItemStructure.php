<?php
/**
 * @author Michal Palma <michal.palma@gmail.com>
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
			$next	= $this->node->previousSibling;
			$inMenu	= false;
			while ($next && !$inMenu) {
				while ($next && (!$next instanceof DOMElement)) {
					$next	= $next->previousSibling;
				}
				if ($next && $next->getAttributeNode('in_menu') && $next->getAttributeNode('in_menu')->value == $this->__get("in_menu")) {
					$inMenu = true;
					break;
				}
				$next	= $next->previousSibling;
			}
			return !$inMenu;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci jestli je posledni v menu
	 * @param int $menu
	 * @return bool
	 */
	public function isLastInMenu() {
		try {
			$next	= $this->node->nextSibling;
			$inMenu	= false;
			while ($next && !$inMenu) {
				while ($next && (!$next instanceof DOMElement)) {
					$next	= $next->nextSibling;
				}
				if ($next && $next->getAttributeNode('in_menu') && $next->getAttributeNode('in_menu')->value == $this->__get("in_menu")) {
					$inMenu = true;
					break;
				}
				$next	= $next->nextSibling;
			}
			return !$inMenu;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}
?>
