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
				
				if (!$this->isTree()) {
					break;
				}
				if (array_key_exists($pidColName, (array)$this->filter)) {
					break;
				}
				
				if (!$this->whereAdd instanceof QueryBuilderWhere) {
					$this->whereAdd	= new QueryBuilderWhere();
				}
				$this->whereAdd		->addConditionColumn($pidColName, 0);
				$this->whereAdd		->addConditionColumn($pidColName, "<<NULL>>", 0, 1);
				
				//$this->whereAdd .= "AND ($pidColName=0 OR $pidColName IS NULL)";
			} while (false);
			return parent::getDbResult();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>