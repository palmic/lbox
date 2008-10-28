<?php
class ProcessorAddCity extends LBoxFormProcessor
{
	public function process() {
		try {
			// soutezici
			$city 					= new CitiesRecord();
			$city->name				= $this->form->getControlByName("name")->getValue();
			$city->ref_region		= $this->form->getControlByName("ref_region")->getValue();
			$city->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>