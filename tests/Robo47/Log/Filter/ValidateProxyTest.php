<?php
require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Log
 * @group Robo47_Log_Filter
 * @group Robo47_Log_Filter_ValidateProxy
 */
class Robo47_Log_Filter_ValidateProxyTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_Log_Filter_ValidateProxy
     */
    protected $_filter = null;

    public function setUp()
    {
        $validator = new Robo47_Validate_Mock(true);
        $this->_filter = new Robo47_Log_Filter_ValidateProxy($validator, null, false);
    }

    public function tearDown()
    {
        $this->_filter = null;
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy
     */
    public function testDefaultConstructor()
    {
        $validator = new Robo47_Validate_Mock(true);
        $filter = new Robo47_Log_Filter_ValidateProxy($validator);
        $this->assertNull($filter->getKey());
        $this->assertFalse($filter->getNot());
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::__construct
     */
    public function testConstruct()
    {
        $validator = new Robo47_Validate_Mock(true);
        $filter = new Robo47_Log_Filter_ValidateProxy($validator, 'foo', true);
        $this->assertSame($validator, $filter->getValidator());
        $this->assertEquals('foo', $filter->getKey(), 'Wrong key');
        $this->assertTrue($filter->getNot(), 'Wrong not');
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::getOptions
     */
    public function testGetOptions()
    {
        $options = $this->_filter->getOptions();
        $this->assertArrayHasKey('not', $options);
        $this->assertArrayHasKey('validator', $options);
        $this->assertArrayHasKey('key', $options);

        $this->assertSame($this->_filter->getNot(), $options['not'], 'Wrong not');
        $this->assertSame($this->_filter->getKey(), $options['key'], 'Wrong key');
        $this->assertSame($this->_filter->getValidator(), $options['validator'], 'Wrong validator');
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::setKey
     * @covers Robo47_Log_Filter_ValidateProxy::getKey
     */
    public function testSetKeyGetKey()
    {
        $this->_filter->setKey(null);
        $this->assertNull($this->_filter->getKey());
        $this->_filter->setKey('foo');
        $this->assertEquals('foo', $this->_filter->getKey());
        $this->_filter->setKey('bla');
        $this->assertEquals('bla', $this->_filter->getKey());
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::setNot
     * @covers Robo47_Log_Filter_ValidateProxy::getNot
     */
    public function testSetNotGetNot()
    {
        $this->assertFalse($this->_filter->getNot());
        $this->_filter->setNot(true);
        $this->assertTrue($this->_filter->getNot());
        $this->_filter->setNot(false);
        $this->assertFalse($this->_filter->getNot());
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::setValidator
     * @covers Robo47_Log_Filter_ValidateProxy::getValidator
     */
    public function testSetValidatorGetValidator()
    {
        $validator = new Robo47_Validate_Mock();
        $this->_filter->setValidator($validator);
        $this->assertSame($validator, $this->_filter->getValidator());
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::setValidator
     */
    public function testSetValidatorWithString()
    {
        $this->_filter->setValidator('Zend_Validate_Int');
        $this->assertInstanceOf('Zend_Validate_Int', $this->_filter->getValidator());
    }

    /**
     * @return array
     */
    public function invalidObjectProvider()
    {
        $data = array();

        $data[] = array(123);
        $data[] = array(new stdClass());
        $data[] = array('stdClass');

        return $data;
    }

    /**
     * @dataProvider invalidObjectProvider
     * @covers Robo47_Log_Filter_ValidateProxy::setValidator
     */
    public function testSetValidatorWithInvalidObject($object)
    {
        try {
            $this->_filter->setValidator($object);
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Filter_Exception $e) {
            $this->assertEquals('Validator is not instance of Zend_Validate_Interface', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @dataProvider invalidObjectProvider
     * @covers Robo47_Log_Filter_ValidateProxy::accept
     */
    public function testAcceptWithNonExistingKeyThrowsException($object)
    {
        $this->_filter->setKey('blub');
        try {
            $this->_filter->accept(array('foo' => 'baa'));
            $this->fail('No Exception thrown');
        } catch (Robo47_Log_Filter_Exception $e) {
            $this->assertEquals('key "blub" not found in event', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @return array
     */
    public function acceptValuesProvider()
    {
        $data = array();

        $trueValidator = new Robo47_Validate_Mock(true);
        $falseValidator = new Robo47_Validate_Mock(false);

        $data[] = array($trueValidator, 'baa', false, array('baa' => 'foo'), true, 'foo');
        $data[] = array($trueValidator, null, false, array('baa' => 'foo'), true, array('baa' => 'foo'));
        $data[] = array($falseValidator, 'baa', true, array('baa' => 'foo'), true, 'foo');
        $data[] = array($falseValidator, null, true, array('baa' => 'foo'), true, array('baa' => 'foo'));

        $data[] = array($falseValidator, 'baa', false, array('baa' => 'foo'), false, 'foo');
        $data[] = array($falseValidator, null, false, array('baa' => 'foo'), false, array('baa' => 'foo'));
        $data[] = array($trueValidator, 'baa', true, array('baa' => 'foo'), false, 'foo');
        $data[] = array($trueValidator, null, true, array('baa' => 'foo'), false, array('baa' => 'foo'));

        return $data;
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::accept
     * @dataProvider acceptValuesProvider
     */
    public function testAccept($validator, $key, $not, $event, $expected, $expectedLastValue)
    {
        $this->_filter->setValidator($validator);
        $this->_filter->setKey($key);
        $this->_filter->setNot($not);
        $this->assertEquals($expected, $this->_filter->accept($event), 'Accept-Return ist noch valid');
        $this->assertEquals($expectedLastValue, $validator->lastValue, 'lastValue is not expected');
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::factory
     */
    public function testFactory()
    {
        $config = array(
            'key' => 'blub',
            'validator' => 'Robo47_Validate_Mock',
            'not' => true,
        );
        $filter = Robo47_Log_Filter_ValidateProxy::factory($config);

        $this->assertInstanceOf('Robo47_Log_Filter_ValidateProxy', $filter, 'Wrong datatype from factory');

        $this->assertEquals($config['key'], $filter->getKey(), 'Key are wrong');
        $this->assertEquals($config['not'], $filter->getNot(), 'Not are wrong');
        $this->assertInstanceOf($config['validator'], $filter->getValidator(), 'Validator is wrong');
    }

    /**
     * @covers Robo47_Log_Filter_ValidateProxy::factory
     */
    public function testFactoryWithZendConfig()
    {
        $config = array(
            'key' => 'blub',
            'validator' => 'Robo47_Validate_Mock',
            'not' => true,
        );
        $config = new Zend_Config($config);
        $filter = Robo47_Log_Filter_ValidateProxy::factory($config);

        $this->assertInstanceOf('Robo47_Log_Filter_ValidateProxy', $filter, 'Wrong datatype from factory');

        $config = $config->toArray();
        $this->assertEquals($config['key'], $filter->getKey(), 'Key are wrong');
        $this->assertEquals($config['not'], $filter->getNot(), 'Not are wrong');
        $this->assertInstanceOf($config['validator'], $filter->getValidator(), 'Validator is wrong');
    }
}