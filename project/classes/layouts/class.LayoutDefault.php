<?
/**
 * Default component class for layouts
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class LayoutDefault extends LBoxComponentLayout
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pagesCFGs["registrate"]		= LBoxConfigManagerStructure::getPageById(300);
			
			foreach($pagesCFGs as $pageCFG) {
				$pageCFG->setOutputFilter(new OutputFilterPage($pageCFG));
			}
			$TAL->pagesCFGs			= $pagesCFGs;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>