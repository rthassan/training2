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

require_once('modules/ModuleBuilder/parsers/StandardField.php');

/**
 * Bug #46869
 * @ticket 46869
 */
class Bug46869Test extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    private $customVardefPath;

    public function setUp()
    {
        $this->customVardefPath = 'custom' . DIRECTORY_SEPARATOR .
                                  'Extension' . DIRECTORY_SEPARATOR .
                                  'modules' . DIRECTORY_SEPARATOR .
                                  'Cases' . DIRECTORY_SEPARATOR .
                                  'Ext' . DIRECTORY_SEPARATOR .
                                  'Vardefs' . DIRECTORY_SEPARATOR .
                                  'sugarfield_resolution46869.php';
        $dirname = dirname($this->customVardefPath);

        if (file_exists($dirname) === false)
        {
            mkdir($dirname, 0777, true);
        }

        $code = <<<PHP
<?php
\$dictionary['Case']['fields']['resolution46869']['required']=true;
PHP;

        file_put_contents($this->customVardefPath, $code);

        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
    }

    public function tearDown()
    {
        unlink($this->customVardefPath);

        SugarTestHelper::tearDown();
    }

    public function testLoadingCustomVardef()
    {
        $df = new StandardFieldBug46869Test('Cases') ;
        $df->base_path = dirname($this->customVardefPath);
        $customDef = $df->loadCustomDefBug46869Test('resolution46869');

        $this->assertArrayHasKey('required', $customDef, 'Custom definition of Case::resolution46869 does not have required property.');
    }

}

class StandardFieldBug46869Test extends StandardField
{
    public function loadCustomDefBug46869Test($field)
    {
        $this->loadCustomDef($field);

        return $this->custom_def;
    }
}