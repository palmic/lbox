<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class InquiriesResponsesRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "InquiriesResponsesRecords";
	public static $tableName    	= "inquiries_responses";
	public static $idColName    	= "id";

	public static $boundedM1 = array(	"InquiriesOptionsRecords" 		=> "ref_option",
										"AccesRecords" 					=> "ref_access"
	);
	public static $dependingRecords	= array("InquiriesOptionsResponsesRecords");
	
	/**
	 * cache variables
	 */
	protected $option;
	protected $access;
	
	/**
	 * getter na svuj relevantni option
	 * @return InquiriesOptionsRecord
	 * @throws LBoxException
	 */
	public function getOption() {
		try {
			if ($this->option instanceof InquiriesOptionsRecord) {
				return $this->option;
			}
			$this->option = $this->getBounded1MInstance("InquiriesOptionsRecords", $filter, $order, $limit, $whereAdd)->current();
			$this->option->setOutputFilter(new OutputFilterInquiryOption($this->option));
			return $this->option;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>