<?php
/**
 * processor loguje uzivatele pomoci email pole (email je pouzit jako nick)
 */
class ProcessorLoginEmail extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxXT::login(	$this->form->getControlByName("email")->getValue(),
							$this->form->getControlByName("password")->getValue(),
							$remember	= true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>