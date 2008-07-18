<?php

require_once 'Source.php';

/** 
 * @package phptal
 */
interface PHPTAL_SourceResolver 
{
    /**
     * Returns PHPTAL_Source or null.
     */
    public function resolve($path);
}

?>
