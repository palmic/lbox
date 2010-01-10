<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionImage extends LBoxExceptionFilesystem
{
	const CODE_NO_GALLERY_APPENDED			= 5003;
	const CODE_WRONG_GALLERY_APPENDED		= 5004;
	const CODE_UNSET_UNSUCCESFULL			= 5005;
	const CODE_WRONG_FILENAME_TO_SET		= 5006;
	const CODE_FILE_NOT_CREATED				= 5006;
	const CODE_IMAGE_GALLERY_BAD_TYPE		= 5007;
	const CODE_CHANGE_FILENAME_IMAGE_SAVED	= 5008;
	
	const MSG_NO_FILENAME_DEFINED 			= "No filename was defined yet!";
	const MSG_NO_GALLERY_APPENDED 			= "No gallery appended!";
	const MSG_WRONG_GALLERY_APPENDED		= "Wrong or unexisted gallery appended";
	const MSG_UNSET_UNSUCCESFULL			= "Cannot delete image file!";
	const MSG_WRONG_FILENAME_TO_SET			= "Wrong filename to set!";
	const MSG_FILE_NOT_CREATED				= "File not created!";
	const MSG_IMAGE_GALLERY_BAD_TYPE		= "Image gallery has bad type!";
	const MSG_CHANGE_FILENAME_IMAGE_SAVED	= "Cannot change filename, image is already saved!";
}
?>