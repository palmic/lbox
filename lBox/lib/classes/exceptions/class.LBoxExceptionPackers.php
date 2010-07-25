<?php
/**
 * @author Michal Palma <michal.palma@gmail.com>
 * @package LBox
 * @version 1.0

 * @date 2008-05-03
 */
class LBoxExceptionPackers extends LBoxExceptionFilesystem
{
	const CODE_PACKER_ERR_UNRECOGNIZED				= 14001;
	const CODE_PACKER_ERR_NOT_FOUND					= 14002;
	const CODE_PACKER_ERR_INCONNSISTENT				= 14003;
	const CODE_PACKER_ERR_INVALIDARGUMENT			= 14004;
	const CODE_PACKER_ERR_MEMORYALLOC				= 14005;
	const CODE_PACKER_ERR_FILENOTFOUND				= 14006;
	const CODE_PACKER_ERR_OTHERARCHIVETYPE			= 14007;
	const CODE_PACKER_ERR_CANNOTOPEN				= 14008;
	const CODE_PACKER_ERR_CANNOTREAD				= 14009;
	const CODE_PACKER_ERR_CANNOTSEEK				= 14010;
	const CODE_PACKER_ERR_FILEALREADYINARCHIVE		= 14011;
	const CODE_PACKER_ERR_CANNOT_ADD_FILE			= 14012;
	const CODE_PACKER_ERR_CANNOT_CLOSE_ARCHIVE		= 14013;
	
	const MSG_PACKER_ERR_UNRECOGNIZED 				= "Archive extension error code was not recognized!";
	const MSG_PACKER_ERR_NOT_FOUND		 			= "Archive was not found by extension!";
	const MSG_PACKER_ERR_INCONNSISTENT				= "Archive is inconsistent!";
	const MSG_PACKER_ERR_INVALIDARGUMENT			= "Ivalid argument given to extension function!";
	const MSG_PACKER_ERR_MEMORYALLOC				= "Memory allocation error!";
	const MSG_PACKER_ERR_FILENOTFOUND				= "File was not found by extension!";
	const MSG_PACKER_ERR_OTHERARCHIVETYPE			= "Other type of archive!";
	const MSG_PACKER_ERR_CANNOTOPEN					= "Cannot open archive!";
	const MSG_PACKER_ERR_CANNOTREAD					= "Cannot read archive!";
	const MSG_PACKER_ERR_CANNOTSEEK					= "Cannot seek in archive!";
	const MSG_PACKER_ERR_FILEALREADYINARCHIVE		= "File already exists in archive!";
	const MSG_PACKER_ERR_CANNOT_ADD_FILE			= "Cannot add file into archive!";
	const MSG_PACKER_ERR_CANNOT_CLOSE_ARCHIVE		= "Cannot close archive!";
}
?>