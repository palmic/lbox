<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2010-03-07
 */
class LBoxMetaRecord extends LBox
{
	/**
	 * relevant record handler
	 * @var AbstractRecordLBox
	 */
	protected $record;
	
	/**
	 * @param $record
	 */
	public function __construct(AbstractRecordLBox $record) {
		try {
			$this->record	= $record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function __toString() {
		try {
//TODO
throw new Exception(__FILE__);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>