<?php
class LBoxFormFilterQuestion extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			$value	 = $control->getValue();
			$value	.= (substr($value, strlen($value)-1) == "?") ? "" : "?";
			return ucfirst($value);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>