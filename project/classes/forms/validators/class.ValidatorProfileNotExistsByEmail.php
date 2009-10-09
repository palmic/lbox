<?php
class ValidatorProfileNotExistsByEmail extends ValidatorRecordNotExists
{
	protected $filterColName	= "email";
	protected $recordClassName 	= "XTUsersRecord";
}
?>