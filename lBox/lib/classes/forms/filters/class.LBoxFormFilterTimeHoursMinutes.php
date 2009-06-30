<?php
class LBoxFormFilterTimeHoursMinutes extends LBoxFormFilter
{
	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->getValue()) < 1) {
				return $control->getValue();
			}
			// prepinac moznosti formatu casu
			switch (true) {
				//1205 => 12:05
				case ereg("^([[:digit:]]{2})([[:digit:]]{2})$", $control->getValue(), $regs):
						return $regs[1] .":". $regs[2];
					break;
				//125 => 12:05
				case ereg("^([[:digit:]]{2})([[:digit:]]{1})$", $control->getValue(), $regs):
						return $regs[1] .":0". $regs[2];
					break;
				default: 
					//other..
					$valueParts	= explode(":", $control->getValue());
					foreach ($valueParts as $k => $valuePart) {
						$valueParts[$k]	= (is_numeric($valuePart) && strlen($valuePart) < 2) ? "0$valuePart" : $valuePart;
					}
					if (count($valueParts) < 2) {
						$valueParts[]	= "00";
					}
					return implode(":", $valueParts);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>