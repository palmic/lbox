<?php
/**
 * processor loguje uzivatele pomoci email pole (email je pouzit jako nick)
 */
class ProcessorLoginEmail extends LBoxFormProcessor
{
	public function process() {
		try {
			$controls	= $this->form->getSentDataByControlName("email");
			$records	= new XTUsersRecords(array(
													"email" 	=> $this->form->getSentDataByControlName("email"),
													"password" => $this->form->getSentDataByControlName("password"),
			));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormProcessorLogin(	LBoxExceptionFormProcessorLogin::MSG_FORM_PROCESSOR_LOGIN_UNCORRECT,
															LBoxExceptionFormProcessorLogin::CODE_FORM_PROCESSOR_LOGIN_UNCORRECT);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>