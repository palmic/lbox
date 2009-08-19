<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @since 2009-08-18
*/
class OutputFilterPagingPage extends LBoxOutputFilter
{
	public function prepare($name = "", $value = NULL) {
		try {
			// staticke gettery
			switch ($name) {
				case "is_current":
						$value	= $this->instance->getNumber() == $this->instance->getPaging()->getPageNumberCurrent();
					break;
				case "is_previous":
						$value	= $this->instance->getNumber() < $this->instance->getPaging()->getPageNumberCurrent();
					break;
				case "is_next":
						$value	= $this->instance->getNumber() > $this->instance->getPaging()->getPageNumberCurrent();
					break;
				case "next":
						$value	= $this->instance->getNumber() < $this->instance->getPaging()->getPageMax()
									? $this->instance->getPaging()->getPages()->getPageByNumber($this->instance->getNumber()+1)
									: NULL;
					break;
				case "previous":
						$value	= $this->instance->getNumber() > 1
									? $this->instance->getPaging()->getPages()->getPageByNumber($this->instance->getNumber()-1)
									: NULL;
					break;
			}
			// dynamicke gettery
			switch (true) {
				case preg_match("/is_in_range_(\d+)/", $name, $matches):
						$value =  ((	$this->instance->getPaging()->getPageNumberCurrent() - $matches[1]) <= $this->instance->getNumber()
								&&	($this->instance->getPaging()->getPageNumberCurrent() + $matches[1]) >= $this->instance->getNumber());
					break;
				case preg_match("/is_before_(\d+)/", $name, $matches):
						$value =	$this->instance->getNumber() < $matches[1];
					break;
				case preg_match("/is_after_(\d+)/", $name, $matches):
						$value =	$this->instance->getNumber() > $matches[1];
					break;
				case preg_match("/is_previous_in_range_(\d+)/", $name, $matches):
						$value = $this->prepare("is_previous", NULL)
									&& $this->prepare("is_in_range_". $matches[1], NULL);
					break;
				case preg_match("/is_next_in_range_(\d+)/", $name, $matches):
						$value = $this->prepare("is_next", NULL)
									&& $this->prepare("is_in_range_". $matches[1], NULL);
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