<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class InquiriesSummariesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "InquiriesSummariesRecords";
	public static $tableName    	= "inquiries_summaries";
	public static $idColName    	= "ref_option";

	public static $boundedM1 = array("InquiriesRecords" 		=> "ref_inquiry");
	public static $dependingRecords	= array("");
	
	/**
	 * cache variables
	 */
	protected $inquiry;
	
	/**
	 * getter na anketu
	 * @return InquiriesRecord
	 * @throws LBoxException
	 */
	public function getInquiry() {
		try {
			if ($this->inquiry instanceof InquiriesRecord) {
				return $this->inquiry;
			}
			$this->inquiry = $this->getBoundedM1Instance("InquiriesRecords", $filter, $order, $limit, $whereAdd)->current();
			$this->inquiry->setOutputFilter(new OutputFilterInquiry($this->inquiry));
			return $this->inquiry;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>