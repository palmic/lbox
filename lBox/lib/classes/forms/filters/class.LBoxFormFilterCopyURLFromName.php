<?php
/**
 * copy URL value from name control
 */
class LBoxFormFilterCopyURLFromName extends LBoxFormFilterCopyValue
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			return LBoxUtil::getURLByNameString(trim(parent::filter($control)));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>