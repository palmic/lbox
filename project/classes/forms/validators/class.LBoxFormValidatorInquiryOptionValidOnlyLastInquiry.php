<?php
class LBoxFormValidatorInquiryOptionValidOnlyLastInquiry extends  LBoxFormValidatorInquiryOptionValid
{
	/**
	 * doplnuje rodice o kontrolu, jestli jde o volbu z posledni aktivni ankety
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			parent::validate($control);
			
			$record				= new InquiriesOptionsRecord($control->getValue());
			$recordsInquiries	= new InquiriesRecords(array("is_active" => 1), array("created"	=> 0), array(0, 1));
			if ($recordsInquiries->current()->id	!= $record->getInquiry()->id) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID,
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>