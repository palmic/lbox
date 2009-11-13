<?php
class LBoxFormFilterDecimalPointDot extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return str_replace(",", ".", $control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>