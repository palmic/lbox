<?php
/**
 * processor loguje uzivatele bez pouziti databaze
 */
class ProcessorLoginDBFree extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxXTDBFree::login(	$this->form->getControlByName("nick")->getValue(),
							$this->form->getControlByName("password")->getValue(),
							$remember	= true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>