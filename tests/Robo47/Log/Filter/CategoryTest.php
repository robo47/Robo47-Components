<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Log
 * @group Robo47_Log_Filter
 * @group Robo47_Log_Filter_Category
 */
class Robo47_Log_Filter_CategoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Log_Filter_Category
     */
    public function testDefaultConstructor()
    {
        $filter = new Robo47_Log_Filter_Category(array());
        $this->assertEquals(array(), $filter->getCategories(), 'Categories are wrong');
        $this->assertEquals(false, $filter->getNot(), 'Not is Wrong');
    }

    /**
     * @covers Robo47_Log_Filter_Category::__construct
     */
    public function testConstruct()
    {
        $filter = new Robo47_Log_Filter_Category(array('bla'), true);
        $this->assertEquals(array('bla'), $filter->getCategories(), 'Categories are wrong');
        $this->assertEquals(true, $filter->getNot(), 'Not is Wrong');
    }

    /**
     * @covers Robo47_Log_Filter_Category::setCategories
     * @covers Robo47_Log_Filter_Category::getCategories
     */
    public function testGetCategorySetCategory()
    {
        $filter = new Robo47_Log_Filter_Category(array('bla'));
        $filter->setCategories(array('foo'));
        $this->assertEquals(array('foo'), $filter->getCategories());
    }

    /**
     * @covers Robo47_Log_Filter_Category::getOptions
     */
    public function testGetOptions()
    {
        $filter = new Robo47_Log_Filter_Category(array('bla'));
        $options = $filter->getOptions();
        $this->assertArrayHasKey('categories', $options, 'getOptions() misses key categories');

        // parent::getOptions
        $this->assertArrayHasKey('not', $options, 'getOptions() misses key not');
        $this->assertArrayHasKey('validator', $options, 'getOptions() misses key validator');
        $this->assertArrayHasKey('key', $options, 'getOptions() misses key key');

        $this->assertEquals($options['categories'], $filter->getCategories(), 'Wrong categories');
        $this->assertEquals($options['not'], $filter->getNot(), 'Wrong not');
        $this->assertEquals($options['key'], $filter->getKey(), 'Wrong key');
        $this->assertSame($options['validator'], $filter->getValidator(), 'Wrong validator');
    }

    public function filterProvider()
    {
        $data = array();

        $data[] = array(array('Foo'), array('category' => 'Foo'), true);
        $data[] = array(array('Foo', 'Bar'), array('category' => 'Foo'), true);
        $data[] = array(array('Foo', 'Bar'), array('category' => 'Bar'), true);
        $data[] = array(array('Foo'), array('category' => 'Bar'), false);
        $data[] = array(array('Foo'), array('category' => 'foo'), false);

        return $data;
    }

    /**
     * @covers Robo47_Log_Filter_Category::__construct
     * @covers Robo47_Log_Filter_Category::accept
     * @dataProvider filterProvider
     */
    public function testFilter($categories, $event, $expectedResult)
    {
        $filter = new Robo47_Log_Filter_Category($categories);
        $this->assertSame($expectedResult, $filter->accept($event));
    }

    /**
     * @covers Robo47_Log_Filter_Category::factory
     */
    public function testFactory()
    {
        $config = array(
            'categories' => array('blub', 'bla'),
            'not' => true,
        );
        $filter = Robo47_Log_Filter_Category::factory($config);

        $this->assertInstanceOf('Robo47_Log_Filter_Category', $filter, 'Wrong datatype from factory');

        $this->assertEquals($config['categories'], $filter->getCategories(), 'Categories are wrong');
        $this->assertEquals('category', $filter->getKey(), 'Key are wrong');
        $this->assertEquals($config['not'], $filter->getNot(), 'Not is wrong');
        $this->assertInstanceOf('Zend_Validate_InArray', $filter->getValidator(), 'Validator is wrong');
    }

    /**
     * @covers Robo47_Log_Filter_Category::factory
     */
    public function testFactoryWithConfig()
    {
        $config = array(
            'categories' => array('blub', 'bla'),
            'not' => true,
        );
        $filter = Robo47_Log_Filter_Category::factory(new Zend_Config($config));

        $this->assertInstanceOf('Robo47_Log_Filter_Category', $filter, 'Wrong datatype from factory');

        $this->assertEquals($config['categories'], $filter->getCategories(), 'Categories are wrong');
        $this->assertEquals('category', $filter->getKey(), 'Key are wrong');
        $this->assertEquals($config['not'], $filter->getNot(), 'Not are wrong');
        $this->assertInstanceOf('Zend_Validate_InArray', $filter->getValidator(), 'Validator is wrong');
    }
}