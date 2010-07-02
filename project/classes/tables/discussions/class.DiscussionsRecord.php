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
											array("name"=>"title", "type"=>"shorttext", "notnull" => false, "visibility"=>"protected"),
											array("name"=>"email", "type"=>"shorttext", "notnull" => false, "visibility"=>"protected"),
											array("name"=>"www", "type"=>"shorttext", "notnull" => false, "visibility"=>"protected"),
											array("name"=>"body", "type"=>"longtext", "notnull" => false, "visibility"=>"protected"),
											array("name"=>"nick", "type"=>"shorttext", "notnull" => false, "visibility"=>"protected"),
											array("name"=>"ref_acces", "type"=>"int", "notnull" => false, "visibility"=>"protected"),
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

	/**
	 * cache var
	 * @var LBoxForm
	 */
	protected $form;

	/**
	 * getter na form pro vlozeni prispevku
	 * Pokud je zavolan na record diskuze, vrati form pro vlozeni noveho prispevku
	 * POkud je zavolan na record prispevku, vrati form pro vlozeni odpovedi na nej
	 * @return LBoxForm
	 */
	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			
			if ($this->hasParent()) {
				$title = $this->getParent()->title;
				switch (true) {
					case (preg_match("/^re( *)(\d+)(.*)/i", $title, $regs)) :
							$num = $regs[2]+1;
							$title = "RE$num". trim($regs[3]);
						break;
					case (preg_match("/^re(.*)/i", $title, $regs)) :
						$title = "RE2". $regs[1];
						break;
					default:
						$title = "RE: $title";
				}
			}
			else {
				$title	= "";
			}
			
			$id					= $this->params[self::$idColName];

			$controls["pid"]	= new LBoxFormControlFillHidden("pid", "", $id);
				$controls["pid"]	->setDisabled();
			$controls["title"]	= new LBoxFormControlFill("title", "title", $title);
			$controls["nick"]	= new LBoxFormControlFill("nick", "nick", LBoxXT::isLogged() ? LBoxXT::getUserXTRecord()->nick : "");
				if (LBoxXT::isLogged() && strlen(LBoxXT::getUserXTRecord()->nick) > 0) {
					$controls["nick"]->setDisabled();
				}
				$controls["nick"]	->setTemplateFilename("lbox_form_control_nick.html");
				$controls["nick"]	->setRequired();
			$controls["email"]	= new LBoxFormControlFill("email", "email", LBoxXT::isLogged() ? LBoxXT::getUserXTRecord()->email : "");
				if (LBoxXT::isLogged() && strlen(LBoxXT::getUserXTRecord()->email) > 0) {
					$controls["email"]->setDisabled();
				}
				$controls["email"]	->setTemplateFilename("lbox_form_control_email.html");
				$controls["email"]	->addValidator(new LBoxFormValidatorEmail);
			$controls["www"]	= new LBoxFormControlFill("www", "www", LBoxXT::isLogged() ? LBoxXT::getUserXTRecord()->www : "");
				if (LBoxXT::isLogged() && strlen(LBoxXT::getUserXTRecord()->www) > 0) {
					$controls["www"]->setDisabled();
				}
				$controls["www"]	->setTemplateFilename("lbox_form_control_www.html");
				$controls["www"]	->addValidator(new LBoxFormValidatorURLHTTPHTTPS);
			$controls["body"]	= new LBoxFormControlFill("body", "body");
				$controls["body"]	->setTemplateFilename("discussion_body.html");
				$controls["body"]	->setRequired();

			$this->form			= new LBoxForm("discussion-$id-post", "post", $this->hasParent() ? "Odpověď na příspěvek" : "Nový příspěvek", "odeslat");
			$this->form			->addProcessor(new LBoxFormProcessorDev);

			foreach ($controls as $control) {
				$control	->addFilter(new LBoxFormFilterTrim);
				$this->form->addControl($control);
			}

			return $this->form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * cache var
	 * @var bool
	 */
	protected $hasParent;

	/**
	 * pretizeno o cachovani
	 * @see lBox/dbi/AbstractPaterns/AbstractRecord::hasParent()
	 */
	public function hasParent() {
		try {
			if (is_bool($this->hasParent)) {
				return $this->hasParent;
			}
			return $this->hasParent = parent::hasParent();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	
	/**
	 * cache var
	 * @var DiscussionsRecord
	 */
	protected $parent;

	/**
	 * pretizeno o cachovani
	 * @see lBox/dbi/AbstractPaterns/AbstractRecord::hasParent()
	 */
	public function getParent() {
		try {
			if ($this->parent) {
				return $this->parent;
			}
			return $this->parent = parent::getParent();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>