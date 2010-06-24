<?php
$dirArr		= explode("/", dirname(__FILE__));$pathRoot	= "";foreach ($dirArr as $dir) {if ($dir == "tests") break;$pathRoot	.= strlen($pathRoot) > 0 ? "/" : "";$pathRoot	.= $dir;}$pathRoot = "/$pathRoot";require_once $pathRoot.'/lBox/lib/loader_phpunit.php';

class ConfigItem extends PHPUnit_Framework_TestCase
{
    public function testCreateItem() {
    	$this->assertType("LBoxConfigItemStructure", $newItem	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-3/"));
    	$newItem	->state	= "new item";
    	LBoxConfigStructure::getInstance()->store();

    	$this->assertType("LBoxConfigItemStructure", $newItem = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3/"));
		$newItem->delete();
    	LBoxConfigStructure::getInstance()->store();
    }

    public function testCreateChild() {
    	$this->assertType("LBoxConfigItemStructure", $parent	= LBoxConfigStructure::getInstance()->getCreateItem("/parent/"));
    	LBoxConfigStructure::getInstance()->store();
    	$parent	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/parent/");
    	
    	$this->assertType("LBoxConfigItemStructure", $child	= LBoxConfigStructure::getInstance()->getCreateChild($parent, "/child/"));
    	LBoxConfigStructure::getInstance()->store();
    	$this->assertType("LBoxConfigItemStructure", $parent	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/parent/"));
    	$this->assertType("LBoxConfigItemStructure", $child	= $parent->getChildNodesIterator()->current());

    	$this->assertTrue($parent->isParentOf($child), "Rodicovsky vztah neplati podle predpokladu");
    	$this->assertTrue(is_numeric(strpos($child->url, $parent->url)), "URL potomka neni odvozena od parenta");
    	$this->assertSame(((string)$parent->id) . "001", $child->id, "ID potomka neni odvozeno od parenta");
		$this->assertTrue($parent->isParentOf($child), "child neni potomkem parenta");

		$childURL	= $child->url;
		$child->removeFromTree();
		LBoxConfigStructure::getInstance()->store();
		$this->assertFalse($parent->isParentOf($child), "child uz nema byt potomkem parenta");
		
    	$this->assertType("LBoxConfigItemStructure", $parent 	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/parent/"));
		$this->assertType("LBoxConfigItemStructure", $child		= LBoxConfigManagerStructure::getInstance()->getPageByUrl($childURL));
		$parent->delete();
		$child->delete();
		LBoxConfigStructure::getInstance()->store();
    }

    /**
     * @depends testCreateItem
     */
    public function testDeleteItem() {
    	$this->assertType("LBoxConfigItemStructure", $newItem	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-3/"));
    	$newItem	->state	= "new item";
    	LBoxConfigStructure::getInstance()->store();

    	$this->assertType("LBoxConfigItemStructure", $item 		= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3/"));
    	
    	$item->delete();
    	LBoxConfigStructure::getInstance()->store();
    	try {
	    	LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3/");
    	}
    	catch (Exception $e) {
    		$this->assertSame(LBoxExceptionConfigStructure::CODE_NODE_BYURL_NOT_FOUND, $e->getCode());
    	}
    }

    public function testAppendChild() {
    	$itemsCreate1	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-2/");
    	LBoxConfigStructure::getInstance()->store();
    	$itemsCreate2	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-201/");
    	LBoxConfigStructure::getInstance()->store();
    			
		$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	
    	$this->assertFalse($item1->hasChildren());
    	$this->assertFalse($item2->hasParent());
    	$this->assertNull($item1->appendChild($item2));
    	LBoxConfigStructure::getInstance()->store();
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	
    	$this->assertTrue($item1->hasChildren());
    	$this->assertTrue($item2->hasParent());

    	$this->assertSame($item2->id, $item1->getChildNodesIterator()->current()->id);

    	// smaze i vnorenou 201
    	$itemsDelete1	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
		$itemsDelete1	->delete();
    	LBoxConfigStructure::getInstance()->store();
    }

    public function testRemoveFromTree() {
    	$itemsCreate1	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-2/");
    	LBoxConfigStructure::getInstance()->store();
    	$itemsCreate2	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-201/");
    	LBoxConfigStructure::getInstance()->store();
		$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	
    	$this->assertNull($item1->appendChild($item2));
    	LBoxConfigStructure::getInstance()->store();
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	$item2 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	
    	$this->assertTrue($item1->hasChildren());
    	$this->assertTrue($item2->hasParent());

    	$this->assertSame($item2->id, $item1->getChildNodesIterator()->current()->id);
    	
    	$this->assertNull($item2->removeFromTree());
    	LBoxConfigStructure::getInstance()->store();
    	
    	$this->assertFalse($item1->hasChildren());
    	$this->assertFalse($item2->hasParent());

    	$itemsDelete1	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
		$itemsDelete1	->delete();
    	LBoxConfigStructure::getInstance()->store();
		$itemsDelete2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	$itemsDelete2	->delete();
    	LBoxConfigStructure::getInstance()->store();
    }

    public function testAttribute() {
    	$itemsCreate1	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-2/");
    	LBoxConfigStructure::getInstance()->store();
    	$itemsCreate2	= LBoxConfigStructure::getInstance()->getCreateItem("/temporary-201/");
    	LBoxConfigStructure::getInstance()->store();
    	
		$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	
    	$this->assertNull($item1->role);
    	$item1->role	= "parent of test-2";
    	LBoxConfigStructure::getInstance()->store();
    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	
    	$this->assertSame($item1->role, "parent of test-2");

       	$item1->role = "";
    	LBoxConfigStructure::getInstance()->store();
		$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
    	
		$this->assertSame($item1->role, "");

    	$item1 = LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
       	$item1->role = NULL;
       	$this->assertNull($item1->role);
    	LBoxConfigStructure::getInstance()->store();
    	
    	$itemsDelete1	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-2/");
		$itemsDelete1	->delete();
    	LBoxConfigStructure::getInstance()->store();
		$itemsDelete2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/temporary-201/");
    	$itemsDelete2	->delete();
    	LBoxConfigStructure::getInstance()->store();
    }

    public function testInsertBefore() {
    	$this->assertType("LBoxConfigItemStructure", $newItem	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-30/"));
    	LBoxConfigStructure::getInstance()->store();
    	$this->assertType("LBoxConfigItemStructure", $newItem2	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-3001/"));
    	LBoxConfigStructure::getInstance()->store();
    	$this->assertType("LBoxConfigItemStructure", $newItem3	= LBoxConfigStructure::getInstance()->getCreateItem("/new-item-3002/"));
    	
    	$newItem	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-30/");
    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3001/");
    	
    	$this->assertNull($newItem->appendChild($newItem2));
    	LBoxConfigStructure::getInstance()->store();

    	$newItem	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-30/");
    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3001/");
    	
    	$this->assertSame($newItem2->id, $newItem->getChildNodesIterator()->current()->id);
    	
    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3001/");
    	$newItem3	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3002/");
    	$this->assertNull($newItem2->insertBefore($newItem3));
    	LBoxConfigStructure::getInstance()->store();
    	
    	$newItem2	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3001/");
    	$newItem3	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-3002/");
    	$this->assertSame($newItem2->id, $newItem3->getSiblingAfter()->id);
    	$this->assertSame($newItem3->id, $newItem2->getSiblingBefore()->id);

    	$newItem	= LBoxConfigManagerStructure::getInstance()->getPageByUrl("/new-item-30/");
    	$newItem	->delete();
    	LBoxConfigStructure::getInstance()->store();
    }

    public function testSetContent() {
    	$this->assertType("LBoxConfigItemProperty", $item = LBoxConfigProperties::getInstance()->getCreateItem("testname", "<project>"));
    	LBoxConfigProperties::getInstance()->store();
    	$item = LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname");
    	$this->assertSame("<project>", $item->getContent());
    	
    	$this->assertType("LBoxConfigItemProperty", $item = LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname"));
    	$item->setContent("project");
    	$item = LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname");
    	$this->assertSame("project", $item->getContent());

    	$this->assertType("LBoxConfigItemProperty", $item3	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname"));
    	LBoxConfigProperties::getInstance()->store();
    	
    	try {
	    	$item	= LBoxConfigManagerProperties::getInstance()->getPropertyByName("testname");
    	}
    	catch (LBoxExceptionProperty $e) {
    		$this->assertSame(LBoxExceptionProperty::CODE_PROPERTY_NOT_FOUND, $e->getCode());
    	}
    	$item->delete();
    	LBoxConfigProperties::getInstance()->store();
    }
}
?>