<?
/**
 * login formular
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0
 * @since 2008-08-15
 */
class LoginFormMaybelline extends LoginFormEmail
{
	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			$this->form->setTemplateFileName("lbox_form_maybelline_login.html");
			$this->form->getControlByName("email")->setTemplateFileName("lbox_form_control_email_maybelline.html");
			$this->form->getControlByName("password")->setTemplateFileName("lbox_form_control_password_maybelline.html");
			$this->form->getControlByName("form")->setTemplateFileName("lbox_form_control_multi_login_maybelline.html");
			$TAL->pageAdmin	= LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::getPropertyContentByName("ref_page_xt_admin"));

			$this->form->setAntiSpam(false);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>