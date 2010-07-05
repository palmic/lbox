<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @since 2010-07-05
 */
class OutputFilterDiscussion extends OutputFilterComponent
{
	/**
	 * pattern na URL param reply to
	 * @var string
	 */
	protected $patternURLParamReplyTo	= "^replyto\-([\w\d]+)$";
	
	/**
	 * cache var
	 * @var DiscussionsRecord
	 */
	protected $record;

	public function prepare($name = "", $value = NULL) {
		if (!$this->record instanceof AbstractRecord) {
			throw new LBoxExceptionOutputFilter(LBoxExceptionOutputFilter::MSG_INSTANCE_VAR_INSTANCE_CONCRETE_NOTNULL, LBoxExceptionOutputFilter::CODE_BAD_INSTANCE_VAR);
		}
		switch ($name) {
			case "rssURL":
					if ($this->instance->rss) {
						$rssPageUrl	= LBoxConfigManagerStructure::getInstance()->getPageById($this->instance->rss)->url;
						$pageId		= $this->instance->page->id;
						return "$rssPageUrl:$pageId/". LBoxFront::getLocationUrlParam();
					}
				break;
			case "getForm":
					$parentId	= NULL;
					foreach (LBoxFront::getUrlParamsArray() as $param) {
						if (preg_match("/". $this->patternURLParamReplyTo ."/", $param, $matches)) {
							$parentId	= $matches[1];
						}
					}
					if ($parentId) {
						$record	= new DiscussionsRecord($parentId);
						if (!$record->isInDatabase()) {
							LBoxFront::reload(LBoxUtil::getURLWithoutParamsByPattern(array("/". $this->patternURLParamReplyTo ."/")));
						}
						$record	->setOutputFilter(new OutputFilterDiscussionRecord($record));
						return $record->getForm();
					}
					else {
						return $this->record->getForm();
					}
				break;
			default:
				return $value;
		}
	}

	/**
	 * setter pro discussion record
	 * @param DiscussionsRecord $record
	 */
	public function setRecord(DiscussionsRecord $record) {
		try {
			$this->record	= $record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>