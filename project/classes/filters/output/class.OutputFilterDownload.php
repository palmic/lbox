<?
/**
 * @author Michal Palma <palmic@email.cz>
 * @package LBox
 * @version 1.0

 * @since 2007-12-08
 */
class OutputFilterDownload extends LBoxOutputFilter
{
	/**
	 * cache
	 * @var RatingsDownloadsAVGRecord
	 */
	protected $ratingsDownloadsAVGRecord;

	public function prepare($name = "", $value = NULL) {
		switch ($name) {
			case "downloadUrl":
				$virtualPath	= LBoxConfigSystem::getInstance()->getParamByPath("download/output/path_virtual");
				return "$virtualPath:". $this->instance->getFile()->id;
				break;
			case "url":
				return $this->getURLDisplay();
				break;
			case "createdRSS":
					return gmdate("D, d M Y H:i:s", strtotime($this->instance->created)). " GMT";
					break;
			case "downloadedCount":
				return $this->instance->getFile()->getFileDownloaded()->count();
				break;
			case "ratedTotal":
				// pocet hlasu znamkovani downloadu
				if (!$this->getRatingAVGRecord()) {
					return 0;
				}
				return (int)$this->getRatingAVGRecord()->count;
				break;
			case "ratedAVG":
				// prumerna znamka downloadu
				if (!$this->getRatingAVGRecord()) {
					return 0;
				}
				return $this->getRatingAVGRecord()->rating;
				break;
			case "ratedByUser":
				// jestli user uz znamkoval (podle IP)
				$ratings = new RatingsDownloadsRecords(array("ref_download" => $this->instance->id, "ip" => LBOX_REQUEST_IP));
				return ($ratings->count() > 0);
				break;
			case "userMark":
				// jestli user uz znamkoval (podle IP)
				if (!($ratingRecord = $this->instance->getUserRating())) {
					return NULL;
				}
				else return $ratingRecord->rating;
				break;
			case "isAudio":
				switch (strtolower($this->instance->getFile()->ext)) {
					case "mp3":
						//case "ogg":
						//case "wma":
						//case "wav":
						//case "aif":
						//case "aiff":
							return true;
							break;
						default:
							return false;
				}
				break;
						default:
							return $value;
		}
	}

	/**
	 * cachovany getter na relevantni RatingsDownloadsAVGRecord
	 * @return RatingsDownloadsAVGRecord
	 * @throws Exception
	 */
	protected function getRatingAVGRecord() {
		try {
			if ($this->ratingsDownloadsAVGRecord instanceof RatingsDownloadsAVGRecord) {
				return $this->ratingsDownloadsAVGRecord;
			}
			$ratingAVGs = new RatingsDownloadsAVGRecords(array("ref_download" => $this->instance->id));
			if ($ratingAVGs->count() < 1) {
				return NULL;
			}
			return $this->ratingsDownloadsAVGRecord = $ratingAVGs->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vraci zobrazovaci URL downloadu
	 * @return string
	 * @throws Exception
	 */
	protected function getURLDisplay() {
		try {
			$pageDownloadsID	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("ref_page_downloads")->getContent();
			$pageDownloads		= LBoxConfigManagerStructure::getInstance()->getPageById($pageDownloadsID);

			$downloadType		= $this->instance->getDownloadType();
			
			$downloadsPaging	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("downloads_paging")->getContent();
			
			// zjistime si na kolikate strance strankovani tento download je
			$myCreated	= $this->instance->created;
			$whereAdd	= "created > '$myCreated'";
			$recordsNew	= new DownloadsRecords(false, false, false, $whereAdd);
			
			$out			= $pageDownloads->url;
			$page			= ceil($recordsNew->count()/$downloadsPaging);
			if ($page > 1) {				
				$out .= ":". LBoxUtil::getPagingURLString($page);
			}
			return "$out#download-". $this->instance->id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>
