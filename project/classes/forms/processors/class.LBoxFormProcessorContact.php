<?php
/**
 * processor kontaktniho formulare
 */
class LBoxFormProcessorContact extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "<fieldset>";
			echo "<legend>Form sent data</legend>";
			foreach ($this->form->getControls() as $control) {
				var_dump($control->getName() ." = ". $control->getValue());
			}
			echo "</fieldset>";
			flush();
			
			//$this->sendByMailToUs();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * odesila mail s obsahem kontaktniho formu na maily zadane v properties jako cilove (contact_form_addresses)
	 */
	/*protected function sendByMailToUs() {
		try {
			$mail	= new MailContactForm($this->form);
			$mail->init();
		}
		catch (Exception $e) {
			throw $e;
		}
	}*/
}
?>