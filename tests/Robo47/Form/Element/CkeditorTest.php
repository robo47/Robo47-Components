<?php

require_once dirname(__FILE__) . '/../../../TestHelper.php';

class Robo47_Form_Element_CkeditorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers Robo47_Form_Element_Ckeditor
     */
    public function testRightHelper()
    {
        $ckeditor = new Robo47_Form_Element_Ckeditor('ckeditor');
        $this->assertEquals('ckeditor', $ckeditor->helper);
    }
}