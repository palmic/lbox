<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2009-08-17
 */
abstract class LBoxPagingIterator
{
	/**
	 * iterated object class
	 * @var string
	 */
	protected $classNameIterator;
	
	/**
	 * count items per page
	 * @var int
	 */
	protected $pageItems;
	
	/**
	 * unikatni ID paging objektu v ramci systemu
	 * @var string
	 */
	protected $pagingID;
	
	/**
	 * pole registrovanych paging IDs
	 * @var array
	 */
	protected static $pagingIDs = array();
	
	/**
	 * cache var
	 * @var int
	 */
	protected $itemsCount;
	
	/**
	 * cache cache iteratoru podle stranek
	 * @var array
	 */
	protected $itemsPages	= array();
	
	/**
	 * 
	 * @param string $iterator
	 * @param int $pageItems
	 */
	public function __construct($classNameIterator, $pageItems) {
		try {
			$iterator	= new $classNameIterator();
			if (!$iterator instanceof Iterator) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INSTANCE_CONCRETE, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			if (!is_numeric($pageItems) || $pageItems < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INT_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			$this->classNameIterator	= $classNameIterator;
			$this->pageItems			= $pageItems;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na Iterator konkretni stranky
	 * @param int $page
	 * @return Iterator
	 */
	public function getItemsPage($page = 1) {
		try {
			if (!is_numeric($page) || $page < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INT_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			if ($this->itemsPages[$page] instanceof Iterator) {
				return $this->itemsPages[$page];
			}

			//TODO

		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na items aktualni stranky
	 * @return Iterator
	 */
	public function getItemsPageCurrent() {
		try {
			return $this->getItemsPage($this->getPageCurrent());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cislo aktualni stranky
	 * @return int
	 */
	protected function getPageCurrent() {
		try {
			//TODO
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cislo aktualni stranky
	 * @return int
	 */
	protected function getPageMax() {
		try {
			return ceil($this->getItemsCount() / $this->pageItems);
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na celkovy pocet items iteratoru
	 * @return int
	 */
	protected abstract function getItemsCount();

	/**
	 * vraci unikatni ID objektu strankovani
	 * @return string
	 */
	protected function getPagingID() {
		try {
			// paging ID je uz nadefinovano
			if (strlen($this->pagingID) > 0) {
				if ($key = array_search($this->pagingID, self::$pagingIDs)) {
					if (spl_object_hash($this) != $key) {
						throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_INSTANCE_ID_INVALID, LBoxExceptionPaging::CODE_INSTANCE_ID_INVALID);
					}
					else {
						return $this->pagingID;
					}
				}
				else {
					self::$pagingIDs[spl_object_hash($this)]	= $this->pagingID;
				}
			}
			// paging ID neni nadefinovano
			else {
				//XXX testnout
				while (array_search($this->pagingID = substr($this->pagingID, 0, rand(0, strlen($this->pagingID))), self::$pagingIDs)) {
					NULL;
				}
			}
			return $this->pagingID;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}