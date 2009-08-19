<?php
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @date 2009-08-18
 */
class LBoxIteratorPagingPages extends LBoxIterator implements OutputItem
{
	/**
	 * relevant paging interator
	 * @var LBoxPagingIterator
	 */
	protected $paging;
	
	/**
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;
	
	/**
	 * params pole pro mozne budouci pouziti - filtrovany getter ho kontroluje a pripadne z nej vraci
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * @param LBoxPagingIterator $paging
	 */
	public function __construct(LBoxPagingIterator $paging) {
		try {
			$this->paging	= $paging;
			//init dat pro iterator
			$this->getPageByNumber(count($this->items)+1);
			$this->setOutputFilter(new OutputFilterPagingPages($this));
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci current stranku
	 * @return LBoxPagingPage
	 */
	public function getPageCurrent() {
		try {
			return $this->getPageByNumber($this->paging->getPageNumberCurrent());
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	/**
	 * getter na page podle poradi
	 * @param $number
	 * @return LBoxPagingPage
	 */
	public function getPageByNumber($number = 1) {
		try {
			if ((!is_numeric($number)) || $number < 1) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PARAM_INT_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
			if ($number > $this->paging->getPageMax()) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PAGE_OUT_OF_RANGE, LBoxExceptionPaging::CODE_PAGE_OUT_OF_RANGE);
			}
			$index	= $number-1;
			if ($this->items[$index] instanceof LBoxPagingPage) {
				return $this->items[$index];
			}
			return $this->items[$index]	= new LBoxPagingPage($this->paging, $number);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * delegace getPageMax() z paging instance
	 * @return int
	 */
	public function getPageMax() {
		try {
			return $this->paging->getPageMax();
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * pretizeno o init dalsi polozky (pokud je). Pokud neni, narazi defaultni valid() iteratoru na prazdny prvek a ukonci iteraci
	 */
	public function next() {
		try {
			if (count($this->items) < $this->getPageMax()) {
				$this->getPageByNumber(count($this->items)+1);
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	/**
	 * getter na paging
	 * @return LBoxPagingIterator
	 */
	public function getPaging() {
		try {
			return $this->paging;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	
	############################################################################################################################################
	#
	# OutputItem
	#
	############################################################################################################################################
	
	/**
	 * filtrovany getter
	 * @return mixed
	 */
	public function __get($name = "") {
		try {
			if ($this->outputFilter instanceof LBoxOutputFilter) {
				return $this->outputFilter->prepare($name, $this->getParamDirect($name));
			}
			else {
				return $this->getParamDirect($name);
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * setter output filteru
	 * @param LBoxOutputFilter $outputFilter
	 */
	public function setOutputFilter(LBoxOutputFilter $outputFilter) {
		try {
			$this->outputFilter	= $outputFilter;
		}
		catch(Exception $e) {
			throw $e;
		}
	}

	public function getParamDirect($name = "") {
		try {
			if (strlen($this->params[$name]) < 1) {
				$value	= NULL;
				switch ($name) {
					default:
						$value	= NULL;
				}
				return $this->params[$name]	= $value;
			}
			else {
				return $this->params[$name];
			}
		}
		catch(Exception $e) {
			throw $e;
		}
	}
}