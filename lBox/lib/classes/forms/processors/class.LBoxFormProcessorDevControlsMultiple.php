<?php
/**
 * dvelopment form processor
 */
class LBoxFormProcessorDevControlsMultiple extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "<fieldset>";
			echo "<legend>Form sent data</legend>";
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) {
					echo "<fieldset>";
					echo "<legend>Multiple control: ". $control->getName() ."</legend>";
					foreach ($control->getControls() as $subControl) {
						var_dump($subControl->getName() ." = ". $subControl->getValue());
					}
					echo "</fieldset>";
				}
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