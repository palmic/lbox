<?php
class LBoxFormValidatorInquiriesAnswers extends LBoxFormValidator
{
	/**
	 * kontroluje, jestli je vyplnena alespon jedna odpoved
	 */
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$controls	= $control->getControls();
			foreach ($control->getControls() as $subControl) {
				// hodnota je vyplnena
				if (strlen($subControl->getValue()) > 0) {
					return;
				}
			}
			// hodnota nikde nebyla vyplnena
			throw new LBoxExceptionFormValidator($control->getName() .": ". LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_EMPTY,
												LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_EMPTY);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
