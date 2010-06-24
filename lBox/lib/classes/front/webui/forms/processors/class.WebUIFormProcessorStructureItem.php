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
			}
			else {
				if ($parentID	= $this->form->getControlByName("parent_id")->getValue()) {
					$parent	= LBoxConfigManagerStructure::getInstance()->getPageById($parentID);
					LBoxConfigStructure::getInstance()->getCreateChild(	$parent, $this->form->getControlByName("url")->getValue());
				}
				else {
					$configItem	= LBoxConfigStructure::getInstance()->getCreateItem($this->form->getControlByName("url")->getValue());
				}
			}
			foreach ($this->form->getControls() as $control) {
				$name	= $control->getName();
				switch ($name) {
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
					case "parent_id":
							NULL;
						break;
					default:
						$configItem->$name	= $control->getValue();
				}
			}
			LBoxConfigStructure::getInstance()->store();
			
			//reload pokud se jedna o editaci aktualni stranky
			if (LBoxFront::getPage()->id == $this->form->getControlByName("id")->getValue()) {
				LBoxFront::reload(LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("id")->getValue())->url);
			}
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