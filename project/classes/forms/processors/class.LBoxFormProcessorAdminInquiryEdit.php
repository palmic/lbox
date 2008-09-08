<?php
class LBoxFormProcessorAdminInquiryEdit extends LBoxFormProcessor
{
	public function process() {
		try {
			if (array_key_exists("id", $this->form->getControls())) {
				$record	= new InquiriesRecord($this->form->getControlByName("id")->getValue());
			}
			else {
				$record					= new InquiriesRecord();
				$record	->question		= $this->form->getControlByName("question")->getValue();
			}
			$record		->is_active		= (int)$this->form->getControlByName("is_active")->isSelected();
			$record		->store();

			if (!array_key_exists("id", $this->form->getControls())) {
				for ($i = 1; $i <= LBoxConfigManagerProperties::getPropertyContentByName("inquiries_answers_count"); $i++) {
					if (strlen($this->form->getControlByName("answer-$i")->getValue()) < 1) continue;
					$optionsRecords[$i]					= new InquiriesOptionsRecord();
					$optionsRecords[$i]	->ref_inquiry	= $record->id;
					$optionsRecords[$i]	->answer		= $this->form->getControlByName("answer-$i")->getValue();
					$optionsRecords[$i]	->store();
				}
			}

			LBoxFront::reload(LBoxFront::getPage()->url .":". $record->id);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>