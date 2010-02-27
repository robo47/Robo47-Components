<?php
require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Auth_Adapter_ArrayTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Auth_Adapter_Array<extended>
     * @covers Robo47_Auth_Adapter_Array::__construct
     */
    public function testConstructorDefaults()
    {
        $adapter = new Robo47_Auth_Adapter_Array();
        $this->assertEquals('', $adapter->getUsername(), 'wrong username');
        $this->assertEquals('', $adapter->getPassword(), 'wrong password');
        $this->assertEquals(array(), $adapter->getUserData(), 'wrong userData');
    }

    /**
     * @covers Robo47_Auth_Adapter_Array::setUsername
     * @covers Robo47_Auth_Adapter_Array::getUsername
     */
    public function testSetUsername()
    {
        $adapter = new Robo47_Auth_Adapter_Array();
        $adapter->setUsername('foo');
        $this->assertEquals('foo', $adapter->getUsername());
    }

    /**
     * @covers Robo47_Auth_Adapter_Array::setPassword
     * @covers Robo47_Auth_Adapter_Array::getPassword
     */
    public function testSetPassword()
    {
        $adapter = new Robo47_Auth_Adapter_Array();
        $adapter->setPassword('foo');
        $this->assertEquals('foo', $adapter->getPassword());
    }

    /**
     * @covers Robo47_Auth_Adapter_Array::setUserData
     * @covers Robo47_Auth_Adapter_Array::getUserData
     */
    public function testSetUserData()
    {
        $adapter = new Robo47_Auth_Adapter_Array();
        $adapter->setUserData(array(array('baa' => 'foo')));
        $this->assertEquals(array(array('baa' => 'foo')), $adapter->getUserData());
    }
    
    public function authenticateProvider()
    {
        $data = array();

        $userData = array('user1' => 'pw1',
            'user2' => 'pw2',
            'user3' => 'pw3');

        $data[] = array('user1', 'pw1', $userData, true);
        $data[] = array('user2', 'pw2', $userData, true);
        $data[] = array('user3', 'pw3', $userData, true);

        $data[] = array('user1', 'pw3', $userData, false);
        $data[] = array('user2', 'pw1', $userData, false);
        $data[] = array('user3', 'pw2', $userData, false);

        return $data;
    }

    /**
     * @covers Robo47_Auth_Adapter_Array::authenticate
     * @dataProvider authenticateProvider
     */
    public function testAuthenticate($username, $password, $userData, $authenticated)
    {
        $adapter = new Robo47_Auth_Adapter_Array($username, $password, $userData);
        $authResult = $adapter->authenticate();

        $this->assertEquals($authenticated, $authResult->isValid(), 'wrong authentication result');
        $this->assertEquals($username, $authResult->getIdentity(), 'Wrong identity');
    }
}