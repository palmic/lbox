<?php
class QueryBuilderPlatformMysql extends QueryBuilderPlatform
{
	public function getInsert(/*string*/ $table, /*array*/ $values = array()) {
		if (strlen($table) < 1) {
			throw new DbControlException("Ilegal parameter table. Must be NOT-NULL string.");
		}
		if (count($values) < 1) {
			throw new DbControlException("Ilegal parameter values. Must be NOT-NULL string.");
		}
		$columnsString	= "";
		$valuesString	= "";
		foreach( $values as $index => $value) {
			$columnsString	.= strlen($columnsString) > 0 ? ", " : "";
			$columnsString	.= reset(self::getQuotesColumnName()) . $index . end(self::getQuotesColumnName());
			$valuesString	.= strlen($valuesString) > 0 ? ", " : "";
			$valuesString	.= $this->getValueWrapped($value);
		}
		$table	= reset($this->getQuotesTableName()) . $table . end($this->getQuotesTableName());
		$out	= "INSERT INTO $table ($columnsString) VALUES ($valuesString)";
		return $out;
	}

	public function getDelete(/*string*/ $table, QueryBuilderWhere $where = NULL) {
		if ($where instanceof QueryBuilderWhere) $whereString	= $this->getWhereStringByObject($where);
		$table			= reset($this->getQuotesTableName()) . $table . end($this->getQuotesTableName());
		$out			= "DELETE FROM $table \n$whereString";
		return $out;
	}
	
	public function getUpdate(/*string*/ $table, /*array*/ $values, QueryBuilderWhere $where = NULL) {
		try {
			if (strlen($table) < 1) {
				throw new DbControlException("Ilegal parameter table. Must be NOT-NULL string.");
			}
			if (count($values) < 1) {
				throw new DbControlException("Ilegal parameter values. Must be NOT-NULL array.");
			}
			$updateString	= "";
			foreach($values as $index => $value) {
				$updateString	.= strlen($updateString) > 0 ? ", " : "";
				$updateString 	.= reset(self::getQuotesColumnName()) ."$index". end(self::getQuotesColumnName()) . "=". $this->getValueWrapped($value);
			}
			if ($where instanceof QueryBuilderWhere) $whereString	= $this->getWhereStringByObject($where);
			$table	= reset($this->getQuotesTableName()) . $table . end($this->getQuotesTableName());
			$out	= "UPDATE $table SET \n$updateString \n$whereString";
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getSelectColumns($table, $what = array(), QueryBuilderWhere $where = NULL, $limit	= array(), $groupBy = array(), $orderBy = array()) {
		try {
			if (!is_array($what)) {
				throw new DbControlException("Ilegal parameter what. Must be NOT-NULL string.");
			}
			// whatString
			$whatString	= "";
			if (count($what) < 1) {
				$whatString	= "*";
			}
			else {
				foreach ($what as $columnName) {
					$whatString	.= strlen($whatString) > 0 ? ", " : "";
					$whatString	.= reset(self::getQuotesColumnName()) ."$columnName". end(self::getQuotesColumnName()) ."";
				}
			}
			return $this->getSelect($table, $whatString, $where, $limit, $groupBy, $orderBy);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getSelectCount($table, QueryBuilderWhere $where = NULL, $limit	= array(), $groupBy = array(), $orderBy = array()) {
		try {
			return $this->getSelect($table, "COUNT(*) AS count", $where, $limit, $groupBy, $orderBy);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getSelectMaxColumns($table, $what = array(), QueryBuilderWhere $where = NULL, $groupBy = array(), $orderBy = array()) {
		try {
			if (!is_array($what)) {
				throw new DbControlException("Ilegal parameter what. Must be NOT-NULL string.");
			}
			if (count($what) < 1) {
				throw new DbControlException("You have to specify columns for get max values.");
			}
			// whatString
			$whatString	= "";
			foreach ($what as $columnName) {
				$whatString	.= strlen($whatString) > 0 ? ", " : "";
				$whatString	.= "MAX(". reset(self::getQuotesColumnName()) ."$columnName". end(self::getQuotesColumnName()) .") AS $columnName";
			}
			return $this->getSelect($table, $whatString, $where, $groupBy, $orderBy);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function getSelect($table, $what = "*", QueryBuilderWhere $where = NULL, $limit = array(), $groupBy = array(), $orderBy = array()) {
		if (strlen($table) < 1) {
			throw new DbControlException("Ilegal parameter table. Must be NOT-NULL string.");
		}
		if (strlen($what) < 1) {
			throw new DbControlException("Ilegal parameter what. Must be NOT-NULL string.");
		}
		if (!is_array($limit)) {
			throw new DbControlException("Ilegal parameter limit. Must be array.");
		}
		if (!is_array($groupBy)) {
			throw new DbControlException("Ilegal parameter groupBy. Must be array.");
		}
		if (!is_array($orderBy)) {
			throw new DbControlException("Ilegal parameter groupBy. Must be array.");
		}
		$whereString	= $where instanceof QueryBuilderWhere ? $this->getWhereStringByObject($where) : "";
		
		// groupByString
		$groupByString	= "";
		if (count($groupBy) < 1) {
			$groupByString	= "";
		}
		else {
			foreach ($groupBy as $columnName) {
				$groupByString	.= strlen($groupByString) > 0 ? ", " : "";
				$groupByString	.= reset(self::getQuotesColumnName()) ."$columnName". end(self::getQuotesColumnName()) ."";
			}
			$groupByString	= "GROUP BY $groupByString";
		}
		// orderByString
		$orderByString	= $this->getOrderByStringByArray($orderBy);
		// limitString
		$limitString	= "";
		switch (count($limit)) {
			case 0:
					$limitString	= "";
				break;
			case 1:
					$limitString	= "LIMIT ". reset($limit);
				break;
			case 2:
					$limitString	= "LIMIT ". reset($limit) .", ". end($limit);
				break;
			default:
					throw new DbControlException("Ilegal parameter limit!");
		}
		$out	= "SELECT $what FROM ". reset(self::getQuotesTableName()) ."$table". end(self::getQuotesTableName()) .
					" $whereString $groupByString $orderByString $limitString";
		return $out;
	}

	/**
	 * returns where clause as string generated from QueryBuilderWhere instance
	 * @param QueryBuilderWhere $where
	 * @param bool $isSub - whether is it generated for sub where or not (true only for recursive call, so do not care)
	 * @return string
	 */
	protected function getWhereStringByObject (QueryBuilderWhere $where, $isSub	= false) {
		// whereString
		$whereString	= "";
		if (count($where->getConditions()) < 1) {
			NULL;
		}
		else {
			foreach ($where->getConditions() as $condition) {
				$columnName		= $condition["column"];
				$glue			= $condition["glue"] > 0 ? "OR" : "AND";
				switch ($condition["comparison"]) {
								case  -3 	: $comparisonValue	= strtoupper($this->getValueWrapped($condition["value"])) == "NULL"
																		? " IS NOT NULL"
																		: " != ". $this->getValueWrapped($condition["value"]) .""; break;
								case -2		: $comparisonValue	=" < ". $this->getValueWrapped($condition["value"]) .""; break;
								case -1		: $comparisonValue	=" <= ". $this->getValueWrapped($condition["value"]) .""; break;
								case  0 	: $comparisonValue	= strtoupper($this->getValueWrapped($condition["value"])) == "NULL"
																		? " IS NULL"
																		: " = ". $this->getValueWrapped($condition["value"]) .""; break;
								case  1		: $comparisonValue	=" >= ". $this->getValueWrapped($condition["value"]) .""; break;
								case  2		: $comparisonValue	=" > ". $this->getValueWrapped($condition["value"]) .""; break;
								case  3		: $comparisonValue	=" LIKE ". $this->getValueWrapped("%". $condition["value"] ."") .""; break;
								case  4		: $comparisonValue	=" LIKE ". $this->getValueWrapped("%". $condition["value"] ."%") .""; break;
								case  5		: $comparisonValue	=" LIKE ". $this->getValueWrapped("". $condition["value"] ."%") .""; break;
								default		: throw new DbControlException("Ilegal comparison '". $condition["comparison"] ."'!");
				}
				$whereString	.= strlen($whereString) > 0 ? " $glue " : "";
				$whereString	.= reset(self::getQuotesColumnName()) . $columnName . end(self::getQuotesColumnName()) . $comparisonValue;
			}
		}
		foreach ($where->getWheres() as $subWhereSet) {
			$subWhere		= $subWhereSet["where"];
			$subWhereGlue	= $subWhereSet["glue"] > 0 ? "OR" : "AND";
			if (strlen($subWhereString	= $this->getWhereStringByObject($subWhere, true)) < 1) {
				continue;
			}
			$whereString	= strlen($whereString) > 0 ? "($whereString) $subWhereGlue " : "";
			$whereString 	.= "($subWhereString)";
		}
		if (!$isSub && strlen($whereString) > 0) {
			$whereString	= "WHERE $whereString";
		}
		return $whereString;
	}

	/**
	 * returns ORDER BY clause as string generated from given array
	 * @param array $orderBy
	 * @param bool $reverse - order by statemants generated in reverse logic if true (only for cross platform emulations of some unavailable queries)
	 * @return string
	 */
	protected function getOrderByStringByArray ($orderBy	= array(), $reverse	= false) {
		$orderByString	= "";
		if (count($orderBy) < 1) {
			$orderByString	= "";
		}
		else {
			foreach ($orderBy as $columnName => $value) {
				$orderByString	.= strlen($orderByString) > 0 ? ", " : "";
				$orderByString	.= reset(self::getQuotesColumnName()) ."$columnName". end(self::getQuotesColumnName()) ."";
				if ($reverse) 	{ $orderByString	.= $value < 1 ? " ASC" : " DESC"; }
				else 			{ $orderByString	.= $value < 1 ? " DESC" : " ASC"; }
			}
			$orderByString	= "ORDER BY $orderByString";
		}
		return $orderByString;
	}

	public function getValueFormatedDateTime($timeStamp	= 0) {
		if (!is_int($timeStamp)) {
			throw new DbControlException("Ilegal parameter timeStamp. Must be NOT-NULL integer.");
		}
		if ($timeStamp < 1) {
			throw new DbControlException("Ilegal parameter timeStamp. Must be NOT-NULL integer.");
		}
		return date("Y-m-d H:i:s", $timeStamp);
    }
    
	public function getQuotesDatabaseName() {
		return array("`", "`");
	}

	public function getQuotesTableName() {
		return array("`", "`");
	}

	public function getQuotesColumnName() {
		return array("`", "`");
	}

	public function getQuotesValue() {
		return array("'", "'");
	}

	protected function escapeString($string = "") {
		/*NONSENSE!!! if (ini_get("magic_quotes_gpc") == 1 || strtolower(ini_get("magic_quotes_gpc")) == "on") {
			return $string;
		}*/
		return mysql_escape_string($string);
	}
}
?>