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
	 * //TODO do budoucna vykonova optimalizace nutna!
	 * Prepsana kuli stromum
	 * V pripade ze ma potomky, misto vypsani vyjimky smaze nejdrive potomky a potom preda iniciativu parentovi 
	 * @throws Exception
	 */
	public function delete() {
		try {
			if (!$this->isInDatabase()) {
				$this->clearCache();
				return;
			}
			if ($this->isTree()) {
				$this->clearCache();
				if ($this->hasChildren()) {
					foreach ($this->getChildren() as $child) {
						$child->delete();
					}
					$this->setSynchronized(false);
					$this->hasChildren	= false;
					$this->load();
				}
			}
			parent::delete();
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
			return parent::__get($name);
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