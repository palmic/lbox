<?php
/**
* @author Michal Palma <michal.palma@gmail.com>
* @package LBox
* @version 1.0
* @date 2008-07-17
*/
class LBoxExceptionForm extends LBoxException
{
	const CODE_FORM_DATA_INVALID						= 15101;
	const CODE_FORM_CONTROL_DOESNOT_EXISTS				= 15102;
	const CODE_FORM_DUPLICATE_FORMNAME					= 15103;
	const CODE_FORM_PROCESSOR_DOESNOT_EXISTS			= 15104;
	const CODE_FORM_FORM_SUB_NOT_SET					= 15105;
	const CODE_FORM_FORM_SUB_ALREADY_SET_BY_NAME		= 15106;
	const CODE_FORM_FORM_STEP_DOES_NOT_EXISTS			= 15107;
	const CODE_FORM_CONTROL_DOES_EXISTS					= 15108;
	const CODE_FORM_PROCESSOR_DOES_EXISTS				= 15109;
	const CODE_FORM_DATA_UNEXPECTED_FORM_NAME			= 15110;
	const CODE_FORM_DATA_UNEXPECTED_CONTROL_NAME		= 15111;
	
	const MSG_FORM_DATA_INVALID							= "Form data invalid";
	const MSG_FORM_CONTROL_DOESNOT_EXISTS				= "Form control does not exists";
	const MSG_FORM_DUPLICATE_FORMNAME					= "This form name is already used";
	const MSG_FORM_PROCESSOR_DOESNOT_EXISTS				= "No processor defined";
	const MSG_FORM_FORM_SUB_NOT_SET						= "You are trying to use multiform with no sub-form set at current step";
	const MSG_FORM_FORM_SUB_ALREADY_SET_BY_NAME			= "Subform with this name is already set in this multiple form";
	const MSG_FORM_FORM_STEP_DOES_NOT_EXISTS			= "This step does not exists in form multistep";
	const MSG_FORM_CONTROL_DOES_EXISTS					= "This form control does already exists";
	const MSG_FORM_PROCESSOR_DOES_EXISTS				= "This processor is already bounded to this form";
	const MSG_FORM_DATA_UNEXPECTED_FORM_NAME			= "Unexpected form name!";
	const MSG_FORM_DATA_UNEXPECTED_CONTROL_NAME			= "Unexpected form control name!";
	
	protected $formArray				= array();

	/**
	 * form array setter
	 * @param array $formArray
	 * @throws LBoxException
	 */
	public function setFormArray ($formArray	= array()) {
		try {
			if (!is_array($formArray)) {
				throw new LBoxException(LBoxException::MSG_PARAM_STRING_NOTNULL, LBoxException::CODE_BAD_PARAM);
			}
			$this->formArray	= $formArray;
		}
		catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * form array getter
	 * @return array
	 */
	public function getFormArray () {
		return $this->formArray;
	}
}
?>