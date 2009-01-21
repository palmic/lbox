<?php

/**
 * adds LBoxOutputFilter compatibility
 * @author Michal Palma <palmic at email dot cz>
 * @date 2007-11-03
 */
abstract class AbstractRecordLBox extends AbstractRecord implements OutputItem
{
	/**
	 * set OutputFilters
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;

	/**
	 * getter for filtered param
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name = "") {
		try {
			$value = $this->getParamDirect($name);
			// multilang get
			if (($name != "*") && (!array_key_exists($name, $this->params))) {
				$value = $this->getParamDirect($this->getColNameLNGCurrent($name));
			}
			if ($this->outputFilter instanceof LBoxOutputFilter) {
				return $this->outputFilter->prepare($name, $value);
			}
			else {
				return $value;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci current language colname podle konvenci
	 * @param string $name puvodni nazev parametru
	 * @return mixed
	 */
	protected function getColNameLNGCurrent($name) {
		try {
			return $name ."_". LBoxFront::getDisplayLanguage();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * //TODO do budoucna vykonova optimalizace nutna!
	 * Prepsana kuli stromum
	 * V pripade ze ma potomky, misto vypsani vyjimky smaze nejdrive potomky a potom preda iniciativu parentovi 
	 * @throws Exception
	 */
	public function delete() {
		try {
			$this->isCacheOn = false;
			$this->clearCache();
			if ($this->isTree()) {
				foreach ($this->getChildren() as $child) {
					$child->delete();
				}
				$this->hasChildren = false;
				$this->setSynchronized(false);
				$this->load();
			}
			parent::delete();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o predavani output filteru
	 * @return AbstractRecords
	 */
	public function getChildren() {
		try {
			$children	= parent::getChildren();
			if ($this->outputFilter instanceof LBoxOutputFilter) {
				if ($children instanceof AbstractRecordsLBox ||
					$children instanceof AbstractRecordsTreeLBox) {
					$children->setOutputFilterItemsClass(get_class($this->outputFilter));
				}
			}
			return $children;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter for unfiltered param
	 * @param string $name 
	 * @return mixed
	 * @throws Exception
	 */
	public function getParamDirect($name = "") {
		try {
			$value	= parent::__get($name);
			if (($name != "*") && (!array_key_exists($name, $this->params))) {
				$value = parent::__get($this->getColNameLNGCurrent($name));
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function setOutputFilter(LBoxOutputFilter $outputFilter) {
		$this->outputFilter = $outputFilter;
	}	
}
?>