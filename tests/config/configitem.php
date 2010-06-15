<?php
$dirArr		= explode("/", dirname(__FILE__));
$pathRoot	= "";
foreach ($dirArr as $dir) {
	if ($dir == "tests") break;
	$pathRoot	.= strlen($pathRoot) > 0 ? "/" : "";
	$pathRoot	.= $dir;
}
$pathRoot = "/$pathRoot";
require_once $pathRoot.'/lBox/lib/loader_phpunit.php';

class ConfigItem extends PHPUnit_Framework_TestCase
{
    /*public function testAppendChild() {
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageById(2);
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageById(201);
    	
    	$this->assertFalse($item1->hasChildren());
    	$this->assertFalse($item2->hasParent());
    	$this->assertNull($item1->appendChild($item2));
    	$this->assertTrue($item1->hasChildren());
    	$this->assertTrue($item2->hasParent());

    	$this->assertSame($item2->id, $item1->getChildNodesIterator()->current()->id);
    }

    public function testRemoveFromTree() {
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageById(2);
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageById(201);

    	$this->assertTrue($item1->hasChildren());
    	$this->assertTrue($item2->hasParent());

    	$this->assertSame($item2->id, $item1->getChildNodesIterator()->current()->id);
    	
    	$this->assertNull($item2->removeFromTree());

    	$this->assertFalse($item1->hasChildren());
    	$this->assertFalse($item2->hasParent());
    }

    public function testAttribute() {
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageById(2);
    	
    	$this->assertNull($item1->role);
    	$item1->role	= "parent of test-2";
    	$item1->store();
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageById(2);
    	
    	$this->assertSame($item1->role, "parent of test-2");

       	$item1->role = "";
    	$item1->store();
       	$this->assertSame($item1->role, "");

    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageById(2);
       	$item1->role = NULL;
       	$this->assertNull($item1->role);
    	$item1->store();
    }

    public function testCreateItem() {
    	$this->assertType("LBoxConfigItemStructure", $newItem	= LBoxConfigStructure::getInstance()->getCreateItem(3, "/new-item-3/"));
    	$newItem	->state	= "new item";
    	$newItem	->store();
    	$this->assertType("LBoxConfigItemStructure", $newItem = LBoxConfigManagerStructure::getInstance()->getPageById(3));
    }

    public function testDeleteItem() {
    	$this->assertType("LBoxConfigItemStructure", $item 		= LBoxConfigManagerStructure::getInstance()->getPageById(3));
    	
    	$item->delete();
    	try {
	    	LBoxConfigManagerStructure::getInstance()->getPageById(3);
    	}
    	catch (Exception $e) {
    		$this->assertSame(LBoxExceptionConfig::CODE_NODE_BYID_NOT_FOUND, $e->getCode());
    	}
    }

    public function testInsertBefore() {
    	$this->assertType("LBoxConfigItemStructure", $newItem	= LBoxConfigStructure::getInstance()->getCreateItem(30, "/new-item-30/"));
    	$this->assertType("LBoxConfigItemStructure", $newItem2	= LBoxConfigStructure::getInstance()->getCreateItem(3001, "/new-item-3001/"));
    	$this->assertType("LBoxConfigItemStructure", $newItem3	= LBoxConfigStructure::getInstance()->getCreateItem(3002, "/new-item-3002/"));

    	$this->assertNull($newItem->appendChild($newItem2));
    	$this->assertSame($newItem2->id, $newItem->getChildNodesIterator()->current()->id);
    	
    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageById(3001);
    	$newItem3	= LBoxConfigManagerStructure::getInstance()->getPageById(3002);
    	$this->assertNull($newItem2->insertBefore($newItem3));

    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageById(3001);
    	$newItem3	= LBoxConfigManagerStructure::getInstance()->getPageById(3002);
    	$this->assertSame($newItem2->id, $newItem3->getSiblingAfter()->id);
    	$this->assertSame($newItem3->id, $newItem2->getSiblingBefore()->id);

    	$newItem	= LBoxConfigManagerStructure::getInstance()->getPageById(30);
    	$newItem	->delete();
    }*/

    public function testSetContent() {
    	$this->assertType("LBoxConfigItemProperty", $item = LBoxConfigProperties::getInstance()->getCreateItem("testname", "<project>"));
    	$item->store();
    	$this->assertSame("<project>", $item->getContent());
    	
    	$this->assertType("LBoxConfigItemProperty", $item = LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname"));
    	$item->setContent("project");
    	$this->assertSame("project", $item->getContent());

    	$this->assertType("LBoxConfigItemProperty", $item	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname"));
    	$item->delete();
  }
}
?>