<?php
/**
 * enables direct load choose control from database records collection 
 */
abstract class LBoxFormControlChooseFromRecords extends LBoxFormControlChoose
{
	/**
	 * options records
	 * @var AbstractRecords
	 */
	protected $records;
	
	/**
	 * colname used for control value atributtes
	 * @var string
	 */
	protected $colnameValue = "";
	
	/**
	 * colname used for control labels
	 * @var string
	 */
	protected $colnameLabel = "";
	
	/**
	 * @param string name
	 * @param string label
	 * @param string default defaultni hodnota ovladaciho prvku
	 * @param AbstractRecords $records
	 * @param string $colnameValue
	 * @param string $colnameLabel
	 * @throws LBoxException
	 */
	public function __construct($name = "",  $label = "",  $default = "", AbstractRecords $records, $colnameValue = "", $colnameLabel = "") {
		try {
			parent::__construct($name,  $label,  $default);
			if (!$records) {
				throw new LBoxExceptionFormControl("\$records: ". LBoxExceptionFormControl::MSG_PARAM_INSTANCE_CONCRETE, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			if (strlen($colnameValue) < 1) {
				throw new LBoxExceptionFormControl("\$colnameValue: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			if (strlen($colnameLabel) < 1) {
				throw new LBoxExceptionFormControl("\$colnameLabel: ". LBoxExceptionFormControl::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormControl::CODE_BAD_PARAM);
			}
			$this->records		= $records;
			$this->colnameValue	= $colnameValue;
			$this->colnameLabel	= $colnameLabel;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretezuje o nacitani options z records
	 * @return array
	 */
	public function getOptions() {
		try {
			if (count($this->options) < 1) {
				$colnameValue	= $this->colnameValue;
				$colnameLabel	= $this->colnameLabel;
				foreach($this->records as $record) {
					$this->addOption(new LBoxFormControlOption($record->$colnameValue, $record->$colnameLabel));
				}
			}
			return parent::getOptions();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}