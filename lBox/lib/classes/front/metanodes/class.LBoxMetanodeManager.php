<?
/**
 * @author Michal Palma <palmic at email dot cz>
 * @package LBox
 * @version 1.0
 * @license http://creativecommons.org/licenses/by-sa/3.0/
 * @date 2009-06-06
 */
class LBoxMetanodeManager extends LBox
{
	/**
	 * metanodes types definitions
	 * @var string
	 */
	const NODES_TYPE_INT		= "int";
	const NODES_TYPE_STRING		= "string";
	const NODES_TYPE_RICHTEXT	= "richtext";
	
	/**
	 * metanodes cache
	 * @var array
	 */
	protected static $cache		= array();
	
	/**
	 * 
	 * @param string $type node type
	 * @param int $seq node order on page
	 * @param LBoxComponent $caller
	 * @param string $lng
	 * @return LBoxMetanode
	 */
	public static function getNode($type = "", $seq = 1, LBoxComponent $caller, $lng = "") {
		try {
			if (!is_int($seq) || $seq < 1) {
				throw new LBoxExceptionMetanodes("\$seq: ". LBoxExceptionMetanodes::MSG_PARAM_INT_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			if (strlen($type) < 1) {
				throw new LBoxExceptionMetanodes(LBoxExceptionMetanodes::MSG_PARAM_STRING_NOTNULL, LBoxExceptionMetanodes::CODE_BAD_PARAM);
			}
			if (strlen($lng) < 1) {
				$lng	= LBoxFront::getDisplayLanguage();
			}
			// get node className
			switch (strtolower($type)) {
				case self::NODES_TYPE_INT:
						$nodeClassName	= "LBoxMetanodeInt";
					break;
				case self::NODES_TYPE_STRING:
						$nodeClassName	= "LBoxMetanodeString";
					break;
				case self::NODES_TYPE_RICHTEXT:
						$nodeClassName	= "LBoxMetanodeRichText";
					break;
				default:
					throw new LBoxExceptionMetanodes("$type: ". LBoxExceptionMetanodes::MSG_NODETYPE_UNRECOGNIZED, LBoxExceptionMetanodes::CODE_NODETYPE_UNRECOGNIZED);
			}
			// try cache var
			$callerType	= $caller instanceof LBoxPage ? "pages" : "components";
			if (array_key_exists($callerType, self::$cache)
				&& array_key_exists($caller->config->id, self::$cache[$callerType])
				&& array_key_exists($seq, self::$cache[$callerType][$caller->config->id])) {
				if (self::$cache[$callerType][$caller->config->id][$seq] instanceof LBoxMetanode) {
					// check propability of second call for same metanode of another node type
					if (!self::$cache[$callerType][$caller->config->id][$seq] instanceof $nodeClassName) {
						throw new LBoxExceptionMetanodes($caller->config->id. ": ". LBoxExceptionMetanodes::MSG_NODE_ALREADY_EXISTS_ANOTHER_TYPE, LBoxExceptionMetanodes::CODE_NODE_ALREADY_EXISTS_ANOTHER_TYPE);
					}
					return self::$cache[$callerType][$caller->config->id][$seq];
				}
			}
			return self::$cache[$callerType][$caller->config->id][$seq] = new $nodeClassName($seq, $caller, $lng);
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	protected function __construct() {}
}
?>