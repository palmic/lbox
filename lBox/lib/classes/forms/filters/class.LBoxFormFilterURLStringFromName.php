<?php
class LBoxFormFilterURLStringFromName extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return LBoxUtil::getURLByNameString(trim($control->getValue()));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>