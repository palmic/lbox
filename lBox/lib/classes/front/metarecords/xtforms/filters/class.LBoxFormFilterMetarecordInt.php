<?php
class LBoxFormFilterMetarecordInt extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return $control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>