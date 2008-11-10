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
			
			$form			 = new LBoxForm("registration", 			"post", "", "ok");
			$form			->setTemplateFileName("lbox_form_registration.html");
			
			$ctrlName		= new LBoxFormControlFill("name", 		"jméno", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_name"));
			$ctrlName		->setTemplateFileName("lbox_form_control_name_maybelline_registration.html");
			$ctrlName		->addFilter(new LBoxFormFilterName());
			$ctrlName	    ->setRequired();
			$ctrlSurname	= new LBoxFormControlFill("surname", 	"příjmení", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_surname"));
			$ctrlSurname	->setTemplateFileName("lbox_form_control_surname_maybelline_registration.html");
			$ctrlSurname	->addFilter(new LBoxFormFilterName());
			$ctrlSurname    ->setRequired();
			$ctrlEmail		= new LBoxFormControlFill("email", 		"email", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_email"));
			$ctrlEmail		->setTemplateFileName("lbox_form_control_email_maybelline_registration.html");
			$ctrlEmail	    ->setRequired();
			$ctrlEmail		->addValidator(new LBoxFormValidatorEmail());
			$ctrlEmail		->addValidator(new LBoxFormValidatorRegistrationEmailUnique());
			$ctrlPhone		= new LBoxFormControlFill("phone", 	"telefon", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_phone"));
			$ctrlPhone		->setTemplateFileName("lbox_form_control_phone_maybelline_registration.html");
			$ctrlPhone		->addFilter(new LBoxFormFilterEraseSpaces());
			$ctrlPhone		->addValidator(new LBoxFormValidatorPhone());
			$ctrlCity		= new LBoxFormControlFill("city", 	"město", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_city"));
			$ctrlCity		->setTemplateFileName("lbox_form_control_city_maybelline_registration.html");
			$ctrlCity		->addFilter(new LBoxFormFilterName());
			$ctrlCity	    ->setRequired();
			$ctrlBirthYear	= new LBoxFormControlChooseOne("birth_year", 	"rok narození", date("Y", time()-17*365*24*3600));
			$ctrlBirthYear	->setTemplateFileName("lbox_form_control_choose_birth_year_maybelline_registration.html");
			$ctrlBirthYear	->setRequired();
			$ctrlBirthYear	->addValidator(new LBoxFormValidatorBirthYear());
			foreach ($this->getBirthYearsValues() as $year) {
				$ctrlBirthYear->addOption(new LBoxFormControlOption($year, $year));
			}
			$ctrlSchool		= new LBoxFormControlChooseOne("ref_school", "škola");
			$ctrlSchool		->setTemplateFilename("lbox_form_control_choose_one_school.html");
			$ctrlSchool		->setRequired();
			$ctrlSchool		->addValidator(new LBoxFormValidatorSchoolExists());
			foreach ($this->getSchoolsCitiesRegions() as $school) {
				$ctrlSchool->addOption(new LBoxFormControlOption($school->ref_school, $school->region ." kraj > ". $school->city ." > ". $school->school));
			}
			$ctrlPassword1	= new LBoxFormControlPassword("password", 	"heslo", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$ctrlPassword1  ->setRequired();
			$ctrlPassword1  ->setTemplateFileName("lbox_form_control_password_registration_maybelline.html");
			$ctrlPassword2	= new LBoxFormControlPassword("password2", 	"heslo znovu", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$ctrlPassword2  ->setTemplateFileName("lbox_form_control_password_registration_maybelline.html");
			$ctrlPassword2  ->setRequired();
				$ctrlPasswords	= new LBoxFormControlMultiple("passwords");
				$ctrlPasswords ->setTemplateFileName("lbox_form_control_multi_passwords.html");
				$ctrlPasswords ->addControl($ctrlPassword1);
				$ctrlPasswords ->addControl($ctrlPassword2);
				$ctrlPasswords ->addValidator(new LBoxFormValidatorPasswords());
			$ctrlConditions		= new LBoxFormControlBool("conditions", 	"Souhlasím s poskytnutím osobních údajů a pravidly soutěže");
			$ctrlConditions  	->setTemplateFilename("lbox_form_control_contest_conditions.html");
			$ctrlConditions  	->setRequired();
			$ctrlProducts		= new LBoxFormControlChooseMore("products", "Jaké výrobky dekorativní kosmetiky používáte?");
			$ctrlProducts		->setTemplateFilename("lbox_form_control_choose_products_registration.html");
			foreach ($this->getProductsUsing() as $product) {
				$ctrlProducts->addOption(new LBoxFormControlOption($product->id, $product->name));
			}
			$form->addControl($ctrlName);
			$form->addControl($ctrlSurname);
			$form->addControl($ctrlEmail);
			$form->addControl($ctrlPhone);
			$form->addControl($ctrlCity);
			$form->addControl($ctrlBirthYear);
			//$form->addControl($ctrlSchool);
			$form->addControl($ctrlPasswords);
			$form->addControl($ctrlProducts);
			$form->addControl($ctrlConditions);
			
			$form->addProcessor(new ProcessorRegistrationEmail);
			$form->setAntiSpam();
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
	protected function getSchoolsCitiesRegions () {
		try {
			return new SchoolsCitiesRegionsRecords(false, array("region" => 1, "city" => 1, "school" => 1));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na vyber produktu "ktere pouzivas"
	 * @return ProductsRegistrationRecords
	 */
	protected function getProductsUsing () {
		try {
			return new ProductsRegistrationRecords(false, array("name" => 1));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na pole let do vyberu roku narozeni
	 * @return array
	 */
	protected function getBirthYearsValues () {
		try {
			$range	= 70;
			$start	= date("Y")-$range;
			$end	= date("Y");
			for ($i = $start; $i <= $end; $i++) {
				$out[$i] = $i;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>