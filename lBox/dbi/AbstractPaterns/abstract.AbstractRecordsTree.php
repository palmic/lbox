<?php

/**
 * adds Tree functionality to Records class
 * @author Michal Palma <palmic at email dot cz>
 * @date 2007-09-16
 */
abstract class AbstractRecordsTree extends AbstractRecords
{
	/**
	 * if true, getDbResult() method does not check whereAdd and filter
	 * @var bool
	 */
	protected $forceTreeMode = false;

	/**
	 * setter for $forceTreeMode - be carefull, resets result 
	 * @param bool $mode
	 */
	public function setForceTreeMode($mode = false) {
		$this->dbResult = NULL;
		$this->records = array();
		$this->forceTreeMode = $mode;
	}
	
	protected function getDbResult() {
		try {
			do {
				$itemType 	= $this->getClassVar("itemType");
				$treeColNames = eval("return $itemType::\$treeColNames;");
				$pidColName	= $treeColNames[2];
				if (!$this->isTree())			 	break;
				if (!$this->forceTreeMode) {
					if (strlen($this->whereAdd) > 0) 	break;
					if (is_array($this->filter))		break;
				}
				$glue = strlen($this->whereAdd) > 0 ? " AND" : "WHERE";
				$this->whereAdd .= "$glue ($pidColName=0 OR $pidColName IS NULL)";
			} while (false);
			return parent::getDbResult();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>