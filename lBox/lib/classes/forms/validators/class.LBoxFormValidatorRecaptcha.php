<?php
/**
 * zkontroluje recaptchu pres recaptcha API
 */
class LBoxFormValidatorRecaptcha extends LBoxFormValidator
{
	/**
	 * recaptcha response object
	 * @var ReCaptchaResponse
	 */
	protected $resp;
	
	/**
	 * explicit recaptcha private key
	 * @var string
	 */
	protected $keyPrivate = "";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			//if (strlen($control->getValue()) > 0)
			if (!$this->isValid($control)) {
				throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_CONTROL_NOT_VALID ." [". $this->resp->error ."]",
														LBoxExceptionFormValidator::CODE_FORM_VALIDATION_CONTROL_NOT_VALID);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * kontrola validity
	 * @param LBoxFormControl $control
	 * @return bool
	 */
	protected function isValid(LBoxFormControl $control) {
		try {
			new recaptcha;
			$dataPost	= LBoxFront::getDataPost();
			$this->resp	= recaptcha_check_answer ($this->getKeyPrivate(),
			                              		  LBOX_REQUEST_IP,
			                              		  $dataPost["recaptcha_challenge_field"],
			                              		  $dataPost["recaptcha_response_field"]);
			return $this->resp->is_valid;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * explicitni setter private key
	 * @param string $key
	 */
	public function setKeyPrivate($key = "") {
		try {
			if (strlen($key) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$this->keyPrivate	= $key;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * private recaptcha key getter
	 * @return string
	 */
	protected function getKeyPrivate() {
		try {
			return strlen($this->keyPrivate) > 0 ? $this->keyPrivate : LBoxConfigManagerProperties::getPropertyContentByName("recaptcha_key_private");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>