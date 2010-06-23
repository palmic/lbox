<?php
/**
 * uklada zmeny do structure.<lng>.xml
 */
class WebUIFormProcessorStructureItem extends LBoxFormProcessor
{
	public function process() {
		try {
			if (strlen($this->form->getControlByName("id")->getValue()) > 0) {
				$configItem	= LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("id")->getValue());
			}
			else {
				//$configItem	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-3/")
				//$configItem	= LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("id")->getValue());
			}
			foreach ($this->form->getControls() as $control) {
				
			}
			LBoxConfigStructure::getInstance()->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>