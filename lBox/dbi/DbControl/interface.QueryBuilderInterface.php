<?php

/**
* Interface of class that helps with queries
* @author Michal Palma <palmic at email dot cz>

* @package DbControl
* @version 1.5

* @date 2006-01-11
*/
interface QueryBuilderInterface
{

    //== constructors ====================================================================

    public function QueryBuilder(/*string*/ $platform = "mysql");


    //== public functions ===============================================================

    /**
    * Make insert query from values array indexed by colnames
    * @return string
    */
     public function insert(/*string*/ $table, /*array*/ $values);
}

?>
