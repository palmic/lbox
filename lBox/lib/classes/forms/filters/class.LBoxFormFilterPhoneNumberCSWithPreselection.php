<?php
class LBoxFormFilterPhoneNumberCSWithPreselection extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			$out	= $control->getValue();
			if (is_numeric($out) && strlen($out) == 9) {
				$out	= "420$out";
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>