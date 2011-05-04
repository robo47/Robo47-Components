<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

class MyStaticTestContainer
{

    public static $blub = null;
    public static $bla = null;
    public static $foo = null;
    public static $something = false;

    public static function setFoo($value)
    {
        self::$foo = $value;
    }

    public static function something()
    {
        self::$something = true;
    }

    public static function resetClass()
    {
        self::$blub = null;
        self::$bla = null;
        self::$foo = null;
        self::$something = false;
    }
}

/**
 * @group Robo47_Application
 * @group Robo47_Application_Resource
 * @group Robo47_Application_Resource_Object
 */
class Robo47_Application_Resource_ObjectTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->application = new Zend_Application('testing');
        $this->bootstrap = new Zend_Application_Bootstrap_Bootstrap($this->application);
        Zend_Registry::_unsetInstance();
    }

    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
        MyStaticTestContainer::resetClass();
    }

    /**
     * @covers Robo47_Application_Resource_Object<extended>
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInit()
    {
        $options = array(
            'classname' => 'Robo47_Mock'
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithParams()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'params' => array('bla', 'blub')
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
        $object = $resource->getObject();
        $this->assertEquals(1, count($object->mockData['call']));
        $this->assertContains(array('__construct', array('bla', 'blub')), $object->mockData['call']);
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_Object(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_Object.', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Exception
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithoutClassname()
    {
        $options = array(
            'params' => array('bla', 'blub')
        );

        $resource = new Robo47_Application_Resource_Object($options);
        try {
            $resource->init();
            $this->fail('no exception thrown on init without classname');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('no classname found for Object resource', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithFunctions()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'functions' => array(
                array('setFoo', array('baa', 'foo')),
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
        $object = $resource->getObject();
        $this->assertEquals(2, count($object->mockData['call']));
        $this->assertContains(array('setFoo', array('baa', 'foo')), $object->mockData['call']);
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithFunctionsWithoutParams()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'functions' => array(
                array('setFoo'),
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
        $object = $resource->getObject();
        $this->assertEquals(2, count($object->mockData['call']));
        $this->assertContains(array('setFoo', array()), $object->mockData['call']);
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Exception
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithFunctionsWithInvalidParameter()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'functions' => array(
                array('setFoo', 'blub'),
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        try {
            $resource->init();
            $this->fail('no exception thrown on using function with invalid parameter');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('invalid parameters for function setFoo', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Exception
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithFunctionsWithoutFunctionname()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'functions' => array(
                array(),
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        try {
            $resource->init();
            $this->fail('no exception thrown on using function without functionname');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('no valid functionname found on function', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithVariables()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'variables' => array(
                'bla' => 'blub',
                'foo' => 'baa',
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
        $object = $resource->getObject();
        $this->assertEquals(2, count($object->mockData['set']));
        $this->assertContains(array('bla', 'blub'), $object->mockData['set']);
        $this->assertContains(array('foo', 'baa'), $object->mockData['set']);
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithStaticVariables()
    {
        $options = array(
            'classname' => 'MyStaticTestContainer',
            'staticVariables' => array(
                'blub' => 'blub',
                'bla' => 'baa',
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('MyStaticTestContainer', $resource->getObject());

        $this->assertEquals('blub', MyStaticTestContainer::$blub);
        $this->assertEquals('baa', MyStaticTestContainer::$bla);
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithRegistryKey()
    {
        $options = array(
            'classname' => 'Robo47_Mock',
            'registryKey' => 'Robo47_Mock',
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('Robo47_Mock', $resource->getObject());
        $object = $resource->getObject();

        $this->assertTrue(Zend_Registry::isRegistered($options['registryKey']));
        $this->assertInstanceOf('Robo47_Mock', Zend_Registry::get($options['registryKey']));
    }

    /**
     * @covers Robo47_Application_Resource_Object::init
     * @covers Robo47_Application_Resource_Object::<protected>
     * @covers Robo47_Application_Resource_Object::getObject
     */
    public function testInitWithStaticFunctions()
    {
        $options = array(
            'classname' => 'MyStaticTestContainer',
            'staticFunctions' => array(
                array('setFoo', array('bla')),
            )
        );

        $resource = new Robo47_Application_Resource_Object($options);
        $resource->init();

        $this->assertInstanceOf('MyStaticTestContainer', $resource->getObject());
        $this->assertEquals('bla', MyStaticTestContainer::$foo);
    }
}

