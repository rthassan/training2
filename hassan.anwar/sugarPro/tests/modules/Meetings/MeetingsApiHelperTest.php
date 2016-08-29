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


require_once('modules/Meetings/MeetingsApiHelper.php');
require_once('include/api/RestService.php');

class MeetingsApiHelperTest extends Sugar_PHPUnit_Framework_TestCase
{

    protected $bean =null;
    protected $contact = null;

    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp('current_user');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_strings');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('moduleList');

        // ACL's are junked need to have an admin user
        $GLOBALS['current_user']->is_admin = 1;
        $GLOBALS['current_user']->save();

        $this->bean = BeanFactory::newBean('Meetings');
        $this->bean->id = create_guid();
        $this->bean->name = 'Super Awesome Meetings Time';

        // gotta unfortunately create a contact for this
        $this->contact = SugarTestContactUtilities::createContact();
        $this->bean->contact_id = $this->contact->id;

    }

    public function tearDown()
    {
        unset($this->bean);
        unset($this->contact);
        SugarTestHelper::tearDown();
        SugarTestContactUtilities::removeAllCreatedContacts();
        parent::tearDown();
    }

    public function testFormatForApi() 
    {
        $helper = new MeetingsApiHelper(new MeetingsServiceMockup());
        $data = $helper->formatForApi($this->bean);
        $this->assertEquals($data['contact_name'], $this->contact->full_name, "Calls name does not match");
    }

    public function testFormatForApi_SendInvitesFlagIsNotReturned()
    {
        $helper = new MeetingsApiHelper(new MeetingsServiceMockup());
        $this->bean->send_invites = true;
        $data = $helper->formatForApi($this->bean);
        $this->assertArrayNotHasKey('send_invites', $data, 'Should not include the send_invites flag');
    }
}

class MeetingsServiceMockup extends ServiceBase
{
    public function __construct() {$this->user = $GLOBALS['current_user'];}
    public function execute() {}
    protected function handleException(Exception $exception) {}
}
