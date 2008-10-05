<?php
/**
 * zkontroluje, jestli hodnota odpovida pravdepodobnemu roku narozeni
 */
class LBoxFormValidatorSubmitedYetCookie extends LBoxFormValidator
{
	/**
	 * nazev cookie pro identifikaci
	 * - musi byt public static pro procesory, ktere tuto cookie plni!!!
	 * @var string
	 */
	protected static $cookieName	= "<formName>-submited";
	
	/**
	 * pocet dni pro trvanlivost cookie
	 * - musi byt public static pro procesory, ktere tuto cookie plni!!!
	 * @var int
	 */
	protected static $cookiePersistenceDays	= 9999;
	
	/**
	 * @var LBoxFormControl
	 */
	protected $control;
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$this->control	= $control;
			$cookieName	= self::$cookieName;
			$cookieName	= str_replace("<formName>", $control->getForm()->getName(), $cookieName);
			// session
			if (array_key_exists($cookieName, $_SESSION)) {
				if (strlen((string)$_SESSION[$cookieName]) > 0) {
					@setcookie($cookieName, (string)time(), time() + self::$cookiePersistenceDays * 24*60*60, "/");
					throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT,
															LBoxExceptionFormValidator::CODE_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT);
				}
			}
			// cookie
			if (array_key_exists($cookieName, $_COOKIE)) {
				if (strlen((string)$_COOKIE[$cookieName]) > 0) {
					$_SESSION[$cookieName]	= (string)time();
					throw new LBoxExceptionFormValidator(	LBoxExceptionFormValidator::MSG_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT,
															LBoxExceptionFormValidator::CODE_FORM_VALIDATION_FORM_SUBMITED_YET_CLIENT);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function commitProcessSuccess () {
		try {
			$cookieName	= self::$cookieName;
			$cookieName	= str_replace("<formName>", $this->control->getForm()->getName(), $cookieName);
			
			// ulozit zaznam do session a cookie
			@setcookie($cookieName, (string)time(), time() + self::$cookiePersistenceDays * 24*60*60, "/");
			$_SESSION[$cookieName]	= (string)time();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>