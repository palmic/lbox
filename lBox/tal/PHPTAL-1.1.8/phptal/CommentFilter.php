<?php

require_once PHPTAL_DIR_CORE .'/Filter.php';

class PHPTAL_CommentFilter implements PHPTAL_Filter
{
	public function filter($src){
		return preg_replace('/(<!--.*?-->)/s', '', $src);
	}
}

?>
