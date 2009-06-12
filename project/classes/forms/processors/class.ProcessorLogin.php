<?php
/**
 * processor loguje uzivatele
 */
class ProcessorLogin extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxXTProject::login(	$this->form->getControlByName("nick")->getValue(),
							$this->form->getControlByName("password")->getValue(),
							$remember	= true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>