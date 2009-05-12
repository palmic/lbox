<?php
class LBoxFormFilterUpperCase extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return strtoupper($control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>