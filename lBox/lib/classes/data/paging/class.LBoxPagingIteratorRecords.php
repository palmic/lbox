<?php

/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2009-08-17
 */
class LBoxPagingIteratorRecords extends LBoxPagingIterator
{
	/**
	 * cache vars
	 */
	protected $classNameOutputFilter = "";
	protected $filter = array();
	protected $order = array();
	protected $limit = array();
	protected $whereAdd;

	/**
	 * pretizeno o konretni pozadavky abstract recordu
	 * @param QueryBuilderWhere $classNameIterator
	 * @param int $pageItems
	 * @param array $filter
	 * @param array $order
	 * @param array $limit
	 * @param QueryBuilderWhere $whereAdd
	 */
	public function __construct($classNameIterator, $pageItems, $classNameOutputFilter = "", $filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd = NULL) {
		try {
			parent::__construct($classNameIterator, $pageItems);
			$this->classNameOutputFilter	= $classNameOutputFilter;
			$this->filter					= $filter;
			$this->order					= $order;
			$this->limit					= $limit;
			$this->whereAdd					= $whereAdd;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getItemsPage($page = 0) {
		try {
			if (!is_numeric($page) || $page < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INT_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			if ($this->itemsPages[$page] instanceof Iterator) {
				return $this->itemsPages[$page];
			}
			if ($page > $this->getPageMax()) {
				return;
			}
			$classNameRecords	= $this->classNameIterator;

			// vypocet limitu od
			$limitFrom		= ($page-1) * $this->pageItems;
			$limitCount		= $this->count();
			$this->itemsPages[$page]	= new $classNameRecords($this->filter, $this->order, array($limitFrom, $limitCount), $this->whereAdd);
			if (strlen($this->classNameOutputFilter) > 0) {
				$this->itemsPages[$page]	->setOutputFilterItemsClass($this->classNameOutputFilter);
			}
			return $this->itemsPages[$page];
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getItemsCount() {
		try {
			if (is_int($this->itemsCount)) {
				return $this->itemsCount;
			}
			$classNameRecords			= $this->classNameIterator;
			$records					= new $classNameRecords($this->filter, $this->order, $this->limit, $this->whereAdd);
			return $this->itemsCount	= (int)$records->count();
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci realny pocet items na strance (polymorfne vuci AbstractRecords::count())
	 * @return int
	 */
	public function count() {
		try {
			// vypocet limitu count
			if (is_array($this->limit) && (($page+1) * $this->pageItems > end($this->limit))) {
				return end($this->limit) % $this->pageItems;
			}
			else {
				return $this->pageItems > $this->getItemsCount() ? $this->getItemsCount() : $this->pageItems;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function current() {
		try {
			return $this->getItemsPageCurrent() ? $this->getItemsPageCurrent()->current() : NULL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function key() {
		try {
			return $this->getItemsPageCurrent() ? $this->getItemsPageCurrent()->key() : NULL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function next() {
		try {
			return $this->getItemsPageCurrent() ? $this->getItemsPageCurrent()->next() : NULL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function rewind() {
		try {
			return $this->getItemsPageCurrent() ? $this->getItemsPageCurrent()->rewind() : NULL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function valid() {
		try {
			return $this->getItemsPageCurrent() ? $this->getItemsPageCurrent()->valid() : NULL;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}