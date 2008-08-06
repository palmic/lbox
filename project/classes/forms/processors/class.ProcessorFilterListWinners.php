<?php
/**
* filteruje
*/
class ProcessorFilterListWinners extends LBoxFormProcessor
{
	public function process() {
		try {
			$school	= new SchoolsRecord($this->form->getControlByName("school")->getValue());
			$city	= $school->getCity();
			$region	= $city->getRegion();
			
			$refPageID	= LBoxConfigManagerProperties::getPropertyContentByName("ref_page_list_models");
			
			LBoxFront::reload(LBoxFront::getPage()->url .":". $region->id ."/". $city->id ."/". $school->id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>