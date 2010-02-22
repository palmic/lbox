<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-12-08
*/
class DiscussionsPostsRecord extends DiscussionsRecord
{
	public static $dependingRecords	= array(
											"DiscussionsRecords",
	);
	
	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			$this->params["type"] 	= "post";
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}	
}
?>