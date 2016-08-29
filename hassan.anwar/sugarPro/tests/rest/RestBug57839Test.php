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

/**
 * Bug 57839 - REST non-GET API must set no-cache headers in response
 */
class RestBug57839Test extends RestTestBase
{
    protected $_accountId;
    
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        $GLOBALS['db']->query("DELETE FROM accounts WHERE id = '{$this->_accountId}'");
        $GLOBALS['db']->commit();
        
        parent::tearDown();
    }

    /**
     * @group rest
     */
    public function testCorrectResponseHeadersForRequestTypes()
    {
        // Create an Account - POST
        $reply = $this->_restCall("Accounts/", json_encode(array('name'=>'UNIT TEST - AFTER')), 'POST');
        $this->assertTrue(isset($reply['reply']['id']), "An account was not created (or if it was, the ID was not returned)");
        $this->_accountId = $reply['reply']['id'];
        
        // Header assertions
        $this->assertNotEmpty($reply['headers']['Cache-Control'], "Cache-Control header missing after POST request");
        $this->assertEquals('no-cache, must-revalidate', $reply['headers']['Cache-Control'], "Incorrect Cache Control value for POST request");
        $this->assertNotEmpty($reply['headers']['Pragma'], "Pragma header missing after POST request");
        $this->assertEquals('no-cache', $reply['headers']['Pragma'], "Incorrect Pragma value for POST request");
        
        // Get the Account - GET with ETag
        $reply = $this->_restCall("Accounts/{$this->_accountId}");
        $this->assertTrue(isset($reply['reply']['id']), "Account ID was not returned");
        
        // Sugar REST GET reply includes empty Cache-Control and Pragma headers
        $this->assertFalse(isset($reply['headers']['Cache-Control']), "Cache-Control header had a value in the GET reply");
        $this->assertFalse(isset($reply['headers']['Pragma']), "Pragma header had a value in the GET reply");
        $this->assertNotEmpty($reply['headers']['ETag'], "ETag header missing from GET request");
        
        // Modify the Account - PUT
        $reply = $this->_restCall("Accounts/{$this->_accountId}", json_encode(array('name'=>'UNIT TEST - AFTER')), 'PUT');
        $this->assertTrue(isset($reply['reply']['id']), "Account ID was not returned in the PUT request");
        $this->assertEquals($this->_accountId, $reply['reply']['id'], "Account ID from reply is different from the create after PUT");
        
        // Header assertions
        $this->assertNotEmpty($reply['headers']['Cache-Control'], "Cache-Control header missing after PUT request");
        $this->assertEquals('no-cache, must-revalidate', $reply['headers']['Cache-Control'], "Incorrect Cache Control value for PUT request");
        $this->assertNotEmpty($reply['headers']['Pragma'], "Pragma header missing after PUT request");
        $this->assertEquals('no-cache', $reply['headers']['Pragma'], "Incorrect Pragma value for PUT request");
        
        // Delete the Account - DELETE
        $reply = $this->_restCall("Accounts/{$this->_accountId}", '', 'DELETE');
        $this->assertTrue(isset($reply['reply']['id']), "Account ID was not returned in the DELETE request");
        $this->assertEquals($this->_accountId, $reply['reply']['id'], "Account ID from reply is different from the create after DELETE");
        
        // Header assertions
        $this->assertNotEmpty($reply['headers']['Cache-Control'], "Cache-Control header missing after DELETE request");
        $this->assertEquals('no-cache, must-revalidate', $reply['headers']['Cache-Control'], "Incorrect Cache Control value for DELETE request");
        $this->assertNotEmpty($reply['headers']['Pragma'], "Pragma header missing after DELETE request");
        $this->assertEquals('no-cache', $reply['headers']['Pragma'], "Incorrect Pragma value for DELETE request");
    }
}
