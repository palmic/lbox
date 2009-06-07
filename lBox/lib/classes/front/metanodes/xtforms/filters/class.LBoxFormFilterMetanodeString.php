<?php
class LBoxFormFilterMetanodeString extends LBoxFormFilterMetanode
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return (string)$control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>