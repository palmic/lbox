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
	
	protected $idAttributeName		= "id";
	

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
			return $this->hasSiblingBefore() ? 
				$this->hasSiblingBefore()->in_menu === $this->__get("in_menu") : false;
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
			return $this->hasSiblingAfter() ? 
				$this->getSiblingAfter()->in_menu === $this->__get("in_menu") : false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>