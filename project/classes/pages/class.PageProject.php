<?php
/**
 * root stranka vsech stranek projektu Prazska vodka s vyjimkou Stranky pro age check
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-08-06
*/
class PageProject extends LBoxPage
{
	public function executeInit() {
		try {
			parent::executeInit();
			if ($this->showConnivance()) {
				FirePHP::getInstance(true)->setEnabled(true);
				FB::setEnabled(true);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterPage($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {
			foreach((array)$pagesCFGs as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPage($pageCFG));
			}
			$TAL->pagesCFGs			= $pagesCFGs;
		}
		catch (Exception $e) {
			throw ($e);
		}
	}


	/**
	 * vraci, jestli je prepnuto do schvalovaciho modu
	 * @return bool
	 */
	public function showConnivance() {
		try {
			switch (true) {
				case LBOX_REQUEST_IP == LBOX_REQUEST_IP_MY:
				case is_numeric(strpos(strtolower(LBOX_REQUEST_URL_HOST), "localhost")):
				case is_numeric(strpos(strtolower(LBOX_REQUEST_URL_HOST), "beta")):
						return true;
					break;
				default:
					return false;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>