<?php
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox techhouse.cz
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @since 2007-12-15
 */
class DiscussionPostsListRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "DiscussionPostsListRecords";
	public static $tableName    	= "discussionPostsList";
	public static $idColName    	= "id";

	public static $dependingRecords	= array(
											"DiscussionsRecords",
											"DiscussionsPostsRecords",
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
			throw new LBoxException("This is read-only view!");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>