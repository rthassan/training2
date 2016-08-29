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

require_once('modules/Import/ImportFieldSanitize.php');
require_once('modules/Import/sources/ImportFile.php');
require_once('tests/SugarTestLangPackCreator.php');

class ImportFieldSanitizeTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_ifs = new ImportFieldSanitize();
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $beanList = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['timedate'] = TimeDate::getInstance();
    }

    public function tearDown()
    {
	 	SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_list_strings']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        $GLOBALS['timedate'] = TimeDate::getInstance();
    }

	public function testValidBool()
    {
        $this->assertEquals($this->_ifs->bool(0,array()),0);
        $this->assertEquals($this->_ifs->bool('no',array()),0);
        $this->assertEquals($this->_ifs->bool('off',array()),0);
        $this->assertEquals($this->_ifs->bool('n',array()),0);
        $this->assertEquals($this->_ifs->bool('yes',array()),1);
        $this->assertEquals($this->_ifs->bool('y',array()),1);
        $this->assertEquals($this->_ifs->bool('on',array()),1);
        $this->assertEquals($this->_ifs->bool(1,array()),1);
    }

    public function testValidBoolVarchar()
    {
        $vardefs = array('dbType' => 'varchar');

        $this->assertEquals($this->_ifs->bool(0,$vardefs),'off');
        $this->assertEquals($this->_ifs->bool('no',$vardefs),'off');
        $this->assertEquals($this->_ifs->bool('off',$vardefs),'off');
        $this->assertEquals($this->_ifs->bool('n',$vardefs),'off');
        $this->assertEquals($this->_ifs->bool('yes',$vardefs),'on');
        $this->assertEquals($this->_ifs->bool('y',$vardefs),'on');
        $this->assertEquals($this->_ifs->bool('on',$vardefs),'on');
        $this->assertEquals($this->_ifs->bool(1,$vardefs),'on');
    }

    public function testInvalidBool()
    {
        $this->assertFalse($this->_ifs->bool('OK',array()));
        $this->assertFalse($this->_ifs->bool('yep',array()));
    }

    public function testValidCurrency()
    {
        $this->_ifs->dec_sep = '.';
        $this->_ifs->currency_symbol = '$';

        $this->assertEquals($this->_ifs->currency('$100',array()),100);
    }

    public function testInvalidCurrency()
    {
        $this->_ifs->dec_sep = '.';
        $this->_ifs->currency_symbol = 'ï¿½';

        $this->assertNotEquals($this->_ifs->currency('$123.23',array()),123.23);
    }

    public function testValidDatetimeSameFormat()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->dateformat = $GLOBALS['timedate']->get_date_format();
        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'some_date');
        $date = date($this->_ifs->dateformat . ' ' .$this->_ifs->timeformat);

        $comparedate = date(
            $GLOBALS['timedate']->get_db_date_time_format(),
            strtotime(
                $GLOBALS['timedate']->handle_offset(
                    $date, $GLOBALS['timedate']->get_date_time_format(), false,
                    $userMock, $userTZ)
                )
            );

        $this->assertEquals(
            $this->_ifs->datetime(
                $date,
                $vardef),
            $comparedate);
    }

    public function testValidDatetimeDifferentFormat()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->dateformat   = 'm/d/Y';
        if ( $this->_ifs->dateformat == $GLOBALS['timedate']->get_date_format() )
            $this->_ifs->dateformat = 'Y/m/d';
        $this->_ifs->timeformat   = 'h:ia';
        if ( $this->_ifs->timeformat == $GLOBALS['timedate']->get_time_format() )
            $this->_ifs->timeformat = 'h.ia';
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'some_date');
        $date = date($this->_ifs->dateformat . ' ' . $this->_ifs->timeformat);

        $comparedate = date(
            $GLOBALS['timedate']->get_db_date_time_format(),
            strtotime(
                $GLOBALS['timedate']->handle_offset(
                    $date, $GLOBALS['timedate']->get_date_time_format(), false,
                    $userMock, $userTZ)
                ));

        $this->assertEquals(
            $this->_ifs->datetime(
                $date,
                $vardef),
            $comparedate);

    }

    public function testValidDatetimeDifferentTimezones()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->dateformat = $GLOBALS['timedate']->get_date_format();
        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $format = $GLOBALS['timedate']->get_date_time_format();
        $this->_ifs->timezone = 'America/Denver';
        $vardef = array('name' => 'some_date');
        $date = date($format);
        $comparedate = date(
            $GLOBALS['timedate']->get_db_date_time_format(),
            strtotime('+2 hours',strtotime(
                $GLOBALS['timedate']->handle_offset(
                    $date, $GLOBALS['timedate']->get_date_time_format(), false,
                    $userMock, $userTZ)
                )));

        $this->assertEquals(
            $this->_ifs->datetime(
                $date,
                $vardef
            ),
            $comparedate
        );
    }

    public function testValidDatetimeDateEntered()
    {
        $userTZ = 'Atlantic/Cape_Verde';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->dateformat = $GLOBALS['timedate']->get_date_format();
        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $format = $GLOBALS['timedate']->get_date_time_format();
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'date_entered');
        $date = date($format);
        $comparedate = date(
            $GLOBALS['timedate']->get_db_date_time_format(),
            strtotime('+1 hours',strtotime($date)));

        $this->assertEquals(
            $this->_ifs->datetime(
                $date,
                $vardef
            ),
            $comparedate
        );
    }

    public function testValidDatetimeDateOnly()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->dateformat = $GLOBALS['timedate']->get_date_format();
        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $format = $GLOBALS['timedate']->get_date_format();
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'date_entered');
        $date = date($format);

        $this->assertTrue(
            (bool) $this->_ifs->datetime(
                $date,
                $vardef));

    }

    public function testInvalidDatetime()
    {
        $this->_ifs->dateformat = 'm.d.Y';
        $this->_ifs->timeformat = 'h:ia';
        $this->_ifs->timezone = 'America/New_York';

        $this->assertFalse(
            $this->_ifs->datetime(
                '11/22/2008 11:21',
                array('name' => 'some_date')));
    }

    public function testInvalidDatetimeBadDayBadHour()
    {
        $this->_ifs->dateformat = 'm.d.Y';
        $this->_ifs->timeformat = 'h:ia';
        $this->_ifs->timezone = 'America/New_York';

        $this->assertFalse(
            $this->_ifs->datetime(
                '11/40/2008 18:21',
                array('name' => 'some_date')));
    }

    public function testValidDateSameFormat()
    {
        $this->_ifs->dateformat = $GLOBALS['timedate']->get_date_format();
        $date = date($this->_ifs->dateformat);
        $focus = new stdClass;

        $this->assertEquals(
            $this->_ifs->date(
                $date,
                array(),
                $focus),
            $date);
    }

    public function testValidDateDifferentFormat()
    {
        $this->_ifs->dateformat = 'm/d/Y';
        if ( $this->_ifs->dateformat  == $GLOBALS['timedate']->get_date_format() )
            $this->_ifs->dateformat  = 'Y/m/d';
        $date = date($this->_ifs->dateformat );
        $comparedate = date(
            $GLOBALS['timedate']->get_date_format(),
            strtotime($date));
        $focus = new stdClass;

        $this->assertEquals(
            $this->_ifs->date(
                $date,
                array(),
                $focus),
            $comparedate);
    }

    public function testInvalidDate()
    {
        $this->_ifs->dateformat = 'm/d/Y';
        $focus = new stdClass;

        $this->assertFalse(
            $this->_ifs->date(
                '11/22/08',
                array(),
                $focus));
    }

    public function testInvalidDateBadMonth()
    {
        $this->_ifs->dateformat = 'm/d/Y';
        $focus = new stdClass;

        $this->assertFalse(
            $this->_ifs->date(
                '22/11/08',
                array(),
                $focus));
    }

    public function testValidEmail()
    {
        $this->assertEquals(
            $this->_ifs->email(
                'sugas@sugarcrm.com',array()),
            'sugas@sugarcrm.com');
    }

    public function testInvalidEmail()
    {
        $this->assertFalse(
            $this->_ifs->email(
                'sug$%$@as@sugarcrm.com',array()));
    }

    public function testValidEnum()
    {
        $vardefs = array('options' => 'salutation_dom');

        $this->assertEquals(
            $this->_ifs->enum(
                'Mr.',$vardefs),
            'Mr.');
    }

    public function testInvalidEnum()
    {
        $vardefs = array('options' => 'salutation_dom');

        $this->assertFalse(
            $this->_ifs->enum(
                'Foo.',$vardefs));
    }

    /**
	 * @ticket 23485
	 */
    public function testEnumWithDisplayValue()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('checkbox_dom',array(''=>'','1'=>'Yep','2'=>'Nada'));
        $langpack->save();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

        $vardefs = array('options' => 'checkbox_dom');

        $this->assertEquals(
            $this->_ifs->enum(
                'Yep',$vardefs),
            '1');
    }

    /**
     * @ticket 27467
     */
    public function testEnumWithExtraSpacesAtTheEnd()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('checkbox_dom',array(''=>'','1'=>'Yep','2'=>'Nada'));
        $langpack->save();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

        $vardefs = array('options' => 'checkbox_dom');

        $this->assertEquals(
            $this->_ifs->enum(
                '    1  ',$vardefs),
            '1');
    }

    /**
     * @ticket 33328
     */
    public function testEnumWithKeyInDifferentCase()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('gender_list',array('male' => 'Male','female' => 'Female',));
        $langpack->save();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

        $vardefs = array('options' => 'gender_list');

        $this->assertEquals(
            $this->_ifs->enum(
                'MALE',$vardefs),
            'male');
    }

    /**
     * @ticket 33328
     */
    public function testEnumWithValueInDifferentCase()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('checkbox_dom',array(''=>'','1'=>'Yep','2'=>'Nada'));
        $langpack->save();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

        $vardefs = array('options' => 'checkbox_dom');

        $this->assertEquals(
            $this->_ifs->enum(
                'YEP',$vardefs),
            '1');
    }

    public function testValidId()
    {
        $this->assertEquals(
            $this->_ifs->id(
                '1234567890',array()),
            '1234567890');
    }

    public function testInvalidId()
    {
        $this->assertFalse(
            $this->_ifs->id(
                '1234567890123456789012345678901234567890',array()));
    }

    public function testValidInt()
    {
        $this->assertEquals($this->_ifs->int('100',array()),100);

        $this->_ifs->num_grp_sep = ',';

        $this->assertEquals($this->_ifs->int('1,123',array()),1123);
    }

    public function testInvalidInt()
    {
        $this->_ifs->num_grp_sep = '.';
        $this->assertFalse($this->_ifs->int('123,23',array()));
        $this->_ifs->num_grp_sep = ',';
        $this->assertFalse($this->_ifs->int('123.23',array()));
    }

    public function testValidFloat()
    {
        $this->_ifs->dec_sep = '.';

        $this->assertEquals($this->_ifs->float('100',array()),100);
        $this->assertEquals($this->_ifs->float('123.23',array()),123.23);

        $this->_ifs->dec_sep = ',';

        $this->assertEquals($this->_ifs->float('123,23',array()),123.23);

        $this->_ifs->num_grp_sep = ',';

        $this->assertEquals($this->_ifs->float('1,123.23',array()),1123.23);
    }

    public function testInvalidFloat()
    {
        $this->_ifs->dec_sep = '.';

        $this->assertNotEquals($this->_ifs->float('123,23',array()),123.23);
    }

    public function testValidFullname()
    {
        $this->_ifs->default_locale_name_format = 'l f';

        $focus = BeanFactory::getBean('Contacts');

        $this->_ifs->fullname('Bar Foo',array(),$focus);

        $this->assertEquals('Foo', $focus->first_name);
        $this->assertEquals('Bar', $focus->last_name);
    }

    public function testInvalidFullname()
    {
        $this->_ifs->default_locale_name_format = 'f l';

        $focus = BeanFactory::getBean('Contacts');

        $this->_ifs->fullname('Bar Foo',array(),$focus);

        $this->assertNotEquals('Foo', $focus->first_name);
        $this->assertNotEquals('Bar', $focus->last_name);
    }

    public function testValidMultiEnum()
    {
        $vardefs = array('options' => 'salutation_dom');

        $this->assertEquals(
            $this->_ifs->multienum(
                'Mr.,Mrs.',$vardefs),
            encodeMultienumValue(array('Mr.', 'Mrs.')));
        $this->assertEquals(
            $this->_ifs->multienum(
                '^Mr.^,^Mrs.^',$vardefs),
            encodeMultienumValue(array('Mr.', 'Mrs.')));
    }

    /**
     * @ticket 37842
     */
    public function testValidMultiEnumWhenSpacesExistInTheValue()
    {
        $vardefs = array('options' => 'salutation_dom');

        $this->assertEquals(
            $this->_ifs->multienum(
                'Mr., Mrs.',$vardefs),
            encodeMultienumValue(array('Mr.', 'Mrs.')));
    }

    public function testInvalidMultiEnum()
    {
        $vardefs = array('options' => 'salutation_dom');

        $this->assertFalse(
            $this->_ifs->multienum(
                'Mr.,foo.',$vardefs));
    }

    public function testValidName()
    {
        $this->assertEquals(
            $this->_ifs->name(
                '1234567890',array('len' => 12)),
            '1234567890');
    }

    public function testInvalidName()
    {
        $this->assertEquals(
            $this->_ifs->name(
                '1234567890123456789012345678901234567890',array('len' => 12)),
            '123456789012');
    }

    public function testParent()
    {
        $account_name = 'test case account'.date("YmdHis");
        $focus = BeanFactory::getBean('Accounts');
        $focus->name = $account_name;
        $focus->save();
        $account_id = $focus->id;

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array(
          'required' => false,
          'source' => 'non-db',
          'name' => 'parent_name',
          'vname' => 'LBL_FLEX_RELATE',
          'type' => 'parent',
          'massupdate' => 0,
          'comments' => '',
          'help' => '',
          'importable' => 'false',
          'duplicate_merge' => 'disabled',
          'duplicate_merge_dom_value' => '0',
          'audited' => 0,
          'reportable' => 0,
          'len' => 25,
          'options' => 'parent_type_display',
          'studio' => 'visible',
          'type_name' => 'parent_type',
          'id_name' => 'parent_id',
          'parent_type' => 'record_type_display',
        );
        $focus->parent_name = '';
        $focus->parent_id = '';
        $focus->parent_type = 'Accounts';

        $this->_ifs->parent(
            $account_name,
            $vardef,
            $focus);

        $this->assertEquals($focus->parent_id,$account_id);

        $GLOBALS['db']->query("DELETE FROM accounts where id = '$account_id'");
    }

    public function testRelate()
    {
        $account_name = 'test case account'.date("YmdHis");
        $focus = BeanFactory::getBean('Accounts');
        $focus->name = $account_name;
        $focus->save();
        $account_id = $focus->id;

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array (
			'name' => 'account_name',
			'rname' => 'name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
			'unified_search' => true,
		);

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        $this->assertEquals($focus->account_id,$account_id);

        $GLOBALS['db']->query("DELETE FROM accounts where id = '$account_id'");
    }

    public function testRelateCreateRecord()
    {
        $account_name = 'test case account'.date("YmdHis");

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array (
			'name' => 'account_name',
			'rname' => 'name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
			'unified_search' => true,
		);

        // setup
        $beanList = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertEquals($focus->account_id,$relaterow['id']);

        $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 38356
     */
    public function testRelateCreateRecordNoTableInVardef()
    {
        $account_name = 'test case account'.date("YmdHis");

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array (
			'name' => 'account_name',
			'rname' => 'name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
			'unified_search' => true,
		);

        // setup
        $beanList = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertEquals($focus->account_id,$relaterow['id']);

        $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 32869
     */
    public function testRelateCreateRecordIfNoRnameParameter()
    {
        $account_name = 'test case account'.date("YmdHis");

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array (
			'name' => 'account_name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
			'unified_search' => true,
		);

        // setup
        $beanList = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertEquals($focus->account_id,$relaterow['id']);

        $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 26897
     */
    public function testRelateCreateRecordCheckACL()
    {
        $account_name = 'test case account '.date("YmdHis");

        $focus = new Import_Bug26897_Mock;
        $vardef = array (
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_CATEGORY_NAME',
            'join_name'=>'accounts',
            'type' => 'relate',
            'link' => 'accounts_link',
            'table' => 'accounts',
            'isnull' => 'true',
            'module' => 'Import_Bug26897_Mock',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            );

        // setup
        $beanList = array();
        require('include/modules.php');
        $beanList['Import_Bug26897_Mock'] = 'Import_Bug26897_Mock';
        $beanFiles['Import_Bug26897_Mock'] = 'modules/Accounts/Account.php';
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertTrue(empty($focus->account_id),'Category ID should not be set');
        $this->assertFalse($relaterow,'Record should not be added to the related table');

        $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 33704
     */
    public function testRelateDoNotCreateRecordIfRelatedModuleIsUsers()
    {
        $account_name = 'test case account'.date("YmdHis");
        $focus = BeanFactory::getBean('Users');
        $vardef = array (
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'category_id',
            'vname' => 'LBL_CATEGORY_NAME',
            'join_name'=>'accounts',
            'type' => 'relate',
            'link' => 'account_link',
            'table' => 'users',
            'isnull' => 'true',
            'module' => 'Users',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            );

        $this->_ifs->relate(
            $account_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertTrue(empty($focus->account_id),'Category ID should not be set');
        $this->assertFalse($relaterow,'Record should not be added to the related table');

        $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 27562
     */
    public function testRelateCreateRecordUsingMultipleFieldToLinkRecords()
    {
        $contact_name = 'testcase contact'.date("YmdHis");

        $focus = new Import_Bug27562_Mock;

        $vardef = array (
            'name' => 'contact_name',
            'rname' => 'name',
            'id_name' => 'contact_id',
            'vname' => 'LBL_CATEGORY_NAME',
            'join_name'=>'contacts',
            'type' => 'relate',
            'link' => 'contact_link',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Import_Bug27562_Mock',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            );

        // setup
        $beanList = array();
        require('include/modules.php');
        $beanList['Import_Bug27562_Mock'] = 'Import_Bug27562_Mock';
        $beanFiles['Import_Bug27562_Mock'] = 'modules/Contacts/Contact.php';
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $this->_ifs->relate(
            $contact_name,
            $vardef,
            $focus);

        // teardown
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);

        $nameParts = explode(' ',$contact_name);
        $result = $GLOBALS['db']->query(
            "SELECT id FROM contacts where first_name = '{$nameParts[0]}' and last_name = '{$nameParts[1]}'");
        $relaterow = $focus->db->fetchByAssoc($result);

        $this->assertEquals($focus->contact_id,$relaterow['id']);

        $GLOBALS['db']->query("DELETE FROM contacts where id = '{$relaterow['id']}'");
    }

    public function testRelateDontCreateRecord()
    {
        $account_name = 'test case account'.date("YmdHis");

        $focus = BeanFactory::getBean('Contacts');
        $vardef = array (
			'name' => 'account_name',
			'rname' => 'name',
			'id_name' => 'account_id',
			'vname' => 'LBL_ACCOUNT_NAME',
			'join_name'=>'accounts',
			'type' => 'relate',
			'link' => 'accounts',
			'table' => 'accounts',
			'isnull' => 'true',
			'module' => 'Accounts',
			'dbType' => 'varchar',
			'len' => '255',
			'source' => 'non-db',
			'unified_search' => true,
		);

        // setup
        $beanList = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;

        $this->assertFalse(
            $this->_ifs->relate(
                $account_name,
                $vardef,
                $focus,
                false),
            'Should return false since record could not be found'
            );

        // teardown
        unset($GLOBALS['beanList']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);
        $this->assertFalse($relaterow,'Record should not have been created');
        if ( $relaterow )
            $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    /**
     * @ticket 27046
     */
    public function testRelateWithInvalidDataFormatting()
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('checkbox_dom',array(''=>'','1'=>'Yep','2'=>'Nada'));
        $langpack->save();

        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

        $account_name = 'test case category'.date("YmdHis");

        $focus = new Import_Bug27046_Mock;
        $vardef = array (
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_NAME',
            'join_name'=>'accounts',
            'type' => 'relate',
            'link' => 'accounts_link',
            'table' => 'accounts',
            'isnull' => 'true',
            'module' => 'Import_Bug27046_Mock',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            'rtype' => 'int',
            );

        // setup
        $beanList = array();
        require('include/modules.php');
        $beanList['Import_Bug27046_Mock'] = 'Import_Bug27046_Mock';
        $beanFiles['Import_Bug27046_Mock'] = 'modules/Accounts/Account.php';
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $this->assertFalse(
            $this->_ifs->relate(
                $account_name,
                $vardef,
                $focus),
            'Should return false since field format is invalid'
            );

        // teardown
        unset($GLOBALS['beanList']);

        $result = $GLOBALS['db']->query(
            "SELECT id FROM accounts where name = '$account_name'");
        $relaterow = $focus->db->fetchByAssoc($result);
        $this->assertFalse($relaterow,'Record should not have been created');
        if ( $relaterow )
            $GLOBALS['db']->query("DELETE FROM accounts where id = '{$relaterow['id']}'");
    }

    public function testValidSyncToOutlookUser()
    {
        $value = $GLOBALS['current_user']->id . ',' . $GLOBALS['current_user']->user_name;
        $bad_names = array();

        $this->assertTrue(
            (bool) $this->_ifs->synctooutlook(
                $value,
                array(),
                $bad_names
                ),
            'Test $this->_ifs->synctooutlook() not returning false');

        $this->assertEquals($bad_names,array());
    }
    public function testValidSyncToOutlookTeam()
    {
        $team = SugarTestTeamUtilities::createAnonymousTeam();

        $value = $team->id . ',' . $team->name;
        $bad_names = array();

        $this->assertTrue(
            (bool) $this->_ifs->synctooutlook(
                $value,
                array(),
                $bad_names
                ),
            'Test $this->_ifs->synctooutlook() not returning false');

        $this->assertEquals($bad_names,array());

        SugarTestTeamUtilities::removeAllCreatedAnonymousTeams();
    }
    public function testInvalidSyncToOutlook()
    {
        $value = "jghu8h8yhuh8hhi889898898";
        $bad_names = array();

        $this->assertFalse(
            $this->_ifs->synctooutlook(
                $value,
                array(),
                $bad_names
                ),
            'Test $this->_ifs->synctooutlook() should return false');
    }

    public function testValidTimeSameFormat()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'some_date');
        $date = date($this->_ifs->timeformat);
        $focus = new stdClass;

        $this->assertEquals(
            $this->_ifs->time(
                $date,
                $vardef,
                $focus),
            $date);

    }

    public function testValidTimeDifferentFormat()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->timeformat = 'h:ia';
        if ( $this->_ifs->timeformat == $GLOBALS['timedate']->get_time_format() )
            $this->_ifs->timeformat = 'h.ia';
        $this->_ifs->timezone = $userTZ;
        $vardef = array('name' => 'some_date');

        $date = date($this->_ifs->timeformat);
        $comparedate = date(
            $GLOBALS['timedate']->get_time_format(),
            strtotime($date));
        $focus = new stdClass;

        $this->assertEquals(
            $this->_ifs->time(
                $date,
                $vardef,
                $focus
            ),
            $comparedate
        );

    }

    public function testValidTimeDifferentTimezones()
    {
        $userTZ = 'America/New_York';
        $userMock = $this->setupUserMockWithTZ($userTZ);

        $this->_ifs->timeformat = $GLOBALS['timedate']->get_time_format();
        $this->_ifs->timezone = 'America/Denver';
        $vardef = array('name' => 'some_date');
        $date = date($this->_ifs->timeformat);
        $comparedate = date(
            $GLOBALS['timedate']->get_time_format(),
            strtotime('+2 hours',strtotime($date)));
        $focus = new stdClass;

        $this->assertEquals(
            $this->_ifs->time(
                $date,
                $vardef,
                $focus
            ),
            $comparedate
        );

    }

    public function testInvalidTime()
    {
        $this->_ifs->timeformat = 'h:ia';
        $this->_ifs->timezone = 'America/New_York';
        $focus = new stdClass;

        $this->assertFalse(
            $this->_ifs->time(
                '11:21',
                array('name' => 'some_date'),
                $focus));
    }

    public function testInvalidTimeBadSeconds()
    {
        $this->_ifs->timeformat = 'h:ia';
        $this->_ifs->timezone = 'America/New_York';
        $focus = new stdClass;

        $this->assertFalse(
            $this->_ifs->time(
                '11:60',
                array('name' => 'some_date'),
                $focus));
    }

    protected function setupUserMockWithTZ($tz) {
        $userMock = $this->getMock("User", array('getPreference'));
        $userMock->expects($this->atLeastOnce())
            ->method('getPreference')
            ->will($this->returnValueMap(array(
                array('timezone', $tz),
                array('timezone', 'global', $tz),
                array('datef', 'Y-m-d'),
                array('timef', 'H:i'),
                array('fdow', ''),
            )));

        $GLOBALS['current_user'] = $userMock;
        return $userMock;
    }

    /**
     * Test enum field with function in it's definition.
     * @dataProvider enumWithOpts
     */
    public function testEnumWithFunc($key, $value, $error, $type)
    {
        BeanFactory::setBeanClass('ImportEnumOptions', 'ImportEnumOptions');
        $def = array(
            'name' => 'enumTest',
            'type' => 'enum',
            'function' => array(
                'returns' => 'array',
                'name' => 'getOpts'
            ),
            'function_bean' => 'ImportEnumOptions'
        );
        $this->assertEquals(
            $key,
            $this->_ifs->$type($value, $def),
            $error
        );
    }

    public function testWrongEnumFunc()
    {
        BeanFactory::setBeanClass('ImportEnumOptions', 'ImportEnumOptions');
        $def = array(
            'name' => 'enumTest',
            'type' => 'enum',
            'function' => array(
                'returns' => 'html',
                'name' => 'getOpts'
            ),
            'function_bean' => 'ImportEnumOptions'
        );

        $this->assertEquals(
            false,
            $this->_ifs->enum('First', $def),
            'Should return no value with wrong function in definition'
        );
    }

    /**
     * Data provider for `testEnumWithFunc`.
     * @return array
     */
    public function enumWithOpts()
    {
        return array(
            array(
                'frst',
                'First',
                'Should return key by value',
                'enum',
            ),
            array(
                'frst',
                'FIRST',
                'Should return key by value independently of value\'s case',
                'enum',
            ),
            array(
                'key',
                'Key',
                'Should return key with right case',
                'enum',
            ),
            array(
                false,
                'NoValue',
                'Should return no value',
                'enum',
            ),
            array(
                encodeMultienumValue(array('frst', 'key')),
                'first,Value',
                'Should return key by value without special symbols',
                'multienum',
            ),
            array(
                encodeMultienumValue(array('frst', 'key')),
                '^first^,^Value^',
                'Should return key by value with special symbols',
                'multienum',
            ),
            array(
                false,
                '^NoValue^,^Value^',
                'Should return no value if one element is missing',
                'multienum',
            ),
        );
    }
}

/**
 * Mock class to test enum field.
 * Class ImportEnumOptions
 */
class ImportEnumOptions
{
    public function getOpts()
    {
        return array(
            'frst' => 'First',
            'key' => 'Value',
        );
    }
}

class Import_Bug26897_Mock extends Account
{
    function ACLAccess($view,$is_owner='not_set')
    {
        return false;
    }

    function bean_implements($interface)
    {
		return true;
    }
}

class Import_Bug27562_Mock extends Contact
{
    var $contact_id;

    function ACLAccess($view,$is_owner='not_set')
    {
        return true;
    }
}

class Import_Bug27046_Mock extends Account
{
    function ACLAccess($view,$is_owner='not_set')
    {
        return false;
    }

    function bean_implements($interface)
    {
		return true;
    }

    function getFieldDefintion($name)
    {
        return array(
            'name' => 'name',
            'type' => 'int',
            );
    }
}
