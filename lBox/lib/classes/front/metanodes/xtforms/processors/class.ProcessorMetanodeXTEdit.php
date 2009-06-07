<?php
/**
 * processor uklada obsah metanode
 */
class ProcessorMetanodeXTEdit extends LBoxFormProcessor
{
	public function process() {
		try {
			// page metanode
			if ($this->form->getControlByName("caller_type")->getValue() == "page") {
				$callerConfig		= LBoxConfigManagerStructure::getInstance()->getPageById($this->form->getControlByName("caller_id")->getValue());
				$callerClassName	= $callerConfig->class;
				$caller				= new $callerClassName($callerConfig);
			}
			// component metanode
			else {
				$callerConfig	= LBoxConfigManagerComponents::getInstance()->getComponentById($this->form->getControlByName("caller_id")->getValue());
				$caller			= new LBoxComponent($callerConfig, LBoxFront::getPage());
			}
			$node	= LBoxMetanodeManager::getNode(		$this->form->getControlByName("type")->getValue(),
														(int)$this->form->getControlByName("seq")->getValue(),
														$caller);
			$node->setContent($this->form->getControlByName("content")->getValue());

			LBoxFront::reload(LBoxUtil::getURLWithoutParams(array("edit-". $this->form->getName())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>