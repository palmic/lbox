<?
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
			foreach ($this->getSchools() as $school) {
				$controls["school"]->addOption(new LBoxFormControlOption($school->ref_school, $school->school));
			}
			$form	= new LBoxForm("filter", "post");
			$form->setTemplateFileName("lbox_form_filter_list.html");
			$form->addProcessor(new $this->formProcessorClassName());
			foreach ($controls as $control) {
				$form->addControl($control);
			}
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na seznam mest podle (ne)zvoleneho regionu
	 * @return CitiesRecords
	 */
	protected function getSchools() {
		try {
			if ($this->cities instanceof CitiesRecords) {
				return $this->cities;
			}
			$filter	= false;
			if (strlen($this->getURLParamFirst()) > 0) {
				$records	= new SchoolsCitiesRegionsRecords(array("ref_region" => $this->getURLParamFirst()), array("school" => 1));
				if ($records->count() > 0) {
					return $records;
				}
			}
			return new SchoolsCitiesRegionsRecords(false, array("school" => 1));
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