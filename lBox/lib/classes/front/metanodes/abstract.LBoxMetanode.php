<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
abstract class LBoxMetanode extends LBox
{
	/**
	 * caller instance
	 * @var LBoxComponent
	 */
	protected $caller;
	
	/**
	 * items seq
	 * @var int
	 */
	protected $seq;
	
	/**
	 * file pointer cache
	 * @var resource
	 */
	protected $fileH;
	
	/**
	 * data cache
	 * @var string
	 */
	protected $data = "";
	
	/**
	 * data filename ext
	 * @var string
	 */
	protected $ext	= "txt";
	
	/**
	 * path cache
	 * @var string
	 */
	protected static $path	= "";
	
	/**
	 * paths cacheed by caller types
	 * @var array
	 */
	protected static $pathsByCallerTypes	= array();
	
	public function __construct($seq = 0, LBoxComponent $caller) {
		if (!is_int($seq) || $seq < 1) {
			throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_INT_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
		}
		$this->seq		= $seq;
		$this->caller	= $caller;
		
		//init file
		$this->getFileH();
		if (filesize($this->getFilePath()) > 0) {
			if (($this->data	= fread($this->getFileH(), filesize($this->getFilePath()))) === false) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_READ, LBoxExceptionMetanodes::CODE_DATA_CANNOT_READ);
			}
		}
	}
	
	/**
	* toString converter
	* @return string
	*/
	public function __toString() {
		try {
			return (string)$this->getContent();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function __destruct() {
		@fclose($this->fileH);
	}
	
	/**
	* getter na obsah
	* @return string
	*/
	protected function getContent() {
		try {
			return $this->data;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* setter obsahu
	* @param string $content
	*/
	public function setContent($content = "") {
		try {
			// drop previous cache
			unset($this->fileH);
			@unlink($this->getFilePath());
			if (!fwrite($this->getFileH(), $content)) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_DATA_CANNOT_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_WRITE);
			}
			$this->data	= $content;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na file pointer
	 * @return resource
	 */
	protected function getFileH() {
		try {
			if (!file_exists($this->getFilePath())) {
				$temp = fopen($this->getFilePath(), "a+");
				fclose($temp);
			}
			if (!$this->fileH	= fopen($this->getFilePath(), "a+")) {
				throw new LBoxExceptionMetanodes($this->getFilePath() .": ". LBoxExceptionMetanodes::MSG_DATA_CANNOT_OPEN_WRITE, LBoxExceptionMetanodes::CODE_DATA_CANNOT_OPEN_WRITE);
			}
			fseek($this->fileH, 0);
			return $this->fileH;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* getter na cestu k souboru s obsahem
	* @return string
	*/
	protected function getFilePath() {
		try {
			if (strlen($this->path) > 0) {
				return $this->path;
			}
			$this->path	= self::getPathByCallerType($this->caller instanceof LBoxPage ? "pages" : "components");
			$this->path	= str_replace("\$caller_id", $this->caller->config->id, $this->path);
			$this->path	= str_replace("\$seq", $this->seq, $this->path);
			$this->path	= str_replace("\$ext", $this->ext, $this->path);
			
			$this->path	= LBoxUtil::fixPathSlashes($this->path);
			LBoxUtil::createDirByPath(dirname($this->path));
			return $this->path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	* vraci predparserovanou hodnotu parametru z configu - proti opakovanemu parserovani
	* @param string $type
	* @return string
	*/
	protected static function getPathByCallerType($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_STRING_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			if (array_key_exists($type, self::$pathsByCallerTypes)) {
				return self::$pathsByCallerTypes[$type];
			}
			self::$pathsByCallerTypes[$type]	= LBoxConfigSystem::getInstance()->getParamByPath("metanodes/data/path");
			self::$pathsByCallerTypes[$type]	= str_replace("\$caller_type", $type, self::$pathsByCallerTypes[$type]);
			return self::$pathsByCallerTypes[$type];
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>