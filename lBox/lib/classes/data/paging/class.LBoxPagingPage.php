<?php
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0
 * @date 2009-08-18
 */
class LBoxPagingPage implements OutputItem
{
	/**
	 * relevant paging interator
	 * @var LBoxPagingIterator
	 */
	protected $paging;

	/**
	 * page number
	 * @var int
	 */
	protected $number;

	/**
	 * @var LBoxOutputFilter
	 */
	protected $outputFilter;
	
	/**
	 * pole atributu
	 * @var array
	 */
	protected $params	= array();
	
	/**
	 * @param LBoxPagingIterator $paging
	 * @param int $number
	 */
	public function __construct(LBoxPagingIterator $paging, $number = 1) {
		try {
			$this->paging	= $paging;
			$this->number	= $number;
			if (!$number > $this->paging->getPageMax()) {
				throw new LBoxExceptionPaging(LBoxExceptionPaging::MSG_PAGE_OUT_OF_RANGE, LBoxExceptionPaging::CODE_PAGE_OUT_OF_RANGE);
			}
			$this->setOutputFilter(new OutputFilterPagingPage($this));
		}
		catch (Exception $e) {
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

	/**
	 * getter na number
	 * @return int
	 */
	public function getNumber() {
		try {
			return $this->number;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * getter na svuj items Iterator
	 * @return Iterator
	 */
	public function getItems() {
		try {
			return $this->paging->getItemsPage($this->number);
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
	 * getter na filtrovane atributy
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name = "") {
		try {
			if (strlen($name) < 1) {
				throw new LBoxExceptionFront(LBoxExceptionPaging::MSG_PARAM_STRING_NOTNULL, LBoxExceptionPaging::CODE_BAD_PARAM);
			}
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
					case "url":
							$URLParamPattern	= LBoxConfigSystem::getInstance()->getParamByPath("paging/url_param_pattern");
							$URLParamPattern	= str_replace("<paging_id>", $this->paging->getPagingID(), $URLParamPattern);
							$URLParam	= str_replace("<paging_page>", $this->number, $URLParamPattern);
							$URLParamPattern	= str_replace("<paging_page>", "(\d+)", $URLParamPattern);
							$URLParam	= str_replace("/", "", $URLParam);
							$URLParam	= str_replace("\\", "", $URLParam);
							if ($this->getNumber() > 1) {
								$value		= LBoxUtil::getURLWithParams(array($URLParam), LBoxUtil::getURLWithoutParamsByPattern($URLParamPattern));
							}
							else {
								$value		= LBoxUtil::getURLWithoutParamsByPattern($URLParamPattern);
							}
							
						break;
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