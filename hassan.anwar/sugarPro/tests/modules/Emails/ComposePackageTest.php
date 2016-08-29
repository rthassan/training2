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
 
require_once('modules/Contacts/Contact.php');


/**
 * @ticket 32487
 */
class ComposePackageTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $c = null;
	var $a = null;
	var $ac_id = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
        $mod_strings = return_module_language($GLOBALS['current_language'], "Contacts");
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $current_user = SugarTestUserUtilities::createAnonymousUser();
        $unid = uniqid();
        $time = date('Y-m-d H:i:s');

        $contact = new Contact();
        $contact->id = 'c_'.$unid;
        $contact->first_name = 'testfirst';
        $contact->last_name = 'testlast';
        $contact->new_with_id = true;
        $contact->disable_custom_fields = true;
        $contact->save();
		$this->c = $contact;
		
		$beanList = array();
		$beanFiles = array();
		require('include/modules.php');
		$GLOBALS['beanList'] = $beanList;
		$GLOBALS['beanFiles'] = $beanFiles;

	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);

        unset($GLOBALS['mod_strings']);
        unset($GLOBALS['beanList']);
		unset($GLOBALS['beanFiles']);
		

        $GLOBALS['db']->query("DELETE FROM contacts WHERE id= '{$this->c->id}'");
        
        unset($this->c);
    }

	public function testComposeFromMethodCallNoData()
	{    
	    $_REQUEST['forQuickCreate'] = true;
	    require_once('modules/Emails/Compose.php');
	    $data = array();
	    $compose_data = generateComposeDataPackage($data,FALSE);
	    
		$this->assertEquals('', $compose_data['to_email_addrs']);
    }
    
    public function testComposeFromMethodCallForContact()
    {    
	    $_REQUEST['forQuickCreate'] = true;
	    require_once('modules/Emails/Compose.php');
	    $data = array();
	    $data['parent_type'] = 'Contacts';
	    $data['parent_id'] = $this->c->id;
	    
	    $compose_data = generateComposeDataPackage($data,FALSE);

		$this->assertEquals('Contacts', $compose_data['parent_type']);
		$this->assertEquals($this->c->id, $compose_data['parent_id']);
		$this->assertEquals($this->c->name, $compose_data['parent_name']);
    }
}