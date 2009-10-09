<?php
class ValidatorProfileNotExistsByNick extends ValidatorRecordNotExists
{
	protected $filterColName	= "nick";
	protected $recordClassName 	= "XTUsersRecord";
}
?>