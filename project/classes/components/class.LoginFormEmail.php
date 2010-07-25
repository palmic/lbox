<?php
/**
 * login formular
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-09-18
*/
class LoginFormEmail extends LoginForm
{
	protected function executeStart() {
		try {
			parent::executeStart();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na form
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if (count($this->processors) < 1) {
				$this->processors[]	= new ProcessorLogin;
			}
			if (count($this->validators) < 1) {
				$this->validators[]	= new LBoxFormValidatorLogin();
			}
			$controlEmail		= new LBoxFormControlFill("email", "e-mail", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_email"));
			$controlEmail		->setTemplateFileName("lbox_form_control_email.html");
			$controlEmail		->setRequired();
			$controlPassword	= new LBoxFormControlPassword("password", "heslo", "", LBoxConfigManagerProperties::getPropertyContentByName("form_max_length_password"));
			$controlPassword	->setRequired();

			$controlsLogin			= new LBoxFormControlMultiple("form", "controls");
			$controlsLogin->setTemplateFileName("lbox_form_control_multi_login.html");
			$controlsLogin->addControl($controlEmail);
			$controlsLogin->addControl($controlPassword);
			foreach ($this->validators as $validator) {
				$controlsLogin->addValidator($validator);
			}
			
			$form					= new LBoxForm("login", "post", "Přihlášení uživatele", "přihlásit");
			$form->setTemplateFileName("lbox_form_login.html");
			$form->addControl($controlsLogin);
			$form->setAntiSpam(true);
			foreach ($this->processors as $processor) {
				$form->addProcessor($processor);
			}
			return $this->form	= $form;			
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vrati kompletni logout URL
	 * @return string
	 * @throws Exception
	 */
	public function getURLLogout() {
		try {
			return LBoxUtil::getURLWithParams(array(LBoxFront::getURLParamNameLogout()), LBoxUtil::getURLWithoutParams(array(LBoxFront::getURLParamNameLogout())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>