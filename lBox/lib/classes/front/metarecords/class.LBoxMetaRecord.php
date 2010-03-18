<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2010-03-07
 */
class LBoxMetaRecord extends LBox
{
	/**
	 * relevant record handler
	 * @var AbstractRecordLBox
	 */
	protected $record;
	
	/**
	 * relevant record's edit form
	 * @var LBoxForm
	 */
	protected $form;
	
	/**
	 * default template filename
	 * @var string
	 */
	protected $filenameTemplate = "metarecord.html";
	
	/**
	 * @param $record
	 */
	public function __construct(AbstractRecordLBox $record) {
		try {
			$this->record	= $record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function __toString() {
		try {
			try {
				try {
					$out 	 = "";
					$out	.= $this->getTAL()->execute();
				}
				catch (Exception $e) {
					// var_dump($e);
	
					$out 	 = "";
					$out	.= "PHPTAL Exception thrown";
					$out	.= "\n";
					$out	.= "\n";
					$out	.= "code: ". nl2br($e->getCode()) ."";
					$out	.= "\n";
					$out	.= "mdessage: ". nl2br($e->getMessage()) ."";
					$out	.= "\n";
					$out	.= "Thrown by: '". $e->getFile() ."'";
					$out	.= "\n";
					$out	.= "on line: '". $e->getLine() ."'.";
					$out	.= "\n";
					$out	.= "\n";
					$out	.= "Stack trace:";
					$out	.= "\n";
					$out	.= nl2br($e->getTraceAsString());
					// $out 	= nl2br($out) ."<hr />\n\n";
					$out 	= "<!--$out-->";
				}
				return $out;
			}
			catch (Exception $e) {
				throw $e;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return bool
	 */
	public function isRecordInDatabase() {
		try {
			return $this->record->isInDatabase();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getForm() {
		try {
			if ($this->form instanceof LBoxForm) {
				return $this->form;
			}
			$rType		= is_string($this->record) ? $this->record : get_class($this->record);
			$record		= $this->record;
			$idColName  = eval("return $rType::\$idColName;");
			$formID		= "metarecord-$rType-" . ($this->record->isInDatabase() ? $this->record->$idColName : "");
			
			$subCtrls["type"]			= new LBoxFormControlFillHidden("type", "", $rType);
				$subCtrls["type"]			->setTemplateFileName("metarecord_hidden.html");
			$subCtrls["id"]				= new LBoxFormControlFillHidden("id", "", $this->record->isInDatabase() ? $this->record->$idColName : "");
				$subCtrls["id"]				->setTemplateFileName("metarecord_hidden.html");
			
	/*protected static $attributes	=	array(
											array("name"=>"ref_type", "type"=>"int", "notnull" => true, "default"=>"", "visibility"=>"protected"),
											array("name"=>"url_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"url_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"heading_cs", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"heading_sk", "type"=>"shorttext", "notnull" => true, "default"=>""),
											array("name"=>"perex_cs", "type"=>"richtext", "default"=>""),
											array("name"=>"perex_sk", "type"=>"richtext", "default"=>""),
											array("name"=>"body_cs", "type"=>"richtext", "default"=>""),
											array("name"=>"body_sk", "type"=>"richtext", "default"=>""),
											array("name"=>"description_cs", "type"=>"longtext", "default"=>""),
											array("name"=>"description_sk", "type"=>"longtext", "default"=>""),
											array("name"=>"time_published", "type"=>"int", "notnull" => true, "default"=>""),
											array("name"=>"ref_photo", "type"=>"int", "notnull" => true),
											array("name"=>"ref_access", "type"=>"int", "notnull" => true, "default"=>""),
											);*/
				
			// nasazet tam jednotlive record attributes
			foreach ($record->getAttributes() as $attribute) {
				if (array_key_exists("visibility", $attribute) && $attribute["visibility"] == "protected")  {
					continue;
				}
				$attName			= $attribute["name"];
				$default			= $attribute["default"];
				$type				= $attribute["type"];
				$filterType			= "LBoxFormFilterMetarecord". ucfirst($type);
				$validatorType		= "LBoxFormValidatorMetarecord". ucfirst($type);
				$subCtrls[$attName]	= new LBoxFormControlFill($attName, $attName, $this->record->isInDatabase() ? $this->record->$attName : $default);
				$subCtrls[$attName]	->setTemplateFilename("metarecord_". $attribute["type"] .".html");
				$subCtrls[$attName]	->addFilter(new LBoxFormFilterTrim);
				$subCtrls[$attName]	->addFilter(new $filterType);
				$subCtrls[$attName]	->addValidator(new $validatorType);
				if ($attribute["required"]) {
					$subCtrls[$attName]->setRequired(true);
				}
			}

			// vlozime ho do dialog boxu pro JS GUI
			$ctrlDialog				= new LBoxFormControlMultiple("dialog", "");
			$ctrlDialog				->setTemplateFileName("metarecord_dialog.html");
			foreach ($subCtrls as $subCtrl) {
				$ctrlDialog				->addControl($subCtrl);
			}

			$form					= new LBoxForm($formID, "post", "", "editovat");
			$form					->setTemplateFileName("metarecord_xt_edit.html");
			$form->action			= LBoxConfigSystem::getInstance()->getParamByPath("metarecords/api/url");
			$form					->addControl($ctrlDialog);
			$form					->addProcessor(new ProcessorMetaRecordEdit);
			$form->className		= "metarecord metarecord-$rType";

			return $this->form = $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @return PHPTAL
	 */
	protected function getTAL() {
		try {
			if (!$this->TAL instanceof PHPTAL) {
				$this->TAL = new PHPTAL($this->getPathTemplate());
			}
			$translator	= new LBoxTranslator($this->getPathTemplate());
			// zajistit existenci ciloveho adresare PHP kodu pro TAL:
			$phptalPhpCodeDestination	= LBoxUtil::fixPathSlashes(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_PHP_CODE_DESTINATION"));
			LBoxUtil::createDirByPath($phptalPhpCodeDestination);
			$this->TAL->setTranslator($translator);
			$this->TAL->setForceReparse(LBoxConfigSystem::getInstance()->getParamByPath("output/tal/PHPTAL_FORCE_REPARSE"));
			$this->TAL->setPhpCodeDestination($phptalPhpCodeDestination);
			$this->TAL->SELF = $this;
			return $this->TAL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci celou cestu k sablone control (ovlivnenou atributem filenameTemplate instance control)
	 * @return string
	 */
	protected function getPathTemplate() {
		try {
			$pathTemplatesForms	= LBoxConfigSystem::getInstance()->getParamByPath("metarecords/templates/path");
			return LBoxUtil::fixPathSlashes("$pathTemplatesForms/". $this->filenameTemplate);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>