<?php
/**
 * processor kontaktniho formulare ukladajici odeslana data do databaze
 */
class LBoxFormProcessorContactStoreToDB extends LBoxFormProcessor
{
	public function process() {
		try {
			$record	= new DataContactFormRecord();
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				if ($control instanceof LBoxFormControlSpamDefense) continue;
				$colName	= $control->getName();
				$record	->$colName	= strlen($control->getValue()) > 0 ? $control->getValue() : "<<NULL>>";
			}
			$record	->ref_access	= AccesRecord::getInstance()->id;
			$record	->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>