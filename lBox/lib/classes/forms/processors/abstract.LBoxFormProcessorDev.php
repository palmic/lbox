<?php
/**
 * dvelopment form processor vypisujici vsechny controls
 */
class LBoxFormProcessorDev extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "<fieldset>";
			echo "<legend><font color='0000ff'><strong>". $this->form->getName() ."</strong></font> Form sent data</legend>";
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) {
					echo "<fieldset>";
					echo "<legend>Multiple control: <font color='cc0000'><strong>". $control->getName() ."</strong></font></legend>";
					foreach ($control->getControls() as $subControl) {
						$this->printHTMLControlSimple($subControl);
					}
					echo "</fieldset>";
				}
				else {
					if (!$control->isSubControl()) {
						$this->printHTMLControlSimple($control);
					}
				}
			}
			echo "</fieldset>";
			flush();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vypisuje HTML kod pro vypis simple controlu
	 * @param LBoxFormControl $control
	 * @return string
	 */
	protected function printHTMLControlSimple(LBoxFormControl $control) {
		try {
			if ($control instanceof LBoxFormControlChooseMore) {
				echo "<fieldset>";
				echo "<legend><font color='#cc0000'><strong>". $control->getName() ." Choose more values choosed:</strong></font></legend>";
				foreach ($control->getValue() as $value => $choosed) {
					var_dump("$choosed");
				}
				echo "</fieldset>";
			}
			else {
				var_dump($control->getName() ." = ". $control->getValue());
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>