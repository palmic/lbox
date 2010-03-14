<?php
/**
 * processor, ktery uklada MetaRecords
 */
class ProcessorMetaRecordEdit extends ProcessorRecordEdit
{
	public function process() {
		try {
//DbControl::$debug = "firephp";
			$this->classNameRecord	= $this->form->getControlByName("type")->getValue();
			$this->controlsIgnore[] = "type";
			parent::process();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>