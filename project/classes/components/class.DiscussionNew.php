<?php
/**
 * komponenta kompletne resici diskuzi
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class DiscussionNew extends LBoxComponent
{
	/**
	 * cache record
	 * @var DiscussionsRecord
	 */
	protected $record;

	protected function executeStart() {
		try {
			parent::executeStart();
			$outputFilter	= new OutputFilterDiscussion($this->config);
			$outputFilter	->setRecord($this->getRecord());
			$this->config->setOutputFilter($outputFilter);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executePrepend(PHPTAL $TAL) {
		try {
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na record
	 * @return DiscussionsRecord
	 */
	public function getRecord() {
		try {
			if ($this->record instanceof DiscussionsRecord) {
				return $this->record;
			}
			$pageID			= LBoxConfigManagerStructure::getInstance()->getPageByUrl(LBOX_REQUEST_URL_VIRTUAL)->id;
			$locationURL	= $this->getLocationUrlParam();
			$discussions = new DiscussionsRecords(array("pageId" => $pageID/*, "urlParam" => $locationURL*/), array("lft" => 1), array(0, 1));
			if ($discussions->count() < 1) {
				// pokud diskuze nebyla nalezena, vytvorime ji a vratime
				$discussion 			= new DiscussionsRecord();
				$discussion->pageId 	= $pageID;
				$discussion->urlParam 	= $locationURL;
				$discussion->store();
			}
			else {
				$discussion 			= $discussions->current();
			}
			$discussion->setOutputFilter(new OutputFilterDiscussionRecord($discussion));

			return $this->record = $discussion;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns URL param location
	 * @return string
	 */
	protected function getLocationUrlParam() {
		try {
			foreach ($this->getUrlParamsArray() as $param) {
				if (LBoxFront::isUrlParamPaging($param)) continue;
				return $param;
			}
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>