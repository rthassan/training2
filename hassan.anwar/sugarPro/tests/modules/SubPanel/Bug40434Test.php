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

require_once "include/SubPanel/SubPanelDefinitions.php";

class Bug40434Test extends Sugar_PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $user = SugarTestUserUtilities::createAnonymousUser();
        $user->is_admin = true;
        $user->save();
        $GLOBALS['current_user'] = $user;
    }

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * @group bug40434
     */
    public function testNameOfModifiedByNameField()
    {
        $contact = new Contact();
        $contact->create_new_list_query("", "");
        $this->assertEquals($contact->field_defs['modified_by_name']['name'], "modified_by_name", "Name of modified by name field should be 'modified_by_name'");
    }
}
