<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_View_Helper_CdnTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Robo47_View_Helper_Cdn<extended>
     * @covers Robo47_View_Helper_Cdn::__construct
     */
    public function testConstructor()
    {
        $helper = new Robo47_View_Helper_Cdn();
        $this->assertEquals('', $helper->getCdn());

        $helper = new Robo47_View_Helper_Cdn('http://cdn.domain.tld');
        $this->assertEquals('http://cdn.domain.tld', $helper->getCdn());
    }

    /**
     * @covers Robo47_View_Helper_Cdn::setCdn
     * @covers Robo47_View_Helper_Cdn::getCdn
     */
    public function testSetCdnGetCdn()
    {
        $helper = new Robo47_View_Helper_Cdn();
        $helper->setCdn('http://cdn.domain.tld');
        $this->assertEquals('http://cdn.domain.tld', $helper->getCdn());
    }

    /**
     * @covers Robo47_View_Helper_Cdn::__tostring
     */
    public function testToString()
    {
        $helper = new Robo47_View_Helper_Cdn();
        $helper->setCdn('http://cdn.domain.tld');
        $this->assertEquals('http://cdn.domain.tld', (string)$helper);
    }

    /**
     * @covers Robo47_View_Helper_Cdn::Cdn
     */
    public function testCdn()
    {
        $helper = new Robo47_View_Helper_Cdn();
        $helper->setCdn('http://cdn.domain.tld');
        $this->assertEquals('http://cdn.domain.tld', $helper->Cdn(''));
        $this->assertSame($helper, $helper->Cdn());

        $helper->setCdn('http://cdn.domain.tld');
        $this->assertEquals('http://cdn.domain.tld/some/image/on/the/cdn.jpg', (string)$helper->Cdn('/some/image/on/the/cdn.jpg'));
    }
}