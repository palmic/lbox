<?php
/**
* filteruje 
*/
class ProcessorFilterList extends LBoxFormProcessor
{
	public function process() {
		try {
			if ($this->form->getControlByName("school")->getValue() < 1) {
				return;
			}
			$school	= new SchoolsRecord($this->form->getControlByName("school")->getValue());
			$city	= $school->getCity();
			$region	= $city->getRegion();
			
			$refPageID	= LBoxConfigManagerProperties::getPropertyContentByName("ref_page_list_models");
			
			LBoxFront::reload(LBoxConfigManagerStructure::getInstance()->getPageById($refPageID)->url .":". $region->id ."/". $city->id ."/". $school->id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>