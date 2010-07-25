<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @since 2009-10-09
*/
class PageProfileConfirm extends PageDefault
{
	protected function executeStart() {
		try {
			parent::executeStart();
			if (LBoxXTProject::isLogged()) {
				LBoxFront::reloadHomePage();
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		//DbControl::$debug = true;
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na records uzivatelskych profilu loadnutych podle hashe z URL
	 * @return XTUsers
	 */
	public function getXTUsersByURLHash() {
		try {
			if (strlen($this->getURLHash()) < 1) {
				LBoxFront::reloadHomePage();
			}
			return new XTUsersRecords(array("hash" => $this->getURLHash()));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	protected function getURLHash() {
		try {
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				return $param;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>