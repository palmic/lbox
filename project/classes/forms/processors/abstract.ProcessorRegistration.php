<?php
class ProcessorRegistration extends LBoxFormProcessor
{
	public function process() {
		try {
			$controls	= $this->form->getSentDataByControlName("email");
			$records	= new XTUsersRecords(array(
													"email" 	=> $this->form->getSentDataByControlName("email"),
													"password" => $this->form->getSentDataByControlName("password"),
			));
			if ($records->count() < 1) {
				throw new LBoxExceptionFormProcessor();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>