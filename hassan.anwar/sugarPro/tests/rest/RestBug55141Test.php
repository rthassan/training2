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


require_once('tests/rest/RestTestBase.php');

class RestBug55141Test extends RestTestBase {
    public function setUp()
    {
        parent::setUp();

        // Clear all caches for this test
        MetaDataManager::clearAPICache();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @group rest
     */
    public function testCache() {
        // Get the manager to clear the metadata
        $mm = MetaDataManager::getManager();
        
        // create metadata cache
        $data = $mm->getMetadata();

        // verify hash file exists
        $this->assertTrue(file_exists('cache/api/metadata/metadata_base_private.php'), "Didn't create the cache file");

        // run repair and rebuild and verify the cache file is gone
        $old_user = $GLOBALS['current_user'];
        $user = new User();
        $GLOBALS['current_user'] = $user->getSystemUser();

        $_REQUEST['repair_silent']=1;
        $rc = new RepairAndClear();
        $rc->clearAdditionalCaches();
        $GLOBALS['current_user'] = $old_user;
        
        // verify the cache file for this platform and visibility no longer exists
        $this->assertFileNotExists('cache/api/metadata/metadata_base_private.php', "Didn't really clear the cache");

    }
}
