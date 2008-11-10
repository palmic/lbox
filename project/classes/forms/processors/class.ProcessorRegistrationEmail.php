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
				$record		->name			= $this->form->getControlByName("name")->getValue();
				$record		->surname		= $this->form->getControlByName("surname")->getValue();
				$record		->nick			= $this->form->getControlByName("email")->getValue();
				$record		->email			= $this->form->getControlByName("email")->getValue();
				$record		->phone			= $this->form->getControlByName("phone")->getValue();
				$record		->city			= $this->form->getControlByName("city")->getValue();
				//$record		->ref_school	= $this->form->getControlByName("ref_school")->getValue();
				$record		->password		= $this->form->getControlByName("password")->getValue();
				$record->store();

				// zvolene produkty
				foreach ($this->form->getControlByName("products")->getValue() as $value) {
					$recordProduct								= new ProductsRegistrationXXTUsersRecord();
					$recordProduct->ref_xt_user					= $record->id;
					$recordProduct->ref_product_registration	= $value;
					$recordProduct->store();
				}
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