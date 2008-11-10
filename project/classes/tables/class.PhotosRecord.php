<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-12-08
*/
class PhotosRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotosRecords";
	public static $tableName    	= "photos";
	public static $idColName    	= "id";

	public static $boundedM1 = array("ModelsRecords" => "ref_model");
	
	public static $dependingRecords	= array("PhotosRatingsXTUsersRecords", "PhotosRatingsTopRecords", "PhotosRatingsRecords",
	);
	
	/**
	 * php resource obrazku pro zpracovavani
	 * @var resource
	 */
	protected $resource;

	/**
	 * cache variables
	 */
	protected $model;
	
	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				case "sizeX":
					if (!$this->params[$name]) {
						$this->params[$name] = $this->getImgX();
					}
					return $this->params[$name];
				case "sizeY":
					if (!$this->params[$name]) {
						$this->params[$name] = $this->getImgY();
					}
					return $this->params[$name];
					break;
				default:
					return parent::__get($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * synonym to __get() just for comfort in special cases
	 */
	public function get($varName) {
		try {
			return $this->__get($varName);
		}
		catch(Excpetion $e) {
			throw $e;
		}
	}

	public function store() {
		try {
			$this->params["ext"] = strtolower($this->params["ext"]);
			if ($this->params["ext"] == "jpeg") {
				$this->params["ext"] = "jpg";
			}
			if (!is_numeric($this->params["sizeX"])) {
				$this->params["sizeX"] = $this->getImgX();
			}
			if (!is_numeric($this->params["sizeY"])) {
				$this->params["sizeY"] = $this->getImgY();
			}
			if (!is_numeric($this->params["size"])) {
				$this->params["size"] = @filesize($this->getFilePath());
			}
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function delete() {
		try {
			unlink($this->getFilePath());
			parent::delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci root fotku z vetve (vhodne pokud chceme hodnotit fotku a mame v ruce jen jeji thumbnail)
	 * @return PhotosRecord
	 */
	public function getSuperParent() {
		try {
			$parent	= $this;
			while ($parent->hasParent()) {
				$parent	= $parent->getParent();
			}
			return $parent;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * prida hlas momentalne prihlaseneho uzivatele
	 * @param int $rating
	 * @throws LBoxException
	 */
	public function rateByCurrentXTUser($rating	= 10) {
		try {
			if (!is_numeric($rating) || $rating < 1) {
				throw new LBoxException(LBoxException::MSG_PARAM_INT_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			if (!LBoxXT::isLogged()) {
				return;
			}
			// pozor na nahled
			$photo	= $this;
			while ($photo->isThumbnail()) {
				$photo	= $photo->getParent();
			}
			
			// zjistime, jestli uz pro ni uzivatel nehlasoval
			$photosRatings	= new PhotosRatingsXTUsersRecords(array("id_photo" => $photo->id,
																	"email" => LBoxXT::getUserXTRecord()->email)
																);
			if ($photosRatings->count() > 0) return;

			// zkontrolovat pocet hlasu uzivatele
			if ($photo->getModel()->getSchool()->hasUserReachedVotedLimit()) {
				return;
			}
			
			// zapsat hlas
			$ratingRecord	= new PhotosRatingsRecord();
			$ratingRecord->ref_photo	= $photo->id;
			$ratingRecord->ref_acces	= AccesRecord::getInstance()->id;
			$ratingRecord->rating		= $rating;
			$ratingRecord->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci, jestli se jedna o thumbnail
	 * @return bool
	 */
	public function isThumbnail() {
		try {
			return $this->hasParent();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * vytvori nahled obrazku a priradi ho ve stromu pod obrazek
	 * @param int $width
	 * @param int $height
	 * @param bool $proportion
	 * @throws LBoxExceptionImage
	 */
	public function createThumbnail ($width = 0, $height = 0) {
		try {
			$this->addChild($this->getCreateDuplicate($width, $height, $proportion = true));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zmeni velikost obrazku
	 * @param int $width
	 * @param int $height
	 * @param bool $proportion
	 * @throws LBoxExceptionImage
	 */
	public function resize($width = 0, $height = 0, $proportion = true) {
		try {
			$duplicate = $this->getCreateDuplicate($width, $height, $proportion);
			$filePath = $this->getFilePath();
			unset($filePath);

			// presunuti duplikatu
			$fd 				= fopen($duplicate->getFilePath(), "r");
			$fm					= fopen($this->getFilePath(), "w");
			fwrite($fm, fread($fd, filesize($duplicate->getFilePath())));
			@fclose($fd);
			@fclose($fm);

			$this->resource = NULL;
			$duplicate->delete();
			$this->params["sizeX"]	= $this->getImgX();
			$this->params["sizeY"]	= $this->getImgY();
			$this->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * Osetri, ze obrazek nepresahne rozmerem delsi strany predany limit
	 * @param int $longer
	 * @throws LBoxExceptionImage
	 */
	public function limitSize($longer = 0) {
		try {
			if ($this->getImgLonger() > $longer) {
				$x = $this->get("sizeX");
				$y = $this->get("sizeY");
				if ($x > $y) {
					$this->resize($longer, 0, true);
				}
				else {
					$this->resize(0, $longer, true);
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vytvori kopii obrazku
	 * @param int $width
	 * @param int $height
	 * @param bool $proportion
	 * @param string $ext
	 * @return PhotosRecord
	 * @throws LBoxExceptionImage
	 */
	public function getCreateDuplicate ($width = 0, $height = 0, $proportion = true, $ext = "") {
		try {
			//pokud to ma byt proporcne
			if ($proportion) {
				if (!$width) {
					$width = round($height*($this->__get("sizeX")/$this->__get("sizeY")));
				} else {
					$height = round($width*($this->__get("sizeY")/$this->__get("sizeX")));
				}
			}
			if (!is_numeric($width) || $width < 1) {
				$width	= $this->get("sizeX");
			}
			if (!is_numeric($height) || $height < 1) {
				$height	= $this->get("sizeY");
			}
			if ($width < 1 && $height < 1) {
				throw new LBoxExceptionImage("\$width or \$height: ". LBoxExceptionImage::MSG_PARAM_INT_NOTNULL, LBoxExceptionImage::CODE_BAD_PARAM);
			}
			if (strlen($ext) < 1) {
				$ext = $this->get("ext");
			}
			
			//filename
			$thumbFilespaceName = $this->filename.'_'.$width.'x'.$height.'.'.$ext;
			$thumbFilespaceName = $this->getFreeFileNameFrom($thumbFilespaceName);
			$thumbFilespaceDest = $this->getDirName() ."/". $thumbFilespaceName;
				
			//resampling
			$imgParent = $this->getImgResource();
			$imgThumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($imgThumb, $imgParent, 0, 0, 0, 0, $width, $height, $this->__get("sizeX"), $this->__get("sizeY"));

			//ulozeni
			switch ($ext)
			{
				case 'jpg':
					if (!imagejpeg($imgThumb, $thumbFilespaceDest)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;

				case 'png':
					if (!imagepng($imgThumb, $thumbFilespaceDest)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;

				case 'gif':
					if (!imagegif($imgThumb, $thumbFilespaceDest)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;
			}

			//vytvoreni node
			$className	= get_class($this);
			$thumbRecord = new $className;
			$thumbRecord->setFileNamePrivate($thumbFilespaceName);
			$thumbRecord->ref_model = $this->get("ref_model");
			$thumbRecord->store();

			return $thumbRecord;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * crop the image
	 * @param int $x1 upper left crop position
	 * @param int $x2 upper right crop position
	 * @param int $y1 lower left crop position
	 * @param int $y2 lower right crop position
	 * @throws LBoxExceptionImage
	 */
	public function crop($x1 = 0, $x2 = 0, $y1 = 0, $y2 = 0) {
		try {
			if ($x2 < 1) {
				throw new LBoxExceptionImage("\$x2: ". LBoxExceptionImage::MSG_PARAM_INT_NOTNULL, LBoxExceptionImage::CODE_BAD_PARAM);
			}
			if ($y2 < 1) {
				throw new LBoxExceptionImage("\$y2: ". LBoxExceptionImage::MSG_PARAM_INT_NOTNULL, LBoxExceptionImage::CODE_BAD_PARAM);
			}
			$targetPath	= $this->getFilePath();
			$width		= $this->get("sizeX");
			$height		= $this->get("sizeY");
			$widthNew	= abs($x2-$x1) > $width-$x1 ? $width-$x1 : abs($x2-$x1);
			$heightNew	= abs($y2-$y1) > $height-$y1 ? $height-$y1 : abs($y2-$y1);
			$img	= $this->getImgResource();
			$imgNew	= imagecreatetruecolor($widthNew, $heightNew);

			$canvas = imagecreatetruecolor($widthNew,$heightNew);
			$piece 	= $this->getImgResource();
			imagecopyresized($canvas, $piece, 0,0, $x1, $y1, $widthNew, $heightNew, $widthNew, $heightNew);

			//ulozeni
			switch ($this->get("ext"))
			{
				case 'jpg':
					if (!imagejpeg($canvas, $targetPath, 100)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;

				case 'png':
					if (!imagepng($canvas, $targetPath)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;

				case 'gif':
					if (!imagegif($canvas, $targetPath)) {
						throw new LBoxExceptionImage(LBoxExceptionImage::MSG_FILE_NOT_CREATED, LBoxExceptionImage::CODE_FILE_NOT_CREATED);
					}
					break;
			}

			imagedestroy($canvas);
			imagedestroy($piece);

			$this->resource	= NULL;
			$this->params["sizeX"]	= $this->getImgX();
			$this->params["sizeY"]	= $this->getImgY();
			$this->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * chraneny public setter
	 * @param string $fileName
	 */
	public function setFileName($fileName = "") {
		try {
			if ($this->isInDatabase()) {
				throw new LBoxExceptionImage(LBoxExceptionImage::MSG_CHANGE_FILENAME_IMAGE_SAVED, LBoxExceptionImage::CODE_CHANGE_FILENAME_IMAGE_SAVED);
			}
			$this->setFileNamePrivate($fileName);
		}
		catch (Exception $e) {
			throw $e;
		}
	}


	/**
	 * umoznuje nastavit filename vcetne extension - rozlozi jej
	 * @throws LBoxExceptionImage
	 */
	protected function setFileNamePrivate($fileName = "") {
		if (strlen($fileName) < 1) {
			throw new LBoxExceptionImage(LBoxExceptionImage::MSG_PARAM_STRING_NOTNULL, LBoxExceptionImage::CODE_BAD_PARAM);
		}
		// rozdelit na filename + ext
		$fnParts 	= explode(".", $fileName);
		$ext 		= end($fnParts);
		unset($fnParts[key($fnParts)]);
		$fn			= implode(".", $fnParts);

		if (strlen($fn) < 1 || strlen($ext) < 1) {
			throw new LBoxExceptionImage("$fileName: ". LBoxExceptionImage::MSG_WRONG_FILENAME_TO_SET, LBoxExceptionImage::CODE_WRONG_FILENAME_TO_SET);
		}
		$this->params["filename"] 	= $fn;
		$this->params["ext"]		= $ext;
	}

	/**
	 * Vraci rozmer delsi strany obrazku
	 * @returns integer
	 */
	public function getImgLonger() {
		$x = $this->getImgX();
		$y = $this->getImgY();
		return $x > $y ? $x : $y;
	}

	/**
	 * Vraci rozmer kratis strany obrazku
	 * @returns integer
	 */
	public function getImgShorter() {
		$x = $this->getImgX();
		$y = $this->getImgY();
		return $x < $y ? $x : $y;
	}

	/**
	 * Vraci aktualni sirku obrazku
	 * @returns integer
	 */
	public function getImgX() {
		return (int)imagesx($this->getImgResource());
	}

	/**
	 * Vraci aktualni vysku obrazku
	 * @returns integer
	 */
	public function getImgY() {
		return (int)imagesy($this->getImgResource());
	}

	/**
	 * Vraci image resource obrazku
	 * @return resource
	 * @throws LBoxException
	 */
	protected function getImgResource() {
		try {
			if (!$this->resource) {
				switch ($this->get("ext")) {
					case 'jpg':
						$imgResource = imagecreatefromjpeg($this->getFilePath());
						break;

					case 'png':
						$imgResource = imagecreatefrompng($this->getFilePath());
						break;

					case 'gif':
						$imgResource = imagecreatefromgif($this->getFilePath());
						break;
				}
				$this->resource = $imgResource;
			}
			return $this->resource;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Ulozi uploadovany soubor do filesystemu
	 * @param string $tmpPath
	 * @param string $fileName
	 * @param string $size
	 * @throws LboxException
	 */
	public function saveUploadedFile($tmpPath = "", $fileName = "", $size = "") {
		try {
			if (strlen($tmpPath) < 1) {
				throw new LBoxExceptionFilesystem("\$tmpPath: ". LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			if (strlen($fileName) < 1) {
				throw new LBoxExceptionFilesystem("\$fileName: ". LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			if (strtolower($this->getFileExt($fileName)) == "php") {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_RESTRICTED_FILENAME, LBoxExceptionFilesystem::CODE_RESTRICTED_FILENAME);
			}
			if ($size < 1) {
				throw new LBoxExceptionFilesystem("\$size: ". LBoxExceptionFilesystem::MSG_PARAM_INT_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$pathTarget		= $this->getDirName();

			$fileName	= $this->getFreeFileNameFrom($fileName);
			if (!move_uploaded_file($tmpPath, "$pathTarget/$fileName")) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_FILE_UPLOAD_ERROR, LBoxExceptionFilesystem::CODE_FILE_UPLOAD_ERROR);
			}

			$lastDotPos	= strrpos($fileName, ".");
			$nameOfFile	= substr($fileName, 0, $lastDotPos);

			$this->params["ext"]		= $this->getFileExt($fileName);
			$this->params["filename"]	= $nameOfFile;
			$this->params["size"]		= filesize("$pathTarget/$fileName");
			$this->store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * zjisti, jestli je dany nazev filu ve filesystemu volny
	 * @param string $fileName
	 * @throws LboxException
	 */
	protected function getFreeFileNameFrom($fileName = "") {
		try {
			if (strlen($fileName) < 1) {
				throw new LBoxExceptionFilesystem("$fileName: ". LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$fileName		= str_replace(" ", "_", $fileName);
			//$fileName 		= mb_convert_encoding($fileName, "UTF-8");
			// pryc s diakritikou pokud mame prostredi s mbstring extension
			$fileName 		= $this->removeDiacritic($fileName);
			//$fileName		= eregi_replace("")
			$pathTarget		= $this->getDirName();
			while(file_exists("$pathTarget/$fileName")) {
				$ext		= $this->getFileExt($fileName);
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
	 * Vraci priponu souboru
	 * @param string $filename
	 * @return string
	 * @throws LboxException
	 */
	protected function getFileExt($fileName = "") {
		try {
			if (strlen($fileName) < 1) {
				throw new LBoxExceptionFilesystem("$fileName: ". LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$parts	= explode(".", $fileName);
			return end($parts);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci celou cestu k souboru
	 * @return string
	 * @throws LBoxException
	 */
	public function getFilePath () {
		try {
			if (!is_file($filePath = $this->getDirName() .SLASH. $this->getFileName())) {
				throw new LBoxExceptionFilesystem("$filePath: ". LBoxExceptionFilesystem::MSG_FILE_NOT_EXISTS, LBoxExceptionFilesystem::CODE_FILE_NOT_EXISTS);
			}
			return $filePath;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci adresar
	 * @return string
	 * @throws LBoxException
	 */
	public function getDirName () {
		try {
			$path	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("path_photos_models")->getContent();
			//$path	= str_ireplace("<region>", "region_". $this->getModel()->getRegion()->id, $path);
			//$path	= str_ireplace("<city>", "city_". $this->getModel()->getCity()->id, $path);
			//$path	= str_ireplace("<school>", "school_". $this->getModel()->getSchool()->id, $path);
			$path	= str_ireplace("\$project", LBOX_PATH_PROJECT, $path);
			$path	= str_ireplace("/", SLASH, $path);
			$this->createDirByPath($path);

			return $path;
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
	protected function createDirByPath($path = "") {
		try {
			if (strlen($path) < 1) {
				throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_PARAM_STRING_NOTNULL, LBoxExceptionFilesystem::CODE_BAD_PARAM);
			}
			$pathParts	 = explode(SLASH, $path);
			$pathTMP	.= WIN ? "" : "/";
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
	 * vraci jmeno souboru s extension
	 * @return string
	 * @throws LBoxExceptionImage
	 */
	public function getFileName () {
		if (strlen($ext = $this->get("ext")) < 1) {
			throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_NO_EXT_DEFINED, LBoxExceptionFilesystem::CODE_NO_EXT_DEFINED);
		}
		if (strlen($filename = $this->get("filename")) < 1) {
			throw new LBoxExceptionFilesystem(LBoxExceptionFilesystem::MSG_NO_FILENAME_DEFINED, LBoxExceptionFilesystem::CODE_NO_FILENAME_DEFINED);
		}
		return ("$filename.$ext");
	}

	/**
	 * Odstrani diakritiku
	 * @param string $input
	 */
	protected function removeDiacritic($input = "") {
		if (function_exists ("mb_convert_encoding")) {
			return mb_convert_encoding($input, "UTF-8", mb_detect_encoding($input));
		}
		else {
			return 		strtr($input, 				"áäčďéěëíňóöřšťúůüýžÁÄČĎÉĚËÍŇÓÖŘŠŤÚŮÜÝŽ",
	    											"aacdeeeinoorstuuuyzAACDEEEINOORSTUUUYZ");
		}
	}
	
	/**
	 * vraci downloady filu
	 * @return FilesDownloadedRecords
	 */
	public function getFileDownloaded () {
		try {
			return $this->getBounded1MInstance("FilesDownloadedRecords", $filter, $order, $limit, $whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci instanci soutezici fotky
	 * @return ModelsRecord
	 */
	public function getModel () {
		try {
			if ($this->model instanceof ModelsRecord) {
				return $this->model;
			}
			return $this->model	= $this->getBoundedM1Instance("ModelsRecords", $filter, $order, $limit, $whereAdd)->current();
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>