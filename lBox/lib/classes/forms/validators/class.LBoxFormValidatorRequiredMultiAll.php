<?php
/**
 * kontroluje jestli jsou vsechny subcontrols v multicontrolu vyplneny
 */
class LBoxFormValidatorRequiredMultiAll extends LBoxFormValidatorRequired
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			foreach ($control->getControls() as $control) {
				parent::validate($control);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
