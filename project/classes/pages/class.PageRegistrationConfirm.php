<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-07-28
*/
class PageRegistrationConfirm extends PageDefault
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug = true;
		try {
			parent::executePrepend($TAL);
			
			$succes		= true;
			$reasons	= array();
			try {
				$this->confirmUser();
			}
			catch (Exception $e) {
				$succes	= false;
				switch ($e->getCode()) {
					case LBoxExceptionPage::CODE_DISPLAY_ITEM_NOT_FOUND:
							$reasons["badhash"]	= true;
						break;
					case LBoxExceptionXT::CODE_USER_CONFIRMED:
							$reasons["alreadyconfirmed"]	= true;
						break;
				}
			}
			$contactMailAddresses	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("contact_form_addresses")->getContent();
			$contactMailAddress		= trim(current(explode(",", $contactMailAddresses)));
			$TAL->succes		= $succes;
			$TAL->reasons		= $reasons;
			$TAL->contactemail	= $contactMailAddress;
			$TAL->domain		= LBOX_REQUEST_URL_HOST;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function confirmUser() {
		try {
			$user	= $this->getUserByURL();
			if ($user->confirmed) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_USER_CONFIRMED, LBoxExceptionXT::CODE_USER_CONFIRMED);
			}
			$user->confirmed	= true;
			$user->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci usera podle hashe v URL
	 * @return XTUsersRecord
	 * @throws Exception
	 */
	protected function getUserByURL() {
		try {
			$userHash	= "";
			foreach ($this->getUrlParamsArray() as $param) {
				if (!$this->isUrlParamPaging($param)) {
					$userHash	= $param;
				}
			}
			if (strlen($userHash) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_DISPLAY_ITEM_NOT_FOUND, LBoxExceptionPage::CODE_DISPLAY_ITEM_NOT_FOUND);
			}
			$records	= new XTUsersRecords(array("hash" => $userHash));
			if ($records->count() < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_DISPLAY_ITEM_NOT_FOUND, LBoxExceptionPage::CODE_DISPLAY_ITEM_NOT_FOUND);
			}
			return $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>