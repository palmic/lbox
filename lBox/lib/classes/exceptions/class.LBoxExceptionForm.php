<?php
/**
* @author Michal Palma <palmic at email dot cz>
* @package LBox
* @version 1.0
* @date 2008-07-17
*/
class LBoxExceptionForm extends LBoxException
{
	const CODE_FORM_DATA_INVALID			= 15101;
	const CODE_FORM_CONTROL_DOESNOT_EXISTS	= 15102;
	const CODE_FORM_DUPLICATE_FORMNAME		= 15103;
	const CODE_FORM_PROCESSOR_DOESNOT_EXISTS= 15104;
	
	const MSG_FORM_DATA_INVALID				= "Form data invalid";
	const MSG_FORM_CONTROL_DOESNOT_EXISTS	= "Form data invalid";
	const MSG_FORM_DUPLICATE_FORMNAME		= "This form name is already used";
	const MSG_FORM_PROCESSOR_DOESNOT_EXISTS	= "No processor defined";
	
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