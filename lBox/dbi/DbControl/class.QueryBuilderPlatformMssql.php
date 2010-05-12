<?php
class QueryBuilderPlatformMssql extends QueryBuilderPlatform
{
	public function getInsert(/*string*/ $table, /*array*/ $values = array()) {
		if (strlen($table) < 1) {
			throw new DbControlException("Ilegal parameter table. Must be NOT-NULL string.");
		}
		if (count($values) < 1) {
			throw new DbControlException("Ilegal parameter values. Must be NOT-NULL string.");
		}
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
		if (strlen($table) < 1) {
			throw new DbControlException("Ilegal parameter table. Must be NOT-NULL string.");
		}
		if (strlen($values) < 1) {
			throw new DbControlException("Ilegal parameter values. Must be NOT-NULL string.");
		}
		foreach($values as $index => $value) {
			$value			= $this->getValueWrapped($value);
			$updateString	.= strlen($updateString) > 0 ? ", " : "";
			$updateString 	.= reset(self::getQuotesColumnName()) ."$index". end(self::getQuotesColumnName()) . "=$value";
		}
		if ($where instanceof QueryBuilderWhere) $whereString	= $this->getWhereStringByObject($where);
		$table	= reset($this->getQuotesTableName()) . $table . end($this->getQuotesTableName());
		$out	= "UPDATE $table SET \n$updateString \n$whereString";
		return $out;
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
	
	public function getDoesTableExists($table, $database = "") {
		try {
			$quotesColumnName	= $this->getQuotesColumnName();
			return "SELECT name FROM SysObjects WHERE ".$quotesColumnName[0]."name".$quotesColumnName[1]." = ".$this->getValueWrapped($table)."";
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getCreateTable($table, $columns = array(), $attributes = array()) {
		try {
			throw new DbControlException( "getCreateTable() is not implemented yet!");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function getAddColumns($table, $columns = array()) {
		try {
			throw new DbControlException( "getAddColumns() is not implemented yet!");
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
		if ($where instanceof QueryBuilderWhere) $whereString	= $this->getWhereStringByObject($where);
		
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
		$orderByString			= $this->getOrderByStringByArray($orderBy);
		$orderByStringReverse	= $this->getOrderByStringByArray($orderBy, true);
		// limitString
		$limitString	= "";
		switch (count($limit)) {
			case 0:
			case 2:
					NULL;
				break;
			case 1:
					$limit	= array(0, current($limit));
				break;
			default:
					throw new DbControlException("Ilegal parameter limit!");
		}
		$outTmp	= "$what FROM ". reset(self::getQuotesTableName()) ."$table". end(self::getQuotesTableName()) .
					" $whereString $groupByString $orderByString";
		// LIMIT clause emulation for MSSQL
		/**
		 * FOR INSTANCE EMULATION OF:
		 * 	- SELECT emp_id,lname,fname FROM employee LIMIT 20,10
		 * select * from (
			 select top 10 emp_id,lname,fname from (
			    select top 30 emp_id,lname,fname
			    from employee
			   order by lname asc
			 ) as newtbl order by lname desc
			) as newtbl2 order by lname asc
		 */
		if (count($limit) > 0) {
			$limitOffset	= reset($limit);
			$limitLimit		= end($limit);
			/* check table count */
			$sqlCountNoLimit	= "SELECT COUNT(*) AS count FROM ". reset(self::getQuotesTableName()) ."$table". end(self::getQuotesTableName()) .
									" $whereString $groupByString";
			$dbControl			= new DbControl($this->task);
			$countNoLimit		= $dbControl->initiateQuery($sqlCountNoLimit)->count;
			$limitLimit			= $limitOffset + $limitLimit > $countNoLimit
									? $limitOffset > $countNoLimit ? 0 : $countNoLimit - $limitOffset
									: $limitLimit;
			$out	= 	"SELECT * FROM (
							SELECT TOP ". $limitLimit ." $what FROM (".
								"SELECT TOP ". ($limitOffset + $limitLimit) ." $outTmp
							) AS order_tmptable1 $orderByStringReverse".
						") AS order_tmptable2 $orderByString";
		}
		else {
			$out	= "SELECT $outTmp";
		}
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
		/* FULLTEXT WHERE */
		if ($where instanceof QueryBuilderWhereFullText) {
			$columnsString	= "";
			$phrasesString	= "";
			foreach ($where->getPhrases() as $phrase) {
				$phrase			 = ereg_replace("[[:punct:]]", "", $phrase);
				if (strlen(trim($phrase)) < 1) continue;
				$phrase 		 = $this->escapeString($phrase);
				$phrasesString	.= strlen($phrasesString) > 0 ? " " : "";
				$phrasesString	.= '"'. $phrase .'"';
			}
			foreach ($where->getColumns() as $columnName) {
				$columnString	= reset(self::getQuotesColumnName()) . $columnName . end(self::getQuotesColumnName());
				$whereString 	.= strlen($whereString) > 0 ? " OR " : "";
				$whereString	.= "CONTAINS (". $columnString .", \"$phrasesString\")";				
			}
		}
		/* NORMAL WHERE */
		else {
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
		return date("d.m.Y H:i:s", $timeStamp);
    }
    
	public function getQuotesDatabaseName() {
    	return array("[", "]");
    }
    
    public function getQuotesTableName() {
    	return array("[", "]");
    }

    public function getQuotesColumnName() {
    	return array("[", "]");
    }

    public function getQuotesValue() {
		return array("'", "'");
	}
	
	protected function escapeString($string = "") {
		$string	= str_replace("'", "''", $string);
		return $string;
	}
}
?>