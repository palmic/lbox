<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-24
*/
class PageRegistration extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$form			 = new LBoxForm("registration", 			"post");
			$form			->setTemplateFileName("lbox_form_registration.html");
			
			$ctrlEmail		= new LBoxFormControlFill("email", 		"email", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_email"));
			$ctrlEmail		->setTemplateFileName("lbox_form_control_email.html");
			$ctrlEmail	    ->setRequired();
			$ctrlEmail		->addValidator(new LBoxFormValidatorEmail());
			$ctrlEmail		->addValidator(new LBoxFormValidatorRegistrationEmailUnique());
			$ctrlPhone		= new LBoxFormControlFill("phone", 	"telefon", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_phone"));
			$ctrlPhone		->setTemplateFileName("lbox_form_control_phone.html");
			$ctrlPhone		->addFilter(new LBoxFormFilterEraseSpaces());
			$ctrlPhone		->addValidator(new LBoxFormValidatorPhone());
			$ctrlSchool		= new LBoxFormControlChooseOne("ref_school", "škola");
			$ctrlSchool		->setTemplateFilename("lbox_form_control_choose_one_school.html");
			$ctrlSchool		->setRequired();
			$ctrlSchool		->addValidator(new LBoxFormValidatorSchoolExists());
			foreach ($this->getSchoolsCitiesRegions() as $school) {
				$ctrlSchool->addOption(new LBoxFormControlOption($school->ref_school, $school->region ." kraj > ". $school->city ." > ". $school->school));
			}
			$ctrlPassword1	= new LBoxFormControlPassword("password", 	"heslo", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$ctrlPassword1  ->setRequired();
			$ctrlPassword2	= new LBoxFormControlPassword("password2", 	"heslo znovu", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$ctrlPassword2  ->setRequired();
				$ctrlPasswords	= new LBoxFormControlMultiple("passwords");
				$ctrlPasswords ->setTemplateFileName("lbox_form_control_multi_passwords.html");
				$ctrlPasswords ->addControl($ctrlPassword1);
				$ctrlPasswords ->addControl($ctrlPassword2);
				$ctrlPasswords ->addValidator(new LBoxFormValidatorPasswords());
			$ctrlConditions		= new LBoxFormControlBool("conditions", 	"souhlas s podmínkami soutěže");
			$ctrlConditions  	->setTemplateFilename("lbox_form_control_contest_conditions.html");
			$ctrlConditions  	->setRequired();
			
			$form->addControl($ctrlEmail);
			$form->addControl($ctrlPhone);
			$form->addControl($ctrlSchool);
			$form->addControl($ctrlPasswords);
			$form->addControl($ctrlConditions);
			
			$form->addProcessor(new ProcessorRegistrationEmail);
			$form->setAntiSpam(true);
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