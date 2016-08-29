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

require_once('modules/Import/ImportDuplicateCheck.php');

/**
 * Bug #51264
 * Importing updates to rows prevented by duplicates check
 *
 * @ticket 51264
 */
class Bug51264Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $contact;

    public function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;

        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->contact = SugarTestContactUtilities::createContact();
    }

    public function tearDown()
    {
        SugarTestContactUtilities::removeAllCreatedContacts();
        unset($this->contact);
        unset($GLOBALS['beanFiles'], $GLOBALS['beanList']);

        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    /**
     * @group 51264
     */
    public function testIsADuplicateRecordWithID()
    {
        $idc = new ImportDuplicateCheck($this->contact);
        $result = $idc->isADuplicateRecord(array('special_idx_email1::email1'));
        $this->assertFalse($result);
    }

    /**
     * @group 51264
     */
    public function testIsADuplicateRecordWithInvalidID()
    {
        $contact = new Contact();
        $contact->id = '0000000000000000';
        $contact->email1 = $this->contact->email1;
        $idc = new ImportDuplicateCheck($contact);
        $result = $idc->isADuplicateRecord(array('special_idx_email1::email1'));
        $this->assertTrue($result);
    }

    /**
     * @group 51264
     */
    public function testIsADuplicateRecordWithInvalidID2()
    {
        $contact = new Contact();
        $contact->id = '0000000000000000';
        $contact->email1 = 'Bug51264Test@Bug51264Test.com';
        $idc = new ImportDuplicateCheck($contact);
        $result = $idc->isADuplicateRecord(array('special_idx_email1::email1'));
        $this->assertFalse($result);
    }

    /**
     * @group 51264
     */
    public function testIsADuplicateRecord()
    {
        $contact = new Contact();
        $contact->email1 = $this->contact->email1;
        $idc = new ImportDuplicateCheck($contact);
        $result = $idc->isADuplicateRecord(array('special_idx_email1::email1'));
        $this->assertTrue($result);
    }
}