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

require_once 'modules/ModuleBuilder/MB/MBModule.php';

class Bug38281Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $tmp_dir;
    private $tmp_file;

    public function setUp()
    {
        $this->tmp_dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bug32821';
        mkdir($this->tmp_dir);
        $this->tmp_file = tempnam($this->tmp_dir, 'bug32821');
        file_put_contents($this->tmp_file, '$dashletData[\'oldnameDashlet\'][\'searchFields\']');
    }

    public function tearDown()
    {
        unlink($this->tmp_file);
        rmdir($this->tmp_dir);
    }

    /**
     * @group bug38281
     */
    public function testRenameMetaData()
    {
        $mbModule = new MBModule('newname', $this->tmp_dir, 'test', 'test');
        $mbModule->renameMetaData($this->tmp_dir, 'oldname');
        $replacedContents = file_get_contents($this->tmp_file);
        $this->assertEquals('$dashletData[\'test_newnameDashlet\'][\'searchFields\']', $replacedContents, 'Module name replaced correctly in dashlet metadata');
    }
}