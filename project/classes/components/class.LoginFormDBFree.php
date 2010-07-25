<?php
/**
 * login formular
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-10-03
*/
class LoginFormDBFree extends LoginForm
{
	protected function executeStart() {
		try {
			parent::executeStart();
			
			$this->validators[]	= new LBoxFormValidatorLoginDBFree;
			$this->processors[]	= new ProcessorLoginDBFree;
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
			return LBoxUtil::getURLWithParams(array("logout-dbfree"), LBoxUtil::getURLWithoutParams(array("logout-dbfree")));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>