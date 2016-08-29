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

require_once 'include/MetaDataManager/MetaDataManager.php';
require_once 'modules/Administration/QuickRepairAndRebuild.php';

/**
 * Tests metadata manager caching and refreshing. This will be a somewhat slow
 * test as there will be significant file I/O due to nuking and rewriting cache
 * files.
 */
class MetaDataManagerCacheRefreshTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * The build number from sugar_config. Saved here for use in testing as it 
     * will be changed
     * @var string
     */
    protected $buildNumber;
    
    /**
     * Test files for used in testing of pickup of new files during refresh
     * 
     * @var string
     */
    protected $accountsFile = 'modules/Accounts/clients/mobile/views/herfy/herfy.php';
    protected $casesFile = 'modules/Cases/clients/mobile/views/fandy/fandy.php';
    
    public function setUp()
    {
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('current_user', array(true, false));
        
        // Back up the build number from config to check changes in metadata in
        // refresh tests
        $this->buildNumber = isset($GLOBALS['sugar_build']) ? $GLOBALS['sugar_build'] : null;

        //Don't cache now() or else we can't verify updates
        TimeDate::getInstance()->allow_cache = false;

        // Ensure we are starting clean
        MetaDataManager::clearAPICache();
    }
    
    public function tearDown()
    {
        // Reset build number
        if ($this->buildNumber) {
            $GLOBALS['sugar_build'] = $this->buildNumber;
        }

        TimeDate::getInstance()->allow_cache = true;
        
        SugarTestHelper::tearDown();
        
        // Clean up test files
        $c = 0;
        foreach (array($this->accountsFile, $this->casesFile) as $file) {
            $save = $c > 0;
            if (file_exists($file)) {
                unlink($file);
                rmdir(dirname($file));
                SugarAutoLoader::delFromMap($file, $save);
            }
            $c++;
        }
    }
    
    public static function tearDownAfterClass()
    {
        // After all is said and done, reset our caches to the beginning
        //MetaDataManager::clearAPICache();
    }

    /**
     * Tests the metadatamanager getManager method gets the right manager
     *
     * @group MetaDataManager
     * @dataProvider managerTypeProvider
     * @param string $platform
     * @param string $manager
     */
    public function testFactoryReturnsProperManager($platform, $manager)
    {
        $mm = MetaDataManager::getManager($platform);
        $this->assertInstanceOf($manager, $mm, "MetaDataManager for $platform was not an instance of $manager");
    }

    /**
     * Tests delete and rebuild of cache files
     *
     * @group MetaDataManager
     */
    public function testRefreshCacheCreatesNewCacheEntries()
    {
        $db = DBManagerFactory::getInstance();

        // Start by wiping out everything
        TestMetaDataManager::clearAPICache();
        $this->assertEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='meta_hash_public_base'"));
        $this->assertEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='meta_hash_base'"));

        // Refresh the cache and ensure that there are file in place
        TestMetaDataManager::refreshCache(array('base'), true);
        $this->assertNotEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='meta_hash_public_base'"));
        $this->assertNotEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='meta_hash_base'"));
    }

    /**
     * Tests that the cache files for a platform were refreshed
     *
     * @group MetaDataManager
     * @dataProvider platformProvider
     * @param string $platform
     */
    public function testRefreshCacheCreatesNewCacheEntriesForPlatform($platform)
    {
        $db = DBManagerFactory::getInstance();

        // Get the private metadata manager for $platform
        $mm = MetaDataManager::getManager($platform);

        // Get the current metadata to ensure there is a cache built
        $mm->getMetadata();

        $key = "meta_hash_{$platform}";
        if ($platform != "base") {
            $key .= "_base";
        }

        $date = $db->getOne("SELECT date_modified FROM metadata_cache WHERE type='$key'");
        $this->assertNotEmpty($date);
        $dateModified = TimeDate::getInstance()->fromDb(
            $db->fromConvert($date, 'datetime')
        );

        //Wait to ensure timestamp inscreases
        sleep(1);

        // This will wipe out and rebuild the private metadata cache for $platform
        $mm->rebuildCache();

        // Test the file first
        $date = $db->getOne("SELECT date_modified FROM metadata_cache WHERE type='$key'");
        $this->assertNotEmpty($date);
        $newDateModified = TimeDate::getInstance()->fromDb(
            $db->fromConvert($date, 'datetime')
        );

        // Test the time on the new file
        $this->assertGreaterThan(
            $dateModified->getTimestamp(),
            $newDateModified->getTimestamp(),
            "Second cache file make time is not greater than the first."
        );
    }

    /**
     * Essentially the same test as directly hitting metadata manager, except
     * this tests Quick Repairs access to it.
     *
     * @group MetaDataManager
     * @dataProvider visibilityFlags
     */
    public function testQuickRepairRefreshesCache($public)
    {
        $db = DBManagerFactory::getInstance();

        $key = $public ? "meta_hash_public_base" : "meta_hash_base";
        // Get the metadata manager for use in this test
        $mm = MetaDataManager::getManager(array('base'), $public);

        // Wipe out the cache
        $repair = new RepairAndClear();
        $repair->clearMetadataAPICache();
        $this->assertEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='$key'"));

        // Build the cache now to ensure we have a cache file
        $mm->getMetadata();
        $this->assertNotEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='$key'"),
            "Could not load the metadata cache for $key after load"
        );


        // Refresh the cache and ensure that there are file in place
        $repair->repairMetadataAPICache();
        $this->assertNotEmpty($db->getOne("SELECT id FROM metadata_cache WHERE type='$key'"),
            "Could not load the metadata cache for $key after repair"
        );
    }

    /**
     * Tests that a section of metadata was updated
     *
     * @group MetaDataManager
     */
    public function testSectionCacheRefreshes()
    {
        $mmPri = MetaDataManager::getManager('base');

        // Get our private and public metadata
        $mdPri = $mmPri->getMetadata();

        // Change the build number to ensure that server info gets changed
        $GLOBALS['sugar_build'] = 'TESTBUILDXXX';
        MetaDataManager::refreshSectionCache(MetaDataManager::MM_SERVERINFO, array('base'));

        // Get the newest metadata, which should be different
        $dataPri = $mmPri->getMetadata();

        $this->assertNotEmpty($mdPri['server_info'], "Server info from the initial fetch is empty");
        $this->assertNotEmpty($dataPri['server_info'], "Server info from the second fetch is empty");
        $this->assertNotEquals($mdPri['server_info'], $dataPri['server_info'], "First and second metadata server_info sections are the same");
    }
    /**
     * Tests module data refreshing
     *
     * @group MetaDataManager
     */
    public function testSectionModuleCacheRefreshes()
    {
        $mm = MetaDataManager::getManager('mobile');

        // Get our private and public metadata
        $md = $mm->getMetadata();

        // Add two things: a new view to Accounts and a new View to Cases. Test
        // that the Accounts view got picked up and that the Notes view didn't.
        sugar_mkdir(dirname($this->accountsFile));
        sugar_mkdir(dirname($this->casesFile));

        $casesFile = '<?php
$viewdefs[\'Cases\'][\'mobile\'][\'view\'][\'fandy\'] = array(\'test\' => \'test this\');';

        $AccountsFile = '<?php
$viewdefs[\'Accounts\'][\'mobile\'][\'view\'][\'herfy\'] = array(\'test\' => \'test this\');';
        sugar_file_put_contents($this->casesFile, $casesFile);
        sugar_file_put_contents($this->accountsFile, $AccountsFile);
        SugarAutoLoader::addToMap($this->casesFile, false);
        SugarAutoLoader::addToMap($this->accountsFile); // Only save the file map cache on the second add

        // Refresh the modules cache
        MetaDataManager::refreshModulesCache(array('Accounts'), array('mobile'));

        // Get the newest metadata, which should be different
        $data = $mm->getMetadata();

        // Basic assertions
        $this->assertNotEmpty($md['modules']['Accounts'], "Accounts module data from the initial fetch is empty");
        $this->assertNotEmpty($data['modules']['Accounts'], "Accounts module data the second fetch is empty");

        // Assertions of state prior to refresh
        $this->assertArrayNotHasKey('herfy', $md['modules']['Accounts']['views'], "The test view was found in the original Accounts metadata.");
        $this->assertArrayNotHasKey('fandy', $md['modules']['Cases']['views'], "The test view was found in the original Cases metadata.");

        // Assertions of state after refresh. Mobile will cull certain elements from metadata
        $this->assertEquals($md['modules']['Accounts']['views'], $data['modules']['Accounts']['views'], "First and second metadata Accounts module sections are not the same");
        $this->assertEquals($md['modules']['Cases']['views'], $data['modules']['Cases']['views'], "First and second metadata Cases module sections are different");
        $this->assertFalse(isset($data['modules']['Accounts']['views']['herfy']), "The test view was found in the refreshed Accounts metadata.");
        $this->assertArrayNotHasKey('fandy', $md['modules']['Cases']['views'], "The test view was found in the refreshed Cases metadata.");
    }
    public function managerTypeProvider()
    {
        return array(
            array('platform' => 'portal', 'manager' => 'MetaDataManagerPortal'),
            array('platform' => 'mobile', 'manager' => 'MetaDataManagerMobile'),
            array('platform' => 'base', 'manager' => 'MetaDataManager'),
        );
    }

    public function platformProvider()
    {
        return array(
            array('platform' => 'portal'),
            array('platform' => 'mobile'),
            array('platform' => 'base'),
        );
    }

    public function visibilityFlags()
    {
        return array(
            array('public' => true),
            array('public' => false),
        );
    }
}


/**
 * Class TestMetaDataManager
 * Test version that ignores per-user metadata contexts
 */
class TestMetaDataManager extends MetaDataManager
{
    /**
     * @param bool $public
     *
     * For test purposes, always return the public contexts. Role contexts will be tested elsewhere
     * @return MetaDataContextInterface[]
     */
    protected static function getAllMetadataContexts($public)
    {
        return parent::getAllMetadataContexts(true);
    }
}