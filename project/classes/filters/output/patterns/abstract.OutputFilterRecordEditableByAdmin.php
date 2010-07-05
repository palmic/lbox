<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-10-08
*/
abstract class OutputFilterRecordEditableByAdmin extends OutputFilterRecord
{
	/**
	 * musi byt nastaveno konkretnim podedenym OF
	 * @var string
	 */
	protected $propertyNameRefPageEdit			= "";

	/**
	 * pokud bude nastaveno, URL param na page edit bude vyplnen podle patternu v teto property
	 * @var string
	 */
	protected $propertyNamePatternURLParam		= "";

	/**
	 * validatory k pouziti na ovladaci prvek s ID (pokud nejake chceme)
	 * @var array
	 */
	protected $formDeleteValidators				= array();
	
	/**
	 * nazev sablony ovladaciho prvku s ID (pokud chceme nestandardni)
	 * @var string
	 */
	protected $formDeleteTemplateControlID		= "";
	
	/**
	 * cache var
	 * @var LboxForm
	 */
	protected static $formsToEditByIDs = array();

	/**
	 * cache var
	 * @var LboxForm
	 */
	protected static $formsDeleteByIDs = array();

	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "form_xt_to_edit":
						return $this->getFormXTToEdit();
					break;
				case "form_xt_edit":
						return LBoxMetaRecordsManager::getMetaRecord($this->instance);
					break;
				case "form_xt_delete":
						return $this->getFormXTDelete();
					break;
				default:
					return parent::prepare($name, $value);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci form pro forward na editaci clanku
	 * @return LBoxForm
	 * @throws LBoxException
	 */
	protected function getFormXTToEdit() {
		try {
			if (strlen($this->propertyNameRefPageEdit) < 1) {
				throw new LBoxExceptionOutputFilter(get_class($this)."::\$propertyNameRefPageEdit: ". LBoxExceptionOutputFilter::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionOutputFilter::CODE_BAD_INSTANCE_VAR);
			}
			if (!LBoxXTProject::isLoggedAdmin()) {
				return "";
			}
			$instanceType						= get_class($this->instance);
			$idColName							= eval("return $instanceType::\$idColName;");
			$id									= $this->instance->getParamDirect(strlen($this->editURLFilterColname) > 0 ? $this->editURLFilterColname : $idColName);
			$formId								= "record_xt_to_edit_$id";
			if (array_key_exists($formId, self::$formsToEditByIDs)
				&& self::$formsToEditByIDs[$formId] instanceof LBoxForm) {
					return self::$formsToEditByIDs[$formId];
			}

			$controlID							= new LBoxFormControlFillHidden("id", "", $id);
			$controlRefPageEdit					= new LBoxFormControlFillHidden("rpe", "", LBoxConfigManagerStructure::getInstance()->getPageById(LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNameRefPageEdit))->id);
			$controlPropertyNamePatternURLParam	= new LBoxFormControlFillHidden("pnpup", "", $this->propertyNamePatternURLParam);
			$form				= new LBoxForm($formId, "post", "", "editovat");
			$form				->setTemplateFileName("lbox_form_xt_btn_edit.html");
			$form				->addControl($controlID);
			$form				->addControl($controlRefPageEdit);
			$form				->addControl($controlPropertyNamePatternURLParam);
			$form				->addProcessor(new ProcessorRecordToEdit);
			return self::$formsToEditByIDs[$formId] = $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci form pro delete clanku
	 * @return LBoxForm
	 * @throws LBoxException
	 */
	protected function getFormXTDelete() {
		try {
			if (!LBoxXTProject::isLoggedAdmin()) {
				return "";
			}
			$instanceType	= get_class($this->instance);
			$idColName		= eval("return $instanceType::\$idColName;");
			$id				= $this->instance->getParamDirect($idColName);
			$formId			= "record_xt_delete_$id";
			if (array_key_exists($formId, self::$formsDeleteByIDs)
				&& self::$formsDeleteByIDs[$formId] instanceof LBoxForm) {
					return self::$formsDeleteByIDs[$formId];
			}
			
			$controlID		= new LBoxFormControlFillHidden("id", "", $id);
			foreach ($this->formDeleteValidators as $validatorDeleteID) {
				$controlID->addValidator($validatorDeleteID);
			}
			if (strlen($this->formDeleteTemplateControlID) > 0) {
				$controlID->setTemplateFileName($this->formDeleteTemplateControlID);
			}
			$controlType	= new LBoxFormControlFillHidden("type", "", $instanceType);
			$form			= new LBoxForm($formId, "post", "", "delete");
			$form			->setTemplateFileName("lbox_form_xt_btn_delete.html");
			$form			->addControl($controlID);
			$form			->addControl($controlType);
			$form			->addProcessor(new ProcessorRecordDelete);
			$form			->item_name	= strlen($this->instance->name) > 0 ? $this->instance->name : $this->instance->$idColName;
			return self::$formsDeleteByIDs[$formId] = $form;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>