<?php
/**
 * dvelopment form processor
 */
class LBoxFormProcessorDev extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "<fieldset>";
			echo "<legend>Form sent data</legend>";
			var_dump($this->form->getSentData());
			echo "</fieldset>";
			flush();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>