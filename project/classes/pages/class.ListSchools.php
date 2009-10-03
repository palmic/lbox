<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-30
*/
class ListSchools extends PageDefault
{
	/**
	 * cache variable
	 * @var CitiesRecords
	 */
	protected $cities;
	
	/**
	 * trida procesoru formulare
	 * @var string
	 */
	protected $formProcessorClassName	= "ProcessorFilterList";
	
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$controls["school"]	= new LBoxFormControlChooseOne("school", "Vyberte školu");
			$controls["school"]->setTemplateFileName("lbox_form_control_list_models_schools.html");
			$controls["school"]->setRequired();
			//$controls["school"]->addValidator(new LBoxFormValidatorSchoolExists());
			if ($this->getSchools()->count() > 0) {
				foreach ($this->getSchools() as $school) {
					$option			= new LBoxFormControlOption($school->ref_school, $school->school);
					$option->class	= $school->class;
					$controls["school"]->addOption($option);
				}
			}
			else {
				$controls["school"]->addOption(new LBoxFormControlOption(0, "Tento region tentokrát nenavštívíme."));
			}
			$form	= new LBoxForm("filter", "post", "", "ok");
			$form->setTemplateFileName("lbox_form_filter_list.html");
			$form->addProcessor(new $this->formProcessorClassName);
			foreach ($controls as $control) {
				$form->addControl($control);
			}
			
			$TAL->form			= $form;
			$TAL->swfMapLink	= $this->config->url;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na seznam mest podle (ne)zvoleneho regionu
	 * @return CitiesRecords
	 */
	public function getSchools() {
		try {
			if ($this->cities instanceof CitiesRecords) {
				return $this->cities;
			}
			$filter	= false;
			if (strlen($this->getURLParamFirst()) > 0) {
				$records	= new SchoolsCitiesRegionsRecords(array("ref_region" => $this->getURLParamFirst()), array("school" => 1));
				$records->setOutputFilterItemsClass("OutputFilterSchoolsCitiesRegions");
				//pokud v kraji neni skola, zobrazime prazdny select if ($records->count() > 0) {
					return $records;
				//}
			}
			$records	= new SchoolsCitiesRegionsRecords(false, array("school" => 1));
			$records->setOutputFilterItemsClass("OutputFilterSchoolsCitiesRegions");
			return $records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci prvni URL param, ktery neni strankovani
	 * @return CitiesRecords
	 */
	protected function getURLParamFirst() {
		try {
			foreach ($this->getUrlParamsArray() as $param) {
				if (!$this->isUrlParamPaging($param)) {
					return $param;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>