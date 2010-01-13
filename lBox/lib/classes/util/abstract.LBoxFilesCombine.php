<?php
/**
 * combines and save files to cache destination
 * implementation of code Written by Ed Eliot (www.ejeliot.com) - provided as-is, use at your own risk
 * http://www.google.cz/search?q=eliot+Automatic+merging+and+versioning+of+CSS%2FJS+files+with+PHP
* @package LBox
* @version 1.0
* @date 2010-01-10
*/
abstract class LBoxFilesCombine
{
	/**
	 * location to store archive, don't add starting or trailing slashes
	 */
	protected $destination	= "<cache>/combined";

	/**
	 * output file extension - needs to be set in child class
	 * @var string
	 */
	protected static $extOut		= "";

	protected function __construct() {}

	/**
	* callback function to compress destination code
	* @param string $input
	* @return string
	*/
	public function compress($input) {
		try {
			return $input;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* combines code from defined "document root relative" paths and returns path to destination file
	* @param array files
	*/
	public function combine($files = array()) {
		try {
			if (strlen(LBoxJSCombine) < 1) {
				throw new LBoxException("\$extOut: ". LBoxException::MSG_INSTANCE_VAR_STRING_NOTNULL, LBoxException::CODE_BAD_CLASS_VAR);
			}
			if (count($files) < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			$sDocRoot		= $_SERVER['DOCUMENT_ROOT'];
			$destination	= $this->getPathDestination();
		    LBoxUtil::createDirByPath($destination);
			
			// get and merge code
			$sCode 			= "";
			$aLastModifieds = array();
			foreach ($files as $file) {
				$file	= LBOX_PATH_PROJECT . $file;
				if (!file_exists($file)) {
					throw new LBoxExceptionFilesystem("$file: ". LBoxExceptionFilesystem::MSG_FILE_NOT_EXISTS, LBoxExceptionFilesystem::CODE_FILE_NOT_EXISTS);
				}
				$aLastModifieds[] = filemtime($file);
				$sCode .= file_get_contents($file) ."\n";
         	}
	        // sort dates, newest first
	        rsort($aLastModifieds);
	         
	        $fileName			= md5($aLastModifieds[0]) .".js";
	        $destinationFile	= "$destination/$fileName";
	      
		    // cache the data
		    if (!file_exists($destinationFile)) {
//LBoxFirePHP::warn("$destinationFile zatim NEexistuje - vytvarim novy");
				switch (LBoxConfigSystem::getInstance()->getParamByPath("output/js_compress")) {
					case 1:
						$sCode	= $this->compress($sCode);
					break;
					case -1:
						if (LBOX_REQUEST_IP != "127.0.0.1") {
							$sCode	= $this->compress($sCode);
						}
					break;
				}
		    	$sCode	= "/*merged from files: ". implode($files, ",\n") ."*/". $sCode;
		    	$fo	= fopen($destinationFile, "w");
		    	fwrite($fo, $sCode);
		    	fclose($fo);
		    }
		    else {
//LBoxFirePHP::log("$destinationFile uz existuje");
		    }

			return str_replace(LBOX_PATH_PROJECT, "", $destinationFile);
	    }
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	* returns destination path
	* @return string
	*/
	protected function getPathDestination() {
		try {
			$out	= $this->destination;
			$out	= str_ireplace("<cache>", LBOX_PATH_CACHE, $out);
			$out	= str_ireplace("<project>", LBOX_PATH_PROJECT, $out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>