<?php
class LBoxFormFilterLowerCase extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return strtolower($control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>