<?php
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-02-20
*/
class PhotosRecord extends AbstractRecordLBox
{
	public static $itemsType 		= "PhotosRecords";
	public static $tableName    	= "photos";
	public static $idColName    	= "id";

	public static $dependingRecords	= array(
											"TextsRecordXPhotosRecords",
											"PhotosProductsRecords",
											"PhotosPhotogalleriesRecords",
											"PhotosArticlesRecords",
											"PhotosProductsDefaultRecords",
	
	);
	
	/**
	 * Nazev property s cestou k obrazkum
	 * @var string
	 */
	protected $propertyNamePath			= "path_photos_content";
	
	/**
	 * cache cest k fyzickym souborum podle typu
	 * @var array
	 */
	protected $pathsByTypes	= array();

	/**
	 * cache var
	 * @var FrontPhotogalleriesRecord
	 */
	protected $photogalleryReferenced;
	
	/**
	 * php resource obrazku pro zpracovavani
	 * @var resource
	 */
	protected $resource;

	/**
	 * cache var
	 * @var PhotosRecord
	 */
	protected $thumbnail;
	
	/**
	 * OutputItem interface method
	 * @throws LBoxException
	 */
	public function __get($name = "") {
		try {
			switch ($name) {
				case "size_x":
					if (!$this->params[$name]) {
						$this->params[$name] = $this->getImgX();
					}
					return $this->params[$name];
				case "size_y":
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

	public function __set($name, $value) {
		try {
			if ($name == "filename") {
				$value	= strtolower($value);
			}
			if ($name == "ext") {
				$value	= strtolower($value);
			}
			parent::__set($name, $value);
		}
		catch(Exception $e) {
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
				$this->synchronized	= false;
			}
			if (!is_numeric($this->params["size_x"])) {
				$this->params["size_x"] = $this->getImgX();
				$this->synchronized	= false;
			}
			if (!is_numeric($this->params["size_y"])) {
				$this->params["size_y"] = $this->getImgY();
				$this->synchronized	= false;
			}
			if (!is_numeric($this->params["size"])) {
				$this->params["size"] = @filesize($this->getFilePath());
				$this->synchronized	= false;
			}

			if (!is_numeric($this->params["ref_photogallery"])) {
				$this->params["ref_photogallery"] = "<<NULL>>";
			}
			
			parent::store();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function delete() {
		try {
			@unlink($this->getFilePath(true));
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
	 * vraci svuj nahled pokud nejaky ma
	 * @return PhotosRecord
	 */
	public function getThumbnail() {
		try {
			if ($this->thumbnail instanceof PhotosRecord) {
				return $this->thumbnail;
			}
			if ($this->hasChildren()) {
				$this->thumbnail	= $this->getChildren()->current();
			}
			return $this->thumbnail;
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
	 * @param bool $proportion - ponechat proporce
	 * @throws LBoxExceptionImage
	 */
	public function createThumbnail ($width = 0, $height = 0, $proportion = true) {
		try {
			// pokud nemame dano, ze proporce zustanou, zajistime orezani obrazku tak, aby neprosel destrukci
			/*LBoxFirePHP::warn($this->filename);
			LBoxFirePHP::log("My proportions: ". $this->size_x ."X". $this->size_y);
			LBoxFirePHP::log("Thumb proportions: ". $width ."X". $height);*/
			if (!$proportion) {
				$rateMy		= $this->size_x/$this->size_y;
				$rateThumb	= $width/$height;
				// proti destrukci natazenim na vysku
				if ($rateMy > $rateThumb) {
					//LBoxFirePHP::log(" - Orezavame na vysku");
					$thumb	= $this->getCreateDuplicate(0, $height, true);
					$x1		= (int)(($thumb->size_x-$width)/2);
					$x2		= (int)($x1+$width);
					/*LBoxFirePHP::log("zmensenina ma: ". $thumb->size_x ."X". $thumb->size_y);
					LBoxFirePHP::log("pozadovana sirka: $width");
					LBoxFirePHP::log("orezavam ji na : ". ($x2-$x1) ."X". $height);
					LBoxFirePHP::log("bod x1: ". $x1);
					LBoxFirePHP::log("bod x2: ". $x2);*/
					$thumb	->crop($x1, $x2, $y1 = 0, $y2 = $height);
					$this->addChild($thumb);
				}
				// proti destrukci natazenim na sirku
				else {
					//LBoxFirePHP::log(" - Orezavame na sirku");
					$thumb	= $this->getCreateDuplicate($width, 0, true);
					$y1		= (int)(($thumb->size_y-$height)/2);
					$y2		= (int)($y1+$height);
					$thumb	->crop($x1 = 0, $x2 = $width, $y1, $y2);
					$this->addChild($thumb);
				}
			}
			else {
				$this->addChild($this->getCreateDuplicate($width, $height, $proportion));
			}
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
			$this->params["size_x"]	= "";
			$this->params["size_y"]	= "";
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
				$x = $this->get("size_x");
				$y = $this->get("size_y");
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
					$width = round($height*($this->__get("size_x")/$this->__get("size_y")));
				} else {
					$height = round($width*($this->__get("size_y")/$this->__get("size_x")));
				}
			}
			if (!is_numeric($width) || $width < 1) {
				$width	= $this->get("size_x");
			}
			if (!is_numeric($height) || $height < 1) {
				$height	= $this->get("size_y");
			}
			if ($width < 1 && $height < 1) {
				throw new LBoxExceptionImage("\$width or \$height: ". LBoxExceptionImage::MSG_PARAM_INT_NOTNULL, LBoxExceptionImage::CODE_BAD_PARAM);
			}
			if (strlen($ext) < 1) {
				$ext = $this->get("ext");
			}
			
			//filename
			$thumbFilespaceName = strtolower($this->filename).'_'.$width.'x'.$height.'.'.strtolower($ext);
			$thumbFilespaceName = $this->getFreeFileNameFrom($thumbFilespaceName);
			$thumbFilespaceDest = $this->getDirName() ."/". $thumbFilespaceName;
				
			//resampling
			$imgParent = $this->getImgResource();
			$imgThumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($imgThumb, $imgParent, 0, 0, 0, 0, $width, $height, $this->__get("size_x"), $this->__get("size_y"));

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
			if ($this->get("ref_photogallery")) {
				$thumbRecord ->ref_photogallery	= $this->get("ref_photogallery");
			}
			$thumbRecord->setFileNamePrivate($thumbFilespaceName);
			$thumbRecord->store();

			return $thumbRecord;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * crop the image
	 * @param int $x1 left x position
	 * @param int $x2 right x position
	 * @param int $y1 upper y position
	 * @param int $y2 lower y position
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
			$width		= $this->get("size_x");
			$height		= $this->get("size_y");
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
			$this->params["size_x"]	= $this->getImgX();
			$this->params["size_y"]	= $this->getImgY();
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
		$fileName	= strtolower($fileName);
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
				if (!is_resource($this->resource = $imgResource)) {
					throw new LBoxExceptionImage("Theres no image file in '". $this->getFilePath() ."'");
				}
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
	 * @param bool $silent - pokud je true, nevyhazuje vyjimku v pripade fyzickeho nenalezeni souboru (kvuli delete())
	 * @return string
	 * @throws LBoxException
	 */
	public function getFilePath ($silent = false) {
		try {
			if (!file_exists($filePath = $this->getDirName() .SLASH. $this->getFileName())) {
				if (!$silent) {
					throw new LBoxExceptionFilesystem("'$filePath': ". LBoxExceptionFilesystem::MSG_FILE_NOT_EXISTS, LBoxExceptionFilesystem::CODE_FILE_NOT_EXISTS);
				}
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
			if (strlen($this->pathsByTypes[get_class($this)]) > 0) {
				return $this->pathsByTypes[get_class($this)];
			}
			$path	= LBoxConfigManagerProperties::getPropertyContentByName($this->propertyNamePath);
			$path	= str_ireplace("<project>", LBOX_PATH_PROJECT, $path);
			$path	= str_ireplace("<photogallery_name>", LBoxUtil::fixFileName($this->getPhotogallery()->name), $path);
			$path	= str_ireplace("/", SLASH, $path);
			$path	= str_ireplace("\\", SLASH, $path);
			$this->createDirByPath($path);
			return $this->pathsByTypes[get_class($this)] = $path;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na relevantni fotogalerii pokud nejaka je
	 * @return FrontPhotogalleriesRecord
	 */
	public function getPhotogallery() {
		try {
			if ($this->photogalleryReferenced instanceof FrontPhotogalleriesRecord) {
				return $this->photogalleryReferenced;
			}
			if (is_numeric($this->params["ref_photogallery"])) {
				$this->photogalleryReferenced	= new FrontPhotogalleriesRecord($this->get("ref_photogallery"));
				$records	= new FrontPhotogalleriesRecords(array("id" => $this->get("ref_photogallery")));
				if ($records->count() < 1) {
					throw new LBoxException("Bounded photogallery !not found!");
				}
				$this->photogalleryReferenced	= $records->current();
				$this->photogalleryReferenced	->setOutputFilter(new OutputFilterPhotoGallery($this->photogalleryReferenced));
				return $this->photogalleryReferenced;
			}
		}
		catch(Exception $e) {
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
}
?>