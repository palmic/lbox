<?php

/**
 * adds Tree functionality to Records class
 * @author Michal Palma <michal.palma@gmail.com>
 * @date 2007-09-16
 */
abstract class AbstractRecordsTree extends AbstractRecords
{
	protected function getWhere() {
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
			return parent::getWhere();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>