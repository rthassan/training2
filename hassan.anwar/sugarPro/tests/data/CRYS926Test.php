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

/**
 * Class CRYS926
 */
class CRYS926 extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var Account
     */
    protected $account;

    /**
     * @var array
     */
    protected $fieldDefs;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        SugarTestHelper::setUp('current_user', array(true, true));
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');

        $this->account = SugarTestAccountUtilities::createAccount();
        // The "field_defs" is a reference that is stored in static variable, which means should be
        // restored in the tearDown.
        $this->fieldDefs = $this->account->field_defs;
        $this->account->field_defs = array(
            'id' => array(
                'name' => 'id',
                'vname' => 'LBL_ID',
                'type' => 'id',
                'required' => true,
            ),
            'name' => array(
                'name' => 'name',
                'type' => 'name',
                'dbType' => 'varchar',
                'vname' => 'LBL_NAME',
                'len' => '100',
                'required' => true,
            ),
            'custom_id_c' => array(
                'required' => false,
                'name' => 'custom_id_c',
                'vname' => '',
                'type' => 'id',
                'len' => 36,
            ),
            'custom_relate' => array(
                'required' => true,
                'source' => 'non-db',
                'name' => 'custom_relate',
                'vname' => 'LBL_CUSTOM_RELATE',
                'type' => 'relate',
                'len' => '255',
                'id_name' => 'custom_id_c',
                'rname' => 'name',
                // Required keys.
                'ext2' => 'Accounts',
                'module' => 'Accounts',
            ),
        );
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        $this->account->field_defs = $this->fieldDefs;
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestHelper::tearDown();
    }

    /**
     * The 'id_name' field should be valid and present in the select clause.
     */
    public function testIdNameFieldShouldBeInSelect()
    {
        $queryArray = $this->account->create_new_list_query(
            'custom_relate',
            '',
            array('custom_relate'),
            array(),
            0,
            '',
            true
        );
        $this->assertEquals(1, substr_count($queryArray['select'], 'custom_relate'));
        $this->assertEquals(1, substr_count($queryArray['select'], 'accounts.custom_id_c'));
    }
}
