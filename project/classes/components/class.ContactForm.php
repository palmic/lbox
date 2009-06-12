<?
/**
 * formular contact us
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0
 * @since 2008-05-09
 */
class ContactForm extends LBoxComponent
{
	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* getter na form
	* @return LBoxForm
	*/
	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			$ctrls["name"]		= new LBoxFormControlFill("name", 	"jméno", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_name"));
				$ctrls["name"]->setTemplateFileName("lbox_form_control_first_name.html");
				$ctrls["name"]->setRequired();
			$ctrls["surname"]	= new LBoxFormControlFill("surname", "příjmení", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_surname"));
				$ctrls["surname"]->setTemplateFileName("lbox_form_control_surname.html");
				$ctrls["surname"]->setRequired();
			$ctrls["company"]	= new LBoxFormControlFill("company", "firma", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_company"));
			$ctrls["phone"]		= new LBoxFormControlFill("phone", "telefon");
				$ctrls["phone"]->setTemplateFileName("lbox_form_control_phone.html");
				$ctrls["phone"]->addFilter(new LBoxFormFilterEraseSpaces);
				$ctrls["phone"]->addFilter(new LBoxFormFilterPhoneNumberCS);
				$ctrls["phone"]->addValidator(new LBoxFormValidatorPhone);
			$ctrls["email"]		= new LBoxFormControlFill("email", "email", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_email"));
				$ctrls["email"]->setTemplateFileName("lbox_form_control_email.html");
				$ctrls["email"]->setRequired();
				$ctrls["email"]->addValidator(new LBoxFormValidatorEmail);
			$ctrls["message"]	= new LBoxFormControlFill("message", "zpráva");
				$ctrls["message"]->setTemplateFileName("lbox_form_control_message.html");
				$ctrls["message"]->setRequired();

			$ctrlGroup	= new LBoxFormControlMultiple("contact-info");
			foreach ($ctrls as $name	=> $ctrl) {
				if ($name == "message") continue;
				$ctrlGroup->addControl($ctrl);
			}
			$form	= new LBoxForm("contact");
			$form	->setTemplateFileName("lbox_form_contact.html");
			$form	->addProcessor(new LBoxFormProcessorContact);
			$form	->addControl($ctrlGroup);
			$form	->addControl($ctrls["message"]);
			
			return $this->form	= $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>