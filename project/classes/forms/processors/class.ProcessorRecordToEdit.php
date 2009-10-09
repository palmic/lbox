<?php
/**
* reloaduje stranku editace text item
*/
class ProcessorRecordToEdit extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxFront::reload(LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("rpe")->getValue())->url
								. ":". $this->form->getControlByName("id")->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>