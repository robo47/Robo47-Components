<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Form
 * @group Robo47_Form_Decorator
 * @group Robo47_Form_Decorator_Info
 */
class Robo47_Form_Decorator_InfoTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_Form_Decorator_Info
     */
    protected $_decorator = null;

    public function setUp()
    {
        $element = new Zend_Form_Element_Text('foo');
        $element->setView(new Zend_View());
        $this->_decorator = new Robo47_Form_Decorator_Info();
        $this->_decorator->setElement($element);
    }

    public function tearDown()
    {
        $this->_decorator = null;
    }

    /**
     * @covers Robo47_Form_Decorator_Info::__construct
     */
    public function testConstruct()
    {
        $this->assertEquals($this->_decorator->getPlacement(), Zend_Form_Decorator_Abstract::PREPEND);
        $this->assertEquals('', $this->_decorator->getInfo());
    }

    /**
     * @covers Robo47_Form_Decorator_Info::setPlacement
     * @covers Robo47_Form_Decorator_Info::getPlacement
     */
    public function testSetPlacementGetPlacement()
    {
        $this->assertEquals($this->_decorator->getPlacement(), Zend_Form_Decorator_Abstract::PREPEND);
        $this->_decorator->setPlacement(Zend_Form_Decorator_Abstract::APPEND);
        $this->assertEquals($this->_decorator->getPlacement(), Zend_Form_Decorator_Abstract::APPEND);
        $this->_decorator->setPlacement(Zend_Form_Decorator_Abstract::PREPEND);
        $this->assertEquals($this->_decorator->getPlacement(), Zend_Form_Decorator_Abstract::PREPEND);

        $this->_decorator->setPlacement('prepend');
        $this->assertEquals($this->_decorator->getPlacement(), 'PREPEND');
    }

    /**
     * @covers Robo47_Form_Decorator_Info::setInfo
     * @covers Robo47_Form_Decorator_Info::getInfo
     */
    public function testSetInfoGetInfo()
    {
        $this->assertEquals($this->_decorator->getInfo(), '');
        $this->_decorator->setInfo('foo');
        $this->assertEquals($this->_decorator->getInfo(), 'foo');
    }

    /**
     * @covers Robo47_Form_Decorator_Info::render
     */
    public function testRenderAppend()
    {
        $content = 'foo';


        $this->_decorator->setPlacement('append');
        $sep = $this->_decorator->getSeparator();
        $this->_decorator->setInfo('Something');
        $rendered = $this->_decorator->render($content);
        $this->assertEquals($content . $sep . 'Something', $rendered);
    }

    /**
     * @covers Robo47_Form_Decorator_Info::render
     */
    public function testRenderPrepend()
    {
        $content = 'foo';
        $this->_decorator->setPlacement('prepend');
        $sep = $this->_decorator->getSeparator();
        $this->_decorator->setInfo('Something');
        $rendered = $this->_decorator->render($content);
        $this->assertEquals('Something' . $sep . $content, $rendered);
    }

    /**
     * @covers Robo47_Form_Decorator_Info::render
     */
    public function testRenderDefaultPlacement()
    {
        $content = 'foo';
        $this->_decorator->setPlacement('default');
        $sep = $this->_decorator->getSeparator();
        $this->_decorator->setInfo('Something');
        $rendered = $this->_decorator->render($content);
        $this->assertEquals($content . $sep . 'Something', $rendered);
    }

    /**
     * @covers Robo47_Form_Decorator_Info::render
     */
    public function testRenderWithoutView()
    {
        $element = new Zend_Form_Element_Text('foo');
        $this->_decorator = new Robo47_Form_Decorator_Info();
        $this->_decorator->setElement($element);

        $content = 'foo';
        $this->_decorator->setInfo('Something');
        $rendered = $this->_decorator->render($content);
        $this->assertEquals($content, $rendered);
    }
}