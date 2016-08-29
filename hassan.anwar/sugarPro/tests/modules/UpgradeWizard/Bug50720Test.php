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

require_once('modules/UpgradeWizard/uw_utils.php');

/**
 * Bug50720Test.php
 * 
 * This test checks the upgrade_connectors method in modules/UpgradeWizard/uw_utils.php file.  In particular,
 * we want to ensure that upgrade_connectors will delete the custom connectors.php file.
 *
 */
class Bug50720Test extends Sugar_PHPUnit_Framework_TestCase
{
    var $customConnectors;
    var $file = 'custom/modules/Connectors/metadata/connectors.php';
    
    public function setUp() 
    {
        SugarTestHelper::setup('app_list_strings');
        if(file_exists($this->file))
        {
            $this->customConnectors = file_get_contents($this->file);
        } else {
            mkdir_recursive('custom/modules/Connectors/metadata');
            file_put_contents($this->file, '<?php ');
        }
    }

    public function tearDown() 
    {
        SugarTestHelper::tearDown();
        if(!empty($this->customConnectors))
        {
            file_put_contents($this->file, $this->customConnectors);
        } else if(file_exists($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * testUpgradeConnectors
     *
     * This method calls upgrade_connectors and checks to make sure we have deleted the custom connectors.php file
     */
    public function testUpgradeConnectors() {
        upgrade_connectors();
        $this->assertTrue(!file_exists($this->file), 'upgrade_connectors did not remove file ' . $this->file);
    }
}