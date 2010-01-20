<?php
/**
 * komponenta kompletne resici diskuzi
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0

* @since 2007-12-08
*/
class Discussion extends LBoxComponent
{
	/**
	 * discussion DB Record
	 * @var DiscussionsRecord
	 */
	protected $record;

	/**
	 * nazev _GET parametru reply to - pouzito v sablone a v executedPrepend
	 * @var string
	 */
	protected $discussionReplyToParamName	= "replyTo";

	protected function executePrepend(PHPTAL $TAL) {
		// DbControl::$debug = true;
		try {
			// XT admin
			if (LBoxXT::isLoggedAdmin() && count($_POST[$this->getFormGroupName()]["xt"]) > 0) {
				switch ($_POST[$this->getFormGroupName()]["xt"]["action"]) {
					case "delete":
						$this->deletePost($_POST[$this->getFormGroupName()]["xt"]["id"]);
						$this->reload();
					break;
				}
			}
			$TAL->xtAdmin		= LBoxXT::isLoggedAdmin();
			
			$TAL->discussion 		= $this->getRecord();
			$TAL->form				= $this->getFormArray();
			$TAL->replyToParamName	= $this->discussionReplyToParamName;
			$TAL->saved				= false;
			$TAL->spamDefenseJS		= $this->isSpamDefenseJSNeeded();
			
			// reply to
			if (is_numeric($replyToId = $_GET[$this->getFormGroupName()][$this->discussionReplyToParamName])) {
				$replyTo = new DiscussionsPostsRecord($replyToId);
				// jen pokud je replyTo opravdu discussion post z teto diskuze
				if ($this->getRecord()->isParentOf($replyTo)) {
					$titleReply	= $replyTo->title;
					switch (true) {
						case (eregi("^re( +)([0-9]+)(.*)", $titleReply, $regs)) :
							$num = $regs[2]+1;
							$titleReply = "RE$num". trim($regs[3]);
							break;
						case (eregi("^re([0-9]+)(.*)", $titleReply, $regs)) :
							$num = $regs[1]+1;
							$titleReply = "RE$num". trim($regs[2]);
							break;
						case (eregi("^re(.*)", $titleReply, $regs)) :
							$titleReply = "RE2". $regs[1];
							break;
						default:
							$titleReply = "RE: $titleReply";
					}
					// nacteni hodnot odpovedi na replyTo post
					$formArray 	= $this->getFormArray();
					$controls	= $formArray["controls"];
					$controls["pid"]["value"]		= $replyToId;
					$controls["title"]["value"]		= $titleReply;
					// $controls["nick"]["value"]	= "";
					// $controls["email"]["value"]	= "";
					// $controls["www"]["value"]	= "";
					$controls["body"]["value"]		= "";
					$formArray["controls"]	= $controls;
					$TAL->form				= $formArray;
				}
			}
			// save data vyhazujeme jen v pripade, ze nejde o nacteni formulare pro odpoved
			else {
				// v pripade zachyceni konkretni vyjimky zamenime data formu za data z vyjimky - totozna, ale se zaznamenanymi chybami
				try {
					$this->saveData();
				}
				catch (LBoxExceptionForm $eF) {
					$TAL->form	= $eF->getFormArray();
				}
			}
			if ($_SESSION["forms"][$this->getFormGroupName()]["succes"]) {
				unset($_SESSION["forms"][$this->getFormGroupName()]);
				$TAL->saved = true;
			}
			switch ($this->getRecord()->numPosts) {
				case 1:
					$TAL->postsWordDecl1 = true;
					break;
				case 2:
				case 3:
				case 4:
					$TAL->postsWordDecl2 = true;
					break;
				default:
					$TAL->postsWordDecl5 = true;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function executeStart() {
		try {
			parent::executeStart();
			$this->config->setOutputFilter(new OutputFilterDiscussionComponent($this->config));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Prekontroluje data, pokud byla odeslana a upravena je vraci
	 * V pripade chyby vyhozuje vyjimku, ktera obsahuje i kompletni data formu s oznacenymi chybami
	 * @return array
	 * @throws LBoxExceptionForm
	 */
	protected function checkData() {
		try {
			$formGroupName			= $this->getFormGroupName();
			if (count($_POST[$formGroupName]) < 1) {
				return;
			}

			$error 		= false;
			$formArray	= $this->getFormArray();

			// controla spam defense JS
			if ($this->isSpamDefenseJSNeeded()) {
				if ($_POST[$formGroupName]["defense"] != 1) {
					$error 									= true;
					$formArray["error"]["spamDefenseJS"] 	= true;
				}
			}

			$controls	= $formArray["controls"];

			// kontroly
			if (!$error)
			foreach ($controls as $name => $control) {
				if ($name != "body") {
					$control["value"]	= strip_tags($control["value"]);
				}
				// required
				if ($control["required"]) {
					if (strlen(strip_tags($control["value"])) < 1) {
						$control["error"]["empty"] = true;
						$error = true;
					}
				}
				// maxlength
				if (is_numeric($control["maxLength"]))
				if (strlen($control["value"]) > $control["maxLength"]) {
					$control["error"]["tooLong"] = true;
					$error = true;
				}
				// kontrola detailni
				if (!$error);
				switch ($name) {
					case "pid":
						// pokud je pid prazdny nastavime jako parenta diskuzi
						if (strlen($control["value"]) < 1) {
							$control["value"] = $this->getRecord()->id;
						}
						break;
					case "nick":
						if (!$this->isNickFree($control["value"])) {
							if (!LBoxXT::isLogged() || (LBoxXT::getInstance()->getUserXTRecord()->nick != $control["value"])) {
								$control["error"]["isNotFree"] = true;
								$error = true;
								if (LBoxXT::isLogged()) {
									$control["value"] = LBoxXT::getUserXTRecord()->nick;
								}
							}
						}
						break;
					case "email":
						if (strlen($control["value"]))
						if (!eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $control["value"])) {
							$control["error"]["invalid"] = true;
							$error = true;
						}
						break;
					case "www":
						break;
					case "body":
						break;
				}
				$controls[$name] = $control;
			}
			$formArray["controls"] = $controls;
			if ($error) {
				$exception = new LBoxExceptionForm(LBoxExceptionForm::MSG_FORM_DATA_INVALID, LBoxExceptionForm::CODE_FORM_DATA_INVALID);
				$exception->setFormArray($formArray);
				throw $exception;
			}
			else {
				// upravy
				foreach ($controls as $name => $control) {
					switch ($name) {
						case "pid":
							// pokud je pid prazdny nastavime jako parenta diskuzi
							if (strlen($control["value"]) < 1) {
								$control["value"] = $this->getRecord()->id;
								break;
							}
							break;
						case "nick":
							if (LBoxXT::isLogged()) {
								$control["value"] = LBoxXT::getInstance()->getUserXTRecord()->nick;
							}
							break;
						case "email":
							if (LBoxXT::isLogged()) {
								$control["value"] = LBoxXT::getInstance()->getUserXTRecord()->email;
							}
							break;
						case "www":
							if (LBoxXT::isLogged()) {
								$control["value"] = LBoxXT::getInstance()->getUserXTRecord()->www;
							}
							if (strlen($control["value"]) > 0) {
								switch (true) {
									case eregi("^http(s?)://", $control["value"], $regs):
										break;
									case eregi("^www.(.*)", $control["value"], $regs):
										$control["value"] = "http://www.". $regs[1];
										break;
									default:
										$control["value"] = "http://". $control["value"];
								}
							}
							break;
									case "body":
										// remove html and any potencial php
										$control["value"] = eregi_replace(self::PATTERN_HTML, "", $control["value"]);
										$control["value"] = strip_tags($control["value"]);
										// replace urls with links
										$control["value"] = eregi_replace("[a-zA-Z]+://([.]?[a-zA-Z0-9_/-])*", "<a href=\"\\0\">\\0</a>", $control["value"]);
										$control["value"] = eregi_replace("(^| |.)(www([.]?[a-zA-Z0-9_/-])*)", "\\1<a href=\"http://\\2\">\\2</a>", $control["value"]);
											
										// parse it into paragraphs
										$content 			= explode("\n", $control["value"]);
										$control["value"]	= "";
										foreach($content as $row) {
											if (strlen($row = trim($row)) < 1) continue;
											$control["value"] .= "<p>". $row ."</p>";
										}
										break;
					}
					$controls[$name] = $control;
				}
			}
			$formArray["controls"] = $controls;
			return $formArray;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Ulozi data formulare, pokud byl odeslan
	 * @throws Exception
	 */
	protected function saveData() {
		try {
			$formGroupName			= $this->getFormGroupName();
			if (count($_POST[$formGroupName]) < 1) {
				return;
			}
			// ziskame upravena, zkontrolovana data formulare
			$formArray = $this->checkData();

			$post	= new DiscussionsPostsRecord();
			foreach ($formArray["controls"] as $paramName => $control) {
				if ($paramName == "pid") continue;
				$post->$paramName	= $control["value"];
			}
			$post->pageId 		= $this->getPageConfigByUrl()->id;
			$post->urlParam  	= $this->getLocationUrlParam();
			$post->store();
			$parent	= new DiscussionsRecord($formArray["controls"]["pid"]["value"]);
			$parent->addChild($post);
			$_SESSION["forms"][$formGroupName]["succes"] = true;
			$this->reload();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci pole s daty formulare a dalsimi vecmi
	 * @return array
	 */
	protected function getFormArray() {
		try {
			$formGroupName			= $this->getFormGroupName();
			$form 					= array();
			$controls				= array();

			// id
			$controls["pid"]["id"]			= $formGroupName. "-pid";
			$controls["title"]["id"]		= $formGroupName. "-title";
			$controls["nick"]["id"]			= $formGroupName. "-nick";
			$controls["email"]["id"]		= $formGroupName. "-email";
			$controls["www"]["id"]			= $formGroupName. "-www";
			$controls["body"]["id"]			= $formGroupName. "-body";

			// groupName
			$controls["pid"]["name"]		= $formGroupName. "[pid]";
			$controls["title"]["name"]		= $formGroupName. "[title]";
			$controls["nick"]["name"]		= $formGroupName. "[nick]";
			$controls["email"]["name"]		= $formGroupName. "[email]";
			$controls["www"]["name"]		= $formGroupName. "[www]";
			$controls["body"]["name"]		= $formGroupName. "[body]";

			// required
			$controls["pid"]["required"]		= false;
			$controls["title"]["required"]		= true;
			$controls["nick"]["required"]		= true;
			$controls["email"]["required"]		= (bool)LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_required_discussion_email")->getContent();
			$controls["www"]["required"]		= (bool)LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_required_discussion_www")->getContent();
			$controls["body"]["required"]		= true;

			// max length
			$controls["title"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_discussion_title")->getContent();
			$controls["nick"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_nick")->getContent();
			$controls["email"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_email")->getContent();
			$controls["www"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_www")->getContent();
			$controls["body"]["maxLength"]			= LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_max_length_discussion_text")->getContent();
			
			// data - pokud neexistuji postdata, vezmou se defaulty zalogovaneho uzivatele, nebo tam budou prazdne stringy
			$user = LBoxXT::isLogged() ? LBoxXT::getInstance()->getUserXTRecord() : array();
			$controls["pid"]["value"]		= $_POST[$formGroupName]["pid"];
			$controls["title"]["value"]		= $_POST[$formGroupName]["title"];
			$controls["nick"]["value"]		= $_POST[$formGroupName]["nick"] ? $_POST[$formGroupName]["nick"] : $user->nick;
			$controls["email"]["value"]		= $_POST[$formGroupName]["email"] ? $_POST[$formGroupName]["email"] : $user->email;
			$controls["www"]["value"]		= $_POST[$formGroupName]["www"] ? $_POST[$formGroupName]["www"] : $user->www;
			$controls["body"]["value"]		= $_POST[$formGroupName]["body"];

			// disabled
			if (LBoxXT::isLogged()) {
				$controls["nick"]["disabled"]		= "disabled";
				$controls["email"]["disabled"]		= "disabled";
				$controls["www"]["disabled"]		= "disabled";
			}
				
			// ostatni hodnoty formu
			$form["target"]			= LBOX_REQUEST_URL_PATH;
			$form["name"]			= $formGroupName;
			$form["controls"]		= $controls;
			$form["error"] 			= false;

			return $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns config of current page by URL
	 * @return LBoxConfigStructure
	 */
	protected function getPageConfigByUrl() {
		try {
			return LBoxConfigManagerStructure::getInstance()->getPageByUrl(LBOX_REQUEST_URL_VIRTUAL);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns URL param location - public kuli output filteru
	 * @return string
	 */
	public function getLocationUrlParam() {
		try {
			foreach ($this->getUrlParamsArray() as $param) {
				if ($this->isUrlParamPaging($param)) continue;
				return $param;
			}
			return "";
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Zjistuje, jestli je nick jeste volny
	 * @param int $nick
	 * @return bool
	 */
	protected function isNickFree($nick = "") {
		try {
			if (strlen($nick) < 1) {
				throw new LBoxExceptionForm(LBoxExceptionForm::MSG_PARAM_STRING_NOTNULL, LBoxExceptionForm::CODE_BAD_PARAM);
			}
			$records	= new XTUsersRecords(array("nick" => $nick));
			return ($records->count() < 1);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns AbstractRecord of discussion
	 * @return DiscussionsRecord
	 */
	protected function getRecord() {
		try {
			if ($this->record instanceof DiscussionsRecord) {
				return $this->record;
			}
			$pageId	= $this->getPageConfigByUrl()->id;
			$param	= $this->getLocationUrlParam();
			$filter	= array("pageId" => $pageId, "urlParam" => $param);
			$discussions = new DiscussionsRecords($filter, array("lft" => 1), array(0, 1));
			foreach ($discussions as $discussion) {
				$discussion->setOutputFilter(new OutputFilterDiscussion($discussion));
				return $this->record = $discussion;
				break;
			}
			// pokud diskuze nebyla nalezena, vytvorime ji a vratime
			$discussion 			= new DiscussionsRecord();
			$discussion->pageId 	= $this->getPageConfigByUrl()->id;
			$discussion->urlParam 	= $this->getLocationUrlParam();
			$discussion->store();
			$discussion->setOutputFilter(new OutputFilterDiscussion($discussion));
			return $this->record = $discussion;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Smaze prispevek podle predaneho id - pozor smaze i podrazene prispevky 
	 * @param string $id
	 * @throws Exception
	 */
	protected function deletePost($id = "") {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionXT(LBoxExceptionXT::MSG_PARAM_STRING_NOTNULL, LBoxExceptionXT::CODE_BAD_PARAM);
			}
			$record	= new DiscussionsPostsRecord($id);
			$record->delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Zjistuje, zda je zapnuta a nutna spam defense pres JS
	 * @return bool
	 * @throws LBoxException
	 */
	protected function isSpamDefenseJSNeeded() {
		try {
			if (LBoxXT::isLogged()) {
				return false;
			}
			return  (LBoxConfigManagerProperties::getInstance()->getPropertyByName("form_spamdefense_js_discussion")->getContent() == 1);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>