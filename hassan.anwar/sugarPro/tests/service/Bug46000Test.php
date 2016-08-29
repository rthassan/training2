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
require_once 'tests/service/SOAPTestCase.php';


class Bug46000Test extends SOAPTestCase
{
	public $_soapClient = null;
    var $_sessionId;
    var $c = null;
    var $c2 = null;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$_user->is_admin = 0;
        self::$_user->save();
    }

    public function setUp()
    {
        $this->_soapURL = $GLOBALS['sugar_config']['site_url'].'/soap.php';
        parent::setUp();
        $this->_login();
    }

    public function testCreateUser()
    {
        $nv = array(
                     array('name' => 'user_name', 'value' => 'test@test.com'),
                     array('name' => 'user_hash', 'value' => '12345'),
                     array('name' => 'first_name', 'value' => 'TestFirst'),
                     array('name' => 'last_name', 'value' => 'Test Last'),
                     array('name' => 'title', 'value' => 'Test Admin'),
                     array('name' => 'is_admin' , 'value' =>  '1')
                );
        $result = $this->_soapClient->call('set_entry',array('session'=>$this->_sessionId,"module_name" => 'Users', 'name_value_list' => $nv));
        $this->assertEquals('40', $result['error']['number']);
    }

    public function testMakeUserAdmin()
    {
        $nv = array(
                    array('name' => 'id', 'value' => self::$_user->id),
                    array('name' => 'is_admin' , 'value' =>  '1'),
                );
        $result = $this->_soapClient->call('set_entry',array('session'=>$this->_sessionId,"module_name" => 'Users', 'name_value_list' => $nv));
        $this->assertEquals('40', $result['error']['number']);

    }

    public function testGetEntry()
    {
        $result = $this->_soapClient->call('get_entry',array('session'=>$this->_sessionId,"module_name" => 'Users', 'id' => self::$_user->id, 'select_fields' => array('first_name')));
        $this->assertArrayHasKey('entry_list', $result);
        $this->assertEquals($result['entry_list'][0]['name_value_list'][0]['value'], self::$_user->first_name);
    }

}
