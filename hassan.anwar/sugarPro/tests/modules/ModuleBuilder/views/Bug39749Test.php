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

require_once('modules/ModuleBuilder/views/view.labels.php');

/**
 * Bug #39749
 * Quick Create in Studio
 * @ticket 39749
 * TODO: Make this test go away when all modules are out of BWC
 * This test only applies to modules in BWC
 */
class Bug39749Test extends Sugar_PHPUnit_Framework_TestCase
{

    public function editModules()
    {
        return array(
            array('module' => 'ProjectTask', 'get_quick_create' => '0'),
            array('module' => 'Campaigns', 'get_quick_create' => '0'),
            array('module' => 'ProductTemplates', 'get_quick_create' => '0'),
            array('module' => 'Accounts', 'get_quick_create' => '0'),
            array('module' => 'Quotes', 'get_quick_create' => '0'),
            // Documents is in BWC but not in the exclude list above
            array('module' => 'Documents', 'get_quick_create' => '1'),
        );
    }

    /**
     * @group 39749
     * @dataProvider editModules
     */
    public function testGetVariableMap($module, $get_quick_create)
    {
        $vl = new ViewLabels();

        $varMap = $vl->getVariableMap($module);

        $this->assertTrue((isset($varMap['quickcreate']) == $get_quick_create));
    }
}
