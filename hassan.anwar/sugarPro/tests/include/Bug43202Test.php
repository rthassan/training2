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
 * Bug #43202
 * @description
 *  When filtering search with a 'related' field, it's not possible to export "all" records
 * @author aryamrchik@sugarcrm.com
 * @ticket 43202
 */
class Bug43202Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user');
        parent::setUp();
    }

    /**
     * @group 43202
     */
    public function testExportQuery()
    {
        $focus = BeanFactory::getBean('Accounts');
        //use join name for teams as defined in team security vardefs ('tj')
        $query = $focus->create_export_query('', 'tj.name IS NOT NULL');
        $this->assertTrue($focus->db->validateQuery($query));
    }

    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }
}
