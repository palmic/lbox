<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-07-17
*/
class LBoxExceptionForm extends LBoxException
{
	const CODE_FORM_CONTROL_DOESNOT_EXISTS		= 15001;
	const CODE_FORM_CONTROL_DOES_EXISTS			= 15002;
	const CODE_FORM_PROCESSOR_DOESNOT_EXISTS	= 15003;
	const CODE_FORM_PROCESSOR_DOES_EXISTS		= 15004;
	const CODE_FORM_DUPLICATE_FORMNAME			= 15005;
	
	const MSG_FORM_CONTROL_DOESNOT_EXISTS		= "form control does not exists yet";
	const MSG_FORM_CONTROL_DOES_EXISTS			= "this form control does already exists in form";
	const MSG_FORM_PROCESSOR_DOESNOT_EXISTS		= "There are no form processor set";
	const MSG_FORM_PROCESSOR_DOES_EXISTS		= "this form processor does already exists in form";
	const MSG_FORM_DUPLICATE_FORMNAME			= "cannot create new form with already used name";
}
?>