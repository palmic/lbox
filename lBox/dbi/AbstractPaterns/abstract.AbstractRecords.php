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
	 * where instance cache
	 * @var QueryBuilderWhere
	 */
	protected $where;

	/**
	 * sql cache
	 * @var string
	 */
	protected $sql = "";

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
	 * isTree table flags for concrete subclasses - do not define it its only cache of isTree() method
	 * @var array
	 */
	protected static $isTree = array();

	/**
	 * cache array of class vars
	 * @var array
	 */
	private $cacheClassVars = array();

	//== constructors ====================================================================

	/**
	 * constructor
	 * @param array  $filter         - associated array with filter parameters in form $filter[$colname] = $colvalue
	 * @param array  $order 			- for load records in form $order["colname"] = 1 for asc, 0 for desc
	 * @param array  $limit 			- for limit rule  in form $limit = array(min, count). For example $limit = array(0, 10) returns rows 1-11
	 * @param string $whereAdd		- additional where "OR conditions" defined to add.
	 */
	public function __construct($filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		if (is_array($filter)) {
			foreach($filter as $f_key => $f_value) {
				if (empty($f_value) && (!is_string($f_value) && ($f_value !== 0))) {
					throw new LBoxException("You inserted empty filter parameter '$f_key'! ");
				}
			}
		}

		$this->filter           	= $filter;
		$this->order           		= $order;
		$this->limit           		= $limit;
		$this->whereAdd				= $whereAdd;
		$this->loadFromCache();
		reset($this->records);
	}

	//== destructors ====================================================================
	//== cache functions ===============================================================

	/**
	 * is in cache flag
	 * @var bool
	 */
	protected $isInCache;
	
	/**
	 * cache synchronized flag
	 * @var bool
	 */
	protected $isCacheSynchronized	= false;
	
	/**
	 * cache switched on flag
	 * @var bool
	 */
	protected $isCacheOn;
	
	/**
	 * return true if record is cached
	 * @return bool
	 */
	protected function isInCache() {
		try {
			if (is_bool($this->isInCache)) {
				return $this->isInCache;
			}
/*$itemType 		= $this->getClassVar("itemType");
$id				= $this->getSQL();
var_dump("$itemType:: je '$id' v cachi?");
//var_dump($this->getCacheFileName());
var_dump(LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->doesCacheExists());*/
			return $this->isInCache = LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->doesCacheExists();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * loads data from cache
	 */
	protected function loadFromCache() {
			try {
			if (!$this->isCacheOn()) return;
			if (!$this->isInCache()) return;
			if ($this->isCacheSynchronized) return;
/*$sql	= $this->getSQL();
var_dump("loaduju kolekci z cache ". $this->getSQL() . " [". $this->getCacheFileName() ."]");
echo "<br />\n";*/
			$itemType 		= $this->getClassVar("itemType");
			$className		= get_class($this);
			$idColName  	= eval("return $itemType::\$idColName;");
			if (count($data = LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->getData()) > 0) {
				if (array_key_exists("system_istree", $data)) {
					self::$isTree[$className]	= (bool)$data["system_istree"];
				}
				foreach ($data as $key => $row) {
					if ($key == "system_istree") {
						continue;
					}
					$recordRef 		= new $itemType($row[$idColName]);
					$recordRef		->setIsTree($this->isTree());
					foreach ($row as $colName => $colValue) {
						if ($colName == $idColName) continue;
						//XXX v pripade nacitani kolekce children vracenych metodou AbstractRecord::getChildren() se tu loaduje z DB isTree
						//    je to proto, ze se sem tok dostane primo z construktoru jeste pred explicitnim setIsTree() z AbstractRecord
						if (!$this->isTreeKey($colName)) {
							$recordRef->$colName = $colValue;
						}
						else {
							$recordRef->setTreeKey($colName, $colValue);
						}
					}
					// set synchronized-with-db = true to optimize performance (Record shall load data again otherwise)
					$recordRef->setCacheSynchronized(true);
					$recordRef->setSynchronized(true);
					$recordRef->setPasswordChanged(false);
					array_push($this->records, $recordRef);
/*var_dump("Record loadnut:");
echo "$recordRef<hr />\n\n";*/
				}
/*var_dump("Records loadnuty:");
echo count($this->records) ."<hr />\n\n";*/
				$this->isCacheSynchronized	= true;
				$this->doNotLoad			= true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * posledni key precteny z cache
	 * @var int
	 */
	protected $cacheLastKey	= 0;
	
	/**
	 * description here
	 * @return string
	 */
	protected function loadNextFromCache() {
		try {
			if (!$this->isCacheOn()) 		return;
			if (!$this->isInCache()) 		return;
			
			if ($this->isCacheSynchronized) return true;
/*$sql	= $this->getSQL();
var_dump("loaduju kolekci z cache ". $this->getSQL() . " [". $this->getCacheFileName() ."]");
echo "<br />\n";*/
			$itemType 		= $this->getClassVar("itemType");
			$className		= get_class($this);
			$idColName  	= eval("return $itemType::\$idColName;");
			if (count($data = $this->getCacheData()) > 0) {
				if ((!array_key_exists($className, self::$isTree)) || !is_bool(self::$isTree[$className])) {
					if (array_key_exists("system_istree", $data)) {
						self::$isTree[$className]	= (bool)$data["system_istree"];
					}
				}
				$this->cacheLastKey		++;
				if (array_key_exists($this->cacheLastKey, $data)) {
					$recordData				= $data[$this->cacheLastKey];
				}
				else {
					// end of cycle
					$this->isCacheSynchronized	= true;
					$this->doNotLoad			= true;
					return false;
				}

				$recordRef 				= new $itemType($recordData[$idColName]);
				foreach ($recordData as $colName => $colValue) {
					if ($colName == $idColName) continue;
					if (!$this->isTreeKey($colName)) {
						$recordRef->$colName = $colValue;
					}
					else {
						$recordRef->setTreeKey($colName, $colValue);
					}
				}
				array_push($this->records, $recordRef);
	
				// set synchronized-with-db = true to optimize performance (Record shall load data again otherwise)
				$recordRef->setIsTree($this->isTree());
				$recordRef->setSynchronized(true);
				$recordRef->setPasswordChanged(false);
				return true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * cache data getter
	 * @return array
	 */
	protected function getCacheData() {
		try {
			if (count($this->cacheData) > 0) {
				return $this->cacheData;
			}
			return $this->cacheData = LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->getData();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Optimalizovano cyklovani cachi:lBox/dbi/AbstractPaterns/abstract.AbstractRecords.php
	 * stores data to cache
	 */
	protected function storeToCache() {
		try {
			if (!$this->isCacheOn()) return;
			if ($this->isCacheSynchronized) return;
			//if ($this->isCacheSynchronized) return;
//var_dump("ukladam cache: ". $this->getCacheFileName());flush();
			if (is_bool($this->isTree())) {
				LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->system_istree	= (int)$this->isTree();
			}
			LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->saveCachedData();
			$this->isCacheSynchronized	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * last key stored into cache
	 * @var int
	 */
	protected $cacheLastKeyStored	= 0;
	
	/**
	 * adds record's data to cache
	 * @param AbstractRecord $record
	 */
	protected function addToCache($data) {
		try {
			if (!$this->isCacheOn()) return;
//var_dump("pridavam do cache ". $this->getCacheFileName() . " $id");
			$itemType 					= $this->getClassVar("itemType");
			$idColName  				= eval("return $itemType::\$idColName;");
			$id							= $this->cacheLastKeyStored+1;
			LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->$id	= $data;
			$this->isCacheSynchronized	= false;
			$this->cacheLastKeyStored	++;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * resets Record's cache
	 */
	protected function resetCache() {
		try {
//throw new Exception(__FUNCTION__);
			LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->reset();
			$this->isCacheSynchronized	= false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * clear all cache data
	 */
	public function clearCache() {
		try {
//throw new Exception(__FUNCTION__);
			$itemType 		= $this->getClassVar("itemType");
			$cacheDisabled 	= eval("return $itemType::\$cacheDisabled;");
			if (!$cacheDisabled) {
				LBoxCacheAbstractRecord::getInstance($this->getCacheFileName())->clearCache();
				$this->isCacheSynchronized	= false;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * returns cache filename
	 * @return string
	 */
	protected function getCacheFileName() {
		try {
			$itemType 		= $this->getClassVar("itemType");
			$tableName  	= eval("return $itemType::\$tableName;");
			$hashedID		= md5($this->getSQL());
			return "$tableName/collections/$hashedID.cache";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns if cache is swiched on
	 * @return unknown
	 */
	protected function isCacheOn() {
		try {
			if (is_bool($this->isCacheOn)) {
				return $this->isCacheOn;
			}

			$config		= new DbCfg;
			$path		= "/tasks/project/cache";
			$value		= $config->$path;
			if (!current((array)$value)) {
				return $this->isCacheOn = false;
			}
			else {
				$itemType 		= $this->getClassVar("itemType");
				$cacheDisabled 	= eval("return $itemType::\$cacheDisabled;");
				return !$cacheDisabled;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	//== public functions ===============================================================

	/**
	 * isTree flag setter for AbstractRecord(s) loading only!
	 * (for reason of performace)
	 * @param bool $isTree
	 */
	public function setIsTree($isTree = true) {
		try {
			$className					= get_class($this);
			self::$isTree[$className]	= (bool)$isTree;
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
//var_dump($this->getSQL() ." pocet: ". count($this->records));
			if (!is_a(@current($this->records), $itemType)) {
//var_dump($this->getSQL() .": loaduju next");
				$this->loadNext();
			}
			if (!is_a(@current($this->records), $itemType)) {
				$this->storeToCache();
/*var_dump(count($this->records));
var_dump($this->getSQL() .": neni valid");*/
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
		try {
			$itemType = $this->getClassVar("itemType");
			$this->loadNext();
			/*if (!is_a(@current($this->records), $itemType)) {
				$this->loadNext();
			}*/
			if (!is_a($this->records[$this->key+1], $itemType)) {
				$this->storeToCache();
			}
			return current($this->records);
		}
		catch (Exception $e) {
			throw $e;
		}
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
				if ($this->isCacheOn() && $this->isCacheSynchronized) {
					return count($this->records);
				}
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
			$itemType 				= $this->getClassVar("itemType");
			$idColName  			= eval("return $itemType::\$idColName;");
			$record 				= $this->getDbResult()->get();
			$recordRef 				= new $itemType($this->getDbResult()->$idColName);
			$cacheData[$idColName]	= $this->getDbResult()->$idColName;
			foreach ($record as $colName => $colValue) {
				if ($colName == $idColName) continue;
				if (!$this->isTreeKey($colName)) {
					$recordRef->$colName = $colValue;
				}
				else {
					$recordRef->setTreeKey($colName, $colValue);
				}
				$cacheData[$colName]	= $colValue;
			}
			array_push($this->records, $recordRef);

			// set synchronized-with-db = true to optimize performance (Record shall load data again otherwise)
			$recordRef->setIsTree($this->isTree());
			$recordRef->setSynchronized(true);
			$recordRef->setPasswordChanged(false);
			$this->addToCache($cacheData);

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
				$itemType 		= $this->getClassVar("itemType");
				$treeColNames = eval("return $itemType::\$treeColNames;");
				$idColName 		= eval("return $itemType::\$idColName;");
				$pidColName		= $treeColNames[2];
				$tableName 		= eval("return $itemType::\$tableName;");
				
				$whereCount		= clone $this->getWhere();

				if ($this->isTree()) {
					// we want count of all records (including subrecords in trees)
					$whereCountAdd = NULL;
					if ((!$this->whereAdd instanceof QueryBuilderWhere) && (count($this->filter) < 1)) {
						$whereCountAdd	= new QueryBuilderWhere();
						$whereCountAdd	->addConditionColumn($pidColName, 0, 2);
						$whereCount		->addWhere($whereCountAdd, 1);
					}
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
				$this->getDb()->setQuery($this->getSQL(), true);
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
	 * return SQL
	 * @return string
	 */
	protected function getSQL() {
		try {
			if (strlen($this->sql) > 0) {
				return $this->sql;
			}
			$itemType 		= $this->getClassVar("itemType");
			$treeColNames = eval("return $itemType::\$treeColNames;");
			$idColName 		= eval("return $itemType::\$idColName;");
			$pidColName		= $treeColNames[2];
			$tableName 		= eval("return $itemType::\$tableName;");
			if (empty($idColName)) {
				throw new LBoxException("Static variable $itemType::idColName is empty or not exists!");
			}
			if (empty($tableName)) {
				throw new LBoxException("Static variable $itemType::tableName is empty or not exists!");
			}

			// set where clause by $filter items
			$where	= $this->getWhere();

			return $this->sql		= trim($this->getQueryBuilder()->getSelectColumns($tableName, array(), $where, $this->limit ? (array)$this->limit : array(), array(), $this->order ? (array)$this->order : array()));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns prior set where instance
	 * @return QueryBuilderWhere
	 */
	protected function getWhere() {
		try {
			if ($this->where instanceof QueryBuilderWhere) {
				return $this->where;
			}
			$itemType 			= $this->getClassVar("itemType");
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
			return $this->where	= $where;
		}
		catch (Exception $e) {
			throw $e;
		}
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
		if (array_key_exists($varName, $this->cacheClassVars))
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
			$className	= get_class($this);
			if (array_key_exists($className, self::$isTree) && is_bool(self::$isTree[$className])) {
				return self::$isTree[$className];
			}
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
					self::$isTree[$className] = false;
					return self::$isTree[$className];
					break;
				}
			}
			self::$isTree[$className] = true;
			return self::$isTree[$className];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>