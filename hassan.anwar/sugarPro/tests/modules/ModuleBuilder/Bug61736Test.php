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

class Bug61736Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * Holds the vardef after first require so we don't have to keep including the
     * custom vardef file
     * 
     * @var array
     */
    protected static $_vardef = null;

    /**
     * The custom vardef file created during the test
     * 
     * @var string
     */
    protected static $_vardefFile = 'custom/modulebuilder/packages/p1/modules/bbb/vardefs.php';
    
    /**
     * Module Builder Controller
     * 
     * @var ModuleBuilderController
     */
    protected static $_mb;

    /**
     * Holder for the current request array
     * 
     * @var array
     */
    protected static $_request = array();

    /**
     * Mock REQUEST array used to create the test package
     * 
     * @var array
     */
    protected static $_createPackageRequestVars = array(
        'name' => 'p1',
        'description' => '',
        'author' => '',
        'key' => 'p0001',
        'readme' => '',
    );

    /**
     * Mock REQUEST array used to create the test module
     * 
     * @var array
     */
    protected static $_createModuleRequestVars = array(
        'name' => 'bbb',
        'label' => 'BBB',
        'label_singular' => 'BBB',
        'package' => 'p1',
        'has_tab' => '1',
        'type' => 'basic',
    );
    
    /**
     * Mock request for creating the field
     * 
     * @var array
     */
    protected static $_createFieldRequestVars = array(
        "labelValue" => "Basic Address",
        "label" => "LBL_BASIC_ADDRESS",
        "type" => "address",
        "name" => "basic_address",
        "view_module" => "bbb",
        "view_package" => "p1",
    );

    /**
     * Mock request for deleting the field
     * 
     * @var array
     */
    protected static $_deleteFieldRequestVars = array(
        "labelValue" => "Basic Address",
        "label" => "LBL_BASIC_ADDRESS",
        "to_pdf" => "true",
        "type" => "varchar",
        "name" => "basic_address",
        "view_module" => "bbb",
        "view_package" => "p1",
    );
    
    public static function setUpBeforeClass()
    {
        // Basic setup of the environment
        SugarTestHelper::setUp('current_user', array(true, true));
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('mod_strings', array('ModuleBuilder'));
        
        // Back up and reset the REQUEST to create the package
        self::$_request = $_REQUEST;
        $_REQUEST = self::$_createPackageRequestVars;
        
        // Build up the controller to save the new field
        self::$_mb = new ModuleBuilderController();
        self::$_mb->action_SavePackage();
        
        // Now create the module
        $_REQUEST = self::$_createModuleRequestVars;
        self::$_mb->action_SaveModule();
        
        // Now create the address field
        $_REQUEST = self::$_createFieldRequestVars;
        self::$_mb->action_SaveField();
    }
    
    public static function tearDownAfterClass()
    {
        // Set the request to delete the test field
        $_REQUEST = self::$_deleteFieldRequestVars;
        
        // Loop through the created fields and wipe them out
        $suffixes = array('street', 'city', 'state', 'postalcode', 'country');
        foreach ($suffixes as $suffix) {
            $_REQUEST['name'] = self::_getFieldName($suffix);
            self::$_mb->action_DeleteField();
        }
        
        // Delete the custom module
        $_REQUEST = self::$_createModuleRequestVars;
        $_REQUEST['view_module'] = 'bbb';
        self::$_mb->action_DeleteModule();
        
        // Delete the custom package
        $_REQUEST = self::$_createPackageRequestVars;
        $_REQUEST['package'] = $_REQUEST['name'];
        self::$_mb->action_DeletePackage();
        
        // Clean up the environment
        SugarTestHelper::tearDown();
        
        // Reset the request
        $_REQUEST = self::$_request;
    }
    
    public function testCustomAddressFieldVardefFileCreated()
    {
        $this->assertFileExists(self::$_vardefFile, "The custom field vardef for the new module was not found");
    }

    /**
     * @dataProvider _testFieldFileProvider
     */
    public function testCustomAddressFieldContainsGroupPropertyInVardef($suffix)
    {
        $this->markTestIncomplete('Outputs spaces in the console. Need to be fixed by FRM team');
        // Assert that there is a fields index in the vardef
        $vardefs = self::_getTestVardef();
        $this->assertArrayHasKey('fields', $vardefs, "There is no fields vardef found");
        
        // Assert that the address field was created
        $field = self::_getFieldName($suffix);
        $this->assertArrayHasKey($field, $vardefs['fields'], "No vardefs found for $field");
        
        // Assert there is a group property
        $this->assertNotEmpty($vardefs['fields'][$field]['group'], "Group entry for $field was empty");
        
        // Assert that the group property is the name of the created addres field
        $this->assertEquals(self::$_createFieldRequestVars['name'], $vardefs['fields'][$field]['group'], "Group name is not the field name");
    }
    
    public function _testFieldFileProvider()
    {
        return array(
            array('suffix' => 'street'),
            array('suffix' => 'city'),
            array('suffix' => 'state'),
            array('suffix' => 'postalcode'),
            array('suffix' => 'country'),
        );
    }
    
    protected static function _getFieldName($suffix)
    {
        $field = self::$_createFieldRequestVars['name'];
        $name = $field . '_' . $suffix;
        return $name;
    }

    /**
     * Gets the newly created custom vardef. Fetches the vardef from the file 
     * system one time and holds it for this test.
     * 
     * @return array|null
     */
    protected static function _getTestVardef()
    {
        if (is_null(self::$_vardef)) {
            // Set the vardef to an array first
            self::$_vardef = array();
            
            // If the vardef was found, get it and set it
            if (file_exists(self::$_vardefFile)) {
                require self::$_vardefFile;
                
                if (isset($vardefs)) {
                    self::$_vardef = $vardefs;
                }
            }
        }
        
        return self::$_vardef;
    }
}
