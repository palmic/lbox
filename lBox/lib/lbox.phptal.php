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
					throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_COMPONENT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
				}
				return '$ctx->SELF->getComponentById("'.$name.'")->getContent()';
				break;
			case "slot":
				if (strlen($name = $srcArr[1]) < 1) {
					throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_SLOT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
				}
				return '$ctx->SELF->templateGetSlot("'.$name.'")';
				break;
			case 'slot_start':
				return '$ctx->SELF->templateSlotContentBegin()';
				break;
					
			case 'slot_end':
				if (strlen($name = $srcArr[1]) < 1) {
					throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_SLOT_NAME_EMPTY ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_BAD_KEY);
				}
				return '$ctx->SELF->templateSlotContentEnd("'.$name.'")';
				break;
			default:
				throw new LBoxExceptionFront(LBoxExceptionFront::MSG_TPL_LBOX_NS_BAD_CALL ." Called like lbox:$src", LBoxExceptionFront::CODE_TPL_LBOX_NS_BAD_CALL);
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
	 * @return string
	 */
    public function translate($key) {
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

    /**
     * vraci cesty k lang souborum podle jazyka a sablony, ktera instanci obsluhuje
     * @return array
     */
    protected function getLanguageFilePaths() {
    	try {
    		$lang					= LBoxFront::getDisplayLanguage();
    		$out					= array();
    		$out[]					= $this->templatePath .".$lang.xml";
    		$out[]					= LBOX_PATH_FILES_I18N ."/project.$lang.xml";
    		return $out;
    	}
    	catch (Exception $e) {
    		throw $e;
    	}
    }
}

?>