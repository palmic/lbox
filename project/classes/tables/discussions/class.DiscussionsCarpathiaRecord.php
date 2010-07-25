<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2010-01-14
*/
class DiscussionsCarpathiaRecord extends DiscussionsRecord
{
	/**
	 * cache var
	 * @var DiscussionsCarpathiaRecord
	 */
	protected $lastPost;
	
	/**
	 * getter na posledni post
	 * @return DiscussionsCarpathiaRecord
	 * @throws Exception
	 */
	public function getLastPost() {
		try {
			if ($this->lastPost instanceof DiscussionsCarpathiaRecord) {
				return $this->lastPost;
			}
			if (!$this->hasChildren()) {
				return NULL;
			}
//DbControl::$debug = true;
			
			$records				= $this->getChildren(array("created" => 0));
			if ($records->count() < 1) {
				return NULL;
			}
//DbControl::$debug = false;
			$records				->setOutputFilterItemsClass("OutputFilterDiscussion");
			return $this->lastPost	= $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>