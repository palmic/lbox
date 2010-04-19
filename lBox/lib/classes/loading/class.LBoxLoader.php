<?php
require_once(LBOX_PATH_CORE_CLASSES . SLASH ."exceptions". SLASH ."class.LBoxExceptionLoader.php");
require_once(LBOX_PATH_CORE_CLASSES . SLASH ."cache". SLASH ."abstract.LBoxCache.php");
require_once(LBOX_PATH_CORE_CLASSES . SLASH ."cache". SLASH ."class.LBoxCacheLoader.php");

/**
 * Na loadovani pozadovanych typu (tridy, rozhrani atd...)
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxLoader
{
	/**
	 * @var LBoxLoader
	 */
	protected static $instance;

	/**
	 * implicit file prefixes
	 * @var array
	 */
	protected $prefixes 				= array("interface", "abstract", "class", "include");

	/**
	 * file postfix
	 * @var string
	 */
	protected $postfix 					= "php";

	/**
	 * Paths where to search
	 * @var array
	 */
	protected $paths 					= array();

	/**
	 * Paths where do not search
	 * @var array
	 */
	protected $pathsIgnore 				= array();

	/**
	 * already searched paths of concrete types cache
	 * @var array
	 */
	protected $searchedPathsByTypes			= array();

	/**
	 * already searched paths of concrete types with prefix cache
	 * @var array
	 */
	protected $searchedPathsByPrefixedTypes		= array();

	/**
	 * already checked files in project filesystem - usable for quick search
	 * @var array
	 */
	protected $checkedFiles		= array();

	/**
	 * already found types in concrete paths  - array("type" => "path")
	 * @var array
	 */
	protected $foundTypesInPaths		= array();

	/**
	 * found types paths cache
	 * @var array
	 */
	protected static $typesPaths				= array();

	/**
	 * debug verbose
	 * @var bool
	 */
	public $debug = false;

	/**
	 * @param string 	$type type to load
	 * @param array 	$paths paths where to search
	 * @param array 	$prefixes explicit file prefixes
	 * @return LBoxLoader
	 * @throws LBoxExceptionLoader
	 */
	public static function getInstance($paths = array(), $pathsIgnore = array(), $prefixes = array()) {
		$className 	= __CLASS__;
		try {
			if (!isset(self::$instance)) {
				self::$instance = new $className($paths, $pathsIgnore, $prefixes);
			}
			return self::$instance;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * returns path of already found type
	 * @return string
	 */
	public function getFoundTypePath($type = "") {
		try {
			if (strlen($type) < 1) {
				throw new LBoxExceptionLoader("\$type: ". LBoxExceptionLoader::MSG_PARAM_STRING_NOTNULL, LBoxExceptionLoader::CODE_BAD_PARAM);
			}
			$this->load($type);
			if (!array_key_exists($type, self::$typesPaths)) {
				throw new LBoxExceptionLoader("$type: ". LBoxExceptionLoader::MSG_TYPE_NOT_FOUND, LBoxExceptionLoader::CODE_TYPE_NOT_FOUND);
			}
			return self::$typesPaths[$type];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * nacte pozadovany typ
	 * @param string 	$type type to load
	 * @throws LBoxExceptionLoader
	 */
	public function load($type = "") {
		try {
			if (array_key_exists($type, self::$typesPaths)) {
				return;
			}
			// nalezeno v cachi
			if ($value	= LBoxCacheLoader::getInstance()->$type) {
				if ($this->debug) {
					echo __CLASS__ ."(". __LINE__ ."):: <font style='color:fuchsia;'>'<b>$type</b>' found in cache as '<i>$value</i>'<br /></font>\n";
				}
				// zkontrolovat, ze se jedna o path v ramci projektu (opatreni, ze cache neni nakopirovana spolu s puvodnim projektem)
				if (substr($value, 0, strlen(LBOX_PATH_INSTANCE_ROOT)) == LBOX_PATH_INSTANCE_ROOT) {
					if (!@include("$value")) {
						LBoxCacheLoader::getInstance()->resetValue($type);
						// throw new LBoxExceptionLoader("'$type' ". LBoxExceptionLoader::MSG_TYPE_LOAD_ERROR ." Path '$value'", LBoxExceptionLoader::CODE_TYPE_LOAD_ERROR);
					}
					else {
						self::$typesPaths[$type] = $value;
						return;
					}
				}
				else {
					if ($this->debug) {
						echo __CLASS__ ."(". __LINE__ ."):: <font style='color:#C0C0C0;'>'<b>$type</b>' Was found in cache under other project. Returned: '<i>$value</i>' - RESETING cache<br /></font>\n";
					}
					LBoxCacheLoader::getInstance()->reset();
				}
			}
			// nenalezeno v cachi
			else {
				if ($this->debug) {
						echo __CLASS__ ."(". __LINE__ ."):: <font style='color:orange;'>'<b>$type</b>' NOT found in cache. Returned: '<i>$value</i>'<br /></font>\n";
				}
			}
			foreach($this->paths as $path) {
				if (!is_dir($path)) {
					throw new LBoxExceptionLoader("Error in defined paths. Element ". LBoxExceptionLoader::MSG_INVALID_DIRPATH ." ('$path')", LBoxExceptionLoader::CODE_INVALID_DIRPATH);
				}
				$pathSearched = false;
				if (array_key_exists($type, $this->searchedPathsByTypes))
				if (is_array($this->searchedPathsByTypes[$type])) {
					foreach($this->searchedPathsByTypes[$type] as $searchedPath) {
						if (strstr("$path". SLASH, $searchedPath)) {
							$pathSearched = true;
						}
					}
				}
				if ($pathSearched) continue;
				if (strlen($foundPath = $this->getPathOfType($type, $path)) > 0) {
					if (!include("$foundPath")) {
						throw new LBoxExceptionLoader("'$type' ". LBoxExceptionLoader::MSG_TYPE_LOAD_ERROR ." Path '$foundPath'", LBoxExceptionLoader::CODE_TYPE_LOAD_ERROR);
					}
					else {
						LBoxCacheLoader::getInstance()->$type	= $foundPath;
						self::$typesPaths[$type]				= $foundPath;
						return;
					}
				}
				$this->searchedPathsByTypes[$type][] = "$path". SLASH;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vrati uplnou cestu k souboru s definici pozadovaneho typu
	 * jelikoz je rekurzivni, vyhazuje vyjimku jen v pripade skutecne chyby, ne v pripade ze nic nenajde
	 * @param string 	$type type to load
	 * @param string 	$in absolutni cesta kde hledat
	 * @param string 	$prefix pokud je prazdny, pouziji se prefixy z $this->prefixes, jinak se hleda jen konkretni
	 * @return string
	 * @throws LBoxExceptionLoader
	 */
	protected function getPathOfType($type = "", $in = "", $prefix = NULL) {
		if (strlen($type) < 1) {
			throw new LBoxExceptionLoader("\$type ". LBoxExceptionLoader::MSG_PARAM_STRING_NOTNULL, LBoxExceptionLoader::CODE_BAD_PARAM);
		}
		if (strlen($in) < 1) {
			throw new LBoxExceptionLoader("\$in ". LBoxExceptionLoader::MSG_PARAM_STRING_NOTNULL, LBoxExceptionLoader::CODE_BAD_PARAM);
		}
		if (!is_dir($in)) {
			throw new LBoxExceptionLoader("\$in ". LBoxExceptionLoader::MSG_PARAM_PATH_ABSOLUTE_DIR, LBoxExceptionLoader::CODE_BAD_PARAM);
		}
		// sloucit moznosti cesty in s a bez lomitka do jedne varianty
		if (substr($in, strlen($in)-1) == SLASH) {
			$in = substr($in, 0, strlen($in)-1);
		}

		if (strlen($path = $this->getPathOfQuickly($type, $prefix)) > 0) {
			return $path;
		}

		// ignore $this->pathsIgnore
		if (array_search($in, $this->pathsIgnore) !== false) {
			return "";
		}
		if (array_search("$in". SLASH, $this->pathsIgnore) !== false) {
			return "";
		}
		// do not search dir for type again
		if (array_key_exists($type, $this->foundTypesInPaths))
		if (strlen($foundInPath = $this->foundTypesInPaths[$type]) > 0) {
			return $foundInPath;
		}
		if (array_key_exists("$prefix.$type", $this->searchedPathsByPrefixedTypes))
		if (is_array($this->searchedPathsByPrefixedTypes["$prefix.$type"])) {
			sort($this->searchedPathsByPrefixedTypes["$prefix.$type"]);
			foreach ($this->searchedPathsByPrefixedTypes["$prefix.$type"] as $searchedPath) {
				if (strstr("$in". SLASH, $searchedPath)) {
					return "";
				}
			}
		}

		$dir 	= opendir($in);
		if ($this->debug) {
			echo __CLASS__ ."(". __LINE__ .") :: searching for '<b>$type</b>' in '<i>$in</i>'<br />\n";
		}
		while (false !== ($nameReaded = readdir($dir))) {
			if ($nameReaded == ".") continue;
			if ($nameReaded == "..") continue;
			$prefixCompare 	= "";
			// adresare posilame do rekurze
			if (is_dir("$in". SLASH ."$nameReaded")) {
				if (strlen($recursiveNameReaded = $this->getPathOfType($type, "$in". SLASH ."$nameReaded")) > 0) {
					$this->searchedPathsByPrefixedTypes["$prefix.$type"][] = "$in". SLASH ."$nameReaded". SLASH;
					return $recursiveNameReaded;
				}
			}
			else {
				$postfix = $this->postfix;
				if (is_string($prefix)) {
					if (strlen($prefix) > 0)  {
						$prefixCompare = "$prefix.";
					}
					if ($nameReaded == $prefixCompare ."$type.$postfix") {
						$this->searchedPathsByPrefixedTypes["$prefix.$type"][] = "$in". SLASH;
						if ($this->debug) {
							echo __CLASS__ ."(". __LINE__ ."):: <font style='color:green;'>'<b>$type</b>' found in '<i>$in". SLASH ."$nameReaded</i>'<br /></font>\n";
						}
						$this->foundTypesInPaths[$type] = "$in". SLASH ."$nameReaded";
						return "$in". SLASH ."$nameReaded";
					}
					$this->checkedFiles["$nameReaded"] = "$in". SLASH ."$nameReaded";
					if ($this->debug) {
						echo __CLASS__ ."(". __LINE__ ."):: <font style='color:red;'>'<b>$type</b>' NOT found as '<i>$in". SLASH ."$nameReaded</i>'<br /></font>\n";
					}
				}
				else foreach($this->prefixes as $prefixCheck) {
					if (strlen($prefixCheck) > 0)  {
						$prefixCompare = "$prefixCheck.";
					}
					if ($nameReaded == $prefixCompare ."$type.$postfix") {
						$this->searchedPathsByPrefixedTypes["$prefixCheck.$type"][] = "$in". SLASH;
						if ($this->debug) {
							echo __CLASS__ ."(". __LINE__ ."):: <font style='color:green;'>'<b>$type</b>' found in '<i>$in". SLASH ."$nameReaded</i>'<br /></font>\n";
						}
						$this->foundTypesInPaths[$type] = "$in". SLASH ."$nameReaded";
						return "$in". SLASH ."$nameReaded";
					}
					$this->checkedFiles["$nameReaded"] = "$in". SLASH ."$nameReaded";
					if ($this->debug) {
						echo __CLASS__ ."(". __LINE__ ."):: <font style='color:red;'>'<b>$type</b>' NOT found as '<i>$in". SLASH ."$nameReaded</i>'<br /></font>\n";
					}
				}
			}
		}
		$this->searchedPathsByPrefixedTypes["$prefix.$type"][] = "$in". SLASH;
		return "";
	}

	/**
	 * checks searched type in cheked files
	 * @param string $type
	 * @return string
	 */
	protected function getPathOfQuickly($type = "", $prefix = NULL) {
		$postfix = $this->postfix;
		if (is_string($prefix)) {
			$fileNameSearch = "$prefix.$type.$postfix";
			if (array_key_exists($fileNameSearch, $this->checkedFiles)) {
				$filePath = $this->checkedFiles[$fileNameSearch];
				if ($this->debug) {
					echo __CLASS__ ."(". __LINE__ ."):: <font style='color:green;'>'<b>$type</b>' found QUICKLY in '<i>$filePath</i>'<br /></font>\n";
				}
				return $filePath;
			}
			else {
				if ($this->debug) {
					echo __CLASS__ ."(". __LINE__ ."):: <font style='color:red;'>'<b>$type</b>' NOT found QUICKLY<br /></font>\n";
				}
				return "";
			}
		}
		else {
			foreach ($this->prefixes as $prefix) {
				$fileNameSearch = "$prefix.$type.$postfix";
				if (array_key_exists($fileNameSearch, $this->checkedFiles)) {
					$filePath = $this->checkedFiles[$fileNameSearch];
					if ($this->debug) {
						echo __CLASS__ ."(". __LINE__ ."):: <font style='color:green;'>'<b>$type</b>' found QUICKLY in '<i>$filePath</i>'<br /></font>\n";
					}
					return $filePath;
				}
			}
			if ($this->debug) {
				echo __CLASS__ ."(". __LINE__ ."):: <font style='color:red;'>'<b>$type</b>' NOT found QUICKLY<br /></font>\n";
			}
			return "";
		}
	}

	/**
	 * @param array 	$paths paths where to search
	 * @param array 	$pathsIgnore paths where do not search
	 * @param array 	$prefixes explicit file prefixes
	 * @throws LBoxExceptionLoader
	 */
	protected function __construct($paths = array(), $pathsIgnore = array(), $prefixes = array()) {
		if (count($paths) < 1) {
			throw new LBoxExceptionLoader("\$paths ". LBoxExceptionLoader::MSG_PARAM_ARRAY_NOTNULL, LBoxExceptionLoader::CODE_BAD_PARAM);
		}
		$this->paths		= array_merge($this->paths, 		$paths);
		$this->pathsIgnore	= array_merge($this->pathsIgnore,	$pathsIgnore);
		$this->prefixes 	= array_merge($this->prefixes, 		$prefixes);
	}
}
?>