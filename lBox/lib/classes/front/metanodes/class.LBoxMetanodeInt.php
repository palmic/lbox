<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
class LBoxMetanodeInt extends LBoxMetanode
{
	protected function getContent() {
		try {
			if (strlen($content = parent::getContent()) > 0) {
				if (!is_numeric($content)) {
					throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_NODECONTENT_NOT_NUMERIC, LBoxExceptionMetanodes::CODE_NODECONTENT_NOT_NUMERIC);
				}
				return (int)$content;
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>