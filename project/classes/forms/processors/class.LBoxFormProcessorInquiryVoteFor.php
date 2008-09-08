<?php
class LBoxFormProcessorInquiryVoteFor extends LBoxFormProcessor
{
	public function process() {
		try {
			$recordOption	= new InquiriesOptionsRecord($this->form->getControlByName("ref_option")->getValue());
			if ($recordOption->getInquiry()->didUserVotedFor) {
				return;
			}
			
			$record	= new InquiriesResponsesRecord();
			$record	->ref_option	=	$this->form->getControlByName("ref_option")->getValue();
			$record	->ref_access	=	AccesRecord::getInstance()->id;
			$record	->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>