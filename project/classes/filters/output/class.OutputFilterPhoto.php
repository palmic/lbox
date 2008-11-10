<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox techhouse.cz
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-07-31
*/
class OutputFilterPhoto extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			switch ($name) {
				case "url":
					$value 		= str_replace("\\", "/", str_ireplace(LBOX_PATH_PROJECT, "", $this->instance->getFilePath()));
					break;
				case "name":
					if (strlen($value) < 1) {
						$value = $this->instance->filename;
					}
					break;
				case "filtered_x":
						$value	= $this->getFilteredX();
					break;
				case "filtered_y":
						$value	= $this->getFilteredY();
					break;
				case "thumbnail":
					$class = get_class($this->instance);
					if (($thumb = $this->instance->getChildren()->current()) instanceof $class) {
						$myClassName = get_class($this);
						$thumb->setOutputFilter(new $myClassName($thumb));
						$value = $thumb;
					}
					else {
						$value = NULL;
					}
					break;
				default:
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Vraci sirku obrazku filtrovanou pres nastaveni v properties
	 * (pokud je delsi strana obrazku delsi, strany prepocita)
	 * @return int
	 * @throws Exceppion
	 */
	protected function getFilteredX()	{
		try {
			if ($this->isThumbnail()) {
				$maxLonger	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("photogallery_thumbnail_size_longer")->getContent();
			}
			else {
				$maxLonger	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("photogallery_image_size_limit_longer")->getContent();
			}
			if ($this->instance->sizeX > $this->instance->sizeY) {
				return ($this->instance->sizeX > $maxLonger) ? $maxLonger : $this->instance->sizeX;
			}
			else {
				if ($this->instance->sizeY > $maxLonger) {
					$rate	= $this->instance->sizeY / $maxLonger;
					return 	round($this->instance->sizeX / $rate);
				}
			}
		}
		catch (Exception $e) {
			throw ($e);
		}
	}

	/**
	 * viz vyse getFilteredX
	 * @return int
	 * @throws Exceppion
	 */
	protected function getFilteredY()	{
		try {
			if ($this->isThumbnail()) {
				$maxLonger	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("photogallery_thumbnail_size_longer")->getContent();
			}
			else {
				$maxLonger	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("photogallery_image_size_limit_longer")->getContent();
			}
			if ($this->instance->sizeY > $this->instance->sizeX) {
				return ($this->instance->sizeY > $maxLonger) ? $maxLonger : $this->instance->sizeY;
			}
			else {
				if ($this->instance->sizeX > $maxLonger) {
					$rate	= $this->instance->sizeX / $maxLonger;
					return 	round($this->instance->sizeY / $rate);
				}
			}
		}
		catch (Exception $e) {
			throw ($e);
		}
	}

	/**
	 * @return bool
	 * @throws Exceppion
	 */
	protected function isThumbnail()	{
		try {
			return ($this->instance->hasParent());
		}
		catch (Exception $e) {
			throw ($e);
		}
	}
}
?>