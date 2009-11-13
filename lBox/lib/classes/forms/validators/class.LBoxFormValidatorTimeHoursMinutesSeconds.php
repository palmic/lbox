<?php
/**
 * zkontroluje, jestli vyplnena hodnota je cas podle 00:00:00
 */
class LBoxFormValidatorTimeHoursMinutesSeconds extends LBoxFormValidatorTimeHoursMinutes
{
	protected $regDateISO8601	= '^([[:digit:]]{2}):([[:digit:]]{2}):([[:digit:]]{2})$';
}
?>