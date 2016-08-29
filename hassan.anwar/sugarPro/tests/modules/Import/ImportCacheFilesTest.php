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
 
require_once 'modules/Import/ImportCacheFiles.php';

class ImportCacheFilesTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user');
        $this->importdir = ImportCacheFiles::getImportDir();
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }

    public function testgetDuplicateFileName()
    {
        $filename = ImportCacheFiles::getDuplicateFileName();

        $this->assertEquals(
            "{$this->importdir}/dupes_{$GLOBALS['current_user']->id}.csv", $filename);
    }

    public function testgetErrorFileName()
    {
        $filename = ImportCacheFiles::getErrorFileName();

        $this->assertEquals(
            "{$this->importdir}/error_{$GLOBALS['current_user']->id}.csv", $filename);
    }

    public function testgetStatusFileName()
    {
        $filename = ImportCacheFiles::getStatusFileName();

        $this->assertEquals(
            "{$this->importdir}/status_{$GLOBALS['current_user']->id}.csv", $filename);
    }

    public function testclearCacheFiles()
    {
        // make sure there is a file in there
        file_put_contents(ImportCacheFiles::getStatusFileName(),'foo');

        ImportCacheFiles::clearCacheFiles();

        $this->assertFalse(is_file(ImportCacheFiles::getStatusFileName()));
    }
}
