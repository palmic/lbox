<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-18
*/
class PageProfile extends PageDefault
{
	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * vraci form pro editaci profilu
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			
			$controls["id"]					= new LBoxFormControlFillHidden("id", "", 	LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->id : "");
				$controls["id"]					->addFilter(new LBoxFormFilterTrim);
				$controls["id"]					->addValidator(new ValidatorProfileNotExists(LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->id : ""));
			$controls["nick"]				= new LBoxFormControlFill("nick", "přezdívka", 	LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->nick : "", 255);
				$controls["nick"]				->addFilter(new LBoxFormFilterTrim);
				$controls["nick"]				->addValidator(new ValidatorProfileNotExistsByNick(LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->id : "", 255));
				$controls["nick"]				->setTemplateFilename("lbox_form_control_nick.html");
				$controls["nick"]				->setRequired();
			$controls["email"]				= new LBoxFormControlFill("email", "e-mail", 	LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->email : "", 255);
				$controls["email"]				->addFilter(new LBoxFormFilterTrim);
				$controls["email"]				->addValidator(new ValidatorProfileNotExistsByEmail(LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->id : ""));
				$controls["email"]				->addValidator(new LBoxFormValidatorEmail);
				$controls["email"]				->setTemplateFilename("lbox_form_control_email.html");
				$controls["email"]				->setRequired();
			$subControls["passwords"]["password1"]	= new LBoxFormControlPassword("password1", "Heslo", "", 30);
				$subControls["passwords"]["password1"]	->setTemplateFilename("lbox_form_control_password.html");
				$subControls["passwords"]["password1"]	->setRequired();
			$subControls["passwords"]["password2"]	= new LBoxFormControlPassword("password2", "Heslo podruhé", "", 30);
				$subControls["passwords"]["password2"]	->setTemplateFilename("lbox_form_control_password.html");
				$subControls["passwords"]["password2"]	->setRequired();
			$controls["passwords"]			= new LBoxFormControlMultiple("passwords");
				foreach ($subControls["passwords"] as $subControl) {
					$controls["passwords"]	->addControl($subControl);
				}
				$controls["passwords"]	->addValidator(new LBoxFormValidatorPasswords);
				$controls["passwords"]	->setTemplateFilename("lbox_form_control_multi_passwords.html");
			$controls["name"]				= new LBoxFormControlFill("name", "jméno", 		LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->name : "", 255);
				$controls["name"]				->addFilter(new LBoxFormFilterTrim);
				$controls["name"]				->setTemplateFilename("lbox_form_control_name.html");
				$controls["name"]				->setRequired();
			$controls["surname"]			= new LBoxFormControlFill("surname", "příjmení", 	LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->surname : "", 255);
				$controls["surname"]				->addFilter(new LBoxFormFilterTrim);
				$controls["surname"]				->setTemplateFilename("lbox_form_control_surname.html");
				$controls["surname"]				->setRequired();
			$controls["phone"]				= new LBoxFormControlFill("phone", "telefon", 	LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->phone : "", 12);
				$controls["phone"]				->addFilter(new LBoxFormFilterTrim);
				$controls["phone"]				->addFilter(new LBoxFormFilterPhoneNumberCSWithPreselection);
				$controls["phone"]				->addValidator(new ValidatorProfileNotExistsByPhone(LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->id : ""));
				$controls["phone"]				->addValidator(new LBoxFormValidatorPhone);
				$controls["phone"]				->setTemplateFilename("lbox_form_control_phone.html");
				$controls["phone"]				->setRequired();
			$controls["street"]				= new LBoxFormControlFill("street", "Ulice", 		LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->street : "", 255);
				$controls["street"]				->addFilter(new LBoxFormFilterTrim);
				$controls["street"]				->setTemplateFilename("lbox_form_control_street.html");
				$controls["street"]				->setRequired();
			$controls["street_number"]		= new LBoxFormControlFill("street_number", "Číslo domu", LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->street_number : "", 11);
				$controls["street_number"]		->addFilter(new LBoxFormFilterTrim);
				$controls["street_number"]		->setTemplateFilename("lbox_form_control_streetnumber.html");
				$controls["street_number"]		->setRequired();
			$controls["city"]				= new LBoxFormControlFill("city", "město", 		LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->city : "", 255);
				$controls["city"]				->addFilter(new LBoxFormFilterTrim);
				$controls["city"]				->setTemplateFilename("lbox_form_control_city.html");
				$controls["city"]				->setRequired();
			$controls["zip"]				= new LBoxFormControlFill("zip", "PSČ", 		LBoxXTProject::isLogged() ? LBoxXTProject::getUserXTRecord()->zip : "", 5);
				$controls["zip"]				->addFilter(new LBoxFormFilterEraseSpaces);
				$controls["zip"]				->addValidator(new LBoxFormValidatorZIPCS);
				$controls["zip"]				->setTemplateFilename("lbox_form_control_zip.html");
				$controls["zip"]				->setRequired();
				
			$this->form	= new LBoxForm("profile", "post", LBoxXTProject::isLogged() ? "Úprava profilu" : "Vytvořit profil", "Uložit");
			$this->form	->addProcessor($processorSaveProfile	= new ProcessorSaveProfile);
			$this->form	->addProcessor(new ProcessorRegistrationSendConfirmMail($processorSaveProfile));
			foreach ($controls as $control) {
				$this->form->addControl($control);
			}
			return $this->form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>