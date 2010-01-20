<?php
/**
 * processor loguje uzivatele
 */
class ProcessorLogin extends LBoxFormProcessor
{
	public function process() {
		try {
			LBoxXTProject::login(	array_key_exists("email", $this->form->getControls()) ? $this->getNickByEmail($this->form->getControlByName("email")->getValue()) : $this->form->getControlByName("nick")->getValue(),
									$this->form->getControlByName("password")->getValue(),
									$remember	= true);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci nick podle emailu pro pripad 
	 * @return string
	 */
	protected function getNickByEmail() {
		try {
			$records	= new XTUsersRecords(array("email" => $this->form->getControlByName("email")->getValue()));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormProcessor("Record not found");
			}
			return $records->current()->nick;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>