<?
/**
* @author Michal Palma <palmic@email.cz>
* @package LBox
* @version 1.0
* @license http://creativecommons.org/licenses/by-sa/3.0/
* @since 2007-12-08
*/
class Admin extends LBoxPage
{
	protected function executePrepend(PHPTAL $TAL) {
	}

	/**
	 * vraci pole dni v mesici
	 * @return array
	 */
	protected function getDaysArray() {
		try {
			$start	= 1;
			$end	= 31;
			$out	= array();
			for ($i = $start; $i <= $end; $i++) {
				$out[$i] = $i;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci pole mesicu v roce
	 * @return array
	 */
	protected function getMonthsArray() {
		try {
			$start	= 1;
			$end	= 12;
			$out	= array();
			for ($i = $start; $i <= $end; $i++) {
				$out[$i] = $i;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * vraci pole relevantnich roku
	 * @return array
	 */
	protected function getYearsArray() {
		try {
			$start	= date("Y");
			$end	= date("Y", time() + 365 * 24 * 3600);
			$out	= array();
			for ($i = $start; $i <= $end; $i++) {
				$out[$i] = $i;
			}
			return $out;
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>