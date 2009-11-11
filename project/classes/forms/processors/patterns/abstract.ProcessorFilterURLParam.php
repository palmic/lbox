<?php
/**
 * reloaduje URL s URL parametrem podle formulare
 */
abstract class ProcessorFilterURLParam extends LBoxFormProcessor
{
	/**
	 * nazev property se vzorem pro URL param
	 * @var string
	 */
	protected $patternPropertyName	= "";
	
	public function process() {
		try {
			if (strlen($this->patternPropertyName) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			$pattern		= LBoxConfigManagerProperties::gpcn($this->patternPropertyName);
			$patternPCRE	= str_ireplace("<url_param>", "(\w+)", $pattern);
			$killMe			= false;
			foreach ($this->form->getControls() as $control) {
				switch (true) {
					case ($control instanceof LBoxFormControlMultiple):
					case ($control instanceof LBoxFormControlSpamDefense):
							continue;
						break;
					default:
							if (strlen(trim($control->getValue())) > 0) {
								LBoxFront::reload(	LBoxUtil::getURLWithParams(array(str_replace("<url_param>", $control->getValue(), $pattern)),
													LBoxUtil::getURLWithoutParamsByPattern("/$patternPCRE/")));
							}
							else {
								LBoxFront::reload(	LBoxUtil::getURLWithoutParamsByPattern("/$patternPCRE/"));
							}
						$killMe			= true;
				}
				if ($killMe) break;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>