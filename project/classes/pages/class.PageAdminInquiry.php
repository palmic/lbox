<?
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class PageAdminInquiry extends LBoxPage
{
	/**
	 * cache variables
	 */
	protected $inquiry;
	protected $inquiryOptions;
	
	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;
	
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na form editace ankety
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			$controls["is_active"]	= new LBoxFormControlBool("is_active", "Aktivní", $this->getInquiry() ? $this->getInquiry()->is_active : 0);
			$controls["question"]	= new LBoxFormControlFill("question", "Otázka", ($this->getInquiry() ? $this->getInquiry()->question : ""));
			$controls["question"]->setTemplateFileName("lbox_form_control_admin_inquiry_question.html");
			$controls["question"]->addFilter(new LBoxFormFilterTrim);
			$controls["question"]->setRequired();
			$controls["question"]->setDisabled((bool)$this->getInquiry());
			
			$inquiryOptions	= $this->getInquiryOptions();
			for ($i = 1; $i <= LBoxConfigManagerProperties::getPropertyContentByName("inquiries_answers_count"); $i++) {
				$controlsAnswers["answer-$i"]	= new LBoxFormControlFill("answer-$i", "Odpověď $i", $inquiryOptions[$i]);
				$controlsAnswers["answer-$i"]	->addFilter(new LBoxFormFilterTrim);
				$controlsAnswers["answer-$i"]	->setDisabled((bool)$this->getInquiry());
			}
			$controls["answers"]	= new LBoxFormControlMultiple("answers", NULL);
			foreach ($controlsAnswers as $controlAnswer) {
				$controls["answers"]->addControl($controlAnswer);
			}
			$controls["answers"]->setTemplateFileName("lbox_form_control_admin_inquiries_answers.html");
			$controls["answers"]->addValidator(new LBoxFormValidatorInquiriesAnswers());
			
			if ($this->getInquiry()) {
				$controls["id"]	= new LBoxFormControlFillHidden("id", "", $this->getInquiry()->id);
			}

			$form		= new LBoxForm("edit-inquiry", "post", "editace ankety", "ulož");
			foreach ($controls as $control) {
				$form->addControl($control);
			}
			$form->addProcessor(new LBoxFormProcessorAdminInquiryEdit);
			return $this->form = $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci options existujici ankety
	 * @return array
	 */
	public function getInquiryOptions() {
		try {
			if (is_array($this->inquiryOptions)) {
				return $this->inquiryOptions;
			}
			$this->inquiryOptions	= array();
			$i						= 1;
			if ($this->getInquiry()) {
				foreach ($this->getInquiry()->getOptions() as $option) {
					$this->inquiryOptions[$i]	= $option->answer;
					$i++;
				}
			}
			return $this->inquiryOptions;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci anketu pokud je v URL
	 * @return InquiriesRecord
	 * @throws LBoxException
	 */
	public function getInquiry() {
		try {
			if (strlen($this->getInquiryUrlParam()) < 1) {
				return;
			}
			if ($this->inquiry instanceof InquiriesRecord) {
				return $this->inquiry;
			}
			$records = new InquiriesRecords(array("id" => $this->getInquiryUrlParam()));
			foreach ($records as $record) {
				return $this->inquiry = $record;
				break;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns URL param ankety
	 * @return string
	 */
	protected function getInquiryUrlParam() {
		try {
			foreach ($this->getUrlParamsArray() as $param) {
				if ($this->isUrlParamPaging($param)) continue;
				return $param;
			}
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>