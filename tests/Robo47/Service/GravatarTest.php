<?php

require_once dirname(__FILE__ ) . '/../../TestHelper.php';

/**
 * @group Robo47_Service
 * @group Robo47_Service_Gravatar
 */
class Robo47_Service_GravatarTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Zend_Http_Client_Adapter_Test
     */
    protected $_adapter = null;

    public function setUp()
    {
        $this->_adapter = new Zend_Http_Client_Adapter_Test();
        $client = new Zend_Http_Client(null, array(
                'adapter' => $this->_adapter
        ));

        Zend_Service_Abstract::setHttpClient($client);
    }

    /**
     * @covers Robo47_Service_Gravatar
     */
    public function testConstruct()
    {
        $service = new Robo47_Service_Gravatar();
        $this->assertEquals('', $service->getDefault(), 'Default default is wrong');
        $this->assertEquals(Robo47_Service_Gravatar::RATING_G, $service->getRating(), 'Default rating is wrong');
        $this->assertEquals(80, $service->getSize(), 'Default size is wrong');
        $this->assertEquals('gravatar_', $service->getCachePrefix(), 'Default rating is wrong');
        $this->assertEquals(null, $service->getCache(), 'Default rating is wrong');
    }

    /**
     * @covers Robo47_Service_Gravatar::__construct
     */
    public function testConstructWithOptions()
    {
        $options = array(
            'default' => '404',
            'rating' => Robo47_Service_Gravatar::RATING_X,
            'size' => 200,
            'cachePrefix' => 'foo_',
            'cache' => Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array()),
        );
        $service = new Robo47_Service_Gravatar($options);

        $this->assertEquals($options['default'], $service->getDefault(), 'default is wrong');
        $this->assertEquals($options['rating'], $service->getRating(), 'rating is wrong');
        $this->assertEquals($options['size'], $service->getSize(), 'size is wrong');
        $this->assertEquals($options['cachePrefix'], $service->getCachePrefix(), 'cachePrefix is wrong');
        $this->assertSame($options['cache'], $service->getCache(), 'cache is wrong');
    }

    /**
     * @covers Robo47_Service_Gravatar::setOptions
     */
    public function testSetOptions()
    {
        $options = array(
            'default' => '404',
            'rating' => Robo47_Service_Gravatar::RATING_X,
            'size' => 200,
            'cachePrefix' => 'foo_',
            'useSSL' => true,
            'cache' => Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array()),
        );
        $service = new Robo47_Service_Gravatar();
        $return = $service->setOptions($options);
        $this->assertSame($return, $service, 'Fluent Interface does not work');

        $this->assertEquals($options['default'], $service->getDefault(), 'default is wrong');
        $this->assertEquals($options['rating'], $service->getRating(), 'rating is wrong');
        $this->assertEquals($options['size'], $service->getSize(), 'size is wrong');
        $this->assertEquals($options['cachePrefix'], $service->getCachePrefix(), 'cachePrefix is wrong');
        $this->assertSame($options['useSSL'], $service->usesSSL(), 'ssl is wrong');
        $this->assertSame($options['cache'], $service->getCache(), 'cache is wrong');

        $options = array(
            'default' => 'monsterid',
            'rating' => Robo47_Service_Gravatar::RATING_PG,
            'size' => 123,
            'cachePrefix' => '',
            'useSSL' => false,
            'cache' => null
        );

        $service = new Robo47_Service_Gravatar();
        $return = $service->setOptions($options);
        $this->assertSame($return, $service, 'Fluent Interface does not work');

        $this->assertEquals($options['default'], $service->getDefault(), 'default is wrong');
        $this->assertEquals($options['rating'], $service->getRating(), 'rating is wrong');
        $this->assertEquals($options['size'], $service->getSize(), 'size is wrong');
        $this->assertEquals($options['cachePrefix'], $service->getCachePrefix(), 'cachePrefix is wrong');
        $this->assertSame($options['useSSL'], $service->usesSSL(), 'ssl is wrong');
        $this->assertSame($options['cache'], $service->getCache(), 'cache is wrong');
    }

    /**
     * @covers Robo47_Service_Gravatar::setOptions
     */
    public function testSetOptionsWithUnknownOption()
    {
        // expect no excepton thrown, no error or something like that
        $options = array(
            'foo' => 'baa',
        );
        $service = new Robo47_Service_Gravatar();
        $service->setOptions($options);
    }

    /**
     * @covers Robo47_Service_Gravatar::setDefault
     * @covers Robo47_Service_Gravatar::getDefault
     */
    public function testSetDefaultGetDefault()
    {
        $service = new Robo47_Service_Gravatar();
        $return = $service->setDefault('foo');
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertEquals('foo', $service->getDefault(), 'Setting default failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setRating
     * @covers Robo47_Service_Gravatar::getRating
     */
    public function testSetRatingGetRating()
    {
        $service = new Robo47_Service_Gravatar();
        $return = $service->setRating(Robo47_Service_Gravatar::RATING_X);
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertEquals(Robo47_Service_Gravatar::RATING_X, $service->getRating(), 'Setting rating failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setSize
     * @covers Robo47_Service_Gravatar::getSize
     */
    public function testSetSizeGetSize()
    {
        $service = new Robo47_Service_Gravatar();
        $return = $service->setSize(100);
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertEquals(100, $service->getSize(), 'Setting rating failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setSize
     * @covers Robo47_Service_Gravatar::getSize
     */
    public function testSetSizeGetSizeWithString()
    {
        $service = new Robo47_Service_Gravatar();
        $service->setSize('100');
        $this->assertEquals(100, $service->getSize(), 'Setting rating failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setCachePrefix
     * @covers Robo47_Service_Gravatar::getCachePrefix
     */
    public function testSetCachePrefixGetCachePrefix()
    {
        $service = new Robo47_Service_Gravatar();
        $return = $service->setCachePrefix('prefix_');
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertEquals('prefix_', $service->getCachePrefix(), 'Setting cachePrefix failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setCache
     * @covers Robo47_Service_Gravatar::getCache
     */
    public function testSetCacheGetCache()
    {
        $cache = Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array());
        $service = new Robo47_Service_Gravatar();
        $return = $service->setcache($cache);
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertSame($cache, $service->getCache(), 'Setting cache failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setCache
     * @covers Robo47_Service_Gravatar::_cacheFromRegistry
     */
    public function testSetCacheFromRegistry()
    {
        $cache = Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array());
        Zend_Registry::set('My_Cache', $cache);
        $service = new Robo47_Service_Gravatar();
        $service->setCache('My_Cache');
        $this->assertSame($cache, $service->getCache(), 'Setting cache from registry failed');
    }

    /**
     * @covers Robo47_Service_Gravatar::setCache
     * @covers Robo47_Service_Gravatar::_cacheFromRegistry
     * @covers Robo47_Service_Gravatar_Exception
     */
    public function testSetCacheFromRegistryWithoutCacheInRegistry()
    {
        $service = new Robo47_Service_Gravatar();
        try {
            $service->setCache('MyCache');
            $this->fail('No exception thrown on setting cache via registry without existing in Registry');
        } catch (Robo47_Service_Gravatar_Exception $e) {
            $this->assertEquals('Registry key "MyCache" for Cache is not registered.', $e->getMessage(), 'Wrong Exception Message');
        }
    }

    /**
     * @covers Robo47_Service_Gravatar::setCache
     * @covers Robo47_Service_Gravatar::_cacheFromRegistry
     */
    public function testSetCacheNull()
    {
        $service = new Robo47_Service_Gravatar();
        $service->setCache(null);
        $this->assertNull($service->getCache(), 'Setting cache null did not work');
    }

    /**
     * @covers Robo47_Service_Gravatar::setCache
     */
    public function testSetInvalidCache()
    {
        $service = new Robo47_Service_Gravatar();
        try {
            $service->setCache(new stdClass());
            $this->fail('no exception thrown setRating() with invalid rating');
        } catch (Robo47_Service_Gravatar_Exception $e) {
            $this->assertEquals('cache is not instance of Zend_Cache_Core', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers Robo47_Service_Gravatar::setRating
     */
    public function testSetInvalidRating()
    {
        $service = new Robo47_Service_Gravatar();
        try {
            $service->setRating('a');
            $this->fail('no exception thrown setRating() with invalid rating');
        } catch (Robo47_Service_Gravatar_Exception $e) {
            $this->assertEquals('Invalid rating: a', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers Robo47_Service_Gravatar::setSize
     */
    public function testSetTooBigSize()
    {
        $service = new Robo47_Service_Gravatar();
        try {
            $service->setSize(513);
            $this->fail('no exception thrown setSize() with too big value');
        } catch (Robo47_Service_Gravatar_Exception $e) {
            $this->assertEquals('size is greater than 512', $e->getMessage(), 'Wrong exception message');
        }
    }

    /**
     * @covers Robo47_Service_Gravatar::setSize
     */
    public function testSetTooSmallSize()
    {
        $service = new Robo47_Service_Gravatar();
        try {
            $service->setSize(0);
            $this->fail('no exception thrown setSize() with too small value');
        } catch (Robo47_Service_Gravatar_Exception $e) {
            $this->assertEquals('size is smaller than 1', $e->getMessage(), 'Wrong exception message');
        }
    }

    public function usesSSLProvider()
    {
        $data = array();

        $data[] = array(0, false);
        $data[] = array(1, true);

        $data[] = array(false, false);
        $data[] = array(true, true);

        return $data;
    }

    /**
     *
     * @covers Robo47_Service_Gravatar::useSSL
     * @covers Robo47_Service_Gravatar::usesSSL
     * @dataProvider usesSSLProvider
     */
    public function testUseSSLUsesSSL($flag, $expected)
    {
        $service = new Robo47_Service_Gravatar();
        $return = $service->useSSL($flag);
        $this->assertSame($return, $service, 'Fluent Interface failed');
        $this->assertSame($expected, $service->usesSSL());
    }

    public function cacheIdProvider()
    {
        $data = array();

        $options = array(
            'cachePrefix' => 'prefix_',
            'rating' => Robo47_Service_Gravatar::RATING_PG,
            'size' => 234,
            'default' => 'default',
        );

        $data[] = array(
            $options,
            'foo@example.com',  // email
            'prefix_',          // prefix
            Robo47_Service_Gravatar::RATING_X,                // rating
            100,                // size
            'foo'               // $default
        );

        $data[] = array(
            $options,
            'baa@example.com',  // email
            '',                 // prefix
            Robo47_Service_Gravatar::RATING_G,                // rating
            200,                // size
            null                // $default
        );

        $data[] = array(
            $options,
            'foo@example.com',  // email
            'null',               // prefix
            Robo47_Service_Gravatar::RATING_X,                // rating
            100,                // size
            'foo'               // $default
        );

        $data[] = array(
            $options,
            'foo@example.com',  // email
            null,               // prefix
            null,               // rating
            null,               // size
            null                // $default
        );

        foreach ($data as $key => $value) {
            $data[$key][6] = $this->_cacheId($value);
        }
        return $data;
    }

    protected function _cacheId($array)
    {
        $email = $array[1];
        $prefix = $array[2];
        if (null === $prefix) {
            $prefix = 'prefix_';
        }
        $rating = $array[3];
        if (null === $rating) {
            $rating = Robo47_Service_Gravatar::RATING_PG;
        }
        $size = $array[4];
        if (null === $size) {
            $size = 234;
        }
        $default = $array[5];
        if (null === $default) {
            $default = 'default';
        }

        $id = $prefix;
        $id .= md5($email);
        $id .= '_' . $rating;
        $id .= '_' . $size;
        $id .= '_' . md5($default);
        return $id;
    }

    /**
     * @covers Robo47_Service_Gravatar::getCacheId
     * @covers Robo47_Service_Gravatar::_filterCachePrefix
     * @covers Robo47_Service_Gravatar::_filterSize
     * @covers Robo47_Service_Gravatar::_filterRating
     * @covers Robo47_Service_Gravatar::_filterDefault
     * @dataProvider cacheIdProvider
     */
    public function testGetCacheId($options, $email, $prefix, $rating, $size, $default, $expectedId)
    {
        $service = new Robo47_Service_Gravatar($options);
        $cacheId = $service->getCacheId($email, $prefix, $rating, $size, $default);
        $this->assertEquals($expectedId, $cacheId);
    }

    public function gravatarExistsProvider()
    {
        $data = array();

        $data[] = array (
            new Zend_Http_Response(404, array()),
            false
        );

        $data[] = array (
            new Zend_Http_Response(301, array()),
            false
        );

        $data[] = array (
            new Zend_Http_Response(200, array()),
            true
        );

        return $data;
    }

    /**
     * @covers Robo47_Service_Gravatar::gravatarExists
     * @dataProvider gravatarExistsProvider
     */
    public function testGravatarExists($response, $result)
    {
        $service = new Robo47_Service_Gravatar();
        $this->_adapter->setResponse($response);
        $this->assertSame($result, $service->gravatarExists('foo@example.com'));
    }

    /**
     * @covers Robo47_Service_Gravatar::gravatarExists
     * @covers Robo47_Service_Gravatar::_getCacheId
     * @dataProvider gravatarExistsProvider
     */
    public function testGravatarExistsWithCache()
    {
        $cache = Zend_Cache::factory('Core', new Robo47_Cache_Backend_Array(), array('automatic_cleaning_factor' => 0), array('automatic_cleaning_factor' => 0));
        $options = array(
            'cachePrefix' => 'foo_',
            'cache' => $cache,
        );
        $service = new Robo47_Service_Gravatar($options);

        // do first request
        $this->_adapter->setResponse(new Zend_Http_Response(404, array()));
        $this->assertSame(false, $service->gravatarExists('foo@example.com'));
        $cacheEntry = $cache->load($service->getCacheId('foo@example.com'));
        $this->assertEquals('false', $cacheEntry, 'wrong value in cache');

        // assert cache is used and no request is made
        $this->_adapter->setResponse(new Zend_Http_Response(200, array()));
        $this->assertSame(false, $service->gravatarExists('foo@example.com'));
        $cacheEntry = $cache->load($service->getCacheId('foo@example.com'));
        $this->assertEquals('false', $cacheEntry, 'wrong value in cache');

        // ignore cache for checking
        $this->_adapter->setResponse(new Zend_Http_Response(200, array()));
        $this->assertSame(true, $service->gravatarExists('foo@example.com', false));
        $cacheEntry = $cache->load($service->getCacheId('foo@example.com'));
        $this->assertEquals('true', $cacheEntry, 'wrong value in cache');

        // use cache for checking
        $this->_adapter->setResponse(new Zend_Http_Response(404, array()));
        $this->assertSame(true, $service->gravatarExists('foo@example.com'));
        $cacheEntry = $cache->load($service->getCacheId('foo@example.com'));
        $this->assertEquals('true', $cacheEntry, 'wrong value in cache');

        // ignore cache again for checking
        $this->_adapter->setResponse(new Zend_Http_Response(404, array()));
        $this->assertSame(false, $service->gravatarExists('foo@example.com', false));
        $cacheEntry = $cache->load($service->getCacheId('foo@example.com'));
        $this->assertEquals('false', $cacheEntry, 'wrong value in cache');
    }

    /**
     * @covers Robo47_Service_Gravatar::getUri
     * @covers Robo47_Service_Gravatar::_getUri
     * @covers Robo47_Service_Gravatar::_filterSsl
     * @covers Robo47_Service_Gravatar::_filterSize
     * @covers Robo47_Service_Gravatar::_filterRating
     * @covers Robo47_Service_Gravatar::_filterDefault
     */
    public function testGetUriWithSSL()
    {
        $options = array(
            'default' => 'http://www.example.com/foo.jpg',
            'rating' => Robo47_Service_Gravatar::RATING_X,
            'size' => 200,
        );
        $service = new Robo47_Service_Gravatar($options);

        $uri = $service->getUri('foo@example.com', null, null, null, true, '&');

        $parts = parse_url($uri);

        $this->assertEquals('https', $parts['scheme'], 'wrong scheme');
        $this->assertEquals('secure.gravatar.com', $parts['host'], 'wrong host');
        $this->assertEquals('/avatar/' . md5('foo@example.com'), $parts['path'], 'wrong path');

        $queryParts = array();
        parse_str($parts['query'], $queryParts);

        $this->assertArrayHasKey('r', $queryParts, 'Query misses variable for rating (r)');
        $this->assertArrayHasKey('d', $queryParts, 'Query misses variable for default (d)');
        $this->assertArrayHasKey('s', $queryParts, 'Query misses variable for size (s)');

        $this->assertEquals($queryParts['r'], 'x', 'variable for rating has wrong value');
        $this->assertEquals($queryParts['s'], '200', 'variable for size has wrong value');
        $this->assertEquals($queryParts['d'], urldecode($options['default']), 'variable for default has wrong value');
    }

    /**
     * @covers Robo47_Service_Gravatar::getUri
     * @covers Robo47_Service_Gravatar::_getUri
     * @covers Robo47_Service_Gravatar::_filterSsl
     * @covers Robo47_Service_Gravatar::_filterSize
     * @covers Robo47_Service_Gravatar::_filterRating
     * @covers Robo47_Service_Gravatar::_filterDefault
     */
    public function testGetUriWithoutSSL()
    {
        $options = array(
            'default' => 'http://www.example.com/foo.jpg',
            'rating' => Robo47_Service_Gravatar::RATING_X,
            'size' => 200,
        );
        $service = new Robo47_Service_Gravatar($options);

        $uri = $service->getUri('foo@example.com', null, null, null, null, '&');

        $parts = parse_url($uri);

        $this->assertEquals('http', $parts['scheme'], 'wrong scheme');
        $this->assertEquals('www.gravatar.com', $parts['host'], 'wrong host');
        $this->assertEquals('/avatar/' . md5('foo@example.com'), $parts['path'], 'wrong path');

        $queryParts = array();
        parse_str($parts['query'], $queryParts);

        $this->assertArrayHasKey('r', $queryParts, 'Query misses variable for rating (r)');
        $this->assertArrayHasKey('d', $queryParts, 'Query misses variable for default (d)');
        $this->assertArrayHasKey('s', $queryParts, 'Query misses variable for size (s)');

        $this->assertEquals($queryParts['r'], 'x', 'variable for rating has wrong value');
        $this->assertEquals($queryParts['s'], '200', 'variable for size has wrong value');
        $this->assertEquals($queryParts['d'], urldecode($options['default']), 'variable for default has wrong value');
    }

    public function gravatarHashProvider()
    {
        $data = array();

        $data[] = array('Foo@example.com');
        $data[] = array('foo@example.com');
        $data[] = array('bAa@example.com');
        return $data;
    }

    /**
     *
     * @dataProvider gravatarHashProvider
     */
    public function testGetGravatarHash($email)
    {
        $service = new Robo47_Service_Gravatar();
        $this->assertEquals(md5(trim(strtolower($email))), $service->getGravatarHash($email));
    }
}