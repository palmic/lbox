<?php
/**
 * processor obsahujici funkcnost k zapisovani dat do CSV
 */
abstract class LBoxFormProcessorWriteCSV extends LBoxFormProcessor
{
	/**
	 * umisteni ciloveho souboru s daty
	 * @var string
	 */
	protected $pathDataSave	= "";
	
	/**
	 * cache var
	 * @var resource
	 */
	protected $fileH;
	
	/**
	 * zapise do souboru radku podle predaneho pole
	 * @param array $line
	 */
	protected function writeLine($line = array()) {
		try {
			if (!is_array($line) || count($line) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_PARAM_ARRAY_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_PARAM);
			}
			$lines	= array();

			// do noveho souboru pridat na zacatek zahlavi
			if (!file_exists($this->getFilePath()) || filesize($this->getFilePath()) < 1) {
				foreach ($line as $k => $v) {
					$lines[0][]	= $k;
				}
			}
			
			// fixnout problemy s validaci zapisu - odstranit z hodnot vsechny "
			foreach ($line as $colName => $colValue) {
				$line[$colName]	= $colValue;
				$line[$colName]	= str_replace('"', "'", $line[$colName]);
			}

			$lines[]	= $line;
			
			foreach($lines as $lineWrite) {
				// uzavrit hodnoty do ""
				foreach ($lineWrite as $k => $column) {
					$lineWrite[$k]	= '"'. $column .'"';
				}
				fwrite($this->getFileH(), implode(",", $lineWrite) . "\n");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na handler oteverneho filu
	 * @return resource
	 */
	protected function getFileH() {
		try {
			if (is_resource($this->fileH)) {
				return $this->fileH;
			}
			return $this->fileH = fopen($this->getFilePath(), "a+");
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na file path
	 * @return string
	 */
	protected function getFilePath() {
		try {
			if (strlen($this->pathDataSave) < 1) {
				throw new LBoxExceptionFormProcessor(LBoxExceptionFormProcessor::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxExceptionFormProcessor::CODE_BAD_INSTANCE_VAR);
			}
			$path	= $this->pathDataSave;
			$path	= str_replace("<project>", LBOX_PATH_PROJECT, $path);
			$path	= LBoxUtil::fixPathSlashes($path);
			LBoxUtil::createDirByPath(dirname($path));
			return $path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>