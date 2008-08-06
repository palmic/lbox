<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-29
*/
class AdminModel extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$controls["photo"]	= new LBoxFormControlFile("photo", "fotka");
				$controls["photo"]->setRequired();
				$controls["photo"]->setTemplateFilename("lbox_form_control_model_photo.html");
				$controls["photo"]->addValidator(new LBoxFormValidatorFileImage());
			$controls["name"]	= new LBoxFormControlFill("name", "jméno soutěžící", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_name"));
				$controls["name"]->setRequired();
				$controls["name"]->addFilter(new LBoxFormFilterName);
				$controls["name"]->setTemplateFilename("lbox_form_control_first_name.html");
			$controls["surname"]	= new LBoxFormControlFill("surname", "příjmení soutěžící", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_surname"));
				$controls["surname"]->setRequired();
				$controls["surname"]->addFilter(new LBoxFormFilterName);
				$controls["surname"]->setTemplateFilename("lbox_form_control_surname.html");
			$controls["email"]	= new LBoxFormControlFill("email", "e-mail soutěžící", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_email"));
				$controls["email"]->setRequired();
				$controls["email"]->addValidator(new LBoxFormValidatorEmail());
				$controls["email"]->addValidator(new LBoxFormValidatorModelEmailUnique());
				$controls["email"]->setTemplateFilename("lbox_form_control_model_email.html");
			$controls["ref_school"]	= new LBoxFormControlChooseOne("ref_school", "škola soutěžící");
				$controls["ref_school"]->setRequired();
				$controls["ref_school"]->setTemplateFilename("lbox_form_control_choose_one_select.html");
			foreach ($this->getSchoolsCitiesRegions() as $school) {
				$controls["ref_school"]->addOption(new LBoxFormControlOption($school->ref_school, $school->region ." kraj > ". $school->city ." > ". $school->school));
			}
			
			$form	= new LBoxForm("model");
			$form->setTemplateFileName("lbox_form_model.html");
			foreach($controls as $control) {
				$form->addControl($control);
			}
			$form->addProcessor(new ProcessorAddModel());
			
			$TAL->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na seznam skol s mesty a regiony
	 * @return SchoolsCitiesRegionsRecords
	 */
	protected function getSchoolsCitiesRegions() {
		try {
			return new SchoolsCitiesRegionsRecords(false, array("region" => 1, "city" => 1, "school" => 1));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>