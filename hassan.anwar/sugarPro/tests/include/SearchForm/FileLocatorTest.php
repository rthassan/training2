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

require_once 'include/SearchForm/SearchForm2.php';

class FileLocatorTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $form;
    protected $tempfiles = array();

    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $acc = new Account();
        $this->form = new SearchFormMock($acc, "Accounts");
    }

    public function tearDown()
    {
        unset($GLOBALS['app_strings']);
        unset($GLOBALS['current_user']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        if(!empty($this->tempfiles)) {
            foreach($this->tempfiles as $file) {
                @SugarAutoLoader::unlink($file, false);
            }
        }
    }


    /**
     * Check file locator
     */
    public function testFileLocatorOptions()
    {
        $options = $this->form->getOptions();
        $this->assertNotEmpty($options['locator_class_params'][0]);
        $this->assertContains("modules/Accounts/tpls/SearchForm", $options['locator_class_params'][0]);
    }

    /**
     * Check file locator
     */
    public function testFileLocatorSetOptions()
    {
        $paths = array('a', 'b', 'c');

        $options = array(
            'locator_class' => 'FileLocator',
            'locator_class_params' => array(
                $paths
            )
            );
        $this->form->setOptions($options);
        $options = $this->form->getOptions();
        $this->assertEquals($paths, $options['locator_class_params'][0]);
    }

    /**
     * Check file locator
     */
    public function testFileLocatorOptionsCtor()
    {
        $paths = array('a', 'b', 'c');

        $options = array(
            'locator_class' => 'FileLocator',
            'locator_class_params' => array(
                $paths
            )
            );
        $this->form = new SearchForm(new Account(), "Accounts", 'index', $options);
        $options = $this->form->getOptions();
        $this->assertEquals($paths, $options['locator_class_params'][0]);
    }

    public function testFileLocatorFindSystemFile()
    {
        $this->assertEquals("include/SearchForm/tpls/SearchFormGenericAdvanced.tpl",
            $this->form->locateFile('SearchFormGenericAdvanced.tpl'),
            "Wrong file location"
            );
    }

    public function testFileLocatorFindCustomFile()
    {
        sugar_mkdir('custom/include/SearchForm/tpls/', 0755, true);
        sugar_mkdir('custom/modules/Accounts/tpls/SearchForm', 0755, true);
        $this->tempfiles[]= 'custom/include/SearchForm/tpls/FileLocatorTest.tpl';
        SugarAutoLoader::put('custom/include/SearchForm/tpls/FileLocatorTest.tpl', "unittest");
        $this->assertEquals("custom/include/SearchForm/tpls/FileLocatorTest.tpl",
            $this->form->locateFile('FileLocatorTest.tpl'),
            "Wrong file location"
            );

        $this->tempfiles[] = "custom/modules/Accounts/tpls/SearchForm/FileLocatorTest.tpl";
        SugarAutoLoader::put('custom/modules/Accounts/tpls/SearchForm/FileLocatorTest.tpl', "unittest");
        $this->assertEquals("custom/modules/Accounts/tpls/SearchForm/FileLocatorTest.tpl",
            $this->form->locateFile('FileLocatorTest.tpl'),
            "Wrong file location"
            );
    }
}

class SearchFormMock extends SearchForm
{
    public function locateFile($file)
    {
        return parent::locateFile($file);
    }
}
