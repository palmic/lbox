<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxUtil
{
	/**
	 * debug verbose
	 * @var bool
	 */
	public $debug = false;

	protected static $extenssionsIMG	= array(
												"jpg",
												"jpeg",
												"gif",
												"png",
												);
	
	/**
	 * @param string $to
	 * @param string $subject
	 * @param string $body
	 */
	public static function send($to = "", $subject = "", $body = "") {
		try {
			if (strlen($to) < 1) {
				return;
			}
			if (strlen($subject) < 1) {
				return;
			}
			if (strlen($body) < 1) {
				return;
			}
			$to	= str_ireplace("<at>", "@", $to);
			@mail($to, $subject, $body);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci ve spravnem tvaru URL string pozadovane stranky
	 * @return string
	 * @throws Exception
	 */
	public static function getPagingURLString($pageNum = 0) {
		try {
			if ((!is_numeric($pageNum)) || $pageNum < 1) {
				throw new LBoxException("\$pageNum ". LBoxException::MSG_PARAM_INT_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			$pagingUrlParamPattern	= LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_pattern");
			$pagingUrlParamExample	= LBoxConfigSystem::getInstance()->getParamByPath("output/paging/paging_url_param_example");

			if (!ereg($pagingUrlParamPattern, $pagingUrlParamExample, $regs)) {
				throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN, LBoxExceptionConfig::CODE_PAGING_URLPARAM_EXAMPLE_NOT_CORRESPOND_PATTERN);
			}
			// zrusime z pole prvni klic s celym stringem
			unset($regs[0]);
			// nalezneme key numerickeho parametru - stranky (abysme ho snadno mohli zamenit)
			foreach ($regs as $k => $reg) {
				if (is_numeric($reg)) {
					$regsPageKey = $k;
				}
			}
			$pageNumPattern	= $regs[$regsPageKey];
			return str_ireplace($pageNumPattern, $pageNum, $pagingUrlParamExample);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati URL string preparserovany z predaneho nazvu
	 * @param string $name
	 * @return string
	 */
	public static function getURLByNameString($name = "") {
		try {
			$out 	= strtolower(trim($name));

			$vzor = array("@&(.*?);@"); $nahrazeni = array("-"); $text = preg_replace($vzor, $nahrazeni, $out);
			$out 	= strtr($out, array("á" => "a", "č" => "c", "ď" => "d", "é" => "e", "ě" => "e", "í" => "i", "ň" => "n", "ó" => "o", "ř" => "r", "š" => "s", "ť" => "t", "ú" => "u", "ů" => "u", "ý" => "y", "ž" => "z",
			"." => "-", "," => "-", ";" => "-", ":" => "-", "&" => "and", "_" => "-", "@" => "", " " => "-"));
			$out	= ereg_replace("[^[:alnum:]]", "-", $out);
			$out	= ereg_replace("(-+)", "-", $out);
			return 	$out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * @desc returns timestamp of given date
	 * @param string date - ISO formated date or date-time
	 * @param boolean dayPrecission - day precission / second precission
	 * @returns integer
	 * @throws LBoxException
	 */
	public static function getDateTimeStamp($date = "", $dayPrecission = false) {
		try {
			if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $date, $ereg)) {
				if ($dayPrecission) {
					return mktime(0, 0, 0, $ereg[2], $ereg[3], $ereg[1]);
				}
				else {
					return mktime($ereg[4], $ereg[5], $ereg[6], $ereg[2], $ereg[3], $ereg[1]);
				}
			}
			else if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $date, $ereg)) {
				return mktime(0, 0, 0, $ereg[2], $ereg[3], $ereg[1]);
			}
			else {
				throw new LBoxException("Given date is not ISO formated ($date)");
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * zkontroluje, jestli URL nakonci nechybi lomitko a jestli ano, doplni jej
	 * - pouziva se na frontu pri porovnavani URL stranek (nikoli pri zobrazovani odkazu - aby se dal definovat odkaz bez lomitka a tak se i zobrazil)
	 * @param string $url
	 * @return string
	 * @throws Exception
	 */
	public static function fixURLSlash ($url = "") {
		try {
			if (strlen($url) < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_STRING_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			if (!LBoxFront::isURLToFile($url)) {
				$url = (substr($url, -1) == "/") ? $url : "$url/";
			}
			return $url;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * opravi v predane ceste slashe na validni v ramci current systemu
	 * @param string $path
	 * @return string
	 * @throws Exception
	 */
	public static function fixPathSlashes ($path = "") {
		try {
			$path		= str_replace("/", SLASH, $path);
			$path		= str_replace("\\", SLASH, $path);
			return $path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ocisti predany filename od nebezpecnych a invalidnich znaku
	 * @param string $filename
	 * @return string
	 * @throws Exception
	 */
	public static function fixFileName ($filename = "") {
		try {
			return preg_replace("/[\W_\.]/", "-", $filename);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Vytvori adresar podle predane cesty
	 * @param string $path
	 * @throws LBoxExceptionFilesystem
	 */
	public static function createDirByPath($path = "") {
		try {
			if (strlen($path) < 1) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$path		= str_replace("/", SLASH, $path);
			$path		= str_replace("\\", SLASH, $path);
			$pathParts	 = explode(SLASH, $path);
			$pathTMP	 = WIN ? "" : "/";
			if (is_dir($path)) return;
			$i	= 1;
			foreach ($pathParts as $pathPart) {
				if (strlen($pathPart) < 1) continue;
				if (WIN) 	$pathTMP	.= strlen($pathTMP) > 0 ? SLASH ."$pathPart" : $pathPart;
				else 		$pathTMP	.= strlen($pathTMP) > 1 ? SLASH ."$pathPart" : $pathPart;
				if (strlen(strstr($pathPart, ":")) > 0) continue;
				$i++;
				if ($i <= count(explode(SLASH, LBOX_PATH_INSTANCE_ROOT))) continue;
				if (!is_dir($pathTMP)) {
					if (!mkdir($pathTMP)) {
						throw new LBoxExceptionFilesystem(	$pathTMP .": ". LBoxExceptionFilesystem::MSG_DIRECTORY_CANNOT_CREATE,
															LBoxExceptionFilesystem::CODE_DIRECTORY_CANNOT_CREATE);
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vyprazdni adresar podle predane cesty (ta musi byt cestou na adresar)
	 * @param string $path
	 * @param bool $recursive
	 */
	public static function emptyDirByPath($path = "", $recursive = false) {
		try {
			if (strlen($path) < 1) {
				throw new LBoxExceptionCache(LBoxExceptionCache::MSG_PARAM_STRING_NOTNULL, LBoxExceptionCache::CODE_BAD_PARAM);
			}
			$path	= self::fixPathSlashes($path);
			if (is_file($path)) { $path	= dirname($path); }
			$dir	= dir($path);
			while (($entry = $dir->read()) !== false) {
				if($entry == '.' || $entry == '..') continue;
				if(is_dir($path .SLASH. $entry))  {
					if ($recursive) {
						self::emptyDirByPath($path . SLASH . $entry, $recursive);
					}
					continue;
				}
				if(!unlink($path .SLASH. $entry)) {
					throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_CANNOT_DELETE, LBoxExceptionFilesystem::CODE_FILE_CANNOT_DELETE);
				}
/*LBoxFirePHP::warn($path .SLASH. $entry ." ODSTRANEN");
if (($path .SLASH. $entry) == "/windows/E/www/timesheets/project/.cache/abstractrecord/xtusers_employees_positions/collections/ad4bec8f6e8768c0ffda5cfff5093893.cache") {
	die("presne ted");
}*/
			}
			$dir->close();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * smaze adresar podle predane cesty
	 * @param string $path
	 * @param bool $withSubDirs
	 * @throws LBoxExceptionFilesystem
	 */
	public static function removeDirByPath($path = "", $withSubDirs = false) {
		try {
			if (strlen($path) < 1) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$path		= self::fixPathSlashes($path);
			if (is_dir($path) < 1) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_DIRECTORY_NOT_EXISTS, LBoxExceptionFilesystem::CODE_DIRECTORY_NOT_EXISTS);
			}
			$d = dir($path);
			while (false !== ($entry = $d->read())) {
				if($entry == '.' || $entry == '..') continue;
				if (is_dir("$path/$entry")) {
					if (!$withSubDirs) {
						throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_DIRECTORY_CONTAINS_SUBDIRS, LBoxExceptionFilesystem::CODE_DIRECTORY_CONTAINS_SUBDIRS);
					}
					self::removeDirByPath("$path/$entry", true);
				}
				if (file_exists("$path". SLASH ."$entry")) {
					if (!unlink("$path". SLASH ."$entry")) {
						throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_CANNOT_DELETE, LBoxExceptionFilesystem::CODE_FILE_CANNOT_DELETE);
					}
				}
			}
			$d->close();
			if (!rmdir($path)) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_DIRECTORY_CANNOT_DELETE, LBoxExceptionFilesystem::CODE_DIRECTORY_CANNOT_DELETE);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zjisti, jestli je dany nazev filu ve filesystemu volny, jinak vraci upraveny
	 * @param string $path
	 * @throws LboxException
	 */
	public static function getFreeFileNameFrom($path = "") {
		try {
			if (strlen($path) < 1) {
				throw new LBoxExceptionFilesystem("$path: ". LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$path		= self::fixPathSlashes($path);
			$pathParts	= explode(SLASH, $path);
			$fileName	= $pathParts[count($pathParts)-1];
			$pathTarget	= "";
			foreach ($pathParts as $index => $pathPart) {
				if ($index == count($pathParts)-1) break;
				$pathTarget	.= strlen($pathTarget) > 0 ? SLASH : "";
				$pathTarget	.= $pathPart;
			}
			if (!is_dir($pathTarget)) {
				throw new LBoxExceptionFilesystem("'$pathTarget': ". LBoxExceptionFilesystem::MSG_DIRECTORY_NOT_EXISTS, LBoxExceptionFilesystem::CODE_DIRECTORY_NOT_EXISTS);
			}
			$fileName		= ereg_replace("[^[:alpha:]]", "_", $fileName);
			//$fileName 		= mb_convert_encoding($fileName, "UTF-8");
			// pryc s diakritikou pokud mame prostredi s mbstring extension
			$fileName 		= self::removeDiacritic($fileName);
			//$fileName		= eregi_replace("")
			while(file_exists("$pathTarget/$fileName")) {
				$ext		= self::getExtByFilename($fileName);
				$lastDotPos	= strrpos($fileName, ".");
				$nameOfFile	= substr($fileName, 0, $lastDotPos);
				$parts	= explode("_", $nameOfFile);
				if (is_numeric($end = end($parts))) {
					$num 		= $end+1;
					$numLastpos	= strrpos($nameOfFile, "_");
					$nameOfFile	= substr($nameOfFile, 0, $numLastpos) ."_$num";
				}
				else {
					$nameOfFile .= "_2";
				}
				$fileName = "$nameOfFile.$ext";
			}
			return $fileName;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Odstrani diakritiku
	 * @param string $input
	 */
	public static function removeDiacritic($input = "") {
		if (function_exists ("mb_convert_encoding")) {
			return mb_convert_encoding($input, "UTF-8", mb_detect_encoding($input));
		}
		else {
			return 		strtr($input, 				"áäčďéěëíňóöřšťúůüýžÁÄČĎÉĚËÍŇÓÖŘŠŤÚŮÜÝŽ",
	    											"aacdeeeinoorstuuuyzAACDEEEINOORSTUUUYZ");
		}
	}
	
	/**
	 * vraci bool, jestli je file obrazek
	 * @param string $filename (muze byt i path)
	 * @return bool
	 */
	public static function isFileImageByName($filename = "") {
		try {
			return is_numeric(array_search(self::getExtByFilename($filename), self::$extenssionsIMG));
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci nazev filu bez pripony podle predaneho filename
	 * @param string $filename (muze byt i path)
	 * @return string
	 */
	public static function getFileNameWithoutExtByFilename($filename = "") {
		try {
			$filename		= strtolower(self::fixPathSlashes($filename));
			$filenameParts	= explode(SLASH, $filename);
			$basename		= $filenameParts[count($filenameParts)-1];
			$basenameParts	= explode(".", $basename);
			unset($basenameParts[count($basenameParts)-1]);
			return $filename= implode(".", $basenameParts);
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vraci priponu filu podle predaneho filename
	 * @param string $filename (muze byt i path)
	 * @return string
	 */
	public static function getExtByFilename($filename = "") {
		try {
			$filename		= strtolower(self::fixPathSlashes($filename));
			$filenameParts	= explode(SLASH, $filename);
			$basename		= $filenameParts[count($filenameParts)-1];
			$basenameParts	= explode(".", $basename);
			return $ext		= $basenameParts[count($basenameParts)-1];
		}
		catch(Exception $e) {
			throw $e;
		}
	}
	
	/**
	* vrati URL s pridanim parametru
	* @param array $params - parametry k pridani
	* @param string $url - url, do ktere se maji parametry pridat - pokud je prazdna, pouzije se url aktualni
	* @return string
	*/
	public static function getURLWithParams($params = array(), $url = "") {
		try {
			if (!is_array($params)) {
				throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			$url				= strlen($url) > 0 ? $url : LBOX_REQUEST_URL;
			$url				= str_replace("http//", "http://", strtolower($url));
			$url				= str_replace("https//", "https://", strtolower($url));
			$paramsNew			= array();
			$paramsNewString	= "";
			$urlParts			= explode(":", preg_replace("/(http)(s?):\/\//", "", $url));
			$paramsOriginalString	= count($urlParts) > 1 ? end($urlParts) : "";
			$paramsOriginal			= explode("/", $paramsOriginalString);
			if (strlen($paramsOriginalString) > 0) {
				$urlWithoutParams		= str_replace(":$paramsOriginalString", "", $url);
			}
			else {
				$urlWithoutParams	= $url;
			}
			if (count($paramsOriginal) > 0) {
				foreach ($paramsOriginal as $k => $paramOriginal) {
					if (strlen($paramOriginal) < 1) {
						continue;
					}
					if (is_numeric(array_search($paramOriginal, $params))) {
						continue;
					}
					$paramsNew[]	= $paramOriginal;
				}
			}
			if (count($params) > 0) {
				foreach ($params as $param) {
					$paramsNew[]	= $param;
				}
			}
			if (count($paramsNew) > 0) {
				$paramsNewString	= ":". implode("/", $paramsNew);
			}
			$out	= $urlWithoutParams . $paramsNewString;
			$out	= str_replace("::", ":", $out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati URL s odebranim parametru podle predaneho PCRE vzoru
	 * @param array $patterns
	 * @param string $url
	 * @return string
	 */
	public static function getURLWithoutParamsByPattern($patterns = array(), $url = "") {
		try {
			// zpetna kompatibilita
			if (is_string($patterns)) {
				$patterns	= array($patterns);
			}
			$url				= strlen($url) > 0 ? $url : LBOX_REQUEST_URL;
			$url				= str_replace("http//", "http://", strtolower($url));
			$url				= str_replace("https//", "https://", strtolower($url));
			$paramsNew			= array();
			$paramsNewString	= "";
			$urlParts			= explode(":", preg_replace("/(http)(s?):\/\//", "", $url));
			$paramsOriginalString	= count($urlParts) > 1 ? end($urlParts) : "";
			$paramsOriginal			= explode("/", $paramsOriginalString);
			if (strlen($paramsOriginalString) > 0) {
				$urlWithoutParams		= str_replace(":$paramsOriginalString", "", $url);
			}
			else {
				$urlWithoutParams	= $url;
			}
			if (count($patterns) < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			if (count($paramsOriginal) < 1) {
				return $url;
			}
			// nastavit pole vsech dosavadnich parametru
			foreach ($paramsOriginal as $k => $paramOriginal) {
				$paramsNew[$paramOriginal]	= $paramOriginal;
			}
			foreach ($paramsOriginal as $k => $paramOriginal) {
				if (strlen($paramOriginal) < 1) {
					continue;
				}
				foreach ($patterns as $pattern) {
					if (!is_numeric($matchCount = @preg_match($pattern, $paramOriginal, $matches))) {
						throw new LBoxException("Wrong PCRE patter given, or some another error!");
					}
					//smaznout param
					if (strlen($paramOriginal) < 1 || $matchCount > 0) {
						unset($paramsNew[$paramOriginal]);
					}
				}
			}
			if (count($paramsNew) > 0) {
				$paramsNewString	= ":". implode("/", $paramsNew);
			}
			$out	= $urlWithoutParams . $paramsNewString;
			if (substr($out, -1) == ":") {
				$out	= substr($out, 0, -1);
			}
			return $out;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	* vrati URL s odebranim parametru
	* @param array $params - parametry k odebrani
	* @param string $url - url, do ktere se maji parametry pridat - pokud je prazdna, pouzije se url aktualni
	* @return string
	*/
	public static function getURLWithoutParams($params = array(), $url = "") {
		try {
			$url				= strlen($url) > 0 ? $url : LBOX_REQUEST_URL;
			$url				= str_replace("http//", "http://", strtolower($url));
			$url				= str_replace("https//", "https://", strtolower($url));
			$paramsNew			= array();
			$paramsNewString	= "";
			$urlParts			= explode(":", preg_replace("/(http)(s?):\/\//", "", $url));
			$paramsOriginalString	= count($urlParts) > 1 ? end($urlParts) : "";
			$paramsOriginal			= explode("/", $paramsOriginalString);
			$urlWithoutParams		= str_replace(":$paramsOriginalString", "", $url);
			if (!is_array($params) || count($params) < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_ARRAY_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			if (count($paramsOriginal) < 1) {
				return $url;
			}
			foreach ($paramsOriginal as $k => $paramOriginal) {
				if (is_numeric(array_search($paramOriginal, $params))) {
					continue;
				}
				if (strlen($paramOriginal) < 1) {
					continue;
				}
				$paramsNew[]	= $paramOriginal;
			}
			if (count($paramsNew) > 0) {
				$paramsNewString	= ":". implode("/", $paramsNew);
			}
			return $urlWithoutParams . $paramsNewString;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * konvertuje string z UTF-8 do CP-1250 za pomoci dostupnych nastroju na serveru
	 * @param $input
	 * @return string
	 */
	public static function utf8ToCP1250($input = "") {
		try {
			$output	= $input;
			if (function_exists("mb_convert_encoding")) {
				$output	= mb_convert_encoding($output, "ISO-8859-2", "UTF-8");
				//$output = StrTr($output,"\xA9\xAB\xAE\xB9\xBB\xBE","\x8A\x8D\x8E\x9A\x9D\x9E");
			}
			return $output;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * konvertuje string z CP-1250 do UTF-8 za pomoci dostupnych nastroju na serveru
	 * @param $input
	 * @return string
	 */
	public static function cp1250ToUTF8($input = "") {
		try {
			$output	= $input;
			if (function_exists("mb_convert_encoding")) {
				$output	= mb_convert_encoding($output, "UTF-8", "ISO-8859-2");
				//$output = StrTr($output,"\xA9\xAB\xAE\xB9\xBB\xBE","\x8A\x8D\x8E\x9A\x9D\x9E");
			}
			return $output;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na prvni URL param
	 * @return string
	 */
	public static function getURLParamByPatternProperty($propertyName = "") {
		try {
			if (strlen($propertyName) < 1) {
				throw new LBoxExceptionPage(LBoxExceptionPage::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPage::CODE_BAD_PARAM);
			}
			$pattern	= LBoxConfigManagerProperties::getPropertyContentByName($propertyName);
			$pattern	= str_ireplace("<url_param>", "([-\w]+)", $pattern);
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				if (preg_match("/$pattern/", $param, $matches)) {
					return $matches[1];
				}
			}
 		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>