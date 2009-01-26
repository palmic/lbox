<?php
/**
 * recaptcha control
 * @link http://www.recaptcha.net
 */
class LBoxFormControlReCaptcha extends LBoxFormControlFill
{
	/**
	 * @var string
	 */
	protected $filenameTemplate = "lbox_form_control_recaptcha.html";
	
	/**
	 * maxlength html attribute value
	 * @var int
	 */
	protected $lengthMax	= NULL;

	/**
	 * explicit recaptcha public key
	 * @var string
	 */
	protected $keyPublic = "";
	
	/**
	 * recaptcha validator instance for direct explicit private key set possibility
	 * @var LBoxFormValidatorRecaptcha
	 */
	protected $validatorRecaptcha;
	
	/**
	 * instances incrementing value
	 * @var int
	 */
	protected static $i = 1;
	
	/**
	 * doplneno o parametr length
	 */
	public function __construct() {
		try {
			parent::__construct($name = "captcha_". self::$i,  $label = "",  $default = "", $lengthMax = NULL);
			$this->validatorRecaptcha	= new LBoxFormValidatorRecaptcha;
			$this->addValidator($this->validatorRecaptcha);
			self::$i++;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na recaptcha HTML
	 * @return string
	 */
	public function recaptchaGetHTML() {
		try {
			return recaptcha_get_html(	$this->getKeyPublic(),
										$error = NULL,
										$useSSL = (strtolower(LBOX_REQUEST_URL_SCHEME) == "https")
										);
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
			$this->validatorRecaptcha->setKeyPrivate($key);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * explicitni setter public key
	 * @param string $key
	 */
	public function setKeyPublic($key = "") {
		try {
			if (strlen($key) < 1) {
				throw new LBoxExceptionFormValidator(LBoxExceptionFormValidator::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormValidator::CODE_BAD_PARAM);
			}
			$this->keyPublic	= $key;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * private recaptcha key getter
	 * @return string
	 */
	protected function getKeyPublic() {
		try {
			return strlen($this->keyPublic) > 0 ? $this->keyPublic : LBoxConfigManagerProperties::getPropertyContentByName("recaptcha_key_public");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
// load recaptcha plugin
new recaptcha;
?>