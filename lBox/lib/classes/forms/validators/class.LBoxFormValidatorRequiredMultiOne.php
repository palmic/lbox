<?php
/**
 * kontroluje jestli alespon jeden subcontrol v multicontrolu je vyplnen
 */
class LBoxFormValidatorRequiredMultiOne extends LBoxFormValidatorRequired
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			foreach ($control->getControls() as $control) {
				try {
					parent::validate($control);
				}
				catch (LBoxExceptionFormValidator $e) {
					// jina vyjimka nez empty control
					if ($e->getCode() != LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_EMPTY) {
						throw $e;
					}
					else {
						// tento neprosel - dalsi control
						continue;
					}
				}
				// parent usoudil, ze control je vyplnen - OK jeden staci
				return;
			}
			// pokud dojde az sem (nenasel jedinej vyplnenej) vyhodi posledni required vyjimku (validace neprosla)
			throw $e;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
