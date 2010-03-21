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
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('stdClass');
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameStdclassAndType__type()
    {
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('stdClass');

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record stdClass */
            $this->assertType('stdClass', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $record, '__type present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals('Tag', $record->__type);
            $this->assertEquals(4, count((array)$record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameStdclassAndTypeNull()
    {
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName(null);
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('stdClass');

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record stdClass */
            $this->assertType('stdClass', $record, 'Wrong datatype for record');
            $this->assertObjectNotHasAttribute('__type', $record, '__type present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals(3, count((array)$record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameMy_PopoAndTypeNull()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName(null);
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('My_Popo');
        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record My_Popo */
            $this->assertType('My_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectNotHasAttribute('__type', $record, '__type present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals(3, count((array)$record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameMy_PopoAndTypefoo()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName('foo');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('My_Popo');

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record My_Popo */
            $this->assertType('My_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('foo', $record, 'no foo attribute present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals('Tag', $record->foo);
            $this->assertEquals(4, count((array)$record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorNotAddsEmptyRelations()
    {
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('stdClass');

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
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('My_Popo');

        $this->fillTableWithBlogEntries(3);
        $result = $this->getEntryQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $entry) {
            /* @var $entry My_Popo */
            $this->assertType('My_Popo', $entry, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $entry, 'no __type attribute present');
            $this->assertObjectHasAttribute('id', $entry, 'no id attribute present');
            $this->assertObjectHasAttribute('message', $entry, 'no message attribute present');
            $this->assertObjectHasAttribute('categoryId', $entry, 'no categoryId attribute present');
            $this->assertObjectHasAttribute('Tag', $entry, 'no Tag-attribute present');
            $this->assertObjectHasAttribute('Category', $entry, 'no Category-attribute present');
            $this->assertEquals(6, count((array)$entry), 'Wrong attribute count');

            $this->assertEquals('Blogentry', $entry->__type);

            $tags = $entry->Tag;

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
            $this->assertEquals(5, count($tags), 'Wrong Tag-count');

            $category = $entry->Category;
            $this->assertType('My_Popo', $category, 'Wrong datatype for relation Category');
            $this->assertObjectHasAttribute('__type', $category, 'no __type present');
            $this->assertObjectHasAttribute('id', $category, 'no categoryId present');
            $this->assertObjectHasAttribute('name', $category, 'no name present');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::getDefaultTypename
     */
    public function testSetDefaultClassnameGetDefaultClassname()
    {
        $expectedType = 'My_Popo';
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname($expectedType);
        $actualType = Robo47_Doctrine_Hydrator_PopoDriver::getDefaultClassname();
        $this->assertEquals($expectedType, $actualType);
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypeName
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::getDefaultTypeName
     */
    public function testSetDefaultTypenameGetDefaultTypeName()
    {
        $expectedType = '__myType';
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename($expectedType);
        $actualType = Robo47_Doctrine_Hydrator_PopoDriver::getDefaultTypename();
        $this->assertEquals($expectedType, $actualType);
    }
    
    /**
     *
     * @return array
     */
    public function invalidTypenameProvider()
    {
        $data = array();

        $data[] = array(1);

        return $data;
    }

    /**
     *
     * @param <type> $type
     * @dataProvider invalidTypenameProvider
     */
    public function testSetDefaultTypenameThrowsExceptionWithInvalidType($type)
    {

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
}