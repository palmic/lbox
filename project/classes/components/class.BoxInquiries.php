<?
/**
 * breadcrumb navigation class
* @author Michal Palma <palmic@email.cz>
* @package LBox softub.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2008-09-07
*/
class BoxInquiries extends LBoxComponent
{
	protected function executePrepend(PHPTAL $TAL) {
//DbControl::$debug	= true;
		try {
			$TAL->inquiry	= $this->getInquiry();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci posledni aktivni anketu
	 * @return InquiriesRecord
	 */
	protected function getInquiry() {
		try {
			$records	= new InquiriesRecords(array("is_active" => 1), array("created" => 0), array(0, 1));
			$records->setOutputFilterItemsClass("OutputFilterInquiry");
			return $records->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>