<?php

require_once dirname(__FILE__ ) . '/../TestHelper.php';

/**
 * @group Robo47_Popo
 */
class Robo47_PopoTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Popo
     */
    public function testClass()
    {
        $reflection = new ReflectionClass('Robo47_Popo');
        $interfaces = $reflection->getInterfaceNames();
        $this->assertContains('ArrayAccess', $interfaces);
    }

    /**
     * @covers Robo47_Popo::__set
     * @covers Robo47_Popo::__get
     * @covers Robo47_Popo::__isset
     * @covers Robo47_Popo::__unset
     */
    public function testObjectAccess()
    {
        $popo = new Robo47_Popo();

        $this->assertEquals(0, count($popo));

        $popo->foo = 'blub';
        $this->assertEquals('blub', $popo->foo);
        $this->assertTrue(isset($popo->foo));
        $this->assertEquals(1, count($popo));

        unset($popo->foo);
        $this->assertFalse(isset($popo->foo));
        $this->assertEquals(0, count($popo));
    }

    /**
     * @covers Robo47_Popo::__get
     */
    public function test__getThrowsExceptionOnAccessingNonExistingProperty()
    {
        $popo = new Robo47_Popo();
        try {
            $foo = $popo->foo;
            $this->fail('No Exception thrown');
        } catch (Robo47_Exception $e) {
            $this->assertEquals('Variable foo not set', $e->getMessage(), 'Wrong Exception message');
        }
    }
}
