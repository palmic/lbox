<?php
/**
 * breadcrumb navigation class
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @since 2007-03-12
*/
class XTTray extends LBoxComponent
{
	/**
	 * nazev URL param pro logout
	 * @var string
	 */
	protected $urlParamNameLogout	= "";
	
	protected function executeStart() {
		try {
			parent::executeStart();
			$this->urlParamNameLogout	= LBoxFront::getURLParamNameLogout();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {

			$urls["logout"]		= LBOX_REQUEST_URL_VIRTUAL .":". $this->urlParamNameLogout;
			$pagesCFG["admin"]	= LBoxConfigManagerStructure::getInstance()->getPageById(
									LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_xt_admin")->getContent()
									);
			// nastaveni filtru
			foreach ($pagesCFG as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPage($pageCFG));
			}
			$loginGroup					= strlen($this->page->xt) > 0 ? $this->page->xt : 1;
			$TAL->urls			= $urls;
			$TAL->pagesCFG		= $pagesCFG;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * zjisti, jestli uzivatel neklikl na logout
	 * @return bool
	 */
	protected function isToLogout() {
		try {
			return (is_numeric(array_search($this->getURLParamNameLogout(), $this->getUrlParamsArray())));
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * odloguje uzivatele
	 * @return bool
	 */
	protected function logout() {
		try {
			$loginGroup					= strlen($this->page->xt) > 0 ? $this->page->xt : 1;
			LBoxXT::logout($loginGroup);
			$this->reload(LBOX_REQUEST_URL_VIRTUAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na nazev URL parametru pro logout
	 * @return string
	 */
	public function getURLParamNameLogout() {
		try {
			return $this->urlParamNameLogout;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getPageRootAdmin() {
	    try {
	      $node  = LBoxConfigManagerStructure::getInstance()
	              ->getPageById(LBoxConfigManagerProperties::getPropertyContentByName("ref_page_xt_admin"));
	      $node->setOutputFilter(new OutputFilterPage($node));
	      return $node;
	    }
	    catch(Exception $e) {
	      throw $e;
		}
	} 
}
?>