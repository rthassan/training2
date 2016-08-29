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

class M2MRelationshipTestDuplicateRows extends Sugar_PHPUnit_Framework_TestCase
{
    protected $def;
    protected $origDB;

    public function setUp()
        {

            $this->origDB = $GLOBALS['db'];
            $this->db = new SugarTestDatabaseMock();
            $GLOBALS['db'] = $this->db;
            $this->def = array(
                'name' => "accounts_contacts",
                'table' => "accounts_contacts",
                'lhs_module' => 'Accounts',
                'lhs_table' => 'accounts',
                'lhs_key' => 'id',
                'rhs_module' => 'Contacts',
                'rhs_table' => 'contacts',
                'rhs_key' => 'id',
                'relationship_type' => 'many-to-many',
                'join_table' => 'accounts_contacts',
                'join_key_lhs' => 'account_id',
                'join_key_rhs' => 'contact_id',
                'primary_flag_column' => 'primary_account',
                'primary_flag_side' => 'rhs',
                'primary_flag_default' => true,
           );
        }

        public function tearDown()
        {
            $GLOBALS['db'] = $this->origDB;
        }

    /**
     * @dataProvider dupeRowProvider
     */
    public function testM2MDupeRowCheck($row, $accId, $conId, $expected)
    {
        $this->db->addQuerySpy('searchForExisting', '/SELECT.*FROM.*accounts_contacts/i', array($row));

        $m2mRelationship = new TestDuplicateM2MRel($this->def);
        $account = BeanFactory::getBean("Accounts");
        $account->id = $accId;
        $contact = BeanFactory::getBean("Contacts");
        $contact->id = $conId;

        $m2mRelationship->add($account, $contact);

        $this->assertEquals($expected, $m2mRelationship->addRowCalled);
    }

    public function dupeRowProvider() {
            return array(
                array(
                    array(
                        "id" => "12345",
                        "contact_id" => "contact_1",
                        "account_id" => "account_1",
                        "date_modified" => "2014-06-02 22:14:12",
                        "primary_account" => "1",
                        "deleted" => "0",
                    ),
                    "account_1",
                    "contact_1",
                    false,
                ),
                //Check deleted flag
                array(
                    array(
                        "id" => "1234",
                        "contact_id" => "contact_1",
                        "account_id" => "account_1",
                        "date_modified" => "2014-06-02 22:14:12",
                        "primary_account" => "1",
                        "deleted" => "1",
                    ),
                    "account_1",
                    "contact_1",
                    true,
                ),
                //Check for additional fields (primary_account here)
                array(
                    array(
                        "id" => "12345",
                        "contact_id" => "contact_1",
                        "account_id" => "account_1",
                        "date_modified" => "2014-06-02 22:14:12",
                        "primary_account" => "0",
                        "deleted" => "0",
                    ),
                    "account_1",
                    "contact_1",
                    true,
                ),
                //Check for new related ids
                array(
                    array(
                        "id" => "12345",
                        "contact_id" => "contact_1",
                        "account_id" => "account_2",
                        "date_modified" => "2014-06-02 22:14:12",
                        "primary_account" => "1",
                        "deleted" => "0",
                    ),
                    "account_1",
                    "contact_1",
                    true,
                ),
            );
        }
}

class TestDuplicateM2MRel extends M2MRelationship
{
    public $addRowCalled = false;

    protected function addRow(array $row)
    {
        $this->addRowCalled = true;
    }
}
