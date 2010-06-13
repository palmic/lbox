<?php
require_once '../lBox/lib/loader_phpunit.php';

class StackTest extends PHPUnit_Framework_TestCase
{
    public function testPushAndPop()
    {
        $this->assertEquals(1, LBoxConfigManagerStructure::getInstance()->getPageById(1)->id);
    }
}
?>