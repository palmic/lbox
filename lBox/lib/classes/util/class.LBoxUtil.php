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
			$path		= str_replace("/", SLASH, $path);
			$path		= str_replace("\\", SLASH, $path);
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
					$this->removeDirByPath("$path/$entry", true);
				}
				if (!unlink("$path/$entry")) {
					throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_CANNOT_DELETE, LBoxExceptionFilesystem::CODE_FILE_CANNOT_DELETE);
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
}
?>