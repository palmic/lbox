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

?>