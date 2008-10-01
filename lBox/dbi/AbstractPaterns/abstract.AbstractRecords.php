<?php

/**
 * abstract class - parent of classes to handling with database records collection
 * need DbControl class!
 * @author Michal Palma <palmic at email dot cz>
 * @date 2006-02-07
 * @version 0.2 2008-09-16
 */
abstract class AbstractRecords implements Iterator
{

	//== Attributes ======================================================================

	/**
	 * Filter for load records in form $filter["columnname"] = "value"
	 * @var array
	 */
	protected $filter;

	/**
	 * order for load records in form $order["colname"] = >0 for asc, <1 for desc
	 * @var array
	 */
	protected $order;

	/**
	 * limit rulle spec in array form: $limit = array(min, length). For example $limit = array(1, 10)
	 * @var array
	 */
	protected $limit;

	/**
	 * additional where "OR conditions"
	 * @var QueryBuilderWhere
	 */
	protected $whereAdd;

	/**
	 * Loaded records array
	 * @var array
	 */
	protected $records = array();

	/**
	 * Do not automatic load flag used by static records collection created thrue addRecord() method
	 * @var boolean
	 */
	protected $doNotLoad = false;

	/**
	 * Current Loaded records array position
	 * @var integer
	 */
	protected $key = 0;

	/**
	 * database result resource for loading records
	 * @var resource
	 */
	protected $dbResult;

	/**
	 * database connection class instance
	 * @var db
	 */
	protected $db;

	/**
	 * QueryBuilder instance
	 * @var QueryBuilder
	 */
	protected $queryBuilder;

	/**
	 * count of records
	 * @var integer
	 */
	protected $count = false;

	/**
	 * Name of class to create collection items - need to be specified in child class as PUBLIC STATIC!!!
	 * @var string
	 */
	public static $itemType;

	/**
	 * isTree table flag - do not define it its only cache of isTree() method
	 * @var bool
	 */
	protected $isTree;

	/**
	 * cache array of class vars
	 * @var array
	 */
	private $cacheClassVars = array();

	//== constructors ====================================================================

	/**
	 * constructor
	 * @param string $mode         - tree or list mode
	 * @param array  $filter         - associated array with filter parameters in form $filter[$colname] = $colvalue
	 * @param array  $order 			- for load records in form $order["colname"] = 1 for asc, 0 for desc
	 * @param array  $limit 			- for limit rule  in form $limit = array(min, count). For example $limit = array(0, 10) returns rows 1-11
	 * @param string $whereAdd		- additional where "OR conditions" defined to add.
	 */
	public function __construct($filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		if (is_array($filter)) {
			foreach($filter as $f_key => $f_value) {
				if (empty($f_value) && (!is_string($f_value))) {
					throw new LBoxException("You inserted empty filter parameter '$f_key'! ");
				}
			}
		}
		$this->mode					= $mode;
		$this->filter           	= $filter;
		$this->order           		= $order;
		$this->limit           		= $limit;
		$this->whereAdd				= $whereAdd;
	}

	//== destructors ====================================================================
	//== public functions ===============================================================

	/**
	 * isTree flag setter for AbstractRecord(s) loading only!
	 * (for reason of performace)
	 * @param bool $isTree
	 */
	public function setIsTree($isTree = true) {
		try {
			$this->isTree	= (bool)$isTree;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * array iterator acces interface part
	 */
	public function rewind() {
		@reset($this->records);
	}

	/**
	 * array iterator acces interface part
	 */
	public function valid() {
		try {
			$itemType = $this->getClassVar("itemType");
			if (!is_a(@current($this->records), $itemType)) {
				$this->loadNext();
			}
			if (!is_a(@current($this->records), $itemType)) {
				return false;
			}
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * array iterator acces interface part
	 */
	public function key() {
		try {
			// we want use database primary key of record like key
			$itemType = $this->getClassVar("itemType");
			$idColName  = eval("return $itemType::\$idColName;");
			return $this->records[$this->key]->$idColName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * array iterator acces interface part
	 * @return AbstractRecord
	 */
	public function current() {
		$itemType = $this->getClassVar("itemType");
		if (!is_a(@current($this->records), $itemType)) {
			try {
				$this->loadNext();
			}
			catch (Exception $e) {
				throw $e;
			}
		}
		return current($this->records);
	}

	/**
	 * array iterator acces interface part
	 */
	public function next() {
		next($this->records);
	}

	/**
	 * return collection items count
	 */
	public function count() {
		try {
			if (!is_resource($this->dbResult)) {
				$this->getDbResult();
			}
			return (int)$this->count;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * sort records by parameter. Try to use constructor parameter order if posible. This method cause BIG degrade of performance!
	 * @param $by to specify which record parameter to use in sort
	 */
	/* DEPRECATED public function sort($by) {
		try {
			$this->loadAll();

			foreach($this->records as $record_id => $record) {
				$record_ids[$record_id] = $record->$by;
			}
			natcasesort($record_ids);
			reset($record_ids);
			foreach($record_ids as $record_id => $sv) {
				$sorted_records[] = $this->records[$record_id];
			}
			$this->records = $sorted_records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}*/

	/**
	 * add record
	 * @param AbstractRecord $record - record to add to collection
	 */
	public function addRecord($record) {
		try {
			if (!is_a($record, $this->getClassVar("itemType"))) {
				throw new LBoxException("Object of ". get_class($this) ." class accept only object of ". $this->getClassVar("itemType") ." class");
			}
			array_push($this->records, $record);
			$this->doNotLoad = true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * return current child class idColName
	 * @return string
	 */
	public function getIdColName() {
		try {
			$itemType = $this->getClassVar("itemType");
			return eval("return $itemType::\$idColName;");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * empty loaded data - trigger to load table data again
	 */
	public function reset() {
		$this->records	= array();
		$this->dbResult = NULL;
	}

	//== protected functions ===============================================================

	/**
	 * load next record from database into array
	 */
	protected function loadNext() {
		try {
			if ($this->doNotLoad) {
				return false;
			}
			if (!$this->getDbResult()->next()) {
				return false;
			}
			// fill record for NO MORE ADDITIONAL DB QUERY IN EVERY RECORD posibility - optimalization
			$itemType 		= $this->getClassVar("itemType");
			$record 		= $this->getDbResult()->get();
			$recordRef 		= new $itemType();
			foreach ($record as $colName => $colValue) {
				if (in_array($colName, $recordRef->getClassVar("passwordColNames", $force = true))) {
					continue;
				}
				if (!$this->isTreeKey($colName)) {
					$recordRef->$colName = $colValue;
				}
				else {
					$recordRef->setTreeKey($colName, $colValue);
				}
				$recordRef->setIsTree($this->isTree());
			}
			array_push($this->records, $recordRef);
			// set synchronized-with-db = true to optimize performance (Record shall load data again otherwise)
			$recordRef->setSynchronized(true);
			return true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * load all remaining records into array
	 */
	protected function loadAll() {
		try {
			while($this->loadNext()) {
				null;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}


	/**
	 * getter for database result resource with list of items limited by $filter and ordered by $order
	 */
	protected function getDbResult() {
		if (!is_a($this->dbResult, "DbResultInterface")) {
			try {
				// get essential values from used classes. Must flexible accept possibility of using any AbstractRecord-child class
				$itemType 	= $this->getClassVar("itemType");
				$treeColNames = eval("return $itemType::\$treeColNames;");
				$idColName 	= eval("return $itemType::\$idColName;");
				$pidColName	= $treeColNames[2];
				$tableName 	= eval("return $itemType::\$tableName;");
				if (empty($idColName)) {
					throw new LBoxException("Static variable $itemType::idColName is empty or not exists!");
				}
				if (empty($tableName)) {
					throw new LBoxException("Static variable $itemType::tableName is empty or not exists!");
				}

				// set where clause by $filter items
				$where				= new QueryBuilderWhere();
				$passwordColNames	= eval("return $itemType::\$passwordColNames;");
				@reset($this->filter);
				$fcur = @current($this->filter);
				if ((!empty($fcur)) || ((int)$fcur === 0)) {
					if ($this->filter !== false) {
						foreach ((array)$this->filter as $column => $value) {
							// password columns
							if (in_array($column, $passwordColNames)) {
								$value	= md5($value);
							}
							$where->addConditionColumn($column, $value);
						}
					}

				}
				// add whereAdd addition
				if ($this->whereAdd instanceof QueryBuilderWhere) {
					$where->addWhere($this->whereAdd);
				}

				// set order rulle by $order values
				if (is_array($this->order)) {
					$order = "";
					foreach($this->order as $orderColName => $orderType) {
						if (strlen($order) > 0) {
							$order .= ", ";
						}
						if (strlen($orderColName) < 1) {
							continue;
						}
						$order .= " `$orderColName`";
						if ($orderType < 1) {
							$order .= " DESC";
						}
					}
					$sql .= " ORDER BY ". $order;
				}
				
				$sql		= $this->getQueryBuilder()->getSelectColumns($tableName, array(), $where, $this->limit ? (array)$this->limit : array(), array(), $this->order ? (array)$this->order : array());
				$whereCount	= clone $where;
				if ($this->isTree()) {
					// we want count of all records (including subrecords in trees)
					$whereCountAdd	= new QueryBuilderWhere();
					$whereCountAdd	->addConditionColumn($pidColName, 0, 2);
					$whereCount		->addWhere($whereCountAdd, 1);
				}
				$countSql	= $this->getQueryBuilder()->getSelectCount($tableName, $whereCount);
				// call created SQL query for get count on db
				$this->getDb()->setQuery($countSql, true);
				$countResult = $this->getDb()->initiate();
				$this->count = is_numeric(current($countResult->get("*"))) ? current($countResult->get("*")) : 0;
				// mysql cannot get select count(*) from table limit 1, 10!!! hack:
				if (($this->count > 0) && (is_array($this->limit) && count($this->limit) > 0)) {
					$this->count -= $this->limit[0];
					$this->count = ($this->count > $this->limit[1]) ? $this->limit[1] : $this->count;
					if ($this->count < 0) {
						$this->count = 0;
					}
				}

				// call created SQL query on db
				$this->getDb()->setQuery($sql, true);
				$this->dbResult = $this->getDb()->initiate();
				if (!is_a($this->dbResult, "DbResultInterface")) {
					throw new LBoxException("Cannot get result of database query");
				}
			}
			catch(Exception $e) {
				throw $e;
			}
		}
		return $this->dbResult;
	}

	/**
	 * checks if given column name is tree key
	 * @param string $name - column name
	 * @return bool
	 */
	protected function isTreeKey($name = "") {
		if (strlen($name) < 1) return false;
		try {
			$itemType 		= $this->getClassVar("itemType");
			$treeColNames  	= eval("return $itemType::\$treeColNames;");
			foreach ($treeColNames as $treeColName) {
				if ($treeColName == $name) return true;
			}
			return false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter of db class instance
	 * @return DbControlInterface
	 */
	protected function getDb() {
		try {
			if (!is_a($this->db, "DbControlInterface")) {
				$itemType = $this->getClassVar("itemType");
				$dbName 	= eval("return $itemType::\$dbName;");
				$this->db 	= new DbControl(AbstractRecord::$task, AbstractRecord::$charset);
				if (strlen($dbName) > 0) {
					$this->db->selectDb($dbName);
				}
			}
			return $this->db;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter of QueryBuilder instance
	 * @return QueryBuilder
	 */
	protected function getQueryBuilder() {
		try {
			if (!is_a($this->queryBuilder, "QueryBuilder")) {
				$this->queryBuilder = new QueryBuilder(AbstractRecord::$task);
			}
			return $this->queryBuilder;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter of static variable from child class
	 * @param string $varname - Name of child class static variable
	 * @param boolean $force - set to true for no-exception in case of missing value
	 */
	protected function getClassVar($varName, $force = false) {
		if (!is_string($varName)) {
			throw new LBoxException("Bad parameter varName, must be string!");
		}
		if ($this->cacheClassVars[$varName]) {
			return $this->cacheClassVars[$varName];
		}
		$className = get_class($this);
		$value = eval("return $className::\$$varName;");
		if ( (!$force) && ($value === NULL) ) {
			throw new LBoxException("Static variable $className::$varName is empty or not exists!");
		}
		return $this->cacheClassVars[$varName] = $value;
	}

	/**
	 * Check if table has tree structure
	 * @return bool
	 * @throws Exception
	 */
	protected function isTree() {
		try {
			if (is_bool($this->isTree)) {
				return $this->isTree;
			}
			$className 		= get_class($this);
			$itemType		= $this->getClassVar("itemType");
			$tableName		= eval("return $itemType::\$tableName;");
			$columns 		= eval("return $itemType::\$treeColNames;");
			foreach ($columns as $column) {
				try {
					$sql = $this->getQueryBuilder()->getSelectColumns($tableName, (array)$column, NULL, array(1));
					$this->getDb()->initiateQuery($sql);
				}
				catch (DbControlException $e) {
					// throw $e;
					// column does not found - table is not tree
					$this->isTree = false;
					return $this->isTree;
					break;
				}
			}
			$this->isTree = true;
			return $this->isTree;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>