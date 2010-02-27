<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Application_Resource_ObjectMultiTest extends PHPUnit_Framework_TestCase
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
    }

    /**
     * @covers Robo47_Application_Resource_ObjectMulti::init
     */
    public function testInitWithEmptyOptions()
    {
        $resource = new Robo47_Application_Resource_ObjectMulti(array());
        try {
            $resource->init();
            $this->fail('no exception thrown on init with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Empty options in resource Robo47_Application_Resource_ObjectMulti.', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_ObjectMulti::getObject
     */
    public function testGetObjectWithInvalidName()
    {
        $object1 = array(
            'classname'     => 'Robo47_Mock',
        );

        $options = array(
            'obj1' => $object1,
        );
        $resource = new Robo47_Application_Resource_ObjectMulti($options);
        $resource->init();
        try {
            $resource->getObject('foo');
            $this->fail('no exception thrown on getObject with empty options');
        } catch (Robo47_Application_Resource_Exception $e) {
            $this->assertEquals('Object \'foo\' doesn\'t exist', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_Application_Resource_ObjectMulti<extended>
     * @covers Robo47_Application_Resource_ObjectMulti::init
     * @covers Robo47_Application_Resource_ObjectMulti::getObject
     * @covers Robo47_Application_Resource_ObjectMulti::getObjects
     */
    public function testInit()
    {
        $object1 = array(
            'classname'     => 'Robo47_Mock',
            'params' => array('bla', 'blub'),
            'variables' => array(
                'bla' => 'blub',
                'foo' => 'baa',
            ),
            'functions' => array(
                array('setFoo', array('baa', 'foo')),
            ),
            'registryKey' => 'object1',
        );

        $object2 = array(
            'classname'     => 'Robo47_Mock',
            'params' => array('bla', 'blub'),
            'variables' => array(
                'bla' => 'blub',
                'foo' => 'baa',
            ),
            'functions' => array(
                array('setFoo', array('baa', 'foo')),
            ),
            'registryKey' => 'object2',
        );

        $object3 = array(
            'classname'     => 'Robo47_Mock',
            'params' => array('bla', 'blub'),
            'variables' => array(
                'bla' => 'blub',
                'foo' => 'baa',
            ),
            'functions' => array(
                array('setFoo', array('baa', 'foo')),
            ),
            'registryKey' => 'object3',
        );


        $options = array(
            'obj1' => $object1,
            'obj2' => $object2,
            'obj3' => $object3,
        );

        $resource = new Robo47_Application_Resource_ObjectMulti($options);
        $resource->init();

        $this->assertEquals(3, count($resource->getObjects()));

        $this->assertType('Robo47_Mock', $resource->getObject('obj1'));
        $this->assertType('Robo47_Mock', $resource->getObject('obj2'));
        $this->assertType('Robo47_Mock', $resource->getObject('obj3'));

        $this->assertTrue(Zend_Registry::isRegistered($object1['registryKey']), 'object 1 key not found in registry');
        $this->assertTrue(Zend_Registry::isRegistered($object2['registryKey']), 'object 2 key not found in registry');
        $this->assertTrue(Zend_Registry::isRegistered($object3['registryKey']), 'object 3 key not found in registry');
    }
}