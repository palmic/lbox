<?php
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2010-05-26
 */
class LBoxPagingIteratorArray extends LBoxPagingIterator
{
	/**
	 * cache var
	 * @var array
	 */
	protected $array;
	
	/**
	 * cache var
	 * @var mixed
	 */
	protected $key;
	
	/**
	 * flag
	 * @var bool
	 */
	protected $endReached	= false;
	
	/**
	 * pretizeno o konretni pozadavky
	 * @param array $array
	 * @param int $pageItems
	 */
	public function __construct($array, $pageItems) {
		try {
			if (!is_numeric($pageItems) || $pageItems < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INT_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			$this->array				= $array;
			$this->pageItems			= $pageItems;
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
			if (is_array($this->itemsPages[$page])) {
				return new ArrayIterator($this->itemsPages[$page]);
			}
			if ($page > $this->getPageMax()) {
				return;
			}

			$from	= ($page-1) * $this->pageItems;
			$to		= $from + $this->pageItems;
			$i		= 0;
			foreach ($this->array as $k => $v) {
				if ($i >= $from && $i < $to) {
					$this->itemsPages[$page][$k] = $v;
				}
				if ($i >= $to) {
					break;
				}
				$i++;
			}
			$this->itemsPages[$page]	= $this->itemsPages[$page];
			
			return new ArrayIterator($this->itemsPages[$page]);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	public function getItemsCount() {
		try {
			return count($this->array);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci realny pocet items na strance
	 * @return int
	 */
	public function count() {
		try {
//LBoxFirePHP::log(__FUNCTION__ ." = ". $this->pageItems > $this->getItemsCount() ? $this->getItemsCount() : $this->pageItems);
			return $this->pageItems > $this->getItemsCount() ? $this->getItemsCount() : $this->pageItems;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	public function current() {
		try {
			if ($data = $this->itemsPages[$this->getPageNumberCurrent()]) {
//LBoxFirePHP::log(__FUNCTION__ ." = ". $data[$this->key]);
				return $data[$this->key];
			}
			else {
//LBoxFirePHP::log(__FUNCTION__ ." = ". !($this->key === FALSE));
				return NULL;
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function key() {
		try {
//LBoxFirePHP::log(__FUNCTION__);
			return $this->key;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function next() {
		try {
			if (FALSE === next($this->itemsPages[$this->getPageNumberCurrent()])) {
				$this->endReached	= true;
			}
			$this->key	= key($this->itemsPages[$this->getPageNumberCurrent()]);
//LBoxFirePHP::log(__FUNCTION__ ." current key = ". $this->key);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function rewind() {
		try {
			$this->getItemsPage($this->getPageNumberCurrent());
			if (is_array($this->itemsPages[$this->getPageNumberCurrent()])) {
				if (reset($this->itemsPages[$this->getPageNumberCurrent()])) {
					$this->key = key($this->itemsPages[$this->getPageNumberCurrent()]);
				}
			}
//LBoxFirePHP::log(__FUNCTION__ ." current key = ". $this->key);
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function valid() {
		try {
//LBoxFirePHP::log(__FUNCTION__ ." = ". !$this->endReached);
			return !$this->endReached;
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}