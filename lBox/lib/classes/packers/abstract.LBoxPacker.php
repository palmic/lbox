<?php
/**
* abstract class handles basic file packers logic and defines LBoxPacker protocol
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2008-05-03
*/
/**
 * TODO moznost pridani adresaru, nemoznost pridat archivy 
 *
 */
abstract class LBoxPacker
{
	/**
	 * required PHP extension name - need to fill!
	 * @var string
	 */
	protected $extensionName;

	/**
	 * files in archive in form: array("fileBaseName" => archiveIndex) for beware of duplicates
	 * @var array
	 */
	protected $filesPacked = array();

	/**
	 * files unpacked and added to pack in form: array("filePathName" => archiveIndex) for beware of duplicates
	 * file archive index can be NULL in case of files added and not packed
	 * @var array
	 */
	protected $filesExternal = array();
	
	/**
	 * @var string
	 */
	protected $pathPackage;

	/**
	 * @param string pathPackage
	 * @throws Exception 
	 */
	final public function __construct($pathPackage = "") {
		try {
			if (strlen($pathPackage) < 1) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPackers::CODE_BAD_PARAM);
			}
			$this->init();
			$this->pathPackage	= $pathPackage;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	final public function __destruct() {
		try {
			$this->undo();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * undos all the archive changes before pack()
	 * @throws Exception
	 */
	abstract protected function undo();

	/**
	 * packs all the external and internal files together into archive and save it by closing archive
	 * @throws Exception
	 */
	abstract public function pack();

	/**
	 * @param string $path - target path
	 * @throws Exception
	 */
	final public function unpackTo($path = "", $overwrite = false) {
		try {
			foreach($this->getFilesPacked() as $filename => $archiveIndex) {
				$filePath	= "$path/$filename";
				$this->unpackFileTo($filename, $filePath, $overwrite);
				$this->filesExternal[$filePath]	= $archiveIndex;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * extract file from archive by given filename into given path
	 * @param string $filename
	 * @param string $path - complete path with target filename!
	 * @return array
	 * @throws Exception 
	 */
	abstract public function unpackFileTo($filename = "", $path = "", $overwrite = false);
	
	/**
	 * @return array
	 */
	abstract public function getFiles();

	/**
	 * getter for files packed
	 * @return array
	 */
	abstract protected function getFilesPacked();
	
	/**
	 * @return array
	 */
	protected function getFilesExternal() {
		try {
			return $this->filesExternal;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @param string path
	 * @throws Exception
	 */
	public function addFile($path = "") {
		try {
			if (strlen($path) < 0) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPackers::CODE_BAD_PARAM);
			}
			if (!file_exists($path)) {
				throw new LBoxExceptionPackers("'$path': ". LBoxExceptionPackers::MSG_FILE_NOT_EXISTS, LBoxExceptionPackers::CODE_FILE_NOT_EXISTS);
			}
			if (is_dir($path)) {
				throw new LBoxExceptionPackers("'$path': ". LBoxExceptionPackers::MSG_FILE_IS_DIR, LBoxExceptionPackers::CODE_FILE_IS_DIR);
			}
			if (array_key_exists(basename($path), $this->getFilesPacked())) {
				throw new LBoxExceptionPackers("'$path': ". LBoxExceptionPackers::MSG_PACKER_ERR_FILEALREADYINARCHIVE, LBoxExceptionPackers::CODE_PACKER_ERR_FILEALREADYINARCHIVE);
			}
			$this->filesExternal[$path]	= NULL;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * funkce init kontroluje, jestli je v prostredi dostupna potrebna extension,
	 * definovana abstraktnim parametrem extensionName
	 * @throws Exception
	 */
	protected function init() {
		try {
			if (!extension_loaded($this->extensionName)) {
				throw new LBoxExceptionEnvironment($this->extensionName .": ". LBoxExceptionEnvironment::MSG_PHP_EXTENSION_NOTEXISTS, LBoxExceptionEnvironment::CODE_PHP_EXTENSION_NOTEXISTS);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>