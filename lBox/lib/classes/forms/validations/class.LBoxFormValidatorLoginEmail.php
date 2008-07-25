<?php
/**
 * validator kontrolujici login pomoci emailu a hesla (nepouziva nick)
 *
 */
class LBoxFormValidatorLoginEmail extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli se hesla shoduji
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$controls	= $control->getControls();
var_dump($controls);
die(__FILE__);
//TODO
			$records	= new XTUsersRecords(array(
													"email" 	=> $controls["email"],
													"password" => $this->form->getSentDataByControlName("password"),
			));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormProcessorLogin(	LBoxExceptionFormProcessorLogin::MSG_FORM_PROCESSOR_LOGIN_UNCORRECT,
															LBoxExceptionFormProcessorLogin::CODE_FORM_PROCESSOR_LOGIN_UNCORRECT);
			}
			
			
			
			
			
			
			
			
			/* z validator passwords 
			 $controls	= $control->getControls();
			// shodnost hesel
			if (first($controls)->getValue() != end($controls)->getValue()) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_PASSWORDS_NOTSAME,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_PASSWORDS_NOTSAME);
			}
			// pokryjeme moznost zapomenuti nastaveni "required" u jednotlivych controls
			if (strlen(current($controls)->getValue()) < 1) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_EMPTY,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_EMPTY);
			}*/
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
