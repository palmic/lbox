<?php
/**
 * processor, ktery uklada MetaRecords
 */
class ProcessorMetaRecordEdit extends ProcessorRecordEdit
{
	public function process() {
		try {
//DbControl::$debug = "firephp";
			$this->classNameRecord	= $type = $this->form->getControlByName("type")->getValue();
			$testR					= new $type;
			$attributes				= $testR->getAttributes();
			$this->controlsIgnore[] = "type";
			$controls				= $this->form->getControls();
			// find out special references
			foreach ($controls as $control) {
				if (preg_match("/action_(\w+)/", $control->getName())) {
					$this->controlsIgnore[] = $control->getName();
				}
				if (preg_match("/image\-(\w+)/", $control->action)) {
					$this->controlsIgnore[] = $control->getName();
					$ctrlImage	= $control;
				}
			}
			parent::process();
			// handle special references
			if ($ctrlImage instanceof LBoxFormControl) {
				if ($ctrlImage instanceof LBoxFormControlBool) {
					if ($ctrlImage->getValue()) {
						$this->removePhotoByControl($ctrlImage);
					}
				}
				else {
					$this->addPhotoByControl($ctrlImage);
				}
			}
			$this->form->recordProcessed	= $this->record;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * prida photo podle predaneho controlu
	 * @param LBoxFormControlBool $control
	 */
	protected function addPhotoByControl(LBoxFormControlFile $control) {
		try {
			if (!($this->record instanceof AbstractRecord)) {
				throw new LBoxExceptionFormProcessor("Cannot find stored record!", LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			$attributeName			= $control->getName();
			$attribute				= $this->getAttributeByName($attributeName);
			$imageType				= $attribute["reference"]["type"];
			$testRI					= new $imageType;
			if ($testRI instanceof AbstractRecords) {
				$imageType	= eval("return $imageType::\$itemType;");
			}
			$imageIDColName			= eval("return $imageType::\$idColName;");
			$dataFilesimage			= $control->getValueFiles();
			
			if ($dataFilesimage["size"] < 1) {
				return;
			}
			if ($dataFilesimage["size"] > 0) {
				$image	= new $imageType();
			}
			if (!($image instanceof PhotosRecord)) {
				throw new LBoxExceptionFormProcessor("Image record of wrong type '". get_class($image) ."'!");
			}
			$image	->saveUploadedFile($dataFilesimage["tmp_name"], $dataFilesimage["name"], $dataFilesimage["size"]);
			if (array_key_exists("size_resize", $attribute["reference"])) {
				if (!array_key_exists("x", $attribute["reference"]["size_resize"])) {
					throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_BAD_DEFINITION_REFERENCE_IMAGE_RESIZE, LBoxExceptionMetaRecords::CODE_BAD_DEFINITION_REFERENCE_IMAGE_RESIZE);
				}
				$image	->resize($attribute["reference"]["size_resize"]["x"], $attribute["reference"]["size_resize"]["y"], (bool)$attribute["reference"]["size_resize"]["proportions"]);
			}
			if (array_key_exists("size_limit", $attribute["reference"])) {
				if (!array_key_exists("longer", $attribute["reference"]["size_limit"])) {
					throw new LBoxExceptionMetaRecords(LBoxExceptionMetaRecords::MSG_BAD_DEFINITION_REFERENCE_IMAGE_LIMIT, LBoxExceptionMetaRecords::CODE_BAD_DEFINITION_REFERENCE_IMAGE_LIMIT);
				}
				$image	->limitSize($attribute["reference"]["size_limit"]["longer"]);
			}
			$image	->store();
			$this->record->$attributeName	= $image->$imageIDColName;
			$this->record->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * smaze photo podle predaneho controlu
	 * @param LBoxFormControlBool $control
	 */
	protected function removePhotoByControl(LBoxFormControlBool $control) {
		try {
			if (!($this->record instanceof AbstractRecord)) {
				throw new LBoxExceptionFormProcessor("Cannot find stored record!", LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			$attributeName			= $control->getName();
			$attribute				= $this->getAttributeByName($attributeName);
			$imageType				= $attribute["reference"]["type"];
			$testRI					= new $imageType;
			if ($testRI instanceof AbstractRecords) {
				$imageType	= eval("return $imageType::\$itemType;");
			}
			$image	= new $imageType($this->record->$attributeName);
			$image	->delete();
			$this->record->$attributeName	= "<<NULL>>";
			$this->record->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * returns stored record attribute by name
	 * @return array
	 */
	protected function getAttributeByName($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_PARAM);
			}
			$type 					= $this->form->getControlByName("type")->getValue();
			$testR					= new $type;
			$attributes				= $testR->getAttributes();
			foreach ($attributes as $attribute) {
				if ($attribute["name"] == $name) {
					return $attribute;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>