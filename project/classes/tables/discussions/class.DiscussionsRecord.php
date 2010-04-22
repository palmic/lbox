<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-12-08
*/
class DiscussionsRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "DiscussionsRecords";
	public static $tableName    	= "discussions";
	public static $idColName    	= "id";
	
	public static $boundedM1 		= array("AccesRecords" => "ref_acces");
	
	public static $dependingRecords	= array(
											"DiscussionsPostsRecords",
	);

/**
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `pid` (`pid`),
  KEY `location` (`pageId`,`urlParam`),
  KEY `bid` (`bid`),
  KEY `ref_acces` (`ref_acces`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8
 */
	protected static $attributes	=	array(
											array("name"=>"pageId", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"urlParam", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"created", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"type", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"title", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"email", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"www", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"body", "type"=>"longtext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"nick", "type"=>"shorttext", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"ref_acces", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											);
	
	/**
	 * cache var
	 * @var DiscussionsRecords
	 */
	protected $children;
	
	/**
	 * pretizeno o nastaveni tree structure
	 */
	public function __construct($id = NULL, $loaded = false) {
		try {
			// set tree structure
			$treeColNames	= self::$treeColNames;
			$treeColNames	= array_reverse(self::$treeColNames);
			foreach ($treeColNames as $treeColName) {
				array_unshift(self::$attributes, array("name"=>$treeColName, "type"=>"int"));
			}
			parent::__construct($id, $loaded);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			if (!$this->params["created"]) {
				$this->params["created"] = time();
			}
			if (!$this->params["type"]) {
				$this->params["type"] = "discussion";
			}
			$this->params["ref_acces"]	= AccesRecord::getInstance()->id;
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na Acces
	 * @return AccesRecord
	 * @throws Exception
	 */
	public function getAcces() {
		try {
			return $this->getBoundedM1Instance("AccesRecords")->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na potomky, ktere budou v prvni urovni prispevku serazeny sestupne
	 * @return DiscussionsRecords
	 */
	public function getChildren() {
		try {
			if ($this->children instanceof DiscussionsRecords) {
				return $this->children;
			}
			$treeColNames	= $this->getClassVar("treeColNames");
			$this->children = parent::getChildren(false, $this->hasParent() ? array($treeColNames[0] => 1) : array($treeColNames[0] => 0));
			return $this->children;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>