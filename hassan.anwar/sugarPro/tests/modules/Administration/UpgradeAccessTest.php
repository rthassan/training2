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

/**
 * UpgradeAccessTest.php
 *
 * This file tests the code run when UpgradeAccess.php is invoked.
 */

class UpgradeAccessTest extends Sugar_PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        if(!file_exists('.htaccess'))
        {
            $this->markTestSkipped('This may be an instance that does not support the use of .htaccess files');
            return;
        }

        if(!is_writable('.htaccess'))
        {
            $this->markTestSkipped('Cannot write to .htaccess file.');
            return;
        }

        SugarTestHelper::setUp('files');
        SugarTestHelper::setUp('mod_strings', array('Administration'));

        SugarTestHelper::saveFile(".htaccess");
    }


    public function tearDown()
    {
        SugarTestHelper::tearDown();
        parent::tearDown();
    }


    /**
     * This function tests to see the UpgradeAccess file correctly builds the .htaccess file when run.
     * In particular, the mod rewrite rule for rest URLs should be created.
     * @bug 56889
     */
    public function testUpgradeAccessCreatesRewriteRule()
    {
        require('modules/Administration/UpgradeAccess.php');
        $contents = file_get_contents('.htaccess');

        preg_match('/RewriteRule \^rest\/\(\.\*\)\$ api\/rest.php\?\_\_sugar\_url=\$1 \[L\,QSA\]/', $contents, $matches);
        $this->assertNotEmpty($matches, 'Could not find RewriteRule');
        $this->assertEquals(1, count($matches), 'Duplicate blocks were created for the RewriteRule');
        $this->assertContains('<FilesMatch', $contents, 'Code outside of restrictions was not copied over');
    }

}
