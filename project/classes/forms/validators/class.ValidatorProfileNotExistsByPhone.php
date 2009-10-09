<?php
class ValidatorProfileNotExistsByPhone extends ValidatorRecordNotExists
{
	protected $filterColName	= "phone";
	protected $recordClassName 	= "XTUsersRecord";
}
?>