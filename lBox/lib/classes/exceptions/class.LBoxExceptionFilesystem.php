<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0

* @date 2007-12-08
*/
class LBoxExceptionFilesystem extends LBoxException
{
	const CODE_DIRECTORY_NOT_EXISTS			= 6001;
	const CODE_FILE_NOT_EXISTS				= 6002;
	const CODE_FILE_UPLOAD_ERROR			= 6003;
	const CODE_RESTRICTED_FILENAME			= 6004;
	const CODE_NO_EXT_DEFINED 				= 6005;
	const CODE_NO_FILENAME_DEFINED 			= 6006;
	const CODE_DIRECTORY_CANNOT_DELETE		= 6007;
	const CODE_FILE_CANNOT_DELETE			= 6008;
	const CODE_FILE_IS_DIR					= 6009;
	const CODE_DIR_IS_FILE					= 6010;
	const CODE_FILE_ALREADY_EXISTS			= 6011;
	const CODE_FILE_CANNOT_OPEN				= 6012;
	const CODE_FILE_CANNOT_WRITE			= 6013;
	const CODE_DIRECTORY_CANNOT_CREATE		= 6014;
	const CODE_CANNOT_CHANGE_PERMISSIONS	= 6015;
	const CODE_DIRECTORY_CONTAINS_SUBDIRS	= 6016;
	const CODE_FILE_CANNOT_CLOSE			= 6017;
	const CODE_FILE_CANNOT_TRUNCATE			= 6018;
	const CODE_FILE_CANNOT_REWIND			= 6019;
	const CODE_FILE_CANNOT_SEEK				= 6020;
	
	const MSG_DIRECTORY_NOT_EXISTS 			= "directory does not exists!";
	const MSG_FILE_NOT_EXISTS 				= "file does not exists!";
	const MSG_FILE_UPLOAD_ERROR				= "file upload error!";
	const MSG_RESTRICTED_FILENAME			= "Restricted filename!";
	const MSG_NO_EXT_DEFINED 				= "No extension was defined yet!";
	const MSG_NO_FILENAME_DEFINED 			= "No filename was defined yet!";
	const MSG_DIRECTORY_CANNOT_DELETE		= "Directory cannot be deleted!";
	const MSG_FILE_CANNOT_DELETE			= "File cannot be deleted!";
	const MSG_FILE_IS_DIR					= "This is not file, but directory!";
	const MSG_DIR_IS_FILE					= "This is not dir, but file!";
	const MSG_FILE_ALREADY_EXISTS			= "File already exists!";
	const MSG_FILE_CANNOT_OPEN				= "Cannot open the file!";
	const MSG_FILE_CANNOT_WRITE				= "Cannot write into the file!";
	const MSG_DIRECTORY_CANNOT_CREATE		= "Cannot create directory!";
	const MSG_CANNOT_CHANGE_PERMISSIONS		= "Cannot change permissions of file or directory!";
	const MSG_DIRECTORY_CONTAINS_SUBDIRS	= "Directory contains subdirectories";
	const MSG_FILE_CANNOT_CLOSE				= "Cannot close the file!";
	const MSG_FILE_CANNOT_TRUNCATE			= "Cannot truncate the file!";
	const MSG_FILE_CANNOT_REWIND			= "Cannot rewind the file!";
	const MSG_FILE_CANNOT_SEEK				= "Cannot seek the file!";
}
?>