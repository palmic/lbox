<?php
/**
* zaregistruje uzivatele pomoci emailu (email je pouzit jako nick)
*/
class ProcessorRegistrationEmail extends LBoxFormProcessor
{
	public function process() {
		try {
			$records	= new XTUsersRecords(array(	"email" 	=> $this->form->getControlByName("email")->getValue()));
			// pokud uz mame takoveho nepotvrzeneho uzivatele, prepiseme heslo na aktualni a email preposleme znovu na tu samou adresu
			if ($records->current() && $records->current()->confirmed < 1) {
				$record = $records->current();
				$record->password	= $this->form->getControlByName("password")->getValue();
				$record->store();
			}
			// jinak uzivatele vytvarime
			else {
				$record		= new XTUsersRecord();
				$record		->nick		= $this->form->getControlByName("email")->getValue();
				$record		->email		= $this->form->getControlByName("email")->getValue();
				$record		->password	= $this->form->getControlByName("password")->getValue();
				$record->store();
			}
			
			// confirm mail
			$mail	= new MailRegistrationConfirm($record);
			$mail->init();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>