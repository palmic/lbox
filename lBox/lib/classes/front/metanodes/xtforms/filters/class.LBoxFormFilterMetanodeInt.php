<?php
class LBoxFormFilterMetanodeInt extends LBoxFormFilterMetanode
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return (int)$control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>