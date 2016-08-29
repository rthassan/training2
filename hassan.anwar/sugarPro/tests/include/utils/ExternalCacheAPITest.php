<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
 
require_once('include/SugarCache/SugarCache.php');

class ExternalCacheAPITest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        $this->_cacheKey1   = 'test cache key 1 '.date("YmdHis");
        $this->_cacheValue1 = 'test cache value 1'.date("YmdHis");
        $this->_cacheKey2   = 'test cache key 2 '.date("YmdHis");
        $this->_cacheValue2 = 'test cache value 2 '.date("YmdHis");
        $this->_cacheKey3   = 'test cache key 3 '.date("YmdHis");
        $this->_cacheValue3 = array(
            'test cache value 3 key 1 '.date("YmdHis") => 'test cache value 3 value 1 '.date("YmdHis"),
            'test cache value 3 key 2 '.date("YmdHis") => 'test cache value 3 value 2 '.date("YmdHis"),
            'test cache value 3 key 3 '.date("YmdHis") => 'test cache value 3 value 3 '.date("YmdHis"),
            );
    }

    public function tearDown() 
    {
       // clear out the test cache if we haven't already
       if ( sugar_cache_retrieve($this->_cacheKey1) )
           sugar_cache_clear($this->_cacheKey1);
       if ( sugar_cache_retrieve($this->_cacheKey2) )
           sugar_cache_clear($this->_cacheKey2);
       if ( sugar_cache_retrieve($this->_cacheKey3) )
           sugar_cache_clear($this->_cacheKey3);
       SugarCache::$isCacheReset = false;
    }

    public function testSugarCacheValidate()
    {
        $this->assertTrue(sugar_cache_validate());
    }
    
    public function testStoreAndRetrieve()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        sugar_cache_put($this->_cacheKey3,$this->_cacheValue3);
        $this->assertEquals(
            $this->_cacheValue1,
            sugar_cache_retrieve($this->_cacheKey1));
        $this->assertEquals(
            $this->_cacheValue2,
            sugar_cache_retrieve($this->_cacheKey2));
        $this->assertEquals(
            $this->_cacheValue3,
            sugar_cache_retrieve($this->_cacheKey3));
    }

    public function testStoreClearCacheKeyAndRetrieve()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        sugar_cache_clear($this->_cacheKey1);
        $this->assertNotEquals(
            $this->_cacheValue1,
            sugar_cache_retrieve($this->_cacheKey1));
        $this->assertEquals(
            $this->_cacheValue2,
            sugar_cache_retrieve($this->_cacheKey2));
    }
    
    public function testStoreResetCacheAndRetrieve()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2);
        sugar_cache_reset();
        $this->assertNotEquals(
            $this->_cacheValue1,
            sugar_cache_retrieve($this->_cacheKey1));
        $this->assertNotEquals(
            $this->_cacheValue2,
            sugar_cache_retrieve($this->_cacheKey2));
    }

    public function testStoreAndRetrieveWithTTL()
    {
        sugar_cache_put($this->_cacheKey1,$this->_cacheValue1, 100);
        sugar_cache_put($this->_cacheKey2,$this->_cacheValue2, 100);
        sugar_cache_put($this->_cacheKey3,$this->_cacheValue3,100);
        $this->assertEquals(
            $this->_cacheValue1,
            sugar_cache_retrieve($this->_cacheKey1));
        $this->assertEquals(
            $this->_cacheValue2,
            sugar_cache_retrieve($this->_cacheKey2));
        $this->assertEquals(
            $this->_cacheValue3,
            sugar_cache_retrieve($this->_cacheKey3));
    }

    public function testStoreAndRetrieveWithTTLZero()
    {
        $sc = SugarCache::instance();
        $cacheStub = $this->getMock(get_class($sc), array('_setExternal'));
        $cacheStub->expects($this->once())
                       ->method('_setExternal');
        $cacheStub->set($this->_cacheKey1,$this->_cacheValue1,0);
    }

    public function testStoreAndRetrieveWithTTLNull()
    {
        $sc = SugarCache::instance();
        $cacheStub = $this->getMock(get_class($sc), array('_setExternal'));
        $cacheStub->expects($this->once())
                       ->method('_setExternal');
        $cacheStub->set($this->_cacheKey1,$this->_cacheValue1,null);
    }


    /**
     * @ticket 40797
     */
    public function testRetrieveNonExistantKeyReturnsNull()
    {
        $this->assertNull(sugar_cache_retrieve('iamlookingforakeythatainthere'));
    }
}
