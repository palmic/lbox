<?php
class LBoxFormFilterTimeIntervalFlexible extends LBoxFormFilter
{
	/**
	 * detekcni regularni pattern
	 * @var string
	 */
	protected $regTimeFlexible	= "/^(\d+)(\s*)(h|m|s)$/";
	
	public function filter(LBoxFormControl $control = NULL) {
		try {
			if (preg_match($this->regTimeFlexible, $control->getValue(), $matches)) {
				switch ($matches[3]) {
					case "s": return $matches[1] / 3600; break;
					case "m": return $matches[1] / 60; break;
					case "h": return $matches[1]; break;
				}
			}
			return $control->getValue();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>