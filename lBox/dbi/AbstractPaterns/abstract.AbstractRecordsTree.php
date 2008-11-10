<?php

/**
 * adds Tree functionality to Records class
 * @author Michal Palma <palmic at email dot cz>
 * @date 2007-09-16
 */
abstract class AbstractRecordsTree extends AbstractRecords
{
	/**
	 * tree mode - true = tree, false = flat
	 * @var bool
	 */
	protected $treeMode	= true;
	
	/**
	 * 
	 * @param $mode
	 * @return unknown_type
	 */
	public function setTreeMode($mode = true) {
		$this->treeMode	= (bool)$mode;
	}
	
	protected function getWhere() {
		try {
			do {
				$itemType 	= $this->getClassVar("itemType");
				$treeColNames = eval("return $itemType::\$treeColNames;");
				$pidColName	= $treeColNames[2];
				
				if (!$this->isTree() || !$this->treeMode) {
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
			return parent::getWhere();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>