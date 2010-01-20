<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class InquiriesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "InquiriesRecords";
	public static $tableName    	= "inquiries";
	public static $idColName    	= "id";

	public static $bounded1M = array(	"InquiriesOptionsRecords" 	=> "ref_inquiry",
										"InquiriesSummariesRecords"	=> "ref_inquiry");
	public static $dependingRecords	= array("InquiriesSummariesRecords", "InquiriesResponsesRecords");
	
	/**
	 * cache variables
	 */
	protected $options;
	protected $summaries;
	
	public function store() {
		try {
			if (!$this->isInDatabase()) {
				$this->params["created"] = date("Y-m-d H:i:s");
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na options
	 * @return InquiriesOptionsRecords
	 * @throws LBoxException
	 */
	public function getOptions() {
		try {
			if ($this->options instanceof InquiriesOptionsRecords) {
				return $this->options;
			}
			$order["id"]	= 1;
			$this->options 	= $this->getBounded1MInstance("InquiriesOptionsRecords", $filter, $order, $limit, $whereAdd);
			$this->options->setOutputFilterItemsClass("OutputFilterInquiryOption");
			return $this->options;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na summaries
	 * @return InquiriesSummariesRecords
	 * @throws LBoxException
	 */
	public function getOptionsResponses() {
		try {
			if ($this->summaries instanceof InquiriesSummariesRecords) {
				return $this->summaries;
			}
			$this->summaries 	= $this->getBounded1MInstance("InquiriesSummariesRecords", $filter, $order, $limit, $whereAdd);
			$this->summaries->setOutputFilterItemsClass("OutputFilterInquiryOption");
			return $this->summaries;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>