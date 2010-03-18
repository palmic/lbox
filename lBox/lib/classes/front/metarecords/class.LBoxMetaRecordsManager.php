<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2010-03-07
 */
class LBoxMetaRecordsManager extends LBox
{
	/**
	 * metarecords cache
	 * @var array
	 */
	protected static $metaRecords	= array(array());

	/**
	 * forms cache
	 * @var array
	 */
	protected static $forms	= array("types" => array(), "records" => array(array()));
	
	/**
	 * LBoxMetaRecord getter
	 * @param AbstractRecordLBox $record
	 * @return LBoxMetaRecord
	 */
	public static function getMetaRecord(AbstractRecordLBox $record) {
		try {
			$type			= get_class($record);
			$idColName  	= eval("return $type::\$idColName;");
			if (array_key_exists($type, self::$metaRecords) && array_key_exists($record->$idColName, self::$metaRecords[$type])) {
				return self::$metaRecords[$type][$idColName];
			}
			return self::$metaRecords[$type][$idColName] = new LBoxMetaRecord($record);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {}
}
?>