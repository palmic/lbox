<?php
/**
 * form processor vypisujici SQL create dotaz pro tabulku, ktera odpovida formularovym datum
 */
class LBoxFormProcessorSQLCreateTable extends LBoxFormProcessor
{
	public function process() {
		try {
			echo "CREATE TABLE `data_". $this->form->getName() ."` (<br />";
			echo "  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,<br />";
			foreach ($this->form->getControls() as $control) {
				switch (true) {
					case $control instanceof LBoxFormControlMultiple: continue; break;
					case $control->getName() == "id": continue; break;
					case ($control instanceof LBoxFormControlChooseMore || $control instanceof LBoxFormControlChooseMoreFromRecords):
						foreach ($control->getValue() as $value) {
							echo "  `". strtolower($control->getName()) ."_". strtolower(LBoxUtil::getURLByNameString($value)) ."` integer(1) DEFAULT 0,<br />";
						}
					break;
					default:
						echo "  `". strtolower($control->getName()) ."` VARCHAR(255),<br />";
				}
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