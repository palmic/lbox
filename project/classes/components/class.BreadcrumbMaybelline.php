<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2008-08-21
*/
class BreadcrumbMaybelline extends Breadcrumb
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pageCfg	= clone $this->page->config;
			$this->setPageOutputFilter($pageCfg);
			$iterator	= array();
			while ($pageCfg->hasParent()) {
				$iterator[]	= $pageCfg;
				$pageCfg = clone $pageCfg->getParent();
				$this->setPageOutputFilter($pageCfg);
			}
			$iterator[]	= $pageCfg;

			// pridame homepage
			$hpCfg		= clone $this->getHomePageCfg();
			$this->setPageOutputFilter($hpCfg);
			$iterator[]	= $hpCfg;

			$iterator	= array_reverse($iterator);

			$TAL->iterator 		= $iterator;
			$TAL->rootLocation 	= $hpCfg->isCurrent;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * nastavi strance odpovidajici outputfilter
	 * @param LBoxPage $page
	 * @throws LBoxException
	 */
	protected function setPageOutputFilter(LBoxConfigItemStructure $page) {
		try {
			return;
			switch ($page->class) {
				case "ListModels":
						$page->setOutputFilter(new OutputFilterPageListModels($page));
					break;
				default:
						$page->setOutputFilter(new OutputFilterPage($page));
					break;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>