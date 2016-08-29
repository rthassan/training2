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

require_once('tests/rest/RestTestBase.php');

class RestRegisterLeadTest extends RestTestBase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        if (isset($this->lead_id)) {
            $GLOBALS['db']->query("DELETE FROM leads WHERE id = '{$this->lead_id}'");
        }
        parent::tearDown();
    }

    /**
     * @group rest
     */
    public function testCreate()
    {
        $leadProps = array(
            'first_name' => 'UNIT TEST FIRST',
            'last_name' => 'UNIT TEST LAST',
            'email' => array(
                array(
                    'email_address' => 'UT@test.com'
                ))
        );
        $restReply = $this->_restCall("Leads/register",
            json_encode($leadProps),
            'POST');

        $this->assertTrue(isset($restReply['reply']),
            "Lead was not created (or if it was, the ID was not returned)");


        $nlead = new Lead();
        $nlead->id = $restReply['reply'];
        $nlead->retrieve();
        $this->assertEquals($leadProps['first_name'],
            $nlead->first_name,
            "Submitted Lead and Lead Bean Do Not Match.");
        $this->assertEquals("UT@test.com",
            $nlead->email1,
            "Submitted Lead and Lead Bean Do Not Match.");
    }

    /**
     * @group rest
     */
    public function testCreateEmpty()
    {
        $leadProps = array(
        );
        $restReply = $this->_restCall("Leads/register",
            json_encode($leadProps),
            'POST');
        $this->assertEquals($restReply['info']['http_code'], 412);
    }

}