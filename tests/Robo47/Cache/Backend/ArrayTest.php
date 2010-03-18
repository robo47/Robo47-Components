<?php

require_once dirname(__FILE__ ) . '/../../../TestHelper.php';

/**
 * @group Robo47_Cache
 * @group Robo47_Cache_Backend
 * @group Robo47_Cache_Backend_Array
 */
class Robo47_Cache_Backend_ArrayTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Robo47_Cache_Backend_Array
     */
    public $_backend = null;
    
    public function setUp()
    {
        $this->_backend = new Robo47_Cache_Backend_Array();
    }
    
    public function tearDown()
    {
        unset($this->_backend);
    }

    /**
     * @covers Robo47_Cache_Backend_Array::save
     */
    public function testCacheSaveData()
    {
        $key = 'foo';
        $value = 'bar';

        $this->assertTrue($this->_backend->save($value, $key));
        $this->assertArrayHasKey($key, $this->_backend->data, 'Data was not saved in cache');
        $this->assertEquals($this->_backend->data[$key], $value, 'Data in cache is wrong');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::load
     */
    public function testCacheLoadData()
    {
        $key = 'foo';
        $value = 'bar';

        $this->_backend->data[$key] = $value;

        $data = $this->_backend->load($key);

        $this->assertEquals($data, $value, 'Data in cache is wrong');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::load
     */
    public function testCacheLoadNotExistingKey()
    {
        $key = 'nonExistingKey';

        $data = $this->_backend->load($key);

        $this->assertFalse($data, 'Data in cache should not exist');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::test
     */
    public function testCacheTestData()
    {
        $key = 'foo';
        $value = 'bar';

        $this->_backend->data[$key] = $value;

        $test = $this->_backend->test($key);

        $this->assertTrue($test, 'Data in cache should exist');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::test
     */
    public function testCacheTestNotExistingKey()
    {
        $key = 'nonExistingKey';

        $data = $this->_backend->test($key);

        $this->assertFalse($data, 'Data in cache should not exist');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::remove
     */
    public function testCacheRemoveKey()
    {
        $key = 'foo';
        $value = 'bar';

        $this->_backend->data[$key] = $value;
        $removed = $this->_backend->remove($key);

        $this->assertArrayNotHasKey($key, $this->_backend->data, 'Key in cache should not exist anymore');
        $this->assertTrue($removed, 'Robo47_Cache_Backend_Array::remove() should return true if it deleted a key successfully');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::remove
     */
    public function testCacheRemoveNonExistingKey()
    {
        $key = 'foo';

        $removed = $this->_backend->remove($key);

        $this->assertFalse($removed, 'Robo47_Cache_Backend_Array::remove() should return true if it deleted a key successfully');
    }

    /**
     * @covers Robo47_Cache_Backend_Array::clean
     */
    public function testCacheClean()
    {
        $this->_backend->data = array(
            'bla' => 'foo',
            'blub' => 'bla',
        );

        $this->_backend->clean();
        $this->assertEquals(0, count($this->_backend->data), 'Cleaning the cache did not empty it');
    }
    
    public function unsupportedModesDataProvider()
    {
        $unsupportedModes = array();
        $unsupportedModes[] = array(Zend_Cache::CLEANING_MODE_OLD);
        $unsupportedModes[] = array(Zend_Cache::CLEANING_MODE_MATCHING_TAG);
        $unsupportedModes[] = array(Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG);
        $unsupportedModes[] = array(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG);

        return $unsupportedModes;
    }

    /**
     * @covers Robo47_Cache_Backend_Array::clean
     * @covers Robo47_Cache_Backend_Exception
     * @dataProvider unsupportedModesDataProvider
     */
    public function testCacheCleanWithUnsupportedModes($mode)
    {
        $this->_backend->data = array(
            'bla' => 'foo',
            'blub' => 'bla',
        );
        try {
            $this->_backend->clean($mode);
            $this->fail('Cleaning with mode ' . $mode . ' worked, but should throw Exception');
        } catch (Robo47_Cache_Backend_Exception $e) {
            $this->assertEquals('Mode ' . $mode . ' not supported', $e->getMessage(), 'Exception message missmatch');
        }
    }

    /**
     * @covers Robo47_Cache_Backend_Array::clean
     */
    public function testCacheCleanWithUnknownMode()
    {
        $mode = 'unknownMode';
        $this->_backend->data = array(
            'bla' => 'foo',
            'blub' => 'bla',
        );
        try {
            $this->_backend->clean($mode);
            $this->fail('Cleaning with mode ' . $mode . ' worked, but should throw Exception');
        } catch (Robo47_Cache_Backend_Exception $e) {
            $this->assertEquals('Unknown mode ' . $mode, $e->getMessage(), 'Exception message missmatch');
        }
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getIds
     */
    public function testGetIds()
    {
        $this->assertEquals(array_keys($this->_backend->data), $this->_backend->getIds());
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getTags
     */
    public function testGetTags()
    {
        $this->assertEquals(array(), $this->_backend->getTags());
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getIdsMatchingTags
     */
    public function testGetIdsMatchingTags()
    {
        $this->assertEquals(array(), $this->_backend->getIdsMatchingTags(array()));
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getIdsNotMatchingTags
     */
    public function testGetIdsNotMatchingTags()
    {
        $this->assertEquals(array(), $this->_backend->getIdsNotMatchingTags(array()));
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getIdsMatchingAnyTags
     */
    public function testGetIdsMatchingAnyTags()
    {
        $this->assertEquals(array(), $this->_backend->getIdsMatchingAnyTags(array()));
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getFillingPercentage
     */
    public function testGetFillingPercentage()
    {
        $this->assertEquals(0, $this->_backend->GetFillingPercentage());
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getMetadatas
     */
    public function testGetMetadatas()
    {
        $this->assertFalse($this->_backend->getMetadatas('foo'));
    }

    /**
     * @covers Robo47_Cache_Backend_Array::touch
     */
    public function testTouch()
    {
        $this->assertFalse($this->_backend->touch('foo', 1234));
    }

    /**
     * @covers Robo47_Cache_Backend_Array::getCapabilities
     */
    public function testGetCapabilities()
    {
        $capabilities = array(
            'automatic_cleaning' => false,
            'tags' => false,
            'expired_read' => false,
            'priority' => false,
            'infinite_lifetime' => false,
            'get_list' => false
        );

        $this->assertEquals($capabilities, $this->_backend->getCapabilities());
    }
}