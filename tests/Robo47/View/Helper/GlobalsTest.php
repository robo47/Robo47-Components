<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_View
 * @group Robo47_View_Helper
 * @group Robo47_View_Helper_Globals
 */
class Robo47_View_Helper_GlobalsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_View_Helper_Globals
     */
    protected $_helper = null;
    /**
     * @var Zend_View
     */
    protected $_view = null;

    public function setUp()
    {
        $this->_helper = new Robo47_View_Helper_Globals();
        $this->_helper->setView($this->getView());
    }

    /**
     * @return Zend_View
     */
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = new Zend_View();
            // doctype needed because of usage of cdata in HeadScript-Helper
            $this->_view->doctype('XHTML1_STRICT');
        }
        return $this->_view;
    }

    public function tearDown()
    {
        $this->_helper = null;
        $this->_view = null;
        // View-Helpers use the registry!
        Zend_Registry::_unsetInstance();
    }


    /**
     * @return array
     */
    public function globalsProvider()
    {
        $data = array();

        $globals = $this->getGlobals();
        foreach ($globals as $glob => $array) {
            if (!isset($GLOBALS[$array])) {
                $GLOBALS[$array] = array();
            }
            $GLOBALS[$array]['foo'] = '<strong><?php echo "foo"; ?></strong>';
        }

        // some "blackboxtests" lower case, uppercase, ...
        foreach ($globals as $global => $array) {
            $data[] = array(strtolower($global), null, null, false, $GLOBALS[$array]);
            $data[] = array(strtoupper($global), null, null, false, $GLOBALS[$array]);
            $data[] = array(ucFirst(strtolower($global)), null, null, false, $GLOBALS[$array]);
        }

        foreach ($globals as $global => $array) {
            $data[] = array($global, 'foo', null, false, '<strong><?php echo "foo"; ?></strong>');
            $data[] = array($global, 'foo', null, true, $this->getView()->escape('<strong><?php echo "foo"; ?></strong>'));
        }
        // unset value
        $data[] = array('get', 'unset', null, false, null);


        return $data;
    }

    public function getGlobals()
    {
        return array('GET' => '_GET',
            'POST' => '_POST',
            'SESSION' => '_SESSION',
            'ENV' => '_ENV',
            'COOKIE' => '_COOKIE',
            'SERVER' => '_SERVER');
    }

    /**
     * @covers Robo47_View_Helper_Globals
     * @covers Robo47_View_Helper_Globals::Globals
     * @covers Robo47_View_Helper_Globals::_getGlobal
     * @dataProvider globalsProvider
     */
    public function testGlobals($global, $name, $default, $escape, $result)
    {
        $this->assertEquals($result, $this->_helper->Globals($global, $name, $default, $escape));
    }

    /**
     * @covers Robo47_View_Helper_Exception
     * @covers Robo47_View_Helper_Globals::Globals
     * @covers Robo47_View_Helper_Globals::_checkGlobal
     */
    public function testExceptions()
    {
        try {
            $this->_helper->Globals('Foo', 'bla', 'blub', true);
        } catch (Robo47_View_Helper_Exception $e) {
            $this->assertEquals('Unknown global "Foo"', $e->getMessage());
        }
    }

    /**
     * @covers Robo47_View_Helper_Globals::Globals
     * @covers Robo47_View_Helper_Globals::_getGlobal
     */
    public function testUnsetGlobal()
    {
        unset($_SESSION);
        $this->assertNull($this->_helper->Globals('Session', 'bla', null));
    }
}