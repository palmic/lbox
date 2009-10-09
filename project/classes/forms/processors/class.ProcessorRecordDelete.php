<?php
/**
* maze record
*/
class ProcessorRecordDelete extends LBoxFormProcessor
{
	public function process() {
		try {
			$type	= $this->form->getControlByName("type")->getValue();
			$record	= new $type($this->form->getControlByName("id")->getValue());
			$record->delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>