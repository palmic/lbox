<?php
class LBoxFormFilterName extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return ucfirst(strtolower($control->getValue()));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>