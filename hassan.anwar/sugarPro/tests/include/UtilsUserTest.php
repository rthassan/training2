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

require_once "include/utils.php";

class UtilsUserTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestUserUtilities::createAnonymousUser(
            true,
            0,
            array(
                'user_name' => 'Utils A',
                'first_name' => 'Utils B',
                'last_name' => 'Utils C',
            )
        );
        SugarTestUserUtilities::createAnonymousUser(
            true,
            0,
            array(
                'user_name' => 'Utils C',
                'first_name' => 'Utils A',
                'last_name' => 'Utils B',
            )
        );
        SugarTestUserUtilities::createAnonymousUser(
            true,
            0,
            array(
                'user_name' => 'Utils B',
                'first_name' => 'Utils C',
                'last_name' => 'Utils A',
            )
        );
        SugarTestUserUtilities::createAnonymousUser(
            true,
            0,
            array(
                'user_name' => 'Utils D',
                'first_name' => 'Utils Ba',
                'last_name' => 'Utils Ab',
            )
        );
    }

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * @param $orderBy
     * @param $usernameFilter
     * @param $expected
     *
     * @dataProvider getUserArrayData
     */
    public function testGetUserArray($orderBy, $usernameFilter, $expected)
    {
        $data = get_user_array(false, 'Active', '', false, $usernameFilter, ' AND portal_only=0 ', false, $orderBy);

        $this->assertNotEmpty($data);
        $this->assertEquals($expected, array_values($data), 'Users not ordered properly.');
    }

    public function getUserArrayData()
    {
        return array(
            array(
                array(array('user_name', 'ASC')), // Test order by user_name
                'Utils',
                array(
                    'Utils B Utils C',
                    'Utils C Utils A',
                    'Utils A Utils B',
                    'Utils Ba Utils Ab',
                )
            ),
            array(
                array(array('', 'ASC')), // Test empty order, defaults to user_name
                'Utils',
                array(
                    'Utils B Utils C',
                    'Utils C Utils A',
                    'Utils A Utils B',
                    'Utils Ba Utils Ab',
                )
            ),
            array(
                array(array('gibberish', 'ASC')), // Test non-existing field, defaults to user_name
                'Utils',
                array(
                    'Utils B Utils C',
                    'Utils C Utils A',
                    'Utils A Utils B',
                    'Utils Ba Utils Ab',
                )
            ),
            array(
                array(array('first_name', 'ASC')), // Test first_name
                'Utils',
                array(
                    'Utils A Utils B',
                    'Utils B Utils C',
                    'Utils Ba Utils Ab',
                    'Utils C Utils A',
                )
            ),
            array(
                array(array('last_name', 'ASC')), // Test last_name
                'Utils',
                array(
                    'Utils C Utils A',
                    'Utils Ba Utils Ab',
                    'Utils A Utils B',
                    'Utils B Utils C',
                )
            ),
            array(
                array(array('last_name', 'ASC'), array('first_name', 'ASC')), // Test last_name, first_name
                'Utils',
                array(
                    'Utils C Utils A',
                    'Utils Ba Utils Ab',
                    'Utils A Utils B',
                    'Utils B Utils C',
                )
            ),
            array(
                array(array('last_name', 'ASC'), array('first_name', 'DESC')), // Test last_name, first_name
                'Utils',
                array(
                    'Utils C Utils A',
                    'Utils Ba Utils Ab',
                    'Utils A Utils B',
                    'Utils B Utils C',
                )
            ),
        );
    }
}
