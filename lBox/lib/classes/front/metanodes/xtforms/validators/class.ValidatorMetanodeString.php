<?php
class ValidatorMetanodeString extends ValidatorMetanode
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			parent::validate($control);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>