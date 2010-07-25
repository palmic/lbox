<?php

/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @date 2009-08-17
 */
abstract class LBoxPagingIterator implements Iterator
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
	 * cache var
	 * @var LBoxIteratorPagingPages
	 */
	protected $pages;

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
			return $this->getItemsPage($this->getPageNumberCurrent());
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cislo aktualni stranky
	 * @return int
	 */
	public function getPageNumberCurrent() {
		try {
			if (strlen($URLParam = $this->getURLParam()) < 1) {
				return 1;
			}
			if (!preg_match($this->getPatternURLParam(), $URLParam, $matches)) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_URL_PARAM_INVALID_PATTERN, LBoxExceptionPaging::CODE_URL_PARAM_INVALID_PATTERN);
			}
			else {
				switch (true) {
					case (!is_numeric($matches[2])):
							throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_URL_PARAM_INVALID, LBoxExceptionPaging::CODE_URL_PARAM_INVALID);
						break;
					case ($matches[2] < 1):
							return 1;
						break;
					case ($matches[2] > $this->getPageMax()):
							return $this->getPageMax();
						break;
					default:
						return (int)$matches[2];
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na instanci stranek k prepinani strankovani
	 * @return LBoxIteratorPagingPages
	 */
	public function getPages() {
		try {
			if ($this->pages instanceof LBoxIteratorPagingPages) {
				return $this->pages;
			}
			return $this->pages	= new LBoxIteratorPagingPages($this);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci cislo posledni stranky
	 * @return int
	 */
	public function getPageMax() {
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
	 * setter na paging ID
	 * @param string $id
	 */
	public function setPagingID($id = "") {
		try {
			if (strlen($id) < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			foreach (self::$pagingIDs as $pagingID) {
				if (strtolower($pagingID) == strtolower($id)) {
					throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_INSTANCE_ID_INVALID, LBoxExceptionPaging::CODE_INSTANCE_ID_INVALID);
				}
			}
			$this->pagingID	= self::$pagingIDs[spl_object_hash($this)]	= $id;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci unikatni ID objektu strankovani
	 * @return string
	 */
	public function getPagingID() {
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
				$this->pagingID	= count(self::$pagingIDs)+1;
				/*generovani nahodneho stringu se rozchazi s persistenci URL parametru mezi reloady
				 * $this->pagingID	= spl_object_hash($this);
				 * while (array_search($this->pagingID = substr(substr($this->pagingID, 0, rand(0, strlen($this->pagingID))), -3), self::$pagingIDs)) {
					NULL;
				}*/
			}
			return self::$pagingIDs[spl_object_hash($this)]	= $this->pagingID;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci URL param relevantni k teto instanci
	 * @return string
	 */
	protected function getURLParam() {
		try {
			foreach (LBoxFront::getUrlParamsArray() as $param) {
				if (preg_match($this->getPatternURLParam(), $param)) {
					return $param;
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na pattern, podle ktereho se bude sestavovat URL param pagingu
	 * @return string
	 */
	protected function getPatternURLParam() {
		try {
			$out	= LBoxConfigSystem::getInstance()->getParamByPath("paging/url_param_pattern");
			$out	= str_replace("<paging_id>", "(\w+)", $out);
			$out	= str_replace("<paging_page>", "(.+)", $out);
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	
	########################################################################################################
	# 
	# rozhrani Iterator 
	# 
	########################################################################################################
	
	public function current() {
	}

	public function key() {
	}

	public function next() {
	}

	public function rewind() {
	}

	public function valid() {
	}
}