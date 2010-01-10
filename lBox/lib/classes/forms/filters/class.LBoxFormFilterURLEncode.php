<?php
class LBoxFormFilterURLEncode extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return urlencode($control->getValue());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>