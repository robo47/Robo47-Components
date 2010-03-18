<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

require_once TESTS_PATH . 'Robo47/_files/DoctrineTestCase.php';

class My_Popo
{
}

/**
 * @group Robo47_Doctrine
 * @group Robo47_Doctrine_Hydrator
 * @group Robo47_Doctrine_Hydrator_Popo
 */
class Robo47_Doctrine_Hydrator_PopoDriverTest extends Robo47_DoctrineTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpTableForRecord('Blogentry');
        $this->setUpTableForRecord('Category');
        $this->setUpTableForRecord('Tag');
        $this->setUpTableForRecord('Blogentry2Tag');
        Doctrine_Manager::getInstance()->registerHydrator('popo', 'Robo47_Doctrine_Hydrator_PopoDriver');
    }

    /**
     * @return Doctrine_Query
     */
    public function getTagsQuery()
    {
        $tag = new Tag();
        return $tag->getTable()->createQuery()->setHydrationMode('popo');
    }

    /**
     * @return Doctrine_Query
     */
    public function getEntryQuery()
    {
        $entry = new Blogentry();
        return $entry->getTable()
                     ->createQuery('b')
                     ->leftJoin('b.Tag t')
                     ->leftJoin('b.Category c')
                     ->setHydrationMode('popo');
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameStdclassAndType__type()
    {

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record stdClass */
            $this->assertEquals(4, count((array)$record), 'Wrong attribute count');
            $this->assertType('stdClass', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $record, 'no __type present');
            $this->assertEquals('Tag', $record->__type);
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameStdclassAndTypeNull()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::$typeName = null;

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record stdClass */
            $this->assertEquals(3, count((array)$record), 'Wrong attribute count');
            $this->assertType('stdClass', $record, 'Wrong datatype for record');
            $this->assertObjectNotHasAttribute('__type', $record, '__type present');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameMy_PopoAndTypeNull()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::$typeName = null;
        Robo47_Doctrine_Hydrator_PopoDriver::$classname = 'My_Popo';

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record My_Popo */
            $this->assertEquals(3, count((array)$record), 'Wrong attribute count');
            $this->assertType('My_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectNotHasAttribute('__type', $record, '__type present');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameMy_PopoAndTypefoo()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::$typeName = 'foo';
        Robo47_Doctrine_Hydrator_PopoDriver::$classname = 'My_Popo';

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record My_Popo */
            $this->assertEquals(4, count((array)$record), 'Wrong attribute count');
            $this->assertType('My_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('foo', $record, 'no __type present');
            $this->assertEquals('Tag', $record->foo);
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorNotAddsEmptyRelations()
    {
        $blogentry = new Blogentry();
        $blogentry->message = 'Foo';
        $blogentry->save();

        $result = $this->getEntryQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(1, count($result));

        
        $this->assertObjectNotHasAttribute('Tag', $result[0], 'Object has empty Tag');
        $this->assertObjectNotHasAttribute('Category', $result[0], 'Object has empty Category');

    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithRelations()
    {
        Robo47_Doctrine_Hydrator_PopoDriver::$typeName = '__type';
        Robo47_Doctrine_Hydrator_PopoDriver::$classname = 'My_Popo';

        $this->fillTableWithBlogEntries(3);
        $result = $this->getEntryQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $entry) {
            /* @var $entry My_Popo */
            $this->assertEquals(6, count((array)$entry), 'Wrong attribute count');
            $this->assertType('My_Popo', $entry, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $entry, 'no __type present');
            $this->assertObjectHasAttribute('id', $entry, 'no message present');
            $this->assertObjectHasAttribute('message', $entry, 'no message present');
            $this->assertObjectHasAttribute('categoryId', $entry, 'no categoryId present');
            $this->assertObjectHasAttribute('Tag', $entry, 'no Tag-attribute present');
            $this->assertObjectHasAttribute('Category', $entry, 'no Category-attribute present');

            $this->assertEquals('Blogentry', $entry->__type);

            $tags = $entry->Tag;
            $this->assertEquals(5, count($tags), 'Wrong Tag-count');
            //var_dump($tags);
            $i = 0;
            foreach($tags as $key => $tag) {
                $this->assertType('My_Popo', $tag, 'Wrong datatype for relation Category');
                $this->assertObjectHasAttribute('__type', $tag, 'no __type present');
                $this->assertObjectHasAttribute('id', $tag, 'no id present');
                $this->assertObjectHasAttribute('tag', $tag, 'no tag present');
                $this->assertObjectHasAttribute('name', $tag, 'no name present');
                $this->assertEquals('Tag', $tag->__type);
                $this->assertEquals(4, count((array)$tag), 'Wrong attribute count for Tag');

            }

            $category = $entry->Category;
            $this->assertType('My_Popo', $category, 'Wrong datatype for relation Category');
            $this->assertObjectHasAttribute('__type', $category, 'no __type present');
            $this->assertObjectHasAttribute('id', $category, 'no categoryId present');
            $this->assertObjectHasAttribute('name', $category, 'no name present');
        }
    }

    public function fillTableWithBlogEntries($number)
    {
        $tags = $this->fillTableWithTags(5);

        for ($i = 0; $i < $number; $i++) {
            $category = new Category();
            $category->name = 'Foo ' . $i;
            $category->save();

            $entry = new Blogentry();
            $entry->message = 'entry ' . $i;
            $entry->categoryId = $category->id;
            foreach($tags as $tag) {
                $entry->Tag[] = $tag;
            }
            $entry->save();
        }
    }

    public function fillTableWithTags($number)
    {
        $tags = array();
        for ($j = 0; $j < $number; $j++) {
            $tag = new Tag();
            $tag->tag = 'Tag ' . $j;
            $tag->name = 'tag-'. $j;
            $tag->save();
            $tags[] = $tag;
        }
        return $tags;
    }
}