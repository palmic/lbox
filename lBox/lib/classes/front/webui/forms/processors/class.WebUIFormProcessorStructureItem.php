<?php
/**
 * uklada zmeny do structure.<lng>.xml
 */
class WebUIFormProcessorStructureItem extends LBoxFormProcessor
{
	/**
	 * pattern vztahu typu stranky a nazvu sablony
	 * @var string
	 */
	protected $fileNamesTemplatePagesTypesPattern;

	public function process() {
		try {
			$parent	= NULL;
			if (strlen($this->fileNamesTemplatePagesTypesPattern) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			if (strlen($this->form->getControlByName("id")->getValue()) > 0) {
				$configItem	= LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("id")->getValue());
				// smazat cache pro existujici stranku na jeji puvodni URL jeste pred moznou zmenou!
				LBoxCacheManagerFront::getInstance()->cleanByPageID($this->form->getControlByName("id")->getValue(), true);
				if ($parentID	= $this->form->getControlByName("parent_id")->getValue()) {
					$parent	= LBoxConfigManagerStructure::getInstance()->getPageById($parentID);
				}
			}
			else {
				try {
					if (LBoxConfigManagerStructure::getInstance()->getPageByUrl($this->form->getControlByName("url")->getValue())) {
						throw new LBoxExceptionConfigStructure("URL: ". LBoxExceptionConfigStructure::MSG_ATTRIBUTE_UNIQUE_NOT_UNIQUE, LBoxExceptionConfigStructure::CODE_ATTRIBUTE_UNIQUE_NOT_UNIQUE);
					}
				}
				catch (Exception $e) {
					switch ($e->getCode()) {
						case LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND:
								NULL;
							break;
						default:
							throw $e;
					}
				}
				if ($parentID	= $this->form->getControlByName("parent_id")->getValue()) {
					$parent	= LBoxConfigManagerStructure::getInstance()->getPageById($parentID);
					// zaridi parental vztah jen pri vytvareni (nutno kvuli odvozeni ID) - pro editaci je dodatecne prirazeni nize 
					$configItem	= LBoxConfigStructure::getInstance()->getCreateChild($parent, $this->form->getControlByName("url")->getValue());
				}
				else {
					$configItem	= LBoxConfigStructure::getInstance()->getCreateItem($this->form->getControlByName("url")->getValue());
				}
			}
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				$name	= $control->getName();
				switch ($name) {
					case "id":
					case "parent_id":
					case "move_before":
						NULL;
						break;
					case "type":
							$configItem->template	= str_replace("(.+)", $control->getValue(), $this->fileNamesTemplatePagesTypesPattern);
							$configItem->template	= preg_replace("/[^\w_\-\.]/", "", $configItem->template);
						break;
					case "url":
							if ($parent) {
								$configItem->$name	= "/". $parent->url . "/". $control->getValue() ."/";
								$configItem->$name	= preg_replace("/(\/+)/", "/", $configItem->$name);
							}
							else {
								$configItem->$name	= "/". $control->getValue() ."/";
								$configItem->$name	= preg_replace("/(\/+)/", "/", $configItem->$name);
							}
						break;
					default:
						$configItem->$name	= $control->getValue();
				}
			}
			// je treba dodatecne zajistit parental vztah, pri editaci horni logika to zajistuje pouze pri vytvareni, ale ne pri editaci
			if ($parentID = $this->form->getControlByName("parent_id")->getValue()) {
				LBoxConfigManagerStructure::getInstance()->getPageById($parentID)->appendChild($configItem);
			}
			else {
				$configItem->removeFromTree();
			}

			// move before
			if ($siblingID	= $this->form->getControlByName("move_before")->getValue()) {
				LBoxConfigManagerStructure::getInstance()->getPageById($siblingID)->insertBefore($configItem);
			}
			else {
				if ($parentID = $this->form->getControlByName("parent_id")->getValue()) {
					LBoxConfigManagerStructure::getInstance()->getPageById($parentID)->appendChild($configItem);
				}
				else {
					$configItem->removeFromTree();
				}
			}

			LBoxConfigStructure::getInstance()->store();

			// pro jistotu smazani front cache stranky na jeji potencialne zmenene URL (mohly by tam byt data z minulosti)
			LBoxCacheManagerFront::getInstance()->cleanByPageID($this->form->getControlByName("id")->getValue(), true);
			
			//reload na nove ulozenou stranky
			LBoxFront::reload(LBoxConfigManagerStructure::getInstance()->getPageById($configItem->id)->url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter na pattern vztahu typu stranky a nazvu sablony
	 * @param string $pattern
	 */
	public function setFileNamesTemplatePagesTypesPattern($pattern = "") {
		try {
			if (strlen($pattern) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_PARAM);
			}
			$this->fileNamesTemplatePagesTypesPattern	= $pattern;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>