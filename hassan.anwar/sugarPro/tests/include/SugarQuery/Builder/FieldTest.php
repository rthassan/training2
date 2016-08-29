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

class FieldTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestCaseUtilities::removeAllCreatedCases();

        SugarBean::clearLoadedDef('Case');
        SugarBean::clearLoadedDef('Contact');
        SugarBean::clearLoadedDef('Account');
        parent::tearDown();
    }

    public function testGetJoinRecursion()
    {
        $contact = BeanFactory::getBean('Contacts');

        // create field definition which refers itself as id_name and doesn't have link attribute
        $contact->field_defs['account_name']['id_name'] = 'account_name';
        $contact->field_defs['account_name']['link'] = null;

        $query = new SugarQuery();
        $query->from($contact);
        $field = new SugarQuery_Builder_Field('account_name', $query);
        $alias = $field->getJoin();

        $this->assertFalse($alias, 'Field with invalid vardefs should not produce JOIN');
    }

    public function testGetJoinRelatedFieldWithoutLink()
    {
        // Create account
        $account = BeanFactory::newBean('Accounts');
        $account->name = 'Awesome account';
        $account->save();

        SugarTestAccountUtilities::setCreatedAccount(array($account->id));

        // Create case
        $cases = BeanFactory::newBean('Cases');
        $cases->name = 'Awesome contact!';
        $cases->account_id = $account->id;
        $cases->save();

        SugarTestCaseUtilities::setCreatedCase(array($cases->id));

        // Set link field to null
        $cases->field_defs['account_name']['link'] = null;

        $query = new SugarQuery();
        $query->select('account_name');
        $query->from($cases, array('team_security' => false));
        $query->where()->in('account_id', array($account->id));
        $result = $query->execute();

        $this->assertNotEmpty($result, 'Account should be selected');

        // mark account as deleted and try to select again
        $account->mark_deleted($account->id);

        $queryDeleted = new SugarQuery();
        $queryDeleted->from($cases, array('team_security' => false));
        $queryDeleted->where()->in('account_id', array($account->id));
        $result = $queryDeleted->execute();
        $this->assertEmpty($result, 'Deleted account should not be selected');
    }

    public function testGetFieldDef()
    {
        $account = BeanFactory::getBean('Accounts');
        // create custom field defs
        $account->field_defs['my_field_c'] = array(
            'labelValue' => 'my field',
            'full_text_search' =>
            array (
                'boost' => '0',
                'enabled' => false,
            ),
            'enforced' => '',
            'dependency' => '',
            'required' => false,
            'source' => 'custom_fields',
            'name' => 'my_field_c',
            'vname' => 'LBL_MY_FIELD',
            'type' => 'varchar',
            'massupdate' => false,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'enabled',
            'duplicate_merge_dom_value' => '1',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '255',
            'size' => '20',
            'id' => 'Accountsmy_field_c',
            'custom_module' => 'Accounts',
        );
        $sq = new SugarQuery();
        $sq->select(array("my_field"));
        $sq->from($account);
        $field = new SugarQuery_Builder_Field('my_field', $sq);
        $def = $field->getFieldDef();
        $this->assertNotFalse($def, "can't find field def for custom field");
    }
}
