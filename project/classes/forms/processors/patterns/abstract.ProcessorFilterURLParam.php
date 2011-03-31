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
	protected $patternPropertyNames	= "";
	
	public function process() {
		try {
			if (count($this->patternPropertyNames) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_INSTANCE_VAR_ARRAY_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			if (count($this->patternPropertyNames) < 2) {
				$patternDefault		= LBoxConfigManagerProperties::gpcn(current($this->patternPropertyNames));
			}
			$patternPCRES	= array();
			foreach ($this->patternPropertyNames as $patternPropertyName) {
				$patternPCRES[]	= "/". str_ireplace("<url_param>", "([\w-_\.\/\\\ěščřžýáíéůúřťňĚďŠČŘŽÝÁÍÉŮÚŘŤĎŇ]+)", LBoxConfigManagerProperties::gpcn($patternPropertyName)) ."/";
			}
			$reloadParams	= array();
			foreach ($this->form->getControls() as $control) {
				if (count($this->patternPropertyNames) > 1 && !array_key_exists($control->getName(), $this->patternPropertyNames)) {
					continue;
				}
				$pattern	= "";
				$pattern	= $patternDefault ? $patternDefault : LBoxConfigManagerProperties::gpcn($this->patternPropertyNames[$control->getName()]);
				switch (true) {
					case ($control instanceof LBoxFormControlMultiple):
					case ($control instanceof LBoxFormControlSpamDefense):
							continue;
						break;
					default:
							if ($control->getValue() && strlen(trim($control->getValue())) > 0) {
								$reloadParams[]	= str_replace("<url_param>", $control->getValue(), $pattern);
							}
				}
			}
			if (count($reloadParams) > 0) {
				LBoxFront::reload(	LBoxUtil::getURLWithParams($reloadParams, LBoxUtil::getURLWithoutParamsByPattern($patternPCRES)));
			}
			else {
				LBoxFront::reload(	LBoxUtil::getURLWithoutParamsByPattern($patternPCRES));
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>