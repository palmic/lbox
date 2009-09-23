<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-08-19
*/
class OutputFilterPagingPages extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			// staticke gettery
			switch ($name) {
				case "page_current":
						$value	= $this->instance->getPageByNumber($this->instance->getPaging()->getPageNumberCurrent());
					break;
				case "page_next":
						if ($this->instance->getPaging()->getPageNumberCurrent() >= $this->instance->getPaging()->getPageMax()) {
							$value	= NULL;
						}
						else {
							$value	= $this->instance->getPageByNumber($this->instance->getPaging()->getPageNumberCurrent()+1);
						}
					break;
				case "page_previous":
						if ($this->instance->getPaging()->getPageNumberCurrent() <= 1) {
							$value	= NULL;
						}
						else {
							$value	= $this->instance->getPageByNumber($this->instance->getPaging()->getPageNumberCurrent()-1);
						}
					break;
				default:
					NULL;
			}
			// dynamicke gettery
			switch (true) {
				case preg_match("/page_by_number_(\d+)/", $name, $matches):
						$value	= $this->instance->getPageByNumber($matches[1]);
					break;
				case preg_match("/pages_by_range_(\d+)/", $name, $matches):
						$rangeStart	= $this->instance->getPaging()->getPageNumberCurrent() - $matches[1] < 1
											? 1 : $this->instance->getPaging()->getPageNumberCurrent() - $matches[1]+1;
						$rangeEnd	= $this->instance->getPaging()->getPageNumberCurrent() + $matches[1] > $this->instance->getPaging()->getPageMax()
											? $this->instance->getPaging()->getPageMax()
											: $this->instance->getPaging()->getPageNumberCurrent() + $matches[1]-1;
						for ($i = $rangeStart; $i <= $rangeEnd; $i++) {
							$value[$i]	= $this->instance->getPageByNumber($i);
						}
					break;
			}
			return $value;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>