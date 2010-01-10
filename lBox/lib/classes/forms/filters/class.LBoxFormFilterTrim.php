<?php
class LBoxFormFilterTrim extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return trim($control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>