<?php
/**
* reloaduje stranku editace text item
*/
class ProcessorRecordToEdit extends LBoxFormProcessor
{
	public function process() {
		try {
			$url	= LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("rpe")->getValue())->url;
			if (strlen($this->form->getControlByName("pnpup")->getValue()) > 0) {
				$url .= ":". str_replace("<url_param>", $this->form->getControlByName("id")->getValue(), LBoxConfigManagerProperties::gpcn($this->form->getControlByName("pnpup")->getValue()));
			}
			else {
				$url .= ":". $this->form->getControlByName("id")->getValue();
			}
			LBoxFront::reload($url);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>