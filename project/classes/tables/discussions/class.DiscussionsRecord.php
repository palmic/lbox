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
											"DiscussionPostsListRecords",
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
											array("name"=>"urlParam", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"created", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"type", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"title", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"email", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"www", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"body", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"nick", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											array("name"=>"ref_acces", "type"=>"int", "notnull" => true, "visibility"=>"protected"),
											);
	
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
				$this->params["created"] = date("Y-m-d H:i:s");
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
}
?>