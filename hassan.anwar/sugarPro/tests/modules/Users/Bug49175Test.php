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

require_once('modules/Users/User.php');
require_once('modules/Users/UserViewHelper.php');

/**
 * Bug #49175
 * When user is admin doesn't display on user detailview
 * @ticket 49175
 */
class Bug49175Test extends  Sugar_PHPUnit_Framework_TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = SugarTestUserUtilities::createAnonymousUser();
    }

    public function userTypes()
    {
        return array(
            array('is_admin' => '1', 'is_group' => '0', 'portal_only' => '0', 'type' => 'Administrator'),
            array('is_admin' => '0', 'is_group' => '1', 'portal_only' => '0', 'type' => 'GROUP'),
            array('is_admin' => '0', 'is_group' => '0', 'portal_only' => '1', 'type' => 'PORTAL_ONLY'),
            array('is_admin' => '0', 'is_group' => '0', 'portal_only' => '0', 'type' => 'RegularUser')
        );
    }

    /**
     * @group 49175
     * @dataProvider userTypes
     */
    public function testGetUserType($is_admin, $is_group, $portal_only=0, $type)
    {
        $this->user->is_admin = $is_admin;
        $this->user->is_group = $is_group;
        $this->user->portal_only = $portal_only;
        $userViewHelper = new MockUserViewHelper();
        $userViewHelper->setUserType($this->user);
        $this->assertEquals($this->user->user_type, $type);
    }
}

class MockUserViewHelper extends UserViewHelper {

    //override the constructor, don't bother passing Smarty instance, etc.
    public function __construct() {

    }
}

?>
