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
 
require_once('vendor/nusoap//nusoap.php');

/**
 * @group bug43196
 */
class Bug43196Test extends Sugar_PHPUnit_Framework_TestCase
{
	public $_soapClient = null;
	
	public function setUp() 
    {
        $this->_soapClient = new nusoapclient($GLOBALS['sugar_config']['site_url'].'/soap.php',false,false,false,false,false,600,600);
        
        SugarTestHelper::setUp("beanList");
        SugarTestHelper::setUp("beanFiles");
        SugarTestHelper::setUp("current_user");
        $GLOBALS['current_user']->status = 'Active';
        $GLOBALS['current_user']->is_admin = 1;
        $GLOBALS['current_user']->save();
    }

    public function tearDown() 
    {
        foreach ( SugarTestContactUtilities::getCreatedContactIds() as $id ) {
            $GLOBALS['db']->query("DELETE FROM accounts_contacts WHERE contact_id = '{$id}'");
        }
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestHelper::tearDown();
    }	
    
    public function testGetEntryWhenContactHasMultipleAccountRelationshipsWorks() 
    {
        $contact = SugarTestContactUtilities::createContact();
        $account1 = SugarTestAccountUtilities::createAccount();
        $account2 = SugarTestAccountUtilities::createAccount();
        
        $contact->load_relationship('accounts');
        $contact->accounts->add($account1->id);
        $contact->accounts->add($account2->id);
        
        $this->_login();
        
        $parameters = array(
            'session' => $this->_sessionId,
            'module_name' => 'Contacts',
            'query' => "contacts.id = '{$contact->id}'",
            'order_by' => '',
            'offset' => 0,
            'select_fields' => array('id', 'account_id', 'account_name'),
            'max_results' => 250,
            'deleted' => 0,
            );
            
        $result = $this->_soapClient->call('get_entry_list',$parameters);
        
        $account_names = array($account1->name, $account2->name);
        $account_ids = array($account1->id, $account2->id);
        /*
        $this->assertEquals($result['entry_list'][0]['name_value_list'][1]['value'],$account1->name);
        $this->assertEquals($result['entry_list'][0]['name_value_list'][2]['value'],$account1->id);
        $this->assertEquals($result['entry_list'][1]['name_value_list'][1]['value'],$account2->name);
        $this->assertEquals($result['entry_list'][1]['name_value_list'][2]['value'],$account2->id);
        */
        $this->assertTrue(in_array($result['entry_list'][0]['name_value_list'][1]['value'], $account_names));
        $this->assertTrue(in_array($result['entry_list'][1]['name_value_list'][1]['value'], $account_names));
        $this->assertTrue(in_array($result['entry_list'][0]['name_value_list'][2]['value'], $account_ids));
        $this->assertTrue(in_array($result['entry_list'][1]['name_value_list'][2]['value'], $account_ids));
    }
    
    /**
     * Attempt to login to the soap server
     *
     * @return $set_entry_result - this should contain an id and error.  The id corresponds
     * to the session_id.
     */
    public function _login()
    {
		global $current_user;  	
    	
        $GLOBALS['db']->commit(); // Making sure we commit any changes before logging in
		$result = $this->_soapClient->call(
		    'login',
            array('user_auth' => 
                array('user_name' => $current_user->user_name,
                    'password' => $current_user->user_hash, 
                    'version' => '.01'), 
                'application_name' => 'SoapTest')
            );
        $this->_sessionId = $result['id'];
		
        return $result;
    }
}