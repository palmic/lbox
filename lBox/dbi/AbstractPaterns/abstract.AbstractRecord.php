<?php

/**
 * abstract class - parent of classes, that encapsulates database record
 * you can specify new record by no $id specified. Cannot load record from db whithout $id!
 * Iterator interface implemented for array iterate to get Columns whithout known of their names. Cannot set them like array items! You can set them like public objects params
 * need DbControl class!
 * @author Michal Palma <palmic at email dot cz>
 * @date 2006-02-07
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
	 * isTree table flag - do not define it its only cache of isTree() method
	 * @var bool
	 */
	protected $isTree;

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
	 * database task id
	 * @var string
	 */
	public static $task ="";

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

	//== constructors ====================================================================

	/**
	 * constructor
	 * @param integer $id - leave it empty to create new record
	 */
	public function __construct($id = 0) {
		try {
			if (!empty($id)) {
				$this->params[$this->getClassVar("idColName")] = $id;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}


	//== destructors ====================================================================

	public function __destruct() {
	}

	//== public functions ===============================================================

	public function __toString() {
		$className	 	= get_class($this);
		$tableName		= $this->getClassVar("tableName");
		$string  		= "$className from table '$tableName'\n";
		foreach ($this as $colName => $colValue) {
			$string .= "$colName => $colValue, ";
		}
		$string .= "\n";
		return $string;
	}

	/**
	 * __set() for gradate environs from "array-implementation" of record params
	 * @param $name - variable name
	 * @param $value - variable value
	 */
	public function __set($name, $value) {
		try {
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
	 * __get() for gradate environs from "array-implementation" of record params
	 * @param $varName - variable name
	 */
	public function __get($varName = "*") {
		try {
			if ($varName == "*") {
				$this->load();
				return $this->params;
			}
			if (!$this->params[$varName]) {
				$this->load();
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
	 * getter for types, their are bounded thrue M:1, or 1:M relation
	 * @param string $type - bounded type definition - must be child of AbstractRecords class
	 * @param $filter - Is specified by AbstractRecords class
	 * @param $order  - Is specified by AbstractRecords class
	 * @return AbstractRecords
	 */
	public function getBounded($type, $filter = false, $order = false, $limit = false, $whereAdd = "") {
		try {
			// try to find in child static $boundedM1 array
			if (@array_key_exists($type, $this->getClassVar("boundedM1", true))) {
				$instance = $this->getBoundedM1Instance($type, $filter, $order, $whereAdd);
			}
			// try to find in child static $bounded1M array
			else if (@array_key_exists($type, $this->getClassVar("bounded1M", true))) {
				$instance = $this->getBounded1MInstance($type, $filter, $order, $limit, $whereAdd);
			}
			// try to find in child static $boundedMN array
			else if (@array_key_exists($type, $this->getClassVar("boundedMN", true))) {
				// for getBoundedMNInstance() we does not support additional parameters like $order or $filter
				$instance = $this->getBoundedMNInstance($type);
			}
			else {
				throw new LBoxException("Type '$type' is not defined in bounded types maping in ". get_class($this) ." class!");
			}
			// empty foreign key column value - return false
			if (!$instance) {
				return false;
			}
			if (!is_subclass_of($instance, "AbstractRecords")) {
				throw new LBoxException("Bad bounded type definition! Must be child of AbstractRecords class!");
			}
			return $instance;
		}
		catch (Exception $e) {
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
			if (!array_key_exists($idColName, $this->params)) {
				$this->params[$idColName] = $this->getMaxId()+1;
			}
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

				// separate values by ,
				if (strlen($vals) > 0) {
					$vals .= ", ";
				}

				// numeric values whithout apostrophes
				if (is_int(trim($vvalue))) {
					$value = $vvalue;
				}
				else if ($vvalue == "NULL") {
					$value = $vvalue;
				}
				// other values
				else {
					// escape strings
					$vvalue = stripslashes($vvalue);
					$vvalue = addslashes($vvalue);
					$value = "'$vvalue'";
				}

				// secure password handling
				if ( (in_array($vname, $this->getClassVar("passwordColNames", true))) && ($this->passwordChanged) ) {
					$vals .= "`$vname`=PASSWORD($value)";
				} else {
					$vals .= "`$vname`=$value";
				}
			}
			if (strlen($vals) < 1) {
				return;
			}

			// handle tree record system attributes
			if ($this->isTree()) {
				$treeColNames 	= $this->getClassVar("treeColNames");
				$lftColname		= $treeColNames[0];
				$rgtColname		= $treeColNames[1];
				$pidColname		= $treeColNames[2];
				$bidColname		= $treeColNames[3];
				if (	!is_numeric($this->params[$lftColname])
					|| 	!is_numeric($this->params[$rgtColname])) {
					$bid			= $this->getMaxTreeBid() + 1;
					$lft			= $this->getMaxTreeRgt() + 1;
					$rgt			= $lft + 1;
					$vals 			.= ", $lftColname=$lft, $rgtColname=$rgt, $bidColname=$bid";
				}
			}

			// update query
			if ($this->isInDatabase()) {
				// we dont want to update records whithout primary key known
				if (empty($this->params[$idColName])) {
					throw new LBoxException("Cannot UPDATE record whithout primary key value known!");
				}

				// numeric ID value
				if (is_numeric($this->params[$idColName])) {
					$where = " WHERE ". $idColName ."=". $this->params[$idColName];
				}
				// other ID value
				else {
					$where = " WHERE ". $idColName ."='". $this->params[$idColName] ."'";
				}

				$sql  = "UPDATE ". $tableName ." SET ";
				$sql .= $vals;
				$sql .= $where;
			}
			// insert query
			else {
				$sql  = "INSERT INTO ". $tableName ." SET ";
				$sql .= $vals;
			}
			$this->getDb()->initiateQuery($sql);
		}
		catch(Exception $e) {
			throw $e;
		}
		if (!$this->params[$idColName]) {
			$this->params[$idColName]	= $this->getMaxId();
		}
		$this->load();
	}

	/**
	 * load record from database
	 * @throws Exception
	 */
	public function load() {
		try {
			if ($this->synchronized) {
				return;
			}
			$idColName = $this->getIdColName();
			$where = $this->getWhere();

			// cannot load without idColName value
			if (strlen((string)$this->params[$idColName]) < 1) {
				$className	= get_class($this);
				throw new LBoxException("Cannot load Record without primary column value! ($idColName)");
			}

			// do not pass query whithout WHERE
			if (strlen($where) < 1) {
				throw new LBoxException("Cannot load record by ". get_class($this) ." whithout where specification! Set id or other parameters for get ONE record from db.");
			}
			$sql = "SELECT * FROM `". $this->getClassVar("tableName") ."` ". $where;
			$this->getDb()->setQuery($sql, true);
			$result = $this->getDb()->initiate();
			if ($result->getNumRows() < 1) {
				// $dbName = $this->getClassVar("dbName");
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
		}
		catch(Exception $e) {
			throw $e;
		}
		$this->passwordChanged  = false;
		$this->synchronized     = true;
	}

	/**
	 * delete Database record
	 */
	public function delete() {
		try {
			$idColName = $this->getClassVar("idColName");
			if (!$this->params[$idColName]) {
				throw new LBoxException("Cannot delete database record whithout id specified! Can delete more records!!!");
			}
			if ($this->isTree() && $this->hasChildren()) {
				throw new LBoxException("Cannot delete database record with children!!!");
			}
			if ($this->isTree()) {
				$treeColNames	= $this->getClassVar("treeColNames");
				$lftColName		= $treeColNames[0];
				$rgtColName		= $treeColNames[1];
				$myLft			= $this->get($lftColName);
				$myRgt			= $this->get($rgtColName);
			}

			$this->getDb()->transactionStart();

			$value	 = is_numeric($this->params[$idColName]) ? $this->params[$idColName] : "'". $this->params[$idColName] ."'";
			$sql  	 = "DELETE FROM ". $this->getClassVar("tableName");
			$sql 	.= " WHERE ". $idColName ."=". $value;
			$this->getDb()->setQuery($sql, true);
			if (!$this->getDb()->initiate()) {
				throw new LBoxException("Cannot delete database record with ". $idColName ."=". $this->params[$idColName]);
			}
			if ($this->isTree()) {
				// shift tree
				$sqlsTree[0]  	 = "UPDATE ". $this->getClassVar("tableName");
				$sqlsTree[0] 	.= " SET $lftColName = $lftColName-2";
				$sqlsTree[0] 	.= " WHERE ". $lftColName .">". $myRgt;
				$sqlsTree[1]  	 = "UPDATE ". $this->getClassVar("tableName");
				$sqlsTree[1] 	.= " SET $rgtColName = $rgtColName-2";
				$sqlsTree[1] 	.= " WHERE ". $rgtColName .">". $myRgt;
				foreach ($sqlsTree as $sqlTree) {
					if (!$this->getDb()->initiateQuery($sqlTree)) {
						throw new LBoxException("Cannot shift tree after deleting record with ". $idColName ."=". $this->params[$idColName]);
					}
				}
			}
			$this->getDb()->transactionCommit();
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
	}

	/**
	 * check for the same record in database table
	 * @return boolean
	 */
	public function isDuplicit() {
		try {
			$where  = $this->getWhere();
			$sql    = "SELECT * FROM `". $this->getClassVar("tableName") ."` ". $where;
			$this->getDb()->setQuery($sql, true);
			$result = $this->getDb()->initiate();
			if ($result->getNumRows() < 1) {
				return false;
			}
			return true;
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
	protected function isInDatabase() {
		try {
			$idColName = $this->getClassVar("idColName");
			if (empty($this->params[$idColName])) {
				return false;
			}

			// numeric ID value
			if (is_numeric($this->params[$idColName])) {
				$where = " WHERE ". $idColName ."=". $this->params[$idColName];
			}
			// other ID value
			else {
				$where = " WHERE ". $idColName ."='". $this->params[$idColName] ."'";
			}
			$sql = "SELECT ". $idColName ." FROM `". $this->getClassVar("tableName") ."` ". $where;

			$this->getDb()->setQuery($sql, true);
			$result = $this->getDb()->initiate();
			if ($result->getNumRows() < 1) {
				return false;
			}
			else if ($result->getNumRows() > 1) {
				throw new LBoxException("Defined record values match MORE than one record! We have MORE THAN ONE duplicit records in database!");
			}
			return true;
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
	protected function getBoundedM1Instance($type = false, $filter = false, $order = false, $whereAdd = "") {
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
			if (!$this->params[$boundedM1[$type]]) {                                                                                     //
				throw new LBoxException("Cannot find bounded column '". $boundedM1[$type] ."' in my columns!");                                                                                                                //
			}                                                                                                                                //
			$boundedIdColName  			 = eval("return $OneRecordType::\$idColName;");														 //
			$fKFilter[$boundedIdColName] = $this->params[$boundedM1[$type]];															     //
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// add custom filter to foreignkey rulle
			$filter   = is_array($filter) ? array_merge($fKFilter, $filter) : $fKFilter;
			// create instance
			$instance = new $type($filter, $order, $whereAdd);
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
	protected function getBounded1MInstance($type = false, $filter = false, $order = false, $limit = false, $whereAdd = "") {
		if (!class_exists($type)) {
			throw new LBoxException("Type $type has not defined Class!");
		}
		try {
			$bounded1M = $this->getClassVar("bounded1M", true);
			// must add foreign key rulle into filter (Records to find in bounded type must have foreignkey value of primarykey of this type)
			$fKFilter[$bounded1M[$type]] = $this->params[$this->getClassVar("idColName")];
			// add custom filter to foreignkey rulle
			$filter   = is_array($filter) ? array_merge($fKFilter, $filter) : $fKFilter;
			// create instance
			$instance = new $type($filter, $order, $limit, $whereAdd);
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
	 */
	protected function getBoundedMNInstance($type = false) {
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
	}

	/**
	 * getter of db class instance
	 * @return DbControlInterface
	 */
	protected function getDb() {
		try {
			if (!is_a($this->db, "DbControlInterface")) {
				$this->db = new DbControl(self::$task, self::$charset);
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
	 * getter of static variable from child class
	 * @param string $varname - Name of child class static variable
	 * @param boolean $force - set to true for no-exception in case of missing value
	 */
	public function getClassVar($varName, $force = false) {
		if (!is_string($varName)) {
			throw new LBoxException("Bad parameter varName, must be string!");
		}
		try {
			if ($this->cacheClassVars[$varName]) {
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
	 * return WHERE SQL clause created from $this->params
	 * @return string
	 */
	protected function getWhere() {
		try {

			// if we know ID column value, we dont need other params
			$idColName 	= $this->getIdColName();
			$idColNameValue = $this->params[$idColName];
			if (strlen((string)$idColNameValue) > 0) {
				if(is_string($idColNameValue)) {
					return "WHERE $idColName = '$idColNameValue'";
				}
				return "WHERE $idColName = $idColNameValue";
			}

			// set WHERE clause
			$where = "";
			if (is_array($this->params)) {
				foreach ($this->params as $colName => $colValue) {
					// do not pass empty values into the WHERE rulle
					if ( (empty($colValue)) && (!is_numeric($colValue)) ) {
						continue;
					}
					if (strlen(trim($where)) > 0) {
						$where .= " AND";
					}
					else {
						$where .= " WHERE";
					}
					// numeric values
					if (is_numeric($colValue)) {
						$value = $colValue;
					}
					// other values
					else {
						// escape strings
						$colValue = stripslashes($colValue);
						$colValue = addslashes($colValue);
						$value = "'".mysql_escape_string($colValue)."'";
					}
					// password columns
					if ( (in_array($colName, $this->getClassVar("passwordColNames", true))) && ($this->passwordChanged) ) {
						$where .= " UCASE($colName)=UCASE(PASSWORD($value))";
					}
					// other columns
					else {
						if (is_numeric($value)) {
							$where .= " $colName=$value";
						}
						else {
							//$where .= " UCASE($colName)=UCASE($value)";
							$where .= " $colName=" . stripslashes($value);
						}
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
	 * Returns if the record has children in tree structure
	 * @return bool
	 * @throws Exception
	 */
	public function hasChildren() {
		try {
			$tableName		= $this->getClassVar("tableName");
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			$treeColNames	= $this->getClassVar("treeColNames");
			$pidColName		= $treeColNames[2];
			$idColName		= $this->getClassVar("idColName");
			$id				= $this->get($idColName);

			$pidWhere 		= is_numeric($id) ? "$pidColName=$id" : "$pidColName='$id'";
			$result			= $this->getDb()->initiateQuery("SELECT $idColName FROM $tableName WHERE $pidWhere LIMIT 1");
			return ($result->getNumRows() > 0);
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
	public function getChildren() {
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

			$filter 		= array($pidColName => $id, $bidColName => $bId);
			$order			= array($lftColName => 1);

			return new $itemsType($filter, $order);
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
			$myBid			= $this->get($bidColName);
			$chId			= $child->$idColName;
			$chLft			= (is_numeric($child->$lftColName) && $child->$lftColName > 0) ? $child->$lftColName : $this->getMaxTreeRgt()+1;
			$chRgt			= (is_numeric($child->$rgtColName) && $child->$rgtColName > 0) ? $child->$rgtColName : $chLft+1;
			$chWeight		= $chRgt-$chLft+1;

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
			$sqlChildTmp = "UPDATE $tableName SET
								$lftColName = $lftColName + $maxRgt,
								$rgtColName = $rgtColName + $maxRgt
									WHERE 	$lftColName > ($chLft-1)
									AND 	$rgtColName < ($chRgt+1)
							";
			$this->getDb()->initiateQuery($sqlChildTmp);
			$childTmp 		= clone $child;
			$childTmp->setSynchronized(false);
			$childTmp->load();
			$chTmpLft		= $childTmp->$lftColName;
			$chTmpRgt		= $childTmp->$rgtColName;

			// shift tree left
			if ($chRgt < $myRgt) {
				$sqls[] = "UPDATE $tableName SET
				$lftColName = $lftColName-$chWeight
									WHERE 	$lftColName > $chRgt
									AND 	$lftColName < $myRgt
							";
				$sqls[] = "UPDATE $tableName SET
				$rgtColName = $rgtColName-$chWeight
									WHERE 	$rgtColName > $chRgt
									AND 	$rgtColName < $myRgt
									";
			}
			// shift tree right
			else {
				$sqls[] = "UPDATE $tableName SET
				$lftColName = $lftColName+$chWeight
									WHERE 	$lftColName > $myRgt
									AND 	$lftColName < $chLft
									";
				$sqls[] = "UPDATE $tableName SET
				$rgtColName = $rgtColName+$chWeight
									WHERE 	$rgtColName > ($myRgt-1)
									AND 	$rgtColName < $chLft
									";
			}
			/*
			 if ($this->params["id"]==9) {
			 $testRecs 	= new TestRecords(NULL, array("lft" => 1));
			 listTestTree($testRecs);
			 }
			 */
			foreach ($sqls as $sql) {
				//if ($this->params["id"]==9) DbControl::$debug = true;
				$this->getDb()->initiateQuery($sql);
				//if ($this->params["id"]==9) DbControl::$debug = false;
			}
			/*
			 if ($this->params["id"]==9) {
			 $testRecs 	= new TestRecords(NULL, array("lft" => 1));
			 listTestTree($testRecs);
			 }
			 */
			//if ($this->params["id"]==9) return;

			$this->setSynchronized(false);
			$this->load();
			$chTmpDiff	= $chTmpRgt-($this->rgt-1);

			// shift $child recursive
			$sqls2[] = "UPDATE $tableName SET
			$lftColName = $lftColName - $chTmpDiff
								, $rgtColName = $rgtColName - $chTmpDiff
								, $bidColName = $myBid
								WHERE 	$lftColName > ($chTmpLft-1)
								AND 	$rgtColName < ($chTmpRgt+1)
						";
			// update $child's pid
			$sqls2[] = "UPDATE $tableName SET
			$pidColName = $myId
								WHERE 	$idColName = $chId
						";

			foreach ($sqls2 as $sql) {
				//if ($this->params["id"]==9) DbControl::$debug = true;
				$this->getDb()->initiateQuery($sql);
				//if ($this->params["id"]==9) DbControl::$debug = false;
			}

			$this->getDb()->transactionCommit();
			$child->setSynchronized(false);
			$child->load();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * removes child from Record at end of table
	 * @param AbstractRecord $child - child to remove
	 * @throws Exception
	 */
	public function removeChild(AbstractRecord $child) {
		try {
			$this->load();
			$child->load();
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
			
			if ($child->$pidColName !== $this->get($idColName)) {
				throw new LBoxException("Bad argument - its not my child!");
			}

			$maxRgt			= $this->getDb()->initiateQuery("SELECT MAX($rgtColName) AS max_rgt FROM $tableName")->max_rgt;
			$chDiff			= ($maxRgt+1) - $chLft;

			$this->getDb()->transactionStart();

			// cut child and descendants from tree
			$sqlsChildUpd[] = "UPDATE $tableName SET
									$lftColName = $lftColName + $chDiff,
									$rgtColName = $rgtColName + $chDiff,
									$bidColName = $chBidNew
										WHERE 	$lftColName > ($chLft-1)
										AND 	$rgtColName < ($chRgt+1)
							";
			$sqlsChildUpd[] = "UPDATE $tableName SET
									$pidColName = NULL
									WHERE 	$idColName = $chId
							";
			foreach ($sqlsChildUpd as $sqlChildUpd) {
				$this->getDb()->initiateQuery($sqlChildUpd);
			}

			// shift relevant records left
			$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName-$chWeight
								WHERE 	$lftColName > $chRgt
						";
			$sqls[] = "UPDATE $tableName SET
			$rgtColName = $rgtColName-$chWeight
								WHERE 	$rgtColName > $chRgt
						";
			foreach ($sqls as $sql) {
				$this->getDb()->initiateQuery($sql);
			}

			$this->getDb()->transactionCommit();

			$child->setSynchronized(false);
			$this->setSynchronized(false);
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
			$this->load();
			if (!$this->params[$pidColName]) {
				throw new LBoxException("I have no parent!");
			}
			$this->getParent()->removeChild($this);
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
			$tableName		= $this->getClassVar("tableName");
			$className 		= get_class($this);
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$pidColName		= $treeColNames[2];
			$bidColName		= $treeColNames[3];
			$idColName	= $this->getIdColName();
			if (!($child instanceof $className)) {
				throw new LBoxException("Cannot manipulate records relations between records of another types in '$className' type!");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if (!$child->$idColName == $this->params[$idColName]) {
				throw new LBoxException("You are trying to move me before me!");
			}

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
			$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName + $maxRgt,
			$rgtColName = $rgtColName + $maxRgt
							WHERE $lftColName 	> ($chLft-1)
							AND $rgtColName 	< ($chRgt+1)
						";
			// make space for sibling
			$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName + $chWeight,
			$rgtColName = $rgtColName + $chWeight
							WHERE $lftColName 	> ($myLft-1)
							AND $rgtColName 	< $chLft
						";
			// move sibling before
			$sqls[] = "UPDATE $tableName SET
			$lftColName = $lftColName - ($diff+$maxRgt),
			$rgtColName = $rgtColName - ($diff+$maxRgt),
			$bidColName = $myBid,
							WHERE $lftColName 	> ($chLft+$maxRgt-1)
							AND $rgtColName 	< ($chRgt+$maxRgt+1)
						";
			/*
			 if ($myId == 7) {
			 $testRecs 	= new TestRecords(NULL, array("lft" => 1));
			 listTestTree($testRecs);
			 }
			 */
			foreach ($sqls as $sql) {
				//if ($myId == 7) DbControl::$debug = true;
				$this->getDb()->initiateQuery($sql);
				//if ($myId == 7) DbControl::$debug = false;
			}
			$this->setSynchronized(false);
			$sibling->setSynchronized(false);
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
			return new $className($this->get($pidColName));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * checks if given record is descendant of me
	 * @param AbstractRecord $child
	 * @return bool
	 * @throws Exception
	 */
	public function isParentOf(AbstractRecord $descendant) {
		try {
			$className 		= get_class($this);
			$treeColNames	= $this->getClassVar("treeColNames");
			$lftColName		= $treeColNames[0];
			$rgtColName		= $treeColNames[1];
			$bidColName		= $treeColNames[3];
			if (!($descendant instanceof $className)) {
				throw new LBoxException("Cannot check parental relations between another types in '$className'");
			}
			if (!$isTree = $this->isTree()) {
				throw new LBoxException("Table '$tableName' seems not to be tree!");
			}
			if ($descendant->$idColName != $this->params[$idColName]) {
				throw new LBoxException("Bad argument - the same record like \$this!");
			}
			if ($descendant->$bidColName != $this->params[$bidColName]) {
				return false;
			}
			
			return ($this->params[$lftColName] < $descendant->$lftColName && $this->params[$rgtColName] > $descendant->$rgtColName);
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
			$result			= $this->getDb()->initiateQuery("SELECT count($idColName) AS count FROM $tableName
																WHERE $lftColName > $myLft AND $rgtColName < $myRgt AND $bidColName = $myBid");
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
			$result = $this->getDb()->initiateQuery("SELECT MAX(`$idColName`) AS id_max FROM $tableName");
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->id_max;
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
			$result = $this->getDb()->initiateQuery("SELECT MAX(`$rgtColName`) AS rgt_max FROM $tableName");
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->rgt_max;
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
			$result = $this->getDb()->initiateQuery("SELECT MAX(`$bidColName`) AS bid_max FROM $tableName");
			if ($result->getNumRows() < 1) return 0;
			else return (int)$result->bid_max;
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
			if (is_bool($this->isTree)) {
				return $this->isTree;
			}
			$className 		= get_class($this);
			$columns 		= $this->getClassVar("treeColNames");
			$tableName		= $this->getClassVar("tableName");
			$this->getDb()->setQuery("SELECT `<1>` FROM  `$tableName` LIMIT 1");
			foreach ($columns as $column) {
				try {
					$this->getDb()->initiate($column);
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