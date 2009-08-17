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
	public function __construct($classNameIterator, $pageItems, $filter = false, $order = false, $limit = false, QueryBuilderWhere $whereAdd) {
		try {
			if (is_array($limit)) {
				// seriznout pocet jednotek podle poctu na stranku
				if (end($limit) - reset($limit) > $pageItems) {
					//TODO
				}
			}
			parent::__construct($classNameIterator, $pageItems);
			$this->filter	= $filter;
			$this->order	= $order;
			$this->limit	= $limit;
			$this->whereAdd	= $whereAdd;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function getItemsPage($page = 0) {
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
			// vypocet limitu count
			if (($page+1) * $this->pageItems > end($this->limit)) {
				$limitCount	= end($this->limit) % $this->pageItems;
			}
			else {
				$limitCount	= $this->pageItems;
			}
			return $this->itemsPages[$page]	= new $classNameRecords($this->filter, $this->order, array($limitFrom, $limitCount), $this->whereAdd);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected abstract function getItemsCount() {
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
}