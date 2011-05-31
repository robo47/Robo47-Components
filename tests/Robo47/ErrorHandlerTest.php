<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

function dummyErrorHandler($errno, $errstr, $errfile, $errline)
{
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

/**
 * @group Robo47_ErrorHandler
 */
class Robo47_ErrorHandlerTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        set_error_handler(
            array('PHPUnit_Util_ErrorHandler', 'handleError'),
            E_ALL | E_STRICT
        );
        ini_set('log_errors', 0);
    }

    /**
     * @covers Robo47_ErrorHandler::__construct
     */
    public function testConstruct()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $this->assertEquals(null, $errorHandler->getLog());
        $this->assertEquals('errorHandler', $errorHandler->getLogCategory());
    }

    /**
     * @covers Robo47_ErrorHandler::setLog
     * @covers Robo47_ErrorHandler::getLog
     */
    public function testSetLogGetLog()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $log = new Robo47_Log();
        $errorHandler->setLog($log);
        $this->assertSame($log, $errorHandler->getLog());
    }

    /**
     * @covers Robo47_ErrorHandler::setLogCategory
     * @covers Robo47_ErrorHandler::getLogCategory
     */
    public function testSetLogCategoryGetLogCategory()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $this->assertEquals('errorHandler', $errorHandler->getLogCategory());
        $errorHandler->setLogCategory('foo');
        $this->assertEquals('foo', $errorHandler->getLogCategory());
    }

    /**
     * @covers Robo47_ErrorHandler::setErrorPriorityMapping
     * @covers Robo47_ErrorHandler::getErrorPriorityMapping
     */
    public function testSetErrorPriorityMappingWithEmptyArray()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $errorHandler->setErrorPriorityMapping(array());
        $this->assertEquals(array('unknown' => 0), $errorHandler->getErrorPriorityMapping());
    }

    /**
     * @covers Robo47_ErrorHandler::setErrorPriorityMapping
     * @covers Robo47_ErrorHandler::getErrorPriorityMapping
     */
    public function testSetErrorPriorityWithoutUnknown()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $mapping = array(
            E_WARNING => 3,
            E_ERROR => 5,
        );
        $errorHandler->setErrorPriorityMapping($mapping);

        $expect = $mapping;
        $expect['unknown'] = 0;
        $this->assertEquals($expect, $errorHandler->getErrorPriorityMapping());
    }

    /**
     * @covers Robo47_ErrorHandler::setErrorPriorityMapping
     * @covers Robo47_ErrorHandler::getErrorPriorityMapping
     */
    public function testSetErrorPriorityWithUnknown()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $mapping = array(
            E_WARNING => 3,
            E_ERROR => 5,
            'unknown' => 4,
        );
        $errorHandler->setErrorPriorityMapping($mapping);

        $this->assertEquals($mapping, $errorHandler->getErrorPriorityMapping());
    }

    /**
     * @covers Robo47_ErrorHandler::registerAsErrorHandler
     */
    public function testRegisterAsErrorHandler()
    {
        $errorHandler = new Robo47_ErrorHandler();
        restore_error_handler();
        $errorHandler->registerAsErrorHandler();
        $this->assertNotNull(set_error_handler('dummyErrorHandler'));
        restore_error_handler();
    }

    /**
     * @covers Robo47_ErrorHandler::unregisterAsErrorHandler
     * @covers Robo47_ErrorHandler::getOldErrorHandler
     */
    public function testUnregisterAsErrorHandler()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $errorHandler->registerAsErrorHandler();
        $errorHandler->unregisterAsErrorHandler();
        $this->assertEquals($errorHandler->getOldErrorHandler(), set_error_handler('dummyErrorHandler'));
    }

    /**
     * @covers Robo47_ErrorHandler::handleError
     * @covers Robo47_ErrorHandler::_logError
     * @covers Robo47_ErrorHandler::_getErrorsPriority
     */
    public function testLoggingAndThrowingExceptions()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $writer = new Robo47_Log_Writer_Mock();
        $log = new Robo47_Log($writer);
        $errorHandler->setLogCategory('Foo');
        $errorHandler->setLog($log);

        try {
            $errorHandler->handleError(E_ERROR, 'BaaFoo', __FILE__ , 1234);
            $this->fail('no ErrorException thrown');
        } catch (Robo47_ErrorException $e) {
            $this->assertEquals(__FILE__ , $e->getFile(), 'Wrong value on getFile() of the exception');
            $this->assertEquals(1234, $e->getLine(), 'Wrong value on getLine() of the exception');
            $this->assertEquals(E_ERROR, $e->getSeverity(), 'Wrong value on getSeverity() of the exception');
            $this->assertEquals('BaaFoo', $e->getMessage(), 'Wrong value on getMessage() of the exception');
        }

        $this->assertEquals(1, count($writer->events), 'Event count on writer is wrong');
        $this->assertEquals(3, $writer->events[0]['priority'], 'Event has wrong priority in log writer');
        $this->assertEquals('Foo', $writer->events[0]['category'], 'Event has wrong category in log writer');
        $this->assertEquals('BaaFoo in ' . __FILE__ . ':1234', $writer->events[0]['message'], 'Event has wrong message in log writer');

        try {
            $errorHandler->handleError(123456, 'Blub', __FILE__ , 5678);
            $this->fail('no ErrorException thrown');
        } catch (Robo47_ErrorException $e) {
            $this->assertEquals(__FILE__ , $e->getFile(), 'Wrong value on getFile() of the exception');
            $this->assertEquals(5678, $e->getLine(), 'Wrong value on getLine() of the exception');
            $this->assertEquals(123456, $e->getSeverity(), 'Wrong value on getSeverity() of the exception');
            $this->assertEquals('Blub', $e->getMessage(), 'Wrong value on getMessage() of the exception');
        }

        $this->assertEquals(2, count($writer->events), 'Event count on writer is wrong');
        $this->assertEquals(0, $writer->events[1]['priority'], 'Event has wrong priority in log writer');
        $this->assertEquals('Foo', $writer->events[1]['category'], 'Event has wrong category in log writer');
        $this->assertEquals('Blub in ' . __FILE__ . ':5678', $writer->events[1]['message'], 'Event has wrong message in log writer');
    }

    /**
     * @covers Robo47_ErrorHandler::handleError
     */
    public function testSuppressingWithAt()
    {
        $errorHandler = new Robo47_ErrorHandler();
        $writer = new Robo47_Log_Writer_Mock();
        $log = new Robo47_Log($writer);
        $errorHandler->setLogCategory('Foo');
        $errorHandler->setLog($log);

        @$errorHandler->handleError(E_ERROR, 'BaaFoo', __FILE__ , 1234);
        $this->assertEquals(0, count($writer->events), 'Event count on writer is wrong');
    }
}