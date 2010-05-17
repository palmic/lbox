<?php

/**
 * abstract class - parent of classes, that encapsulates database record
 * you can specify new record by no $id specified. Cannot load record from db whithout $id!
 * Iterator interface implemented for array iterate to get Columns whithout known of their names. Cannot set them like array items! You can set them like public objects params
 * need DbControl class!
 * @author Michal Palma <palmic at email dot cz>
 * @date 2006-02-07
 * @version 0.2 2008-09-16
 */
abstract class AbstractRecord implements Iterator
{

	//== Attributes ======================================================================

	/**
	 * record params array (array for dynamic load of params/columns from database table. array style can easy adapt table changes)
	 * @var array
	 */
	protected $params = array();

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
	 * password-changed flag
	 * @var boolean
	 */
	private $passwordChanged = false;

	/**
	 * record synchronized with db record flag
	 * @var boolean
	 */
	protected $synchronized = false;

	/**
	 * record in database flag
	 * @var boolean
	 */
	protected $isInDatabase;

	/**
	 * record loaded flag - for no additional load
	 * @var boolean
	 */
	protected $loaded = false;

	/**
	 * database name <b>- need to be set by child to specify working essentials!!!</b>
	 * @var string
	 */
	public static $dbName;

	/**
	 * database tableName <b>- need to be set by child to specify working essentials!!!</b>
	 * @var string
	 */
	public static $tableName;

	/**
	 * database id columnName <b>- need to be set by child to specify working essentials!!!</b>
	 * @var string
	 */
	public static $idColName;

	/**
	 * tree structure index columns names
	 * array(lft, rgt, parentId, branchId)
	 * @var array
	 */
	public static $treeColNames = array(0 => "lft", 1 => "rgt", 2 => "pid", 3 => "bid");

	/**
	 * isTree table flags for concrete child classes - do not define it its only cache of isTree() method
	 * @var array
	 */
	protected static $isTree = array();

	/**
	 * hasChildren table flag - do not define it its only cache of hasChildren() method
	 * @var bool
	 */
	protected $hasChildren;

	/**
	 * array with names of columns that has values hashed with password() SQL method for special handling by store() method.
	 * In other words, if you will change value of column, whose name is in $passwordColNames array, change can be stored in database only hashed with PASSWORD() SQL method.
	 * @var array
	 */
	public static $passwordColNames = array();

	/**
	 * array of bounded types (tables) characterization in form $boundedTypes = array("ItemsType(AbstractRecords child class)" => "foreignkeycolumnname") where ItemsType is name of class handling with bounded table records collection and foreignkeycolumnname is name of column used like foreign key in table which is maped by in child of this AbstractRecord class.
	 * Use this Array if foreign key is in table which you are currently maping
	 * you can leave it empty if table has no bounded tables
	 *  <b>- need to be set by child to specify working essentials to possibility of get bounded with foreign keys thrue $this->getBounded() public method</b>
	 * @var array
	 */
	public static $boundedM1 = array(); //array("ItemsType(AbstractRecords child class)" => "foreignkeycolumnname");

	/**
	 * array of bounded types (tables) characterization in form $boundedTypes = array("ItemsType(AbstractRecords child class)" => "foreignkeycolumnname") where ItemsType is name of class handling with bounded table records collection and foreignkeycolumnname is name of column used like foreign key in bounded table which is maped by.
	 * Use this Array if foreign key is in bounded table
	 * you can leave it empty if table has no bounded tables
	 *  <b>- need to be set by child to specify working essentials to possibility of get bounded with foreign keys thrue $this->getBounded() public method</b>
	 * @var array
	 */
	public static $bounded1M = array(); //array("ItemsType(AbstractRecords child class)" => "foreignkeycolumnname");

	/**
	 * array of types (tables) their are bounded thrue M:N relation. Form of array: $boundedTypes = array("ItemsType(AbstractRecords child class)" => array("MNTable" => "name_of_MN_linkage_table", "myForeignKey" => "name_of_foreign_key_column_in_$this_table", "nextForeignKey" => "name_of_foreign_key_column_in_table_bounded_by_M:N_relation"))
	 * you can leave it empty if table has no M:N bounded tables
	 *  <b>- need to be set by child to specify working essentials to possibility of get bounded with foreign keys thrue $this->getBoundedMN() public method</b>
	 * @var array
	 */
	public static $boundedMN = array();

	/**
	 * AbstractRecords types delending on this record values (for instance DB VIEWs of my table)
	 *  - needs to be defined here for prior clearing cache!!!
	 * @var array
	 */
	public static $dependingRecords	= array();

	/**
	 * database task id
	 * @var string
	 */
	public static $task = "project";

	/**
	 * database connection charset
	 * @var string
	 */
	public static $charset ="utf8";

	/**
	 * Name of class to create items collections - need to be specified in child class as PUBLIC STATIC!!!
	 * @var string
	 */
	public static $itemsType;

	/**
	 * cache array of class vars
	 * @var array
	 */
	private $cacheClassVars = array();

	/**
	 * cache var flag
	 * @var array
	 */
	public static $doesTableExistsInDatabaseByTypes;

	/**
	 * attributes definition for data/structure modification manipulations
	 * array(	"name"=>"<COLNAME>",
	 * 			"type"=>"int|shorttext|longtext|richtext",
	 * 			"notnull"=>bool default true,
	 * 			"autoincrement"=>bool default false,
	 * 			"visibility"=>"public|protected" ,
	 * 			"default"=>"",
	 * 			"reference" => array("type" => "item_type", "of" => "output_filter_type", "label" => "xt_select_colname", "size_resize" => array("x" => 300, "y" => 300, "proportions" => 1), "size_limit" => array("longer" => 300))
	 * )
	 * @var array
	 */
	protected static $attributes	=	array(array());

	//== constructors ====================================================================

	/**
	 * constructor
	 * @param mixed $id - leave it empty to create new record
	 * @param bool $loaded - only for records classname init!
	 */
	public function __construct($id = NULL, $loaded = false) {
		try {
			if (strlen($id) > 0) {
				if (!is_scalar($id)) {
					throw new LBoxException("You are trying to set not scalar value of ID column!");
				}
				$this->params[$this->getClassVar("idColName")] = $id;
			}
			if (!$loaded) {
				if ($this->isInCache()) {
					$this->loadFromCache();
				}
			}
			else {
				$this->isInDatabase	= true;
				$this->loaded		= true;
			}
			if (!$this->loaded) {
				if (!$this->doesTableExistsInDatabase()) {
					$this->createTable();
				}
				if ($this->isInDatabase()) {
					$this->load();
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}


	//== destructors ====================================================================

	public function __destruct() {
		// check empty instance creating in AbstractRecords::getDbResult() before autosave set!
	}

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
	 * cache temporary switch - used in cases of data manipulation operations to disable all the caching while operation is done
	 * @var bool
	 */
	public static $isCacheOnTempSwitch;

	/**
	 * explicit var to disable cache for concrete tables
	 * @var bool
	 */
	public static $cacheDisabled	= false;

	/**
	 * return true if record is cached
	 * @return bool
	 */
	public function isInCache() {
		try {
			if (is_bool($this->isInCache)) {
				return $this->isInCache;
			}
			if (!array_key_exists($this->getClassVar("idColName"), $this->params) || strlen($this->params[$this->getClassVar("idColName")]) < 1) {
				return false;
			}
			if (!LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->doesCacheExists()) {
				return $this->isInCache = false;
			}
			return $this->isInCache = (count(LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->getData()) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * isCacheSynchronized setter
	 * @param bool $value
	 */
	public function setCacheSynchronized($value = true) {
//$idColName	= $this->getClassVar("idColName");
//var_dump(get_class($this) .":: '". $this->params[$idColName] ."' nastavuju cache na synchronized");
		$this->isCacheSynchronized	= (bool)$value;
	}

	/**
	 * loads data from cache
	 */
	protected function loadFromCache() {
		try {
			if (!$this->isCacheOn()) return;
			if (!$this->isInCache()) {
				return;
			}
//var_dump("loaduju z cache");
			$idColName	= $this->getClassVar("idColName");
			if (count($data = LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->getData()) > 0) {
				if ($data[$idColName] == $this->params[$idColName]) {
					if (array_key_exists("systemrecord_haschildren", $data)) {
						$this->hasChildren	= (bool)$data["systemrecord_haschildren"];
						unset($data["systemrecord_haschildren"]);
					}
					if (array_key_exists("systemrecord_istree", $data)) {
						self::$isTree[$className]	= (bool)$data["systemrecord_istree"];
						unset($data["systemrecord_istree"]);
					}
					unset($data["sql"]);
					$this->params	= $data;
					$this->passwordChanged  	= false;
					$this->synchronized     	= true;
					$this->isCacheSynchronized	= true;
					$this->loaded     			= true;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * stores data to cache
	 */
	protected function storeToCache() {
		try {
			if (is_bool(self::$isCacheOnTempSwitch) && (!self::$isCacheOnTempSwitch)) {
				return;
			}
			if (!$this->isCacheOn()) return;
			if ($this->isCacheSynchronized) return;
//$idColName	= $this->getClassVar("idColName");
//var_dump(get_class($this) .":: '". $this->params[$idColName] ."' ukladam do cache");
			foreach ($this->params as $key => $value) {
				LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->$key	= $value;
			}
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->sql	= $this->getQueryBuilder()->getSelectColumns($this->getClassVar("tableName"), array(), $this->getWhere());
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->systemrecord_istree	= (int)$this->isTree();
			if ($this->isTree()) {
				LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->systemrecord_haschildren	= (int)$this->hasChildren();
			}
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->saveCachedData();
			$this->isCacheSynchronized	= true;
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
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->clean();

			// smazat zaroven i collections cache
			$itemsType			= $this->getClassVar("itemsType");
			$cacheGroupItems	= eval("return $itemsType::getCacheGroup();");
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], $cacheGroupItems)->clean();

			$this				->resetRelevantCache();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * resets Record's cache
	 */
	public function resetCache() {
		try {
			$this->resetRelevantCache();
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->reset();

			// smazat zaroven i collections cache
			$itemsType			= $this->getClassVar("itemsType");
			$cacheGroupItems	= eval("return $itemsType::getCacheGroup();");
			LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], $cacheGroupItems)->clean();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * array of currently clearing caches
	 * @var array
	 */
	private static $cacheRecordsCurrentlyClearing	= array();

	/**
	 * resets depending records cache
	 */
	protected function resetRelevantCache() {
		try {
			$myClass			= get_class($this);
			$dependingRecords	= $this->getClassVar("dependingRecords", true);
			foreach ((array)$dependingRecords as $dependingRecord) {
				if (strlen($dependingRecord) < 1) continue;
				$instance	= new $dependingRecord;
				if ($instance instanceof AbstractRecords) {
					$dependingRecord	= eval("return $dependingRecord::\$itemType;");
					$instance			= new $dependingRecord;
				}
				if ($dependingRecord instanceof $myClass) {
					throw new LBoxException("Cannot define the same className into dependingRecords attribute!");
				}
				if (array_key_exists($dependingRecord, self::$cacheRecordsCurrentlyClearing)) {
					continue;
				}
				self::$cacheRecordsCurrentlyClearing[$dependingRecord]	= $dependingRecord;
				$instance	->clearCache();
				unset(self::$cacheRecordsCurrentlyClearing[$dependingRecord]);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns cache group
	 * @return string
	 */
	public static function getCacheGroup() {
		try {
			return "records";
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

			// dependingRecords variable set check
			$this		->getClassVar("dependingRecords");

			$config		= new DbCfg;
			$path		= "/tasks/project/cache";
			$value		= $config->$path;
			if (!current((array)$value)) {
				return $this->isCacheOn = false;
			}
			else {
				return $this->isCacheOn = !$this->getClassVar("cacheDisabled");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	//== public functions ===============================================================

	public function __toString() {
		try {
			$className	 	= get_class($this);
			$tableName		= $this->getClassVar("tableName");
			$string  		= "$className from table '$tableName'\n";
			foreach ($this as $colName => $colValue) {
				$string .= "$colName => $colValue, ";
			}
			$string .= "\n";
			return $string;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * __set() for gradate environs from "array-implementation" of record params
	 * @param $name - variable name
	 * @param $value - variable value
	 */
	public function __set($name, $value) {
		try {
			if (is_object($value)) {
				throw new Exception("\$value cannot be object!");
			}
			$idColName 			= $this->getClassVar("idColName");
			$passwordColNames	= $this->getClassVar("passwordColNames", true);

			// No change of primary key column value in database aloved!!!
			if ( (!empty($this->params[$idColName])) && ($name == $idColName) ) {
				throw new LBoxException("Cannot change primary key value!");
			}
			// No change of tree keys value in database aloved!!!
			foreach ((array)$this->getClassVar("treeColNames", true) as $treeColName) {
				if ($name == $treeColName) {
					throw new LBoxException("Cannot change tree key value!");
				}
			}

			$this->params[$name] = $value;
			if (in_array($name, $passwordColNames)) {
				$this->passwordChanged = true;
			}
			$this->synchronized	= false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter of system key (excluded by reason of security)
	 * @param $name - variable name
	 * @param $value - variable value
	 */
	public function setTreeKey($name, $value) {
		try {
			$found 				= false;
			$passwordColNames	= $this->getClassVar("passwordColNames", true);

			foreach ((array)$this->getClassVar("treeColNames", true) as $treeColName) {
				if ($name == $treeColName) {
					$found = true;
				}
			}
			if (!$found) {
				throw new LBoxException("Cannot find '$name' between tree keys!");
			}
			$this->params[$name] = $value;
			$this->synchronized	= false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * isTree flag setter for AbstractRecord(s) loading only!
	 * (for reason of performace)
	 * @param bool $isTree
	 */
	public function setIsTree($isTree = true) {
		try {
			self::$isTree[$className]	= (bool)$isTree;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * __get() for gradate environs from "array-implementation" of record params
	 * @param $varName - variable name
	 */
	public function __get($varName = "*") {
		try {
			if ($varName == "*"
				|| (!array_key_exists($varName, $this->params))
				|| (is_null($this->params[$varName]))) {
					$this->load();
			}
			if ($varName == "*") {
				return $this->params;
			}
			return $this->params[$varName];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * synonym to __get() just for comfort in special cases
	 */
	public function get($varName) {
		try {
			return $this->__get($varName);
		}
		catch(Excpetion $e) {
			throw $e;
		}
	}

	/**
	 * array iterator acces interface part
	 */
	public function rewind() {
		@reset($this->params);
	}

	/**
	 * array iterator acces interface part
	 */
	public function valid() {
		$key = @key($this->params);
		if (empty($key)) {
			return false;
		}
		return true;
	}

	/**
	 * array iterator acces interface part
	 */
	public function key() {
		return key($this->params);
	}

	/**
	 * array iterator acces interface part
	 */
	public function current() {
		try {
			return $this->__get(key($this->params));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * array iterator acces interface part
	 */
	public function next() {
		next($this->params);
	}

	/**
	 * store record into database
	 */
	public function store() {
		if ($this->synchronized) {
			return;
		}
		try {
			$tableName 	= $this->getClassVar("tableName");
			$idColName 	= $this->getClassVar("idColName");
			$vals	= array();
			foreach($this->params as $vname => $vvalue) {
				// ignore empty items
				if ($vvalue === false) {
					continue;
				}

				/* lepsi je podminka vyse
				 if ( (empty($vvalue)) && (!is_numeric($vvalue)) ) {
				 continue;
				 }
				 */

				// ignore primary key set if record already is in database
				if ($this->isInDatabase() && ($vname == $this->getClassVar("idColName"))) {
					continue;
				}

				// secure password handling
				if ( (in_array($vname, $this->getClassVar("passwordColNames", true))) && ($this->passwordChanged) ) {
					$vals[$vname] = md5($vvalue);
				} else {
					$vals[$vname] = "$vvalue";
				}
			}
			// handle tree record system attributes
			if ($this->isTree()) {
				$treeColNames 	= $this->getClassVar("treeColNames");
				$lftColname		= $treeColNames[0];
				$rgtColname		= $treeColNames[1];
				$pidColname		= $treeColNames[2];
				$bidColname		= $treeColNames[3];
				if (	!array_key_exists($lftColname, $this->params)
					||	!array_key_exists($rgtColname, $this->params)
					||	!is_numeric($this->params[$lftColname])
					|| 	!is_numeric($this->params[$rgtColname])) {
						$bid				= $this->getMaxTreeBid() + 1;
						$lft				= $this->getMaxTreeRgt() + 1;
						$rgt				= $lft + 1;
						$vals[$lftColname]	= $lft;
						$vals[$rgtColname]	= $rgt;
						$vals[$bidColname]	= $bid;
						$vals[$pidColname]	= (array_key_exists($pidColname, $this->params) && is_numeric($this->params[$pidColname])) ?
												$this->params[$pidColname] : 0;
				}
			}

			// update query
			if ($this->isInDatabase()) {
				if (count($vals) < 1) return;
				// we dont want to update records whithout primary key known
				if (strlen($this->params[$idColName]) < 1) {
					throw new LBoxException("Cannot UPDATE record whithout primary key value known!");
				}
				if (strtoupper($this->params[$idColName]) == "NULL") {
					throw new LBoxException("Primary key value is !NULL!");
				}
				$whereUpdate	= new QueryBuilderWhere();
				$whereUpdate	->addConditionColumn($idColName, $this->params[$idColName], 0);
				$sql			= $this->getQueryBuilder()->getUpdate($tableName, $vals, $whereUpdate);
				$this			->resetCache();
			}
			// insert query
			else {
				if (!array_key_exists($idColName, $vals) || strlen($vals[$idColName]) < 1) {
					$vals[$idColName]			= $this->getMaxId()+1;
					$this->params[$idColName]	= $vals[$idColName];
				}
				$sql	= $this->getQueryBuilder()->getInsert($tableName, $vals);
				$this	->resetCache();
			}
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
			try {
				$this->getDb()->initiateQuery($sql);
			}
			catch (Exception $e) {
				switch ($e->getCode()) {
					case 1062:
							try {
								$this->getDb()->initiateQuery($sql);
							}
							catch (Exception $e) {
								switch ($e->getCode()) {
									case 1062:
											try {
												$this->getDb()->initiateQuery($sql);
											}
											catch (Exception $e) {throw $e;}
										break; default: throw $e; }
							}
						break; default: throw $e; }
			}
			$this->isInDatabase	= true;
		}
		catch(Exception $e) {
			throw $e;
		}
		$this->isCacheSynchronized	= false;
		$this->load();
	}

	/**
	 * load record from database
	 * @throws Exception
	 */
	public function load() {
		try {
			if ($this->loaded) {
				$this->checkAttributesColumnsExists();
				return;
			}
			$this->loadFromCache();
			if ($this->loaded) {
				$this->checkAttributesColumnsExists();
				return;
			}

			$idColName = $this->getIdColName();

			// cannot load without idColName value
			if (strlen((string)$this->params[$idColName]) < 1) {
				$className	= get_class($this);
				throw new LBoxException(get_class($this). ": Cannot load Record without primary column value! ($idColName)");
			}

			// do not pass query whithout WHERE
			if (count($this->getWhere()->getConditions()) < 1) {
				throw new LBoxException("Cannot load record by ". get_class($this) ." whithout where specification! Set id or other parameters for get ONE record from db.");
			}

			$sql	= $this->getQueryBuilder()->getSelectColumns($this->getClassVar("tableName"), array(), $this->getWhere());
			$this->getDb()->setQuery($sql, true);
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
			$result = $this->getDb()->initiate();
			if ($result->getNumRows() < 1) {
				// $dbName = $this->getClassVar("dbName");
				$paramString	= "";
				foreach($this->params as $paramName => $paramValue) {
					if (strlen($paramString) > 0) {
						$paramString .= ", ";
					}
					$paramString .= "$paramName = '$paramValue'";
				}
				throw new LBoxException("No such record in ". /*$dbName .".". */$this->getClassVar("tableName") .":\n$paramString\n\nSQL:\n$sql");
			}
			elseif ($result->getNumRows() > 1) {
				throw new LBoxException("Query \n$sql\n returned more than one record. Could not be handled by AbstractRecord child. Use AbstractRecord<b>s</b> child, or be more specific set values!");
			}
			$this->params = $result->get("*");
			$this->checkAttributesColumnsExists();
		}
		catch(Exception $e) {
			throw $e;
		}
		$this->passwordChanged  = false;
		$this->setSynchronized(true);
		$this->loaded			= true;
	}

	/**
	 * delete Database record
	 */
	public function delete() {
		try {
			self::$isCacheOnTempSwitch	= false;

			$idColName = $this->getClassVar("idColName");
			if (!$this->params[$idColName]) {
				throw new LBoxException("Cannot delete database record whithout id specified! Can delete more records!!!");
			}
			if (!$this->isInDatabase()) {
				$this->resetCache();
				return;
			}
			if ($this->isTree() && $this->hasChildren()) {
				throw new LBoxException("Cannot delete database record with children!!!");
			}
			if ($this->isTree()) {
				$treeColNames	= $this->getClassVar("treeColNames");
				$lftColName		= $treeColNames[0];
				$rgtColName		= $treeColNames[1];
				$pidColName		= $treeColNames[2];
				$bidColName		= $treeColNames[3];
				$myLft			= $this->get($lftColName);
				$myRgt			= $this->get($rgtColName);

				$quotesColumnName		= $this->getQueryBuilder()->getQuotesColumnName();
				$lftColNameSlashed		= reset($quotesColumnName) . $lftColName . end($quotesColumnName);
				$rgtColNameSlashed		= reset($quotesColumnName) . $rgtColName . end($quotesColumnName);
				$pidColNameSlashed		= reset($quotesColumnName) . $pidColName . end($quotesColumnName);
				$bidColNameSlashed		= reset($quotesColumnName) . $bidColName . end($quotesColumnName);
			}

			$where	= new QueryBuilderWhere();
			$this->getDb()->transactionStart();

			$where	->addConditionColumn($idColName, $this->params[$idColName]);
			$sql	= $this->getQueryBuilder()->getDelete($this->getClassVar("tableName"), $where);
			$this->getDb()->setQuery($sql, true);
			if (!$this->getDb()->initiate()) {
				throw new LBoxException("Cannot delete database record with ". $idColName ."=". $this->params[$idColName]);
			}
			if ($this->isTree()) {
				// shift tree
				/*$sqlsTree[0]  	 = "UPDATE ". $this->getClassVar("tableName");
				$sqlsTree[0] 	.= " SET $lftColName = $lftColName-2";
				$sqlsTree[0] 	.= " WHERE ". $lftColName .">". $myRgt;
				$sqlsTree[1]  	 = "UPDATE ". $this->getClassVar("tableName");
				$sqlsTree[1] 	.= " SET $rgtColName = $rgtColName-2";
				$sqlsTree[1] 	.= " WHERE ". $rgtColName .">". $myRgt;*/
				$treeUpdates[0]["set"]		= array($lftColName => "<<$lftColNameSlashed-2>>");
				$treeUpdates[0]["where"]	= new QueryBuilderWhere();
				$treeUpdates[0]["where"]	-> addConditionColumn($lftColName, $myRgt, 2);
				$treeUpdates[1]["set"]		= array($rgtColName => "<<$rgtColNameSlashed-2>>");
				$treeUpdates[1]["where"]	= new QueryBuilderWhere();
				$treeUpdates[1]["where"]	-> addConditionColumn($rgtColName, $myRgt, 2);
				foreach ($treeUpdates as $treeUpdate) {
					$sqlTree	= $this->getQueryBuilder()->getUpdate($this->getClassVar("tableName"), $treeUpdate["set"], $treeUpdate["where"]);
// var_dump(__CLASS__ .": ". $sqlTree);
					if (!$this->getDb()->initiateQuery($sqlTree)) {
						throw new LBoxException("Cannot shift tree after deleting record with ". $idColName ."=". $this->params[$idColName]);
					}
				}
			}
			$this->getDb()->transactionCommit();
			$this->clearCache();
			$this->isInDatabase	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
		// clear data!!!
		$this->params 		= false;
		$this->synchronized = true;
	}

	/**
	 * setter for synchronized - Thrue this can be secured "do-not-load" flag for optimalization of loading collection. (Object is filled by collection object) Loading of every record in collection especialy shall be BIG degrade of performance
	 * @param boolean $value
	 */
	public function setSynchronized($value) {
		if (!is_bool($value)) {
			throw new LBoxException("Bad parameter value. Must be boolean!");
		}
		$this->synchronized = $value;
		if (!$value) {
			$this->loaded	= false;
		}
		else {
			$this->storeToCache();
		}
	}

	/**
	 * setter for passwordChanged - ONLY FOR AbstractRecords INSTANCES!!!
	 * @param boolean $value
	 */
	public function setPasswordChanged($value) {
		if (!is_bool($value)) {
			throw new LBoxException("Bad parameter value. Must be boolean!");
		}
		$this->passwordChanged = $value;
	}

	/**
	 * check for the same record in database table
	 * @return boolean
	 */
	public function isDuplicit() {
		try {
			$sql	= $this->getQueryBuilder()->getSelectColumns($this->getClassVar("tableName"), array(), $this->getWhere());
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
			$this->getDb()->setQuery($sql, true);
			$result = $this->getDb()->initiate();

			return ($result->getNumRows() > 0);
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
			return $this->getClassVar("idColName");
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	//== protected functions ===============================================================

	/**
	 * check if record is in database
	 * @return boolean
	 */
	public function isInDatabase() {
		try {
			if (is_bool($this->isInDatabase)) {
				return $this->isInDatabase;
			}
			$idColName = $this->getClassVar("idColName");
			if (!array_key_exists($idColName, $this->params) || strlen($this->params[$idColName]) < 1) {
				return false;
			}

			$where	= new QueryBuilderWhere();
			$where	->addConditionColumn($idColName, $this->params[$idColName]);
			$sql	= $this->getQueryBuilder()->getSelectColumns($this->getClassVar("tableName"), array("$idColName"), $where);

			$this->getDb()->setQuery($sql, true);
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
			$result = $this->getDb()->initiate();
			if ($result->getNumRows() < 1) {
				return $this->isInDatabase = false;
			}
			else if ($result->getNumRows() > 1) {
				throw new LBoxException("Defined record values match MORE than one record! We have MORE THAN ONE duplicit records in database!");
			}
			return $this->isInDatabase = true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * alias for getBoundedM1Instance and getBounded1MInstance - dynamicaly chooose prior type, or throws Exception
	 * @param $type - is defined by $this->getBounded()
	 * @param $filter - Is specified by AbstractRecords class
	 * @param $order  - Is specified by AbstractRecords class
	 * @return AbstractRecords
	 * @throws LBoxException
	 */
	public function getBoundedInstance($type = false, $filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		try {
			$boundedM1  = $this->getClassVar("boundedM1", true);
			$bounded1M = $this->getClassVar("bounded1M", true);
			switch (true) {
				case (array_key_exists($type, $boundedM1) && array_key_exists($type, $bounded1M)):
						throw new LBoxException("Type $type is defined in both bounded types definitions (1:M and M:1), cannot choose one automaticaly!");
					break;
				case (array_key_exists($type, $boundedM1)):
						$instance = $this->getBoundedM1Instance($type, $filter, $order, $limit, $whereAdd);
					break;
				case (array_key_exists($type, $bounded1M)):
						$instance = $this->getBounded1MInstance($type, $filter, $order, $limit, $whereAdd);
					break;
				default:
					throw new LBoxException("Type $type has no bounded types definition!");
			}
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for instance of class maping bounded table for relation M:1
	 * @param $type - is defined by $this->getBounded()
	 * @param $filter - Is specified by AbstractRecords class
	 * @param $order  - Is specified by AbstractRecords class
	 * @return AbstractRecords
	 */
	protected function getBoundedM1Instance($type = false, $filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		if (!class_exists($type)) {
			throw new LBoxException("Type $type has no defined Class!");
		}
		try {
			// must add foreign key rulle into filter (Record to find in bounded type must have primarykey value of foreignkey of this type)///
			$boundedM1  = $this->getClassVar("boundedM1", true);
			$fKName     = $boundedM1[$type];
			// return false if foreignkey column value is empty
			if (strlen($this->__get($fKName)) < 1) {
				return false;
			}
			// first we need to get type of one record from bounded type (static var in bounded type class)									 //
			$OneRecordType = eval("return $type::\$itemType;");																			 //
			// then we can get column name to add it into filter rulle (primarykey in bounded type = foreignkey column in this type)		 //
			if (!array_key_exists($type, $boundedM1)) {
				throw new LBoxException("Cannot find bounded column '". $boundedM1[$type] ."' in my columns!");                                                                                                                //
			}                                                                                                                                //
			$boundedIdColName  			 = eval("return $OneRecordType::\$idColName;");														 //
			$fKFilter[$boundedIdColName] = $this->params[$boundedM1[$type]];															     //
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// add custom filter to foreignkey rulle
			$filter   = is_array($filter) ? array_merge($fKFilter, $filter) : $fKFilter;
			// create instance
			$instance = new $type($filter, $order, $limit, $whereAdd);
			if (!$instance instanceof AbstractRecords) {
				throw new LBoxException("Type $type is not AbstractRecords's descendant!");
			}
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for instance of class maping bounded table for relation 1:M
	 * @param $type - is defined by $this->getBounded()
	 * @param $filter - Is specified by AbstractRecords class
	 * @param $order  - Is specified by AbstractRecords class
	 * @return AbstractRecords
	 */
	protected function getBounded1MInstance($type = false, $filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		if (!class_exists($type)) {
			throw new LBoxException("Type $type has not defined Class!");
		}
		try {
			$bounded1M = $this->getClassVar("bounded1M", true);
			if (!array_key_exists($type, $bounded1M)) {
				throw new LBoxException("Cannot find bounded column '". $bounded1M[$type] ."' in my columns!");                                                                                                                //
			}                                                                                                                                //
			// must add foreign key rulle into filter (Records to find in bounded type must have foreignkey value of primarykey of this type)
			$fKFilter[$bounded1M[$type]] = $this->params[$this->getClassVar("idColName")];
			// add custom filter to foreignkey rulle
			$filter   = is_array($filter) ? array_merge($fKFilter, $filter) : $fKFilter;
			// create instance
			$instance = new $type($filter, $order, $limit, $whereAdd);
			if (!$instance instanceof AbstractRecords) {
				throw new LBoxException("Type $type is not AbstractRecords's descendant!");
			}
			// return false if foreignkey column value is empty
			/* disabled because filtered records problems
			if (!$instance->valid()) {
			return false;
			}
			*/
			return $instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for types, their are bounded thrue M:N relation
	 * @param $type - is defined by $this->getBounded()
	 * @return AbstractRecords
	 *
	protected function getBoundedMNInstance($type = false) {
NOT TESTED AND TOTALY INEFFICIENT FOR SURE
		if (!class_exists($type)) {
			throw new LBoxException("Type $type has not defined Class!");
		}
		try {
			$boundedMN = $this->getClassVar("boundedMN", true);
			$idColName = $this->getClassVar("idColName");
			if ( (strlen($nextFKey = $boundedMN[$type]["nextForeignKey"]) < 1) ||
			(strlen($myFKey   = $boundedMN[$type]["myForeignKey"]) < 1) ||
			(strlen($mNTable  = $boundedMN[$type]["MNTable"]) < 1) ) {
				throw new LBoxException("Bad bounded type definition! All parameters must be set!");
			}
			// for numeric id value
			if (is_numeric($this->params[$idColName])) {
				$myIdValue = $this->params[$idColName];
			}
			// for non-numeric id value
			else {
				$myIdValue = "'". $this->params[$idColName] ."'";
			}
			$sql = "SELECT $nextFKey FROM `$mNTable` WHERE $myFKey = ". $myIdValue;
			$this->getDb()->setQuery($sql, true);
			$result = $this->getDb()->initiate();

			// No bounded record in database
			if ($result->getNumRows() < 1) {
				return false;
			}

			// get essential variables
			$records     = new $type();
			$recordClass = eval("return $type::\$itemsType;");
			if (!is_subclass_of($records, "AbstractRecords")) {
				throw new LBoxException("Bad bounded type definition! Must be child of AbstractRecords class!");
			}

			// set records collection
			while ($result->next()) {
				$record = new $recordClass($result->$nextFKey);
				$records->addRecord($record);
			}
			return $records;
		}
		catch (Exception $e) {
			throw $e;
		}
	}*/

	/**
	 * getter of db class instance
	 * @return DbControlInterface
	 */
	protected function getDb() {
		try {
			if (!is_a($this->db, "DbControlInterface")) {
				$this->db = new DbControl($this->getClassVar("task", true), $this->getClassVar("charset", true));
				if (strlen($this->getClassVar("dbName", true)) > 0) {
					$this->db->selectDb($this->getClassVar("dbName"));
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
				$this->queryBuilder = new QueryBuilder(self::$task);
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
	public function getClassVar($varName, $force = false) {
		if (!is_string($varName)) {
			throw new LBoxException("Bad parameter varName, must be string!");
		}
		try {
			if (array_key_exists($varName, $this->cacheClassVars)) {
				return $this->cacheClassVars[$varName];
			}
			$className = get_class($this);
			$value = eval("return $className::\$$varName;");
			if (!$force && empty($value) && !is_numeric($value) && !is_bool($value)) {
				throw new LBoxException("Static variable $className::$varName is empty or not exists!");
			}
			return $this->cacheClassVars[$varName] = $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns WHERE instance created from $this->params
	 * @return QueryBuilderWhere
	 */
	protected function getWhere() {
		try {

			$where = new QueryBuilderWhere();
			// if we know ID column value, we dont need other params
			$idColName 	= $this->getIdColName();
			$idColNameValue = $this->params[$idColName];
			if (strlen((string)$idColNameValue) > 0) {
				$where->addConditionColumn($idColName, $idColNameValue);
				return $where;
			}

			// set WHERE conditions
			if (is_array($this->params)) {
				foreach ($this->params as $colName => $colValue) {
					// do not pass empty values into the WHERE rulle
					if ( (empty($colValue)) && (!is_numeric($colValue)) ) {
						continue;
					}
					// password columns
					if ( (in_array($colName, $this->getClassVar("passwordColNames", true))) && ($this->passwordChanged) ) {
						$where->addConditionColumn($colName, md5($value));
					}
					// other columns
					else {
						$where->addConditionColumn($colName, $value);
					}
				}
			}
			return $where;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Returns true if the record has children in tree structure
	 * @return bool
	 * @throws Exception
	 */
	public function hasChildren() {
		try {
			if (is_bool($this->hasChildren)) {
				return $this->hasChildren;
			}
			$tableName		= $this->getClassVar("tableName");
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if ($this->isInCache()) {
				$data	= LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->getData();
				if (is_numeric($cacheValue = $data["systemrecord_haschildren"])) {
					return $this->hasChildren = (bool)$cacheValue;
				}
			}
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];
			$idColName		= $this->getClassVar("idColName");
			$id				= $this->get($idColName);

			$where			= new QueryBuilderWhere();
			$where			->addConditionColumn($pidColName, $id);
			$result			= $this->getDb()->initiateQuery($this->getQueryBuilder()->getSelectColumns($tableName, array($idColName), $where, array(1)));
			//$result			= $this->getDb()->initiateQuery("SELECT $idColName FROM $tableName WHERE $pidWhere LIMIT 1");
			return $this->hasChildren = ($result->getNumRows() > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Returns if the record has parent in tree structure
	 * @return bool
	 * @throws Exception
	 */
	public function hasParent() {
		try {
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$this->load();
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];
			return ($this->get($pidColName) > 0);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter of record's tree table children
	 * @return AbstractRecords
	 * @throws Exception
	 */
	public function getChildren($filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		try {
			$tableName		= $this->getClassVar("tableName");
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$itemsType		= $this->getClassVar("itemsType");
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$pidColName		= $treeColNames[2];
			$bidColName		= $treeColNames[3];
			$idColName		= $this->getClassVar("idColName");
			$id				= $this->get($idColName);
			$bId			= $this->get($bidColName);
//var_dump($this->params[$idColName] .": getChildren");

			$filter[$pidColName]	= $id;
			$filter[$bidColName]	= $bId;
			$order			= (is_array($order) && (count($order) > 0)) ? $order : array($lftColName => 1);
			$children		= new $itemsType($filter, $order, $limit, $whereAdd);
			$children		->setIsTree($this->isTree());
			return $children;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * appends child below Record
	 * @param AbstractRecord $child - child to append
	 * @throws Exception
	 */
	public function addChild(AbstractRecord $child) {
		try {
			$className 	= get_class($this);
			$idColName	= $this->getIdColName();
			if (!($child instanceof $className)) {
				throw new LBoxException("Cannot append child of another type into '$className' type!");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if (!$child->$idColName == $this->params[$idColName]) {
				throw new LBoxException("You are trying to set Record as child of itself!");
			}
			$this			->load();
			$child			->load();
			$child			->setSynchronized(false);
			$this			->clearCache();
			$tableName		= $this->getClassVar("tableName");
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$pidColName		= $treeColNames[2];
			$bidColName		= $treeColNames[3];
			$myId			= $this->get($this->getIdColName());
			$myLft			= $this->get($lftColName);
			$myRgt			= $this->get($rgtColName);
			$myBid			= $this->get($bidColName);
			$chId			= $child->$idColName;
			$chLft			= (is_numeric($child->$lftColName) && $child->$lftColName > 0) ? $child->$lftColName : $this->getMaxTreeRgt()+1;
			$chRgt			= (is_numeric($child->$rgtColName) && $child->$rgtColName > 0) ? $child->$rgtColName : $chLft+1;
			$chWeight		= $chRgt-$chLft+1;

			$quotesColumnName		= $this->getQueryBuilder()->getQuotesColumnName();
			$lftColNameSlashed		= reset($quotesColumnName) . $lftColName . end($quotesColumnName);
			$rgtColNameSlashed		= reset($quotesColumnName) . $rgtColName . end($quotesColumnName);
			$pidColNameSlashed		= reset($quotesColumnName) . $pidColName . end($quotesColumnName);
			$bidColNameSlashed		= reset($quotesColumnName) . $bidColName . end($quotesColumnName);

			if ($child->$pidColName == $this->$idColName) {
				// throw new LBoxException("This already is my child!");
				return;
			}
			if ($child->$lftColName < $this->get($lftColName) && $child->$rgtColName > $this->get($rgtColName)) {
				throw new LBoxException("You are trying to set my parent as child!");
			}
			// child is descendant
			if ($child->$lftColName > $this->get($lftColName) && $child->$rgtColName < $this->get($rgtColName)) {
				$childsParent = $child->getParent()->removeChild($child);
				$child->setSynchronized(false);
				$this->setSynchronized(false);
				$this->addChild($child);
				return;
			}

			$maxRgt			= $this->getDb()->initiateQuery("SELECT MAX($rgtColName) AS max_rgt FROM $tableName")->max_rgt;

			$this->getDb()->transactionStart();

			// cut child and descendants from tree
			/*$sqlChildTmp = "UPDATE $tableName SET
								$lftColName = $lftColName + $maxRgt,
								$rgtColName = $rgtColName + $maxRgt
									WHERE 	$lftColName > ($chLft-1)
									AND 	$rgtColName < ($chRgt+1)
							";*/
			$whereChildTmp	= new QueryBuilderWhere();
			$whereChildTmp	->addConditionColumn($lftColName, $chLft-1, 2);
			$whereChildTmp	->addConditionColumn($rgtColName, $chRgt+1, -2);
// var_dump(__CLASS__ ."::". __LINE__ .": ". $this->getQueryBuilder()->getUpdate($tableName, 	array(	$lftColName => "<<$lftColNameSlashed + $maxRgt>>",$rgtColName => "<<$rgtColNameSlashed + $maxRgt>>",),$whereChildTmp));
			$this->getDb()->initiateQuery($this->getQueryBuilder()->getUpdate($tableName, 	array(	$lftColName => "<<$lftColNameSlashed + $maxRgt>>",
																									$rgtColName => "<<$rgtColNameSlashed + $maxRgt>>",),
																							$whereChildTmp));
			$childTmp 		= clone $child;
			$childTmp->setSynchronized(false);
			$childTmp->load();
			$chTmpLft		= $childTmp->$lftColName;
			$chTmpRgt		= $childTmp->$rgtColName;

			// shift tree left
			$sqls		= array();
			$sqls2		= array();
			if ($chRgt < $myRgt) {
				/*$sqls[] = "UPDATE $tableName SET
				$lftColName = $lftColName-$chWeight
									WHERE 	$lftColName > $chRgt
									AND 	$lftColName < $myRgt
							";*/
				$i			= count((array)$sqls);
				$wheres[$i]	= new QueryBuilderWhere();
				$wheres[$i]	->addConditionColumn($lftColName, $chRgt, 2);
				$wheres[$i]	->addConditionColumn($lftColName, $myRgt, -2);
				$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed-$chWeight>>"), $wheres[$i]);

				/*$sqls[] = "UPDATE $tableName SET
				$rgtColName = $rgtColName-$chWeight
									WHERE 	$rgtColName > $chRgt
									AND 	$rgtColName < $myRgt
									";*/
				$i			= count((array)$sqls);
				$wheres[$i]	= new QueryBuilderWhere();
				$wheres[$i]	->addConditionColumn($rgtColName, $chRgt, 2);
				$wheres[$i]	->addConditionColumn($rgtColName, $myRgt, -2);
				$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($rgtColName => "<<$rgtColNameSlashed-$chWeight>>"), $wheres[$i]);
			}
			// shift tree right
			else {
				/*$sqls[] = "UPDATE $tableName SET
				$lftColName = $lftColName+$chWeight
									WHERE 	$lftColName > $myRgt
									AND 	$lftColName < $chLft
									";*/
				$i			= count((array)$sqls);
				$wheres[$i]	= new QueryBuilderWhere();
				$wheres[$i]	->addConditionColumn($lftColName, $myRgt, 2);
				$wheres[$i]	->addConditionColumn($lftColName, $chLft, -2);
				$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed+$chWeight>>"), $wheres[$i]);

				/*$sqls[] = "UPDATE $tableName SET
				$rgtColName = $rgtColName+$chWeight
									WHERE 	$rgtColName > ($myRgt-1)
									AND 	$rgtColName < $chLft
									";*/
				$i			= count((array)$sqls);
				$wheres[$i]	= new QueryBuilderWhere();
				$wheres[$i]	->addConditionColumn($rgtColName, $myRgt-1, 2);
				$wheres[$i]	->addConditionColumn($rgtColName, $chLft, -2);
				$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($rgtColName => "<<$rgtColNameSlashed+$chWeight>>"), $wheres[$i]);
			}
			foreach ($sqls as $sql) {
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
				$this->getDb()->initiateQuery($sql);
			}

			$this->setSynchronized(false);
			$this->load();
			$chTmpDiff	= $chTmpRgt-($this->rgt-1);

			// shift $child recursive and updates bid for any children
			/*$sqls2[] = "UPDATE $tableName SET
			$lftColName = $lftColName - $chTmpDiff
								, $rgtColName = $rgtColName - $chTmpDiff
								, $bidColName = $myBid
								WHERE 	$lftColName > ($chTmpLft-1)
								AND 	$rgtColName < ($chTmpRgt+1)
						";*/
			$i				= count((array)$sqls2);
			$wheres2[$i]	= new QueryBuilderWhere();
			$wheres2[$i]	->addConditionColumn($lftColName, $chTmpLft-1, 2);
			$wheres2[$i]	->addConditionColumn($rgtColName, $chTmpRgt+1, -2);
			$sqls2[$i]		= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed - $chTmpDiff>>",
																					$rgtColName	=> "<<$rgtColNameSlashed - $chTmpDiff>>",
																					$bidColName => $myBid), $wheres2[$i]);

			// update $child's pid
			/*$sqls2[] = "UPDATE $tableName SET
			$pidColName = $myId
								WHERE 	$idColName = $chId
						";*/
			$i				= count((array)$sqls2);
			$wheres2[$i]	= new QueryBuilderWhere();
			$wheres2[$i]	->addConditionColumn($idColName, $chId);
			$sqls2[$i]		= $this->getQueryBuilder()->getUpdate($tableName, array($pidColName => $myId), $wheres2[$i]);

			foreach ($sqls2 as $sql) {
// var_dump("hallloooo: ". __CLASS__ ."::". __LINE__ .": ". $sql);
				$this->getDb()->initiateQuery($sql);
			}

			$this	->getDb()->transactionCommit();
			$this	->clearCache();
			$child->load();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * removes child from Record to the end of table
	 * @param AbstractRecord $child - child to remove
	 * @throws Exception
	 */
	public function removeChild(AbstractRecord $child) {
		try {
			$this->load();
			$child->load();
			$this->clearCache();
			$className 	= get_class($this);
			$idColName	= $this->getIdColName();
			if (!($child instanceof $className)) {
				throw new LBoxException("Cannot manipulate parental relations between another types in '$className'");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if (!$child->$idColName == $this->params[$idColName]) {
				throw new LBoxException("Bad argument - the same record like \$this!");
			}

			$this->load();
			$child->load();
			$tableName		= $this->getClassVar("tableName");
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$pidColName		= $treeColNames[2];
			$bidColName		= $treeColNames[3];
			$myId			= $this->get($this->getIdColName());
			$myLft			= $this->get($lftColName);
			$myRgt			= $this->get($rgtColName);
			$chId			= $child->$idColName;
			$chLft			= $child->$lftColName;
			$chRgt			= $child->$rgtColName;
			$chWeight		= $chRgt-$chLft+1;
			$chBidNew		= $this->getMaxTreeBid()+1;

			$quotesColumnName		= $this->getQueryBuilder()->getQuotesColumnName();
			$lftColNameSlashed		= reset($quotesColumnName) . $lftColName . end($quotesColumnName);
			$rgtColNameSlashed		= reset($quotesColumnName) . $rgtColName . end($quotesColumnName);
			$pidColNameSlashed		= reset($quotesColumnName) . $pidColName . end($quotesColumnName);
			$bidColNameSlashed		= reset($quotesColumnName) . $bidColName . end($quotesColumnName);

			if ($child->$pidColName !== $this->get($idColName)) {
				throw new LBoxException("Bad argument - its not my child!");
			}

			$maxRgt			= $this->getDb()->initiateQuery("SELECT MAX($rgtColName) AS max_rgt FROM $tableName")->max_rgt;
			$chDiff			= ($maxRgt+1) - $chLft;

			$this->getDb()->transactionStart();

			// cut child and descendants from tree
			/*$sqlsChildUpd[] = "UPDATE $tableName SET
									$lftColName = $lftColName + $chDiff,
									$rgtColName = $rgtColName + $chDiff,
									$bidColName = $chBidNew
										WHERE 	$lftColName > ($chLft-1)
										AND 	$rgtColName < ($chRgt+1)
							";*/
			$i					= count((array)$sqlsChildUpd);
			$wheresChildUpd[$i]	= new QueryBuilderWhere();
			$wheresChildUpd[$i]	->addConditionColumn($lftColName, $chLft-1, 2);
			$wheresChildUpd[$i]	->addConditionColumn($rgtColName, $chRgt+1, -2);
			$sqlsChildUpd[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed + $chDiff>>",
																				$rgtColName => "<<$rgtColNameSlashed + $chDiff>>",
																				$bidColName => $chBidNew), $wheresChildUpd[$i]);

			/*$sqlsChildUpd[] = "UPDATE $tableName SET
									$pidColName = NULL
									WHERE 	$idColName = $chId
							";*/
			$i					= count((array)$sqlsChildUpd);
			$wheresChildUpd[$i]	= new QueryBuilderWhere();
			$wheresChildUpd[$i]	->addConditionColumn($idColName, $chId);
			$sqlsChildUpd[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($pidColName => 0), $wheresChildUpd[$i]);

			foreach ($sqlsChildUpd as $sqlChildUpd) {
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
				$this->getDb()->initiateQuery($sqlChildUpd);
			}

			// shift relevant records left
			/*$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName-$chWeight
								WHERE 	$lftColName > $chRgt
						";*/
			$i			= count((array)$sqls);
			$wheres[$i]	= new QueryBuilderWhere();
			$wheres[$i]	->addConditionColumn($lftColName, $chRgt, 2);
			$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed-$chWeight>>"), $wheres[$i]);

			/*$sqls[] = "UPDATE $tableName SET
			$rgtColName = $rgtColName-$chWeight
								WHERE 	$rgtColName > $chRgt
						";*/
			$i			= count((array)$sqls);
			$wheres[$i]	= new QueryBuilderWhere();
			$wheres[$i]	->addConditionColumn($rgtColName, $chRgt, 2);
			$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($rgtColName => "<<$rgtColNameSlashed-$chWeight>>"), $wheres[$i]);

			foreach ($sqls as $sql) {
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
				$this->getDb()->initiateQuery($sql);
			}

			$this->getDb()->transactionCommit();

			$child->setSynchronized(false);
			$this->setSynchronized(false);
			$this	->clearCache();
			$child->load();
			$this->load();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * removes child record from tree and move it at the end of table
	 * @throws Exception
	 */
	public function removeFromTree() {
		try {
			$this->clearCache();
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];

			$this->load();
			if (!$this->params[$pidColName]) {
				throw new LBoxException("I have no parent!");
			}
			$this->getParent()->removeChild($this);
			$this	->clearCache();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * moves child before me
	 * @param AbstractRecord $$sibling - child to remove
	 * @throws Exception
	 */
	public function moveSiblingBefore(AbstractRecord $sibling) {
		try {
			$this->load();
			$sibling->load();
			$this->clearCache();
			$tableName		= $this->getClassVar("tableName");
			$className 		= get_class($this);
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$pidColName		= $treeColNames[2];
			$bidColName		= $treeColNames[3];
			$idColName	= $this->getIdColName();
			if (!$sibling instanceof $className) {
				throw new LBoxException("Cannot manipulate records relations between records of another types in '$className' type!");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if (!$sibling->$idColName == $this->params[$idColName]) {
				throw new LBoxException("You are trying to move me before me!");
			}

			$quotesColumnName		= $this->getQueryBuilder()->getQuotesColumnName();
			$lftColNameSlashed		= reset($quotesColumnName) . $lftColName . end($quotesColumnName);
			$rgtColNameSlashed		= reset($quotesColumnName) . $rgtColName . end($quotesColumnName);
			$pidColNameSlashed		= reset($quotesColumnName) . $pidColName . end($quotesColumnName);
			$bidColNameSlashed		= reset($quotesColumnName) . $bidColName . end($quotesColumnName);

			// set sibling as parent's child if is not
			if ($sibling->$pidColName != $this->get($pidColName)) {
				$this->getParent()->addChild($sibling);
				$this->setSynchronized(false);
				$this->load();
			}

			$myId			= $this->get($this->getIdColName());
			$myLft			= $this->get($lftColName);
			$myRgt			= $this->get($rgtColName);
			$myBid			= $this->get($bidColName);
			$myWeight		= $myRgt-$myLft+1;
			$maxRgt			= $this->getMaxTreeRgt();
			$chLft			= $sibling->$lftColName;
			$chRgt			= $sibling->$rgtColName;
			$chWeight		= $chRgt-$chLft+1;
			$weightDiff		= $chWeight - $myWeight;
			$diff			= $chLft - $myLft;

			if ($chRgt == $myLft-1) return;

			// cut sibling from tree
			/*$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName + $maxRgt,
			$rgtColName = $rgtColName + $maxRgt
							WHERE $lftColName 	> ($chLft-1)
							AND $rgtColName 	< ($chRgt+1)
						";*/
			$i			= count((array)$sqls);
			$wheres[$i]	= new QueryBuilderWhere();
			$wheres[$i]	->addConditionColumn($lftColName, $chLft-1, 2);
			$wheres[$i]	->addConditionColumn($rgtColName, $chRgt+1, -2);
			$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed + $maxRgt>>",
																				$rgtColName => "<<$rgtColNameSlashed + $maxRgt>>"), $wheres[$i]);

			// make space for sibling
			/*$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName + $chWeight,
			$rgtColName = $rgtColName + $chWeight
							WHERE $lftColName 	> ($myLft-1)
							AND $rgtColName 	< $chLft
						";*/
			$i			= count((array)$sqls);
			$wheres[$i]	= new QueryBuilderWhere();
			$wheres[$i]	->addConditionColumn($lftColName, $myLft-1, 2);
			$wheres[$i]	->addConditionColumn($rgtColName, $chLft, -2);
			$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed + $chWeight>>",
																				$rgtColName => "<<$rgtColNameSlashed + $chWeight>>"), $wheres[$i]);

			// move sibling before
			/*$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName - ($diff+$maxRgt),
			$rgtColName = $rgtColName - ($diff+$maxRgt),
			$bidColName = $myBid,
							WHERE $lftColName 	> ($chLft+$maxRgt-1)
							AND $rgtColName 	< ($chRgt+$maxRgt+1)
						";*/
			$i			= count((array)$sqls);
			$wheres[$i]	= new QueryBuilderWhere();
			$wheres[$i]	->addConditionColumn($lftColName, $chLft+$maxRgt-1, 2);
			$wheres[$i]	->addConditionColumn($rgtColName, $chRgt+$maxRgt+1, -2);
			$sqls[$i]	= $this->getQueryBuilder()->getUpdate($tableName, array($lftColName => "<<$lftColNameSlashed - ". ($diff+$maxRgt) .">>",
																				$rgtColName => "<<$rgtColNameSlashed - ". ($diff+$maxRgt) .">>",
																				$bidColName => $myBid,), $wheres[$i]);

			foreach ($sqls as $sql) {
// var_dump(__CLASS__ ."::". __LINE__ .": ". $sql);
				$this->getDb()->initiateQuery($sql);
			}
			$this->setSynchronized(false);
			$sibling->setSynchronized(false);
			$this	->clearCache();
			$this->load();
			$sibling->load();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for tree table parent (if any)
	 * @return AbstractRecord
	 * @throws Exception
	 */
	public function getParent() {
		try {
			if (!$this->hasParent()) {
				return NULL;
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$this->load();
			$tableName		= $this->getClassVar("tableName");
			$className 		= get_class($this);
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];
			$parent			= new $className($this->get($pidColName));
			$parent			->setIsTree($this->isTree());
			return $parent;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function isParentOf(AbstractRecord $descendant) {
		try {
			if (!$this->isAncestorOf($descendant)) {
				return false;
			}
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];
			$idColName		= $this->getClassVar("idColName");
			return ($descendant->$pidColName == $this->params[$idColName]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if given record is descendant
	 * @param AbstractRecord $child
	 * @return bool
	 * @throws Exception
	 */
	public function isAncestorOf(AbstractRecord $descendant) {
		try {
			$className 		= get_class($this);
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$bidColName		= $treeColNames[3];
			$idColName		= $this->getClassVar("idColName");
			$this->load();

			if (!($descendant instanceof $className)) {
				throw new LBoxException("Cannot check parental relations between another types in '$className'");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if ($descendant->$idColName == $this->params[$idColName]) {
				throw new LBoxException("Bad argument - the same record like \$this!");
			}
			return ((	$this->params[$lftColName] < $descendant->$lftColName) &&
					(	$this->params[$rgtColName] > $descendant->$rgtColName));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * return descendants count
	 * @return int
	 * @throws Exception
	 */
	public function getDescendantsCount() {
		try {
			$tableName		= $this->getClassVar("tableName");
			$idColName		= $this->getClassVar("idColName");
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$bidColName		= $treeColNames[3];
			$myLft			= $this->params[$lftColName];
			$myRgt			= $this->params[$rgtColName];
			$myBid			= $this->params[$bidColName];

			$where			= new QueryBuilderWhere();
			$where			->addConditionColumn($lftColName, $myLft, 2);
			$where			->addConditionColumn($rgtColName, $myRgt, -2);
			$where			->addConditionColumn($bidColName, $myBid);
			$sql			= $this->getQueryBuilder()->getSelectCount($tableName, $where);
// var_dump(__CLASS__ ."::". __LINE__ .": ". $this->getQueryBuilder()->getSelectCount($tableName, $where));
			$result			= $this->getDb()->initiateQuery($this->getQueryBuilder()->getSelectCount($tableName, $where));
			return (int)$result->count;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns table max($idColName)
	 * @return int
	 */
	protected function getMaxId() {
		try {
			$tableName		= $this->getClassVar("tableName");
			$idColName		= $this->getIdColName();
// var_dump(__CLASS__ ."::". __LINE__ .": ". $this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$idColName));
			$result = $this->getDb()->initiateQuery($this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$idColName));
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->$idColName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns table max(rgt) if table is tree
	 * @return int
	 */
	protected function getMaxTreeRgt() {
		try {
			if (!$this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$tableName		= $this->getClassVar("tableName");
			$treeColNames	= $this->getClassVar("treeColNames");
			$rgtColName		= $treeColNames[1];
// var_dump(__CLASS__ ."::". __LINE__ .": ". $this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$rgtColName));
			$result = $this->getDb()->initiateQuery($this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$rgtColName));
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->$rgtColName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns table max(bid) if table is tree
	 * @return int
	 */
	protected function getMaxTreeBid() {
		try {
			if (!$this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$tableName		= $this->getClassVar("tableName");
			$treeColNames	= $this->getClassVar("treeColNames");
			$bidColName		= $treeColNames[3];
// var_dump(__CLASS__ ."::". __LINE__ .": ". $this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$bidColName));
			$result = $this->getDb()->initiateQuery($this->getQueryBuilder()->getSelectMaxColumns($tableName, (array)$bidColName));
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->$bidColName;
		}
		catch (Exception $e) {
			throw $e;
		}
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
			if ($this->isInCache()) {
				$data	= LBoxCacheAbstractRecord::getInstance($this->getClassVar("tableName"), $this->params[$this->getClassVar("idColName")], self::getCacheGroup())->getData();
				if (is_numeric($cacheValue = $data["systemrecord_istree"])) {
					return self::$isTree[$className] = (bool)$cacheValue;
				}
			}
			$columns 		= $this->getClassVar("treeColNames");
			$tableName		= $this->getClassVar("tableName");

			try {
				$this->getDb()->initiateQuery($this->getQueryBuilder()
				->getSelectColumns($tableName, $columns, new QueryBuilderWhere, array(1)));
			}
			catch (DbControlException $e) {
				// throw $e;
				// column does not found - table is not tree
				self::$isTree[$className] = false;
				return self::$isTree[$className];
				break;
			}
			self::$isTree[$className] = true;
			return self::$isTree[$className];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* ckecks if table exists in database yet
	* @return bool
	* @throws Exception
	*/
	protected function doesTableExistsInDatabase() {
		try {
			$className	= get_class($this);
			if (is_bool(self::$doesTableExistsInDatabaseByTypes[$className])) {
				return self::$doesTableExistsInDatabaseByTypes[$className];
			}
			if (strlen($this->getClassVar("dbName", true)) > 0) {
				$schema	= $this->getClassVar("dbName");
			}
			else {
				$dbSelector	= new DbSelector();
				$schema	= $dbSelector->getTaskSchema(self::$task);
			}
			$value	= $this->getDb()->initiateQuery($this->getQueryBuilder()->getDoesTableExists($this->getClassVar("tableName"), $schema))->getNumRows() > 0;
			return self::$doesTableExistsInDatabaseByTypes[$className] = $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * table created flag
	 * @var bool
	 */
	protected $tableCreated = false;

	/**
	* creates database table by attributes
	* @throws Exception
	*/
	protected function createTable() {
		try {
			if ($this->doesTableExistsInDatabase()) {
				return;
			}
			$attributes	= $this->getClassVar("attributes");
			if (count(current($attributes)) < 1) {
				throw new LBoxException(get_class($this) ." - Attributes not set - cannot automaticaly create database table!");
			}
			$type			= get_class($this);
			$tableName		= $this->getClassVar("tableName");
			$idColName		= $this->getClassVar("idColName");
LBoxFirePHP::log("creating table '$tableName'");
			foreach ($attributes as $attribute) {
				switch (true) {
					case ($attribute["name"] == $idColName):
							throw new LBoxException(get_class($this) ." - Primary colname definition found in '$tableName' - it's not allowed, PK properties must be stricty generated!");
						break;
					/*case is_numeric(array_search($attribute["name"], self::$treeColNames)):
							throw new LBoxException($attribute["name"] ." is registered as tree colname and cannot be defined as param in '$type'!");
						break;*/
				}
			}
			// tree sloupce definujeme do attributes - tim definujeme tree strukturu recordu
			/*if ($this->isTree()) {
				$treeColNames	= self::$treeColNames;
				array_reverse($treeColNames);
				foreach (self::$treeColNames as $treeColName) {
					array_unshift($attributes, array("name"=>$treeColName, "type"=>"int", "visibility"=>"protected"));
				}
			}*/
			array_unshift($attributes, array("name"=>$idColName, "type"=>"int", "notnull" => true, "autoincrement" => true, "visibility"=>"protected"));
			$this->getDb()->initiateQuery($this->getQueryBuilder()->getCreateTable($tableName, $attributes, array("pk" => "$idColName")));
			self::$doesTableExistsInDatabaseByTypes[$className]	= true;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * adds columns by $columns - columns must be defined in $atttributes
	 * @param array $columns
	 * @throws Exception
	 */
	protected function addColumns($columns = array()) {
		try {
			$tableName	= $this->getClassVar("tableName");
			$attributes	= $this->getClassVar("attributes");
			$cols		= array();
LBoxFirePHP::log("adding columns into '$tableName': ". implode(", ", $columns));
			foreach ($columns as $column) {
				foreach ($attributes as $attribute) {
					if ($attribute["name"] == $column) {
						$cols[]	= $attribute;
					}
					// write empty param for checkAttributesColumnsExists checking
					$this->params[$column] = "";
				}
			}
			$this->getDb()->initiateQuery($this->getQueryBuilder()->getAddColumns($tableName, $cols));

			$this->resetCache();
			$this->isCacheSynchronized	= false;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * adds missing columns into database table by attributes
	 * @throws Exception
	 */
	protected function checkAttributesColumnsExists() {
		try {
			// check defined columns
			$attributes		= $this->getClassVar("attributes");
			$createColumns	= array();
			foreach ($attributes as $attribute) {
				if (count($attribute) < 1) continue;
				if (!array_key_exists($attribute["name"], $this->params)) {
					$createColumns[]	= $attribute["name"];
				}
			}
			if (count($createColumns) > 0) {
				$this->addColumns($createColumns);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na attributy
	 * @return array
	 */
	public function getAttributes() {
		return $this->getClassVar("attributes");
	}
}
?>