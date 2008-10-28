<?php
/**
* Pridava skolu
*/
class ProcessorAddSchool extends LBoxFormProcessor
{
	public function process() {
		try {
			// soutezici
			$school 				= new SchoolsRecord();
			$school->name			= $this->form->getControlByName("name")->getValue();
			$school->ref_city		= $this->form->getControlByName("ref_city")->getValue();
			$school->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>