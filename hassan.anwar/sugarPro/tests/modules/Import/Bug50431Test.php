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


require_once('modules/Import/views/view.step3.php');

/**
 * Bug50431Test.php
 *
 * This file tests the getMappingClassName function in modules/Import/views/view.step3.php
 *
 */
class Bug50431Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $customMappingFile = 'custom/modules/Import/maps/ImportMapCustomTestImportToken.php';
    private $customMappingFile2 = 'custom/modules/Import/maps/ImportMapTestImportToken.php';
    private $customMappingFile3 = 'custom/modules/Import/maps/ImportMapOther.php';
    private $outOfBoxTestFile = 'modules/Import/maps/ImportMapTestImportToken.php';
    private $source = 'TestImportToken';

    public function setUp()
    {
        if (!is_dir('custom/modules/Import/maps'))
        {
            mkdir_recursive('custom/modules/Import/maps');
        }

        file_put_contents($this->customMappingFile, '<?php class ImportMapCustomTestImportToken { } ');
        file_put_contents($this->customMappingFile2, '<?php class ImportMapTestImportToken { } ');
        file_put_contents($this->customMappingFile3, '<?php class ImportMapOther { } ');
        file_put_contents($this->outOfBoxTestFile, '<?php class ImportMapTestImportTokenOutOfBox { } ');
        SugarAutoLoader::addToMap($this->customMappingFile, false);
        SugarAutoLoader::addToMap($this->customMappingFile2, false);
        SugarAutoLoader::addToMap($this->customMappingFile3, false);
        SugarAutoLoader::addToMap($this->outOfBoxTestFile, false);
    }

    public function tearDown()
    {
        if(file_exists($this->customMappingFile))
        {
            unlink($this->customMappingFile);
            SugarAutoLoader::delFromMap($this->customMappingFile, false);
        }

        if(file_exists($this->customMappingFile2))
        {
            unlink($this->customMappingFile2);
            SugarAutoLoader::delFromMap($this->customMappingFile2, false);
        }

        if(file_exists($this->customMappingFile3))
        {
            unlink($this->customMappingFile3);
            SugarAutoLoader::delFromMap($this->customMappingFile3, false);
        }

        if(file_exists($this->outOfBoxTestFile))
        {
            unlink($this->outOfBoxTestFile);
            SugarAutoLoader::delFromMap($this->outOfBoxTestFile, false);
        }
    }

    public function testGetMappingClassName()
    {
        $view = new Bug50431ImportViewStep3Mock();
        $result = $view->getMappingClassName($this->source);

        $this->assertEquals('ImportMapCustomTestImportToken', $result, 'Failed to load ' . $this->customMappingFile);

        unlink($this->customMappingFile);
        SugarAutoLoader::delFromMap($this->customMappingFile, false);
        $result = $view->getMappingClassName($this->source);

        $this->assertEquals('ImportMapTestImportToken', $result, 'Failed to load ' . $this->customMappingFile2);

        unlink($this->customMappingFile2);
        SugarAutoLoader::delFromMap($this->customMappingFile2, false);

        $result = $view->getMappingClassName($this->source);

        $this->assertEquals('ImportMapTestImportToken', $result, 'Failed to load ' . $this->outOfBoxTestFile);

        unlink($this->outOfBoxTestFile);
        SugarAutoLoader::delFromMap($this->outOfBoxTestFile, false);

        $result = $view->getMappingClassName($this->source);

        $this->assertEquals('ImportMapOther', $result, 'Failed to load ' . $this->customMappingFile3);
    }

}


class Bug50431ImportViewStep3Mock extends ImportViewStep3
{
    public function getMappingClassName($source)
    {
        return parent::getMappingClassName($source);
    }
}