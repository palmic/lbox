<?php
/**
* zaregistruje uzivatele pomoci emailu (email je pouzit jako nick)
*/
class ProcessorRegistrationEmail extends LBoxFormProcessor
{
	public function process() {
		try {
			$records	= new XTUsersRecords(array(
													"email" 	=> $this->form->getSentDataByControlName("email"),
													"password" => $this->form->getSentDataByControlName("password"),
			));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormProcessorRegistration(	LBoxExceptionFormProcessorRegistration::MSG_FORM_PROCESSOR_REGISTRATION_FAILED,
																	LBoxExceptionFormProcessorRegistration::CODE_FORM_PROCESSOR_LOGIN_UNCORRECT);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>