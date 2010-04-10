<?php
/**
 * telefonni cislo se statni predvolbou
 */
class LBoxFormValidatorPhoneWithPreselection extends LBoxFormValidatorPhone
{
	/**
	 * regularni vyraz kontrolujici validitu telefonniho cisla
	 * bere tyto formaty:
	 * - +420777666333
	 * @var string
	 */
	protected $regPhone	= '^(\+)?([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{6})$';
}
?>