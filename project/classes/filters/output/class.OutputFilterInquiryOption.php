<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class OutputFilterInquiryOption extends LBoxOutputFilter
{
	/**
	 * cache variable
	 * @var LBoxForm
	 */
	protected $form;
	
	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "getForm":
				return $this->getForm();
			default:
				return $value;
		}
	}

	/**
	 * vraci instanci formu pro hlasovani
	 * @return LBoxForm
	 */
	protected function getForm() {
		try {
			$control	= new LBoxFormControlFillHidden("ref_option", "", $this->getID());
			$control	->addValidator(new LBoxFormValidatorInquiryOptionValidOnlyLastInquiry);
			$form		= new LBoxForm("inquiry-option-". $this->getID(), "post", "", "hlasovat");
			$form		->setTemplateFileName("lbox_form_inquiries_votefor.html");
			$form		->addControl($control);
			$form		->addProcessor(new LBoxFormProcessorInquiryVoteFor);
			$form		->setAntiSpam(true);
			return $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci id bez ohledu na typ recordu, ktery obaluje
	 * @return int
	 */
	protected function getID() {
		try {
			switch (get_class($this->instance)) {
				case "InquiriesOptionsRecord":
						return $this->instance->id;
					break;
				default:
					return $this->instance->ref_option;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>