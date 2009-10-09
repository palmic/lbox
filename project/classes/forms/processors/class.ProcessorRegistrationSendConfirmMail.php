<?php
/**
 * procesor odesilajici email s potvrzenim registrace uzivatele
 */
class ProcessorRegistrationSendConfirmMail extends LBoxFormProcessor
{
	/**
	 * cache var
	 * @var ProcessorSaveProfile
	 */
	protected $processorSaveProfile;

	public function __construct(ProcessorSaveProfile $processorSaveProfile) {
		try {
			$this->processorSaveProfile	= $processorSaveProfile;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function process() {
		try {
			$data					= array();
			$data["from"]			= LBoxConfigManagerProperties::getPropertyContentByName("registration_confirm_mail_from");
			$data["to"]				= $this->form->getControlByName("email")->getValue();
			$data["subject"]		= "Potvrzeni registrace";
			$data["page_confirm"]	= LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::getPropertyContentByName("ref_page_registration_confirm"));
			$data["processor_save"]	= $this->processorSaveProfile;

			$mail				= new MailProductsRegistrationConfirm($data);
			$mail->init();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>