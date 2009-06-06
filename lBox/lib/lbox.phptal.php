<?php

/**
 * pridava TALu namespace lbox
 * @param string $src
 * @param bool $nothrow
 * @return string
 */
function phptal_tales_lbox($src, $nothrow = false) {
	try {
		$srcArr = explode(".", $src);
		switch (strtolower($srcArr[0])) {
			case "component":
				if (strlen($name = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_COMPONENT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				return '$ctx->SELF->getComponentById("'.$name.'")->getContent()';
				break;
			case "page":
				$pageParams	= explode("/", $srcArr[1]);
				if (!is_numeric($pageID = $pageParams[0]) || $pageID < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_PAGE_ID_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				unset($pageParams[0]);
				if (count($pageParams) < 1 || strlen(current($pageParams)) < 1) {
					return '$ctx->SELF->getPageById("'.$pageID.'")';
				}
				else {
					return '$ctx->SELF->getPageById("'.$pageID.'")->'. current($pageParams);
				}
				break;
			case "property":
				if (strlen($name = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_PROPERTY_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				return 'LBoxConfigManagerProperties::getPropertyContentByName("'.$name.'")';
				break;
			case "acces":
				return 'AccesRecord::getInstance()';
				break;
			case "front":
				if (strlen($frontCalling = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_FRONT_CALL_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				return 'LBoxFront::'. $frontCalling .'()';
				break;
			case "i18n":
				if (strlen($srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_I18N_CONDITION_INVALID ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				if (count($langConditionArr = explode("/", $srcArr[1])) != 2) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_I18N_CONDITION_INVALID ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				switch ($langConditionArr[0]) {
					case 'isDisplayLanguageCurrent':
						return 'LBoxFront::isDisplayLanguageCurrent("'. $langConditionArr[1] .'")';
						break;
					default:
						if (!$nothrow) {
							throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_I18N_CONDITION_INVALID ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
						}
				}
				break;
			case "request":
				if (strlen($name = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_REQUEST_PARAM_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				switch ($name) {
					case "url":
						return "LBOX_REQUEST_URL";
						break;
					case "url_virtual":
						return "LBOX_REQUEST_URL_VIRTUAL";
						break;
					case "url_params":
						return "LBOX_REQUEST_URL_PARAMS";
						break;
					case "url_query":
						return "LBOX_REQUEST_URL_QUERY";
						break;
					case "url_path":
						return "LBOX_REQUEST_URL_PATH";
						break;
					case "url_scheme":
						return "LBOX_REQUEST_URL_SCHEME";
						break;
					case "url_host":
						return "LBOX_REQUEST_URL_HOST";
						break;
					case "ip":
						return "LBOX_REQUEST_IP";
						break;
					case "ip_my":
						return "LBOX_REQUEST_IP_MY";
						break;
					case "agent":
						return "LBOX_REQUEST_AGENT";
						break;
					case "referer":
						return "LBOX_REQUEST_REFERER";
						break;
					case "request_time":
						return "LBOX_REQUEST_REQUEST_TIME";
						break;
				}
				break;
			case "slot":
				if (strlen($name = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_SLOT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				$silent	= (array_key_exists(2, $srcArr) && $srcArr[2] == "silent") ? "true" : "false";
				return '$ctx->SELF->templateGetSlot("'.$name.'", '.$silent.')';
				break;
			case 'slot_start':
				return '$ctx->SELF->templateSlotContentBegin()';
				break;
					
			case 'slot_end':
				if (strlen($name = $srcArr[1]) < 1) {
					if (!$nothrow) {
						throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_SLOT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
					}
				}
				return '$ctx->SELF->templateSlotContentEnd("'.$name.'")';
				break;
			default:
				if (!$nothrow) {
					throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_LBOX_NS_BAD_CALL ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_LBOX_NS_BAD_CALL);
				}
		}
		return "";
	}
	catch (Exception $e) {
		throw $e;
	}
}

/**
 * LBox translation service
 */
class LBoxTranslator extends PHPTAL_GetTextTranslator
{
	/**
	 * path sablony, ktera ma translator vyuzivat
	 * @var string
	 */
	protected $templatePath	= "";
	
    public function __construct($templatePath = "") {
    	if (strlen($templatePath) < 1) {
    		throw new LBoxExceptionComponent("\$templatePath: ". LBoxExceptionComponent::MSG_PARAM_STRING_NOTNULL, LBoxExceptionComponent::CODE_BAD_PARAM);
    	}
    	$this->templatePath	= $templatePath;
    }
	
	/**
	 * zjisti, zda je dostupna hodnota key a pripadne ji vraci
	 * @param string $key
	 * @param bool $htmlescape
	 * @return string
	 */
    public function translate($key, $htmlescape = true) {
    	try {
    		if (strlen($key) < 1) {
    			throw new LBoxExceptionConfig(LBoxExceptionConfig::MSG_PARAM_STRING_NOTNULL, LBoxExceptionConfig::CODE_BAD_PARAM);
    		}
    		$out	= "";
    		foreach ($this->getLanguageFilePaths() as $lngIndex => $path) {
    			if (!file_exists($path)) continue;
    			try {
   		 			return LBoxI18NDataManager::getInstance($path)->getTextById($key)->getContent();
       			}
    			catch (LBoxException $e) {
    				switch ($e->getCode()) {
    					case LBoxExceptionConfigComponent::CODE_NODE_BYID_NOT_FOUND:
    							// v pripade, ze jsme nenalezli lang text v konkretni definici, hledame jeste v globalni
    							if ($lngIndex < 1) {
    								continue;
    							}
    							throw new LBoxExceptionI18N("$path::$key: ". LBoxExceptionI18N::MSG_LNG_ITEM_NOTEXISTS, LBoxExceptionI18N::CODE_LNG_ITEM_NOTEXISTS);
    						break;
    					default:
    						throw $e;
    				}
    			}
    		}
    		throw new LBoxExceptionI18N(LBoxFront::getDisplayLanguage() .": ". LBoxExceptionI18N::MSG_LNG_NOTEXISTS, LBoxExceptionI18N::CODE_LNG_NOTEXISTS);
   	   	}
    	catch (Exception $e) {
    		throw $e;
    	}
    }

    public function setEncoding($encoding) {}

    /**
     * vraci cesty k lang souborum podle jazyka a sablony, ktera instanci obsluhuje
     * @return array
     */
    protected function getLanguageFilePaths() {
    	try {
    		$lang					= LBoxFront::getDisplayLanguage();
    		$out					= array();
    		$out[]					= LBoxUtil::fixPathSlashes($this->templatePath .".$lang.xml");
    		$out[]					= LBoxUtil::fixPathSlashes(LBOX_PATH_FILES_I18N ."/project.$lang.xml");
    		return $out;
    	}
    	catch (Exception $e) {
    		throw $e;
    	}
    }
}

?>