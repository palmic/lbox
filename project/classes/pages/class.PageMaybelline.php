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
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesCFGs["contacts"]		= LBoxConfigManagerStructure::getPageById(6);
			
			foreach($pagesCFGs as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPage($pageCFG));
			}
		
			$TAL->pagesCFGs			= $pagesCFGs;
		}
		catch (Exception $e) {
			throw ($e);
		}
	}
}
?>