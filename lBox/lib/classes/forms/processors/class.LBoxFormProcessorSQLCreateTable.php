<?php
/**
 * form processor vypisujici SQL create dotaz pro tabulku, ktera odpovida formularovym datum
 */
class LBoxFormProcessorSQLCreateTable extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "CREATE TABLE `startuprok`.`data_". $this->form->getName() ."` (<br />";
			echo "  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,<br />";
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				echo "  `". strtolower($control->getName()) ."` VARCHAR(255),<br />";
			}
			echo "  PRIMARY KEY (`id`)<br />";
			echo ");<br /><br />";
			flush();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>