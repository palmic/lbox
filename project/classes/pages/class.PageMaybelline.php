<?
/**
 * root stranka vsech stranek projektu Prazska vodka s vyjimkou Stranky pro age check
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-08-06
*/
class PageMaybelline extends LBoxPage
{
	protected function executeStart() {
		try {
			$this->config->setOutputFilter(new OutputFilterPageMaybelline($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesCFGs["tourplan"]		= LBoxConfigManagerStructure::getPageById(7);
			$pagesCFGs["rules"]			= LBoxConfigManagerStructure::getPageById(3);
			$pagesCFGs["price_money"]	= LBoxConfigManagerStructure::getPageById(8);
			
			foreach($pagesCFGs as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPageMaybelline($pageCFG));
			}
			$TAL->pagesCFGs			= $pagesCFGs;
		}
		catch (Exception $e) {
			throw ($e);
		}
	}
}
?>