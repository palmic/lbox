<?php
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class Breadcrumb extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
		try {
			$pageCfg	= clone $this->page->config;
			$pageCfg->setOutputFilter(new OutputFilterPage($pageCfg));
			$iterator	= array();
			while ($pageCfg->hasParent()) {
				$iterator[]	= $pageCfg;
				$pageCfg = clone $pageCfg->getParent();
			}
			$iterator[]	= $pageCfg;

			// pridame homepage
			$hpCfg		= clone $this->getHomePageCfg();
			$hpCfg->setOutputFilter(new OutputFilterPage($hpCfg));
			$iterator[]	= $hpCfg;

			$iterator	= array_reverse($iterator);

			$TAL->iterator 		= $iterator;
			$TAL->rootLocation 	= $hpCfg->isCurrent;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>