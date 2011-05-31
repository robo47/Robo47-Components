<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

/**
 * @group Robo47_Log
 */
class Robo47_LogTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Log::__construct
     */
    public function testDefaultConstructor()
    {
        $log = new Robo47_Log();
        $this->assertEquals(null, $log->getDefaultCategory(), 'Wrong default category');
        $this->assertEquals(array(), $log->getWriters(), 'Writers found');
        $this->assertEquals(array(), $log->getFilters(), 'Filters found');
    }

    /**
     * @covers Robo47_Log::__construct
     */
    public function testConstructorWithWriter()
    {
        $writer = new Zend_Log_Writer_Null();
        $log = new Robo47_Log($writer);

        $this->assertEquals(1, count($log->getWriters()));
        $this->assertSame($writer, current($log->getWriters()));
    }

    /**
     * @covers Robo47_Log::getWriters
     */
    public function testGetWriters()
    {
        $log = new Robo47_Log();
        $writer = new Zend_Log_Writer_Null();
        $log->addWriter($writer);

        $this->assertContains($writer, $log->getWriters(), 'Writer added via addWriter not found');

        $writer2 = new Zend_Log_Writer_Null();

        $log = new Robo47_Log($writer);
        $log->addWriter($writer2);

        $this->assertEquals(2, count($log->getWriters()), '2 Writers should be added');

        $this->assertContains($writer, $log->getWriters(), 'Writer given in constructor not found');
        $this->assertContains($writer2, $log->getWriters(), 'Writer added via addWriter not found');
    }

    /**
     * @covers Robo47_Log::removeWriter
     */
    public function testRemoveWriterByInstance()
    {
        $log = new Robo47_Log();
        $writer = new Zend_Log_Writer_Null();
        $writer2 = new Zend_Log_Writer_Null();
        $writer3 = new Zend_Log_Writer_Null();

        $log->addWriter($writer);
        $log->addWriter($writer2);
        $log->addWriter($writer3);

        $log->removeWriter($writer);

        $this->assertNotContains($writer, $log->getWriters(), 'Writer added via addWriter still there');
        $this->assertContains($writer2, $log->getWriters(), 'Writer2 added via addWriter not found');
        $this->assertContains($writer3, $log->getWriters(), 'Writer3 added via addWriter not found');
    }

    /**
     * @covers Robo47_Log::removeWriter
     */
    public function testRemoveWriterByClassname()
    {
        $log = new Robo47_Log();
        $writer = new Zend_Log_Writer_Null();
        $writer2 = new Zend_Log_Writer_Null();
        $writer3 = new Zend_Log_Writer_Null();

        $log->addWriter($writer);
        $log->addWriter($writer2);
        $log->addWriter($writer3);

        $log->removeWriter('Zend_Log_Writer_Null');
        $this->assertEquals(0, count($log->getWriters()), 'Writer added via addWriter still there');
    }

    /**
     * @covers Robo47_Log::getFilters
     */
    public function testGetFilter()
    {
        $log = new Robo47_Log();
        $filter = new Robo47_Log_Filter_Mock();

        $filters = $log->getFilters();
        $this->assertEquals(0, count($filters));

        $log->addFilter($filter);

        $filters = $log->getFilters();
        $this->assertEquals(1, count($filters));

        $this->assertContains($filter, $filters);
    }

    /**
     * @covers Robo47_Log::removeFilter
     */
    public function testRemoveFilterByClass()
    {
        $log = new Robo47_Log();
        $filter = new Robo47_Log_Filter_Mock();
        $filter2 = new Robo47_Log_Filter_Category(array());
        $log->addFilter($filter);
        $log->addFilter($filter2);

        $filters = $log->getFilters();
        $this->assertEquals(2, count($filters));

        $log->removeFilter('Robo47_Log_Filter_Category');

        $filters = $log->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertNotContains($filter2, $filters);
        $this->assertContains($filter, $filters);
    }

    /**
     * @covers Robo47_Log::removeFilter
     */
    public function testRemoveFilterByInstance()
    {
        $log = new Robo47_Log();
        $filter = new Robo47_Log_Filter_Mock();
        $filter2 = new Robo47_Log_Filter_Category(array());
        $log->addFilter($filter);
        $log->addFilter($filter2);

        $filters = $log->getFilters();
        $this->assertEquals(2, count($filters));

        $log->removeFilter($filter2);

        $filters = $log->getFilters();
        $this->assertEquals(1, count($filters));
        $this->assertNotContains($filter2, $filters);
        $this->assertContains($filter, $filters);
    }

    /**
     * @covers Robo47_Log::log
     */
    public function testLogWithoutDefaultCategoryFallback()
    {
        $log = new Robo47_Log();
        $writer = new Robo47_Log_Writer_Mock();
        $log->addWriter($writer);
        $log->setDefaultCategory(null);
        $log->log('Foo', 0);

        $this->assertEquals(1, count($writer->events));
        $this->assertArrayNotHasKey('category', $writer->events[0]);
    }

    /**
     * @covers Robo47_Log::log
     */
    public function testLogWithDefaultCategoryFallback()
    {
        $log = new Robo47_Log();
        $writer = new Robo47_Log_Writer_Mock();
        $log->addWriter($writer);
        $log->setDefaultCategory('default');
        $log->log('Foo', 0);

        $this->assertEquals(1, count($writer->events));
        $this->assertArrayHasKey('category', $writer->events[0]);
        $this->assertEquals('default', $writer->events[0]['category']);
    }

    /**
     * @covers Robo47_Log::factory
     */
    public function testFactoryWithArray()
    {
        $config = array(
            array(
                'writerName' => 'Mock',
                'writerNamespace' => 'Robo47_Log_Writer',
            )
        );

        $log = Robo47_Log::factory($config);

        $this->assertInstanceOf('Robo47_Log', $log, 'Wrong Datatype of Log');
        $writers = $log->getWriters();
        $this->assertEquals(1, count($writers), 'Wrong count for writers');
        $this->assertInstanceOf('Robo47_Log_Writer_Mock', $writers[0], 'Wrong Datatype for Writer');
    }

    /**
     * @covers Robo47_Log::factory
     */
    public function testFactoryWithZendConfig()
    {
        $config = array(
            array(
                'writerName' => 'Mock',
                'writerNamespace' => 'Robo47_Log_Writer',
            )
        );

        $log = Robo47_Log::factory(new Zend_Config($config));

        $this->assertInstanceOf('Robo47_Log', $log, 'Wrong Datatype of Log');
        $writers = $log->getWriters();
        $this->assertEquals(1, count($writers), 'Wrong count for writers');
        $this->assertInstanceOf('Robo47_Log_Writer_Mock', $writers[0], 'Wrong Datatype for Writer');
    }

    /**
     * @covers Robo47_Log::factory
     */
    public function testFactoryWithSingleConfigAndWriterObject()
    {
        $config = array(
            new Robo47_Log_Writer_Mock()
        );

        $log = Robo47_Log::factory($config);

        $this->assertInstanceOf('Robo47_Log', $log, 'Wrong Datatype of Log');
        $writers = $log->getWriters();
        $this->assertEquals(1, count($writers), 'Wrong count for writers');
        $this->assertInstanceOf('Robo47_Log_Writer_Mock', $writers[0], 'Wrong Datatype for Writer');
    }

    /**
     * @covers Robo47_Log::factory
     */
    public function testFactoryThrowsExceptionWithEmptyConfigArray()
    {
        $config = array();
        try {
            $log = Robo47_Log::factory($config);
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Exception $e) {
            $this->assertEquals('Configuration must be an array or instance of Zend_Config', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Log::setDefaultCategory
     * @covers Robo47_Log::getDefaultCategory
     */
    public function testSetDefaultCategoryGetDefaultCategory()
    {
        $log = new Robo47_Log();

        $this->assertEquals(null, $log->getDefaultCategory(), 'Wrong default category');
        $return = $log->setDefaultCategory('foo');
        $this->assertSame($log, $return, 'No Fluent Interface');
        $this->assertEquals('foo', $log->getDefaultCategory(), 'Wrong default category');
    }

    /**
     * @covers Robo47_Log::getPriorities
     */
    public function testGetPriorities()
    {
        $log = new Robo47_Log();
        $priorities = $log->getPriorities();

        $this->assertArrayHasKey(0, $priorities);
        $this->assertArrayHasKey(1, $priorities);
        $this->assertArrayHasKey(2, $priorities);
        $this->assertArrayHasKey(3, $priorities);
        $this->assertArrayHasKey(4, $priorities);
        $this->assertArrayHasKey(5, $priorities);
        $this->assertArrayHasKey(6, $priorities);
        $this->assertArrayHasKey(7, $priorities);

        $this->assertEquals('EMERG', $priorities[0]);
        $this->assertEquals('ALERT', $priorities[1]);
        $this->assertEquals('CRIT', $priorities[2]);
        $this->assertEquals('ERR', $priorities[3]);
        $this->assertEquals('WARN', $priorities[4]);
        $this->assertEquals('NOTICE', $priorities[5]);
        $this->assertEquals('INFO', $priorities[6]);
        $this->assertEquals('DEBUG', $priorities[7]);
    }
}
