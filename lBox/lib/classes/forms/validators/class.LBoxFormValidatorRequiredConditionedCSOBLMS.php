<?php
/**
 * validator required pro controls, ktere jsou required jen v pripade, ze je zvolena konkretni hodnota jineho controlu
 *  - podle logiky forms vytvorene pro CSOB LMS forms
 */
class LBoxFormValidatorRequiredConditionedCSOBLMS extends LBoxFormValidatorRequired
{
	public function validate(LBoxFormControl $control = NULL) {
		try {
			if (strlen($control->title) < 1) {
				return;
			}
			// parse conditions
			$y = 0;
			$conditionsPacks	= explode(";", $control->title);
//LBoxFirePHP::table($conditionsPacks, "\$conditionsPacks");
			$conditions			= array();
			foreach ($conditionsPacks as $conditionsPack) {
				$conditionsParts	= explode(":", $conditionsPack);
				$flag				= 0;
				$k					= "";
				foreach ($conditionsParts as $conditionsPart) {
					if (!$flag) {
						$k = $conditionsPart;
					}
					else {
						$condsParts	= explode("=", $conditionsPart);
						$conds		= array();
						$flag2		= 0;
						$k2			= "";
						foreach ($condsParts as $condsPart) {
							if (!$flag2) {
								$k2 = $condsPart;
							}
							else {
								$conds[$k2]	= explode("|", $condsPart);
							}
							$flag2 = ($flag2==1) ? 0 : 1;
						}
						$conditions[$y][$k]	= $conds;
					}
					$flag = ($flag==1) ? 0 : 1;
				}
				$y++;
			}
//LBoxFirePHP::table($conditions, "\$conditions");
			// check the data
			foreach ($conditions as $conditionsPack) {
				foreach ($conditionsPack as $action => $condition) {
					switch ($action) {
						case "enable":
								foreach ($condition as $masterControlName => $valueOptions) {
									$masterControl	= $control->getForm()->getControlByName($masterControlName);
//LBoxFirePHP::table($valueOptions, $control->getName() ." \$valueOptions");
									foreach ($valueOptions as $valueOption) {
//if($control->getName() == "insurance_amount_reality"){var_dump($masterControl->getValue());die;}
										if ($masterControl instanceof LBoxFormControlChooseMore || $masterControl instanceof LBoxFormControlChooseMoreFromRecords) {
											if (is_numeric(array_search($valueOption, $masterControl->getValue()))) {
											// master control obsahuje jednu z podminovanych hodnot - tento control tedy je povinny
//LBoxFirePHP::warn($control->getName() ." je povinna diky ". $masterControl->getName());
											parent::validate($control);
											}
										}
										if ($masterControl->getValue() == $valueOption) {
											// master control obsahuje jednu z podminovanych hodnot - tento control tedy je povinny
//LBoxFirePHP::warn($control->getName() ." je povinna diky ". $masterControl->getName() ." = ". $masterControl->getValue());
											parent::validate($control);
										}
									}
								}
							break;
					}
				}
			}
		}
		catch (Exception $e) {
			throw $e;
		}
	}
}
?>