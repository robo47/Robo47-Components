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

    public function tearDown()
    {
        parent::tearDown();
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname('array');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('Robo47_Popo');
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameRobo47_PopoAndTypeNull()
    {
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename(null);
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('Robo47_Popo');
        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record Robo47_Popo */
            $this->assertType('Robo47_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectNotHasAttribute('__type', $record, '__type present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals(3, count($record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameRobo47_PopoAndTypefoo()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename('foo');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('Robo47_Popo');

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record Robo47_Popo */
            $this->assertType('Robo47_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('foo', $record, 'no foo attribute present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals('Tag', $record->foo);
            $this->assertEquals(4, count($record), 'Wrong attribute count');
        }
    }
    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithClassnameRobo47_PopoAndType__typeAndContainerArrayObject()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname('ArrayObject');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('Robo47_Popo');

        $this->fillTableWithTags(3);
        $result = $this->getTagsQuery()
                       ->execute();
        $this->assertType('ArrayObject', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $record) {
            /* @var $record Robo47_Popo */
            $this->assertType('Robo47_Popo', $record, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $record, 'no foo attribute present');
            $this->assertObjectHasAttribute('id', $record, 'no id present');
            $this->assertObjectHasAttribute('name', $record, 'no name present');
            $this->assertObjectHasAttribute('tag', $record, 'no tag present');
            $this->assertEquals('Tag', $record->__type);
            $this->assertEquals(4, count($record), 'Wrong attribute count');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver
     */
    public function testPopoHydratorWithRelations()
    {

        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename('__type');
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname('Robo47_Popo');

        $this->fillTableWithBlogEntries(3);
        $result = $this->getEntryQuery()
                       ->execute();
        $this->assertType('array', $result, 'Wrong datatype for result');
        $this->assertEquals(3, count($result));

        foreach($result as $entry) {
            /* @var $entry Robo47_Popo */
            $this->assertType('Robo47_Popo', $entry, 'Wrong datatype for record');
            $this->assertObjectHasAttribute('__type', $entry, 'no __type attribute present');
            $this->assertObjectHasAttribute('id', $entry, 'no id attribute present');
            $this->assertObjectHasAttribute('message', $entry, 'no message attribute present');
            $this->assertObjectHasAttribute('categoryId', $entry, 'no categoryId attribute present');
            $this->assertObjectHasAttribute('Tag', $entry, 'no Tag-attribute present');
            $this->assertObjectHasAttribute('Category', $entry, 'no Category-attribute present');
            $this->assertEquals(6, count($entry), 'Wrong attribute count');

            $this->assertEquals('Blogentry', $entry->__type);

            $tags = $entry->Tag;

            $i = 0;
            foreach($tags as $key => $tag) {
                $this->assertType('Robo47_Popo', $tag, 'Wrong datatype for relation Category');
                $this->assertObjectHasAttribute('__type', $tag, 'no __type present');
                $this->assertObjectHasAttribute('id', $tag, 'no id present');
                $this->assertObjectHasAttribute('tag', $tag, 'no tag present');
                $this->assertObjectHasAttribute('name', $tag, 'no name present');
                $this->assertEquals('Tag', $tag->__type);
                $this->assertEquals(4, count($tag), 'Wrong attribute count for Tag');
            }
            $this->assertEquals(5, count($tags), 'Wrong Tag-count');

            $category = $entry->Category;
            $this->assertType('Robo47_Popo', $category, 'Wrong datatype for relation Category');
            $this->assertObjectHasAttribute('__type', $category, 'no __type present');
            $this->assertObjectHasAttribute('id', $category, 'no categoryId present');
            $this->assertObjectHasAttribute('name', $category, 'no name present');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::getDefaultClassname
     */
    public function testSetDefaultClassnameGetDefaultClassname()
    {
        $expectedType = 'Robo47_Popo';
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname($expectedType);
        $actualType = Robo47_Doctrine_Hydrator_PopoDriver::getDefaultClassname();
        $this->assertEquals($expectedType, $actualType);
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::getDefaultContainerClassname
     */
    public function testSetDefaultContainerClassnameGetDefaultContainerClassname()
    {
        $expectedType = 'ArrayObject';
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname($expectedType);
        $actualType = Robo47_Doctrine_Hydrator_PopoDriver::getDefaultContainerClassname();
        $this->assertEquals($expectedType, $actualType);
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::getDefaultTypename
     */
    public function testSetDefaultTypenameGetDefaultTypename()
    {
        $expectedType = '__myType';
        Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename($expectedType);
        $actualType = Robo47_Doctrine_Hydrator_PopoDriver::getDefaultTypename();
        $this->assertEquals($expectedType, $actualType);
    }

    /**
     * @dataProvider invalidTypenameProvider
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename
     * @covers Robo47_Doctrine_Hydrator_Exception
     */
    public function testSetDefaultTypenameThrowsExceptionWithInvalidType($input)
    {
        try {
            Robo47_Doctrine_Hydrator_PopoDriver::setDefaultTypename($input);
            $this->fail('No Exception thrown');
        } catch (Robo47_Doctrine_Hydrator_Exception $e) {
            $this->assertEquals('Invalid type for $typename: ' . Robo47_Core::getType($input), $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @dataProvider invalidTypenameProvider
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname
     * @covers Robo47_Doctrine_Hydrator_Exception
     */
    public function testSetDefaultContainerClassnameThrowsExceptionWithInvalidType($input)
    {
        try {
            Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname($input);
            $this->fail('No Exception thrown');
        } catch (Robo47_Doctrine_Hydrator_Exception $e) {
            $this->assertEquals('Invalid type for $classname: ' . Robo47_Core::getType($input), $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @dataProvider invalidTypenameProvider
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname
     * @covers Robo47_Doctrine_Hydrator_Exception
     */
    public function testSetDefaultClassnameThrowsExceptionWithInvalidType($input)
    {
        try {
            Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname($input);
            $this->fail('No Exception thrown');
        } catch (Robo47_Doctrine_Hydrator_Exception $e) {
            $this->assertEquals('Invalid type for $classname: ' . Robo47_Core::getType($input), $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname
     * @covers Robo47_Doctrine_Hydrator_Exception
     */
    public function testsetDefaultClassnameThrowsExceptionWithClassWhichNotImplementsArrayAccess()
    {
        $classname = 'stdClass';
        try {
            Robo47_Doctrine_Hydrator_PopoDriver::setDefaultClassname($classname);
            $this->fail('No Exception thrown');
        } catch (Robo47_Doctrine_Hydrator_Exception $e) {
            $this->assertEquals('Type does not implement ArrayAccess: ' . Robo47_Core::getType(new stdClass), $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname
     * @covers Robo47_Doctrine_Hydrator_Exception
     */
    public function testsetDefaultContainerClassnameThrowsExceptionWithClassWhichNotImplementsArrayAccess()
    {
        $classname = 'stdClass';
        try {
            Robo47_Doctrine_Hydrator_PopoDriver::setDefaultContainerClassname($classname);
            $this->fail('No Exception thrown');
        } catch (Robo47_Doctrine_Hydrator_Exception $e) {
            $this->assertEquals('Type does not implement ArrayAccess: ' . Robo47_Core::getType(new stdClass), $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     *
     * @return array
     */
    public function invalidTypenameProvider()
    {
        $data = array();

        $data[] = array(1);
        $data[] = array(1.1);
        $data[] = array(new stdClass);

        return $data;
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
        return $tag->getTable()
                   ->createQuery()
                   ->setHydrationMode('popo');
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
                     ->leftJoin('b.Category e')
                     ->setHydrationMode('popo');
    }
}