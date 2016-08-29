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

require_once 'tests/service/SOAPTestCase.php';
/**
 * This class is meant to test everything SOAP
 *
 */
class SOAPAPI1Test extends SOAPTestCase
{
	public $_contact = null;
	public $_meeting = null;
	public $_userUtils = null;
	public $_sessionId = '';

    /**
     * Create test user
     *
     */
	public function setUp()
    {
    	$this->_soapURL = $GLOBALS['sugar_config']['site_url'].'/soap.php';
		parent::setUp();
        $this->_login(); // Logging in just before the SOAP call as this will also commit any pending DB changes
		$this->_setupTestContact();
        $this->_meeting = SugarTestMeetingUtilities::createMeeting();
    }

    /**
     * Remove anything that was used during this test
     *
     */
    public function tearDown()
    {
        SugarTestContactUtilities::removeCreatedContactsUsersRelationships();
        $this->_contact = null;
        SugarTestMeetingUtilities::removeAllCreatedMeetings();
        SugarTestMeetingUtilities::removeMeetingContacts();
        $this->_meeting = null;
    	parent::tearDown();
    }

	/**
	 * Ensure we can create a session on the server.
	 *
	 */
    public function testCanLogin()
    {
		$result = $this->_login();
    	$this->assertTrue(!empty($result['id']) && $result['id'] != -1,
            'SOAP Session not created. Error ('.$result['error']['number'].'): '.$result['error']['name'].': '.$result['error']['description'].'. HTTP Response: '.$this->_soapClient->response);
    }

    public function testSearchContactByEmail()
    {
    	$result = $this->_soapClient->call('contact_by_email', array('user_name' => $GLOBALS['current_user']->user_name, 'password' => $GLOBALS['current_user']->user_hash, 'email_address' => $this->_contact->email1));
    	$this->assertTrue(!empty($result) && count($result) > 0, 'Incorrect number of results returned. HTTP Response: '.$this->_soapClient->response);
    	$this->assertEquals($result[0]['name1'], $this->_contact->first_name, 'Incorrect result found');
    }

	public function testSearchByModule()
    {
		$modules = array('Contacts');
    	$result = $this->_soapClient->call('search_by_module', array('user_name' => $GLOBALS['current_user']->user_name, 'password' => $GLOBALS['current_user']->user_hash, 'search_string' => $this->_contact->email1, 'modules' => $modules, 'offset' => 0, 'max_results' => 10));
        $this->assertTrue(!empty($result) && count($result['entry_list']) > 0, 'Incorrect number of results returned. HTTP Response: '.$this->_soapClient->response);
    	$this->assertEquals($result['entry_list'][0]['name_value_list'][1]['name'], 'first_name' && $result['entry_list'][0]['name_value_list'][1]['value'] == $this->_contact->first_name, 'Incorrect result returned');
    }

	public function testSearchBy()
    {
        $this->markTestIncomplete('SOAP call "search" is deprecated');

		$result = $this->_soapClient->call('search', array('user_name' => $GLOBALS['current_user']->user_name, 'password' => $GLOBALS['current_user']->user_hash, 'name' => $this->_contact->first_name));
    	$this->assertTrue(!empty($result) && count($result) > 0, "Incorrect number of results returned - Returned $result results. HTTP Response: ".$this->_soapClient->response);
    	$this->assertEquals($result[0]['name1'], $this->_contact->first_name, "Contact First name does not match data returnd from SOAP_test");
    }

	public function testGetModifiedEntries()
    {
		$ids = array($this->_contact->id);
    	$result = $this->_soapClient->call('get_modified_entries', array('session' => $this->_sessionId, 'module_name' => 'Contacts', 'ids' => $ids, 'select_fields' => array()));
    	$decoded = base64_decode($result['result']);
    }

	public function testGetAttendeeList()
    {
    	$this->_meeting->load_relationship('contacts');
    	$this->_meeting->contacts->add($this->_contact->id);
        $GLOBALS['db']->commit();
		$result = $this->_soapClient->call('get_attendee_list', array('session' => $this->_sessionId, 'module_name' => 'Meetings', 'id' => $this->_meeting->id));
    	$decoded = base64_decode($result['result']);
        $decoded = simplexml_load_string($decoded);
        $this->assertTrue(!empty($result['result']), 'Results not returned. HTTP Response: '.$this->_soapClient->response);
		$this->assertEquals(urldecode($decoded->attendee->first_name), $this->_contact->first_name, 'Incorrect Result returned expected: '.$this->_contact->first_name.' Found: '.urldecode($decoded->attendee->first_name));
	}

    public function testSyncGetModifiedRelationships()
    {
    	$ids = array($this->_contact->id);
    	$yesterday = date('Y-m-d', strtotime('last year'));
    	$tomorrow = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") + 1, date("Y")));
    	$result = $this->_soapClient->call('sync_get_modified_relationships', array('session' => $this->_sessionId, 'module_name' => 'Users', 'related_module' => 'Contacts', 'from_date' => $yesterday, 'to_date' => $tomorrow, 'offset' => 0, 'max_results' => 10, 'deleted' => 0, 'module_id' => $GLOBALS['current_user']->id, 'select_fields'=> array(), 'ids' => $ids, 'relationship_name' => 'contacts_users', 'deletion_date' => $yesterday, 'php_serialize' => 0));
    	$this->assertTrue(!empty($result['entry_list']), 'Results not returned. HTTP Response: '.$this->_soapClient->response);
        $decoded = base64_decode($result['entry_list']);
    	$decoded = simplexml_load_string($decoded);
        if (isset($decoded->item[0]) ) {
            $this->assertEquals(urlencode($decoded->item->name_value_list->name_value[1]->name), 'contact_id', "testSyncGetModifiedRelationships - could not retrieve contact_id column name");
            $this->assertEquals(urlencode($decoded->item->name_value_list->name_value[1]->value), $this->_contact->id, "vlue of contact id is not same as returned via SOAP");
        }
    }

    /**********************************
     * HELPER PUBLIC FUNCTIONS
     **********************************/
	private function _setupTestContact() {
        $this->_contact = SugarTestContactUtilities::createContact();
        $this->_contact->contacts_users_id = $GLOBALS['current_user']->id;
        $this->_contact->save();
        $GLOBALS['db']->commit(); // Making sure these changes are committed to the database
    }

}
