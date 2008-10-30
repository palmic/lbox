<?php
class ProcessorAddParty extends LBoxFormProcessor
{
	public function process() {
		try {
			foreach ($this->form->getFormsData() as $step => $data) {
				echo "<fieldset>";
				echo "<legend>Step $step Form sent data</legend>";
				var_dump($data);
				echo "</fieldset>";
				flush();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>