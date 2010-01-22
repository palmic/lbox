<?php
/**
 * dvelopment form processor
 */
class LBoxFormProcessorDevControlsSimple extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "<fieldset>";
			echo "<legend>Form sent data</legend>";
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				var_dump($control->getName() ." = ". $control->getValue());
			}
			echo "</fieldset>";
			flush();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>