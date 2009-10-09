<?php
/**
 * procesor ukladajici profil uzivatele
 */
class ProcessorSaveProfile extends LBoxFormProcessor
{
	/**
	 * cache var
	 * @var XTUsersRecord
	 */
	protected $record;

	public function process() {
		try {
			$this->record	= new XTUsersRecord(strlen($this->form->getControlByName("id")->getValue()) > 0 ? $this->form->getControlByName("id")->getValue() : NULL);
			foreach ($this->form->getControls() as $control) {
				if ($control instanceof LBoxFormControlMultiple) continue;
				if ($control->getName() == "id") continue;
				if ($control->getName() == "password1") continue;
				if ($control->getName() == "password2") {
					$this->record->password	= $control->getValue();
					continue;
				}
				$ctrlName 			= $control->getName();
				$this->record->$ctrlName	= $control->getValue();
			}
			$this->record->confirmed	= 0;
			$this->record->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na vytvoreny profil
	 * @return XTUsersRecord
	 */
	public function getRecord() {
		try {
			return $this->record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>