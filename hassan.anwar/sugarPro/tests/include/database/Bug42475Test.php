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
 * @ticket 42475
 */
class Bug42475Test extends Sugar_PHPUnit_Framework_TestCase
{
    /** @var  Bug42475TestBean */
    private $bean;

    public function setUp()
    {
        $this->bean = new Bug42475TestBean();
        $this->bean->field_defs['test_field'] = array(
            'type' => 'currency',
        );
    }

    public function tearDown()
    {
        unset($this->bean->field_defs['test_field']);
    }

    public function testAuditingCurrency() {
        // getDataChanges
        $testBean = new Bug42475TestBean();
        $dataChanges = $testBean->db->getAuditDataChanges($testBean);

        $this->assertEquals(0,count($dataChanges), "New test bean shouldn't have any changes");

        $testBean = new Bug42475TestBean();
        $testBean->test_field = 3829.83862;
        $dataChanges = $testBean->db->getAuditDataChanges($testBean);

        $this->assertEquals(1,count($dataChanges), "Test bean should have 1 change since we added assigned new value to test_field");

    }
}

class Bug42475TestBean extends SugarBean
{
    function Bug42475TestBean() {
        $this->module_dir = 'Accounts';
        $this->object_name = 'Account';
        parent::__construct();
        
        // Fake a fetched row
        $this->fetched_row = array('test_field'=>257.8300000001);
        $this->test_field = 257.83;
    }
    function getAuditEnabledFieldDefinitions() {
        return array('test_field'=>array('type'=>'currency'));
    }
}
