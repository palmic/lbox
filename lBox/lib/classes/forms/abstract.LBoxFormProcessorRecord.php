<?php
/**
 * class LBoxFormProcessorRecord
 */
abstract class LBoxFormProcessorRecord extends LBoxFormProcessor
{
	/**
	 * @var protected
	 */
	protected $record;

	/**
	 *
	 * @param AbstractRecordLBox record 
	 */
	public function addRecord($record = NULL) {
		try {
			$this->record	= $record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	//TODO
}
?>