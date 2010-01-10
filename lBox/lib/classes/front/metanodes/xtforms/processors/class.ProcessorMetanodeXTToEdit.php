<?php
/**
 * processor uklada obsah metanode
 */
class ProcessorMetanodeXTToEdit extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxFront::reload(LBoxUtil::getURLWithParams(array("edit-". $this->form->getName())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>