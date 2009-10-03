<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-25
*/
class AdminSchool extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$controls["name"]	= new LBoxFormControlFill("name", "název");
				$controls["name"]->setRequired();
				$controls["name"]->addFilter(new LBoxFormFilterName);
			$controls["ref_city"]	= new LBoxFormControlChooseOne("ref_city", "město");
				$controls["ref_city"]->setRequired();
			$citiesRecords	= new CitiesRecords(false, array("name" => 1));
			foreach ($citiesRecords as $city) {
				$controls["ref_city"]->addOption(new LBoxFormControlOption($city->id, $city->name));
			}
			
			$form	= new LBoxForm("school");
			foreach($controls as $control) {
				$form->addControl($control);
			}
			$form->addProcessor(new ProcessorAddSchool());
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>