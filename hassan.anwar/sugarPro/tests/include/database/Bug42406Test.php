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
 * Bug #42406
 * Bad Repair SQL Formed If Extended Vardef References a Field That Doesn't Exist
 *
 * @author mgusev@sugarcrm.com
 * @ticket 42406
 */
class Bug42406 extends Sugar_PHPUnit_Framework_TestCase
{

    public function getBrokenField()
    {
        return array(
            array(
                array(
                    'name' => 'withouttype',
                    'type' => ''
                ),
                'TYPE'
            ),
            array(
                array(
                    'name' => '',
                    'type' => 'withoutname'
                ),
                'NAME'
            )
        );
    }
    /**
     * @dataProvider getBrokenField
     * @group 42406
     */
    public function testVardef($field, $error)
    {
        $fieldsdefs = array(
            'broken_field' => $field,
            'test' => array(
                'name' => 'test',
                'type' => 'varchar'
            )
        );
        $indices = array();

        $db = DBManagerFactory::getInstance();
        $result = $db->repairTableParams('contacts', $fieldsdefs,  $indices, false);

        $this->assertRegExp('/\/\* ' . $error . ' IS MISSING IN VARDEF contacts::broken_field \*\//', $result, 'Broken vardef is passed to db');
    }
}