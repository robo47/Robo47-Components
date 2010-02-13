<?php

require_once dirname(__FILE__) . '/../../TestHelper.php';


class Robo47_Cache_DoctrineAdapterTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        Zend_Registry::_unsetInstance();
        $this->getCache()->clean();
    }

    public function tearDown()
    {
        Zend_Registry::_unsetInstance();
        $this->getCache()->clean();
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return Zend_Cache::factory(
                'Core',
                'Sqlite',
                array('automatic_serialization' => true, 'lifetime' => 3600),
                array('cache_db_complete_path' => ':memory:')
        );

    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter<extended>
     * @covers Robo47_Cache_DoctrineAdapter::__construct
     */
    public function testConstructor()
    {
        $cache = $this->getCache();

        $prefix = 'somePrefix_';

        $adapter = new Robo47_Cache_DoctrineAdapter($cache, $prefix);

        $this->assertSame($cache, $adapter->getCache());

        $this->assertEquals($prefix, $adapter->getPrefix());
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::getPrefix
     * @covers Robo47_Cache_DoctrineAdapter::setPrefix
     */
    public function testGetPrefixSetPrefix()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $prefix = 'somePrefix_';
        $return = $adapter->setPrefix($prefix);
        $this->assertSame($adapter, $return, 'No Fluent Interface');
        $this->assertEquals($prefix, $adapter->getPrefix(), 'Wrong prefix');
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::setTags
     * @covers Robo47_Cache_DoctrineAdapter::getTags
     */
    public function testGetTagsSetTags()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $tags = array('foo', 'baa');
        $return = $adapter->setTags($tags);
        $this->assertSame($adapter, $return, 'No Fluent Interface');
        $this->assertEquals($tags, $adapter->getTags(), 'Wrong tags');
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::getCache
     * @covers Robo47_Cache_DoctrineAdapter::setCache
     */
    public function testGetCacheSetCache()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $cache = Zend_Cache::factory('Core',
                                     new Robo47_Cache_Backend_Array());

        $return = $adapter->setCache($cache);
        $this->assertSame($adapter, $return, 'No Fluent Interface');
        $this->assertSame($cache, $adapter->getCache());
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::setCache
     * @covers Robo47_Cache_DoctrineAdapter::_cacheFromRegistry
     */
    public function testSetCacheFromRegistry()
    {
        $cache = $this->getCache();
        Zend_Registry::set('cache', $cache);
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $this->assertNotSame($cache, $adapter->getCache(), 'Wrong cache');
        $adapter->setCache($cache);
        $this->assertSame($cache, $adapter->getCache(), 'Wrong Cache');
    }

    /**
	 * @covers Robo47_Cache_DoctrineAdapter::setCache
     * @covers Robo47_Cache_DoctrineAdapter::_cacheFromRegistry
     * @covers Robo47_Cache_Exception
     */
    public function testSetCacheFromRegistryWithoutExistingCacheInRegistry()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        try {
            $adapter->setCache('foo');
            $this->fail('No Exception thrown');
        } catch(Robo47_Cache_Exception $e) {
            $this->assertEquals('Registry key "foo" for Cache is not registered.', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
	 * @covers Robo47_Cache_DoctrineAdapter::setCache
     * @covers Robo47_Cache_DoctrineAdapter::_cacheFromRegistry
     * @covers Robo47_Cache_Exception
     */
    public function testSetCacheFromRegistryWithWrongObjectInRegistry()
    {
        Zend_Registry::set('foo', new stdClass());
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        try {
            $adapter->setCache('foo');
            $this->fail('No Exception thrown');
        } catch(Robo47_Cache_Exception $e) {
            $this->assertEquals('Cache is not instance of Zend_Cache_Core', $e->getMessage(), 'Wrong Exception message');
        }
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::_doSave
     */
    public function testSave()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $adapter->save('someId', 'someData');
        $data = $adapter->getCache()->load('prefix_someId');
        $this->assertEquals('someData', $data);
    }


    /**
     * @covers Robo47_Cache_DoctrineAdapter::_doSave
     */
    public function testSaveUsesTags()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $adapter->setTags(array('baa', 'foo'));
        $adapter->save('someId', 'someData');
        $data = $adapter->getCache()->load('prefix_someId');
        $this->assertEquals('someData', $data);

        $tagBaa = $adapter->getCache()->getIdsMatchingTags(array('baa'));
        $this->assertContains('prefix_someId', $tagBaa, 'CacheId not found via Tag');

        $tagFoo = $adapter->getCache()->getIdsMatchingTags(array('foo'));
        $this->assertContains('prefix_someId', $tagFoo, 'CacheId not found via Tag');
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::_doContains
     */
    public function testContains()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $this->assertEquals(false, (bool)$adapter->contains('foo'));
        $adapter->save('foo', 'someData');
        // return-values can be integers, so test for equals true  + cast
        $this->assertNotEquals(false, $adapter->contains('foo'));
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::_doFetch
     */
    public function testFetch()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $adapter->save('foo', 'someData');
        $this->assertEquals('someData', $adapter->fetch('foo'), 'Wrong data in cache');
    }

    /**
     * @covers Robo47_Cache_DoctrineAdapter::_doDelete
     */
    public function testDelete()
    {
        $adapter = new Robo47_Cache_DoctrineAdapter($this->getCache(), 'prefix_');
        $adapter->save('foo', 'someData');

        $this->assertNotEquals(false, $adapter->contains('foo'));
        $adapter->delete('foo');

        $this->assertEquals(false, $adapter->contains('foo'));
    }
}