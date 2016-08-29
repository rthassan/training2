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
 
require_once('include/MVC/View/views/view.vcard.php');

class ViewVcardTest extends Sugar_PHPUnit_Framework_TestCase
{   
    public function setUp()
    {
        parent::setUp();
        SugarTestHelper::setUp("current_user");
    }
    
    public function testConstructor() 
	{
	    $view = new ViewVcard;
        
        $this->assertEquals('detail',$view->type);
	}
	
	public function testDisplay()
	{
	    $view = new ViewVcard;
	    $view->bean = SugarTestContactUtilities::createContact();
	    $view->module = 'Contacts';
        $view->display();
        SugarTestContactUtilities::removeAllCreatedContacts();
        $this->expectOutputRegex('/BEGIN\:VCARD/');
	}
}