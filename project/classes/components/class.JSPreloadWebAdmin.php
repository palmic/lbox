<?php
/**
 * main menu class
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-06-08
*/
class JSPreloadWebAdmin extends JSPreload
{
	/**
	 * zjistuje, jestli je na aktualne zobrazene strance povolen resize
	 * @return bool
	 */
	public function isAllowedResize() {
		try {
			$pagesAllowedResize		= LBoxConfigManagerProperties::getPropertyContentByName("metanodes_allow_resize_pages");
			$pagesAllowedResizeArr	= explode(" ", $pagesAllowedResize);
			foreach ($pagesAllowedResizeArr as $pageAllowedResize) {
				$pageAllowedResize	= trim($pageAllowedResize);
				switch (true) {
					case strtolower($pageAllowedResize) == "all":
					case $pageAllowedResize == LBoxFront::getPage()->id:
							return true;
						break;
				}
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o dalsi kontroly vztahujici se pouze na metanodes
	 * @return bool
	 */
	public function isToShow() {
		try {
			if (!parent::isToShow()) {
				return false;
			}

			$forbiddenXTRoles = explode(",", LBoxConfigManagerProperties::getPropertyContentByName("metanodes_forbidden_xtroles"));
			array_walk($forbiddenXTRoles, "trim");
			foreach ($forbiddenXTRoles as $forbiddenXTRole) {
				if (trim($forbiddenXTRole) == LBoxXTProject::getUserXTRoleRecord()->id) {
					return false;
				}
			}
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>