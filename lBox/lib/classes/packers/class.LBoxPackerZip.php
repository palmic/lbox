<?php
/**
 * class handles zip archives
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0

 * @date 2008-05-03
 */
class LBoxPackerZip extends LBoxPacker
{
	/**
	 * @var string
	 */
	protected $extensionName = "zip";

	/**
	 * @var ZipArchive
	 */
	protected $resource;

	/**
	 * @throws Exception
	 */
	protected function undo() {
		try {
			$this->getResource()->unchangeArchive();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 */
	public function pack() {
		try {
			// if $archiveIndex is numeric, its an extracted file
			foreach ($this->getFilesExternal() as $path => $archiveIndex) {
				if (is_numeric($archiveIndex)) {
					continue;
				}
				if (!$this->getResource()->addFile($path, basename($path))) {
					throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_CANNOT_ADD_FILE, LBoxExceptionPackers::CODE_PACKER_ERR_CANNOT_ADD_FILE);
				}
			}
			if (!$this->getResource()->close()) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_CANNOT_CLOSE_ARCHIVE, LBoxExceptionPackers::CODE_PACKER_ERR_CANNOT_CLOSE_ARCHIVE);
			}
			$this->resource			= NULL;
			$this->filesExternal	= array();
			$this->filesPacked		= array();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function unpackFileTo($filename = "", $path = "", $overwrite = false) {
		try {
			if (strlen($filename) < 1) {
				throw new LBoxExceptionPackers("\$filename". LBoxExceptionPackers::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPackers::CODE_BAD_PARAM);
			}
			if (strlen($path) < 1) {
				throw new LBoxExceptionPackers("\$path". LBoxExceptionPackers::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPackers::CODE_BAD_PARAM);
			}
			if (!$overwrite) {
				if (file_exists($path)) {
					throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_FILE_ALREADY_EXISTS, LBoxExceptionPackers::CODE_FILE_ALREADY_EXISTS);
				}
			}
			$content	= $this->getResource()->getFromName($filename);
			if (!is_resource($tfp		= fopen($path, "w"))) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_FILE_CANNOT_OPEN, LBoxExceptionPackers::CODE_FILE_CANNOT_OPEN);
			}
			if (!is_numeric(fwrite($tfp, $content))) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_FILE_CANNOT_WRITE, LBoxExceptionPackers::CODE_FILE_CANNOT_WRITE);
			}
			fclose($tfp);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @return array
	 * @throws Exception
	 */
	public function getFiles() {
		try {
			return array_merge($this->getFilesExternal(), $this->getFilesPacked());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * @return array
	 */
	protected function getFilesPacked() {
		try {
			if (strlen(current($this->filesPacked)) > 0) {
				return $this->filesPacked;
			}
			$this->filesPacked	= array();
			for ($i = 0; ($entry = $this->getResource()->getNameIndex($i)) !== false; $i++) {
				$this->filesPacked[$entry] = $i;
			}
			return $this->filesPacked;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns opened archive handling instance
	 * @return ZipArchive
	 */
	protected function getResource() {
		try {
			if ($this->resource instanceof ZipArchive) {
				return $this->resource;
			}
			$this->resource	= new ZipArchive();
			if (($returned = $this->resource->open($this->pathPackage, ZIPARCHIVE::CREATE)) !== true) {
				throw $this->getExceptionError($returned);
			}
			return $this->resource;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns prior exception instance by zip error code
	 * @param int $errorCode
	 * @return LBoxExceptionPackers
	 */
	protected function getExceptionError($errorCode = 0) {
		try {
			if ($errorCode < 1) {
				throw new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PARAM_INT_NOTNULL, LBoxExceptionPackers::CODE_BAD_PARAM);
			}
			// suit extension error into prior LBoxExceptionPackers
			switch ($errorCode) {
				case ZIPARCHIVE::ER_EXISTS:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_NOT_FOUND, LBoxExceptionPackers::CODE_PACKER_ERR_NOT_FOUND);
					break;
				case ZIPARCHIVE::ER_INCONS:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_INCONNSISTENT, LBoxExceptionPackers::CODE_PACKER_ERR_INCONNSISTENT);
					break;
				case ZIPARCHIVE::ER_INVAL:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_INVALIDARGUMENT, LBoxExceptionPackers::CODE_PACKER_ERR_INVALIDARGUMENT);
					break;
				case ZIPARCHIVE::ER_MEMORY:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_MEMORYALLOC, LBoxExceptionPackers::CODE_PACKER_ERR_MEMORYALLOC);
					break;
				case ZIPARCHIVE::ER_NOENT:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_FILENOTFOUND, LBoxExceptionPackers::CODE_PACKER_ERR_FILENOTFOUND);
					break;
				case ZIPARCHIVE::ER_NOZIP:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_OTHERARCHIVETYPE, LBoxExceptionPackers::CODE_PACKER_ERR_OTHERARCHIVETYPE);
					break;
				case ZIPARCHIVE::ER_OPEN:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_CANNOTOPEN, LBoxExceptionPackers::CODE_PACKER_ERR_CANNOTOPEN);
					break;
				case ZIPARCHIVE::ER_READ:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_CANNOTREAD, LBoxExceptionPackers::CODE_PACKER_ERR_CANNOTREAD);
					break;
				case ZIPARCHIVE::ER_SEEK:
						return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_CANNOTSEEK, LBoxExceptionPackers::CODE_PACKER_ERR_CANNOTSEEK);
					break;
				default:
					return new LBoxExceptionPackers(LBoxExceptionPackers::MSG_PACKER_ERR_UNRECOGNIZED, LBoxExceptionPackers::CODE_PACKER_ERR_UNRECOGNIZED);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>