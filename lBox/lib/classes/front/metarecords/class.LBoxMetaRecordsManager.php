<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2010-03-07
 */
class LBoxMetaRecordsManager extends LBox
{
	/**
	 * metarecords cache
	 * @var array
	 */
	protected static $metaRecords	= array(array());

	/**
	 * forms cache
	 * @var array
	 */
	protected static $forms	= array("types" => array(), "records" => array(array()));
	
	/**
	 * LBoxMetaRecord getter
	 * @param AbstractRecordLBox $record
	 * @return LBoxMetaRecord
	 */
	public static function getMetaRecord(AbstractRecordLBox $record) {
		try {
			$type			= get_class($record);
			$idColName  	= eval("return $type::\$idColName;");
			if (array_key_exists($type, self::$metaRecords) && array_key_exists($record->$idColName, self::$metaRecords[$type])) {
				return self::$metaRecords[$type][$idColName];
			}
			return self::$metaRecords[$type][$idColName] = new LBoxMetaRecord($record);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na form podle typu/instance
	 * @param AbstractRecordLBox $type
	 * @return LBoxForm
	 */
	public static function getForm($type = "") {
		try {
			if (!$type) {
				throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_PARAM_STRING_NOTNULL, LBoxExceptionMetaRecords::CODE_BAD_PARAM);
			}
			// check cache
			switch (true) {
				case is_object($type):
						$testRecord	= new $type;
						if  (!$type instanceof AbstractRecordLBox) {
							throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_PARAM_INSTANCE_CONCRETE, LBoxExceptionMetaRecords::CODE_BAD_PARAM);
						}
						$rType			= get_class($type);
						$idColName  	= eval("return $rType::\$idColName;");
						if (array_key_exists($rType, self::$forms["records"])
							&& array_key_exists($type->$idColName, self::$forms["records"][$rType])
							&& (self::$forms["records"][$rType][$type->$idColName] instanceof AbstractRecordLBox)) {
							return self::$forms["records"][$rType][$type->$idColName];
						}
					break;
				case is_string($type):
						$testRecord	= new $type;
						if  (!($testRecord instanceof AbstractRecordLBox)) {
							throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_PARAM_INSTANCE_CONCRETE, LBoxExceptionMetaRecords::CODE_BAD_PARAM);
						}
						if (array_key_exists($type, self::$forms["types"]) && (self::$forms["types"][$type] instanceof AbstractRecordLBox)) {
							return self::$forms["types"][$type];
						}
					break;
				default:
					throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_PARAM_UNSPECIFIED_PARAM_ERROR, LBoxExceptionMetaRecords::CODE_BAD_PARAM);
			}
			
			$rType		= is_string($type) ? $type : get_class($type);
			$record		= is_string($type) ? new $type : $type;
			$idColName  = eval("return $rType::\$idColName;");
			$formID		= "metarecord-$rType-" . (is_object($type) ? $type->$idColName : "");
			
			$subCtrls["type"]			= new LBoxFormControlFillHidden("type", "", $rType);
				$subCtrls["type"]			->setTemplateFileName("metarecord_hidden.html");
			$subCtrls["id"]				= new LBoxFormControlFillHidden("id", "", is_object($type) ? $type->$idColName : "");
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
				$subCtrls[$attName]	= new LBoxFormControlFill($attName, $attName, is_object($type) ? $type->$attName : $default);
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

			if (is_object($type)) {
				self::$forms["records"][$rType][$type->$idColName]	= $form;
			}
			else {
				self::$forms["types"][$rType]	= $form;
			}
			return $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	protected function __construct() {}
}
?>