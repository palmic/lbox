<?php
class ProcessorAddPartyStep1 extends LBoxFormProcessor
{
	/**
	 * pattern nazvu controlu pro fotku
	 * @var string
	 */
	protected $regControlNamePhoto	= 'photo-([[:digit:]]+)';
	
	public function process() {
		try {
			/*echo "<fieldset>";
			echo "<legend>Form sent data</legend>";
			foreach ($this->form->getControls() as $control) {
				var_dump($control->getName() ." = ". $control->getValue());
			}
			echo "</fieldset>";
			flush();*/
			
			// store photos into temporary folder
			foreach($this->form->getControls() as $control) {
				if (ereg($this->regControlNamePhoto, $control->getName(), $regs)) {
					$this->savePhotoByControl($control);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ulozi fotku do temporary formulare podle predaneho controlu
	 * @param LBoxFormControl $control
	 */
	protected function savePhotoByControl(LBoxFormControlFile $control) {
		try {
			$path	= LBoxConfigManagerProperties::getPropertyContentByName("form_party_photos_tmp_path");
			$path	= str_replace('$project', LBOX_PATH_PROJECT, $path);
			$path	= str_replace('<date>', date("Y-m-d"), $path);
			$path	= str_replace('<stamp>', $control->getForm()->getControlByName("stamp")->getValue(), $path);
			$path	= str_replace('/', SLASH, $path);
			LBoxUtil::createDirByPath($path);

			$valueFiles	= $control->getValueFiles();

			// check opakovane odeslani (je mozno prejit v multiformu zpet)
			ereg($this->regControlNamePhoto, $control->getName(), $regs);
			if (strlen($tmpFileName = $control->getForm()->getControlByName("photo-handle-". $regs[1])->getValue()) > 0) {
				$fileNameTemp	= "$path/$tmpFileName";
				if (file_exists($fileNameTemp)) {
					$this->form->getControlByName("photo-handle-". $regs[1])->setValue($valueFiles["name"]);
					return;
				}
			}

			if (strlen(trim($valueFiles["name"])) < 1) {
				return;
			}
			move_uploaded_file($valueFiles["tmp_name"], "$path/". $valueFiles["name"]);
			if (!file_exists("$path/". $valueFiles["name"])) {
				throw new LBoxExceptionFormProcessor(	LBoxExceptionFormProcessor::MSG_FORM_PROCESSOR_ERROR,
														LBoxExceptionFormProcessor::CODE_FORM_PROCESSOR_ERROR);
			}
			$this->form->getControlByName("photo-handle-". $regs[1])->setValue($valueFiles["name"]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>