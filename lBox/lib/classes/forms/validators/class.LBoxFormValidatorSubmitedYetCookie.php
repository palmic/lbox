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
	public static $cookieName	= "<formName>-submited";
	
	public function validate(LBoxFormControl $control = NULL) {
		try {
			$cookieName	= self::$cookieName;
			$cookieName	= str_replace("<formName>", $control->getForm()->getName(), $cookieName);
			// session
			if (array_key_exists($cookieName, $_SESSION)) {
				if (strlen((string)$_SESSION[$cookieName]) > 0) {
					@setcookie($cookieName, (string)time(), time() + $this->cookiePersistenceDays * 24*60*60, "/");
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
}
?>