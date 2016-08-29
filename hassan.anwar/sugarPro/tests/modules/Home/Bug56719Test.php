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

require_once 'modules/Home/QuickSearch.php';

class Bug56719Test extends Sugar_PHPUnit_Framework_TestCase
{
    private static $user;

    public static function setUpBeforeClass()
    {
        self::$user  = SugarTestUserUtilities::createAnonymousUser();

        /** @var Team[] $teams */
        $teams = array();
        foreach (array(
             'Priv1' => true,
             'XPriv1' => true,
             'Priv2' => true,
             'Pub1' => false,
             'Pub2' => false,
            ) as $name => $private) {
            $teams[] = SugarTestTeamUtilities::createAnonymousTeam(
                null,
                array(
                    'name'    => $name,
                    'private' => $private,
                )
            );
        }

        // user belongs to two teams with different names
        $teams[0]->add_user_to_team(self::$user->id);
        $teams[1]->add_user_to_team(self::$user->id);

        // relation between user and team is removed
        $teams[2]->add_user_to_team(self::$user->id);
        $teams[2]->remove_user_from_team(self::$user->id);

        // team has a description
        $teams[4]->description = 'Bug56719Test';
        $teams[4]->save();
    }

    public static function tearDownAfterClass()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestTeamUtilities::removeAllCreatedAnonymousTeams();
    }

    public function testGetAllPublicTeams()
    {
        $teams = $this->getTeams();

        // public teams should be retrieved
        $this->assertContains('Pub1', $teams);
        $this->assertContains('Pub2', $teams);

        // private teams shouldn't be retrieved
        $this->assertNotContains('Priv1', $teams);
        $this->assertNotContains('Priv2', $teams);
    }

    public function testFilterPublicTeams()
    {
        $teams = $this->getTeams(
            array(
                'conditions' => array(
                    array(
                        'name'  => 'name',
                        'value' => 'Pub1',
                    ),
                ),
            )
        );

        // team Pub1 meets search criteria
        $this->assertContains('Pub1', $teams);

        // team Pub2 doesn't meet search criteria
        $this->assertNotContains('Pub2', $teams);
    }

    public function testFilterUserTeams()
    {
        $teams = $this->getTeams(
            array(
                'conditions' => array(
                    array(
                        'name'  => 'name',
                        'value' => 'P',
                    ),
                    array(
                        'name'  => 'user_id',
                        'value' => self::$user->id,
                    ),
                ),
            )
        );

        // user belongs to team Pub1 and it meets search criteria
        $this->assertContains('Priv1', $teams);

        // user belongs to team XPriv1 but it doesn't meet search criteria
        $this->assertNotContains('XPriv1', $teams);

        // relation between user and team is removed
        $this->assertNotContains('Priv2', $teams);

        // only private teams are retrieved
        $this->assertNotContains('Pub1', $teams);
    }

    public function testFilterByStandardField()
    {
        $teams = $this->getTeams(
            array(
                'conditions' => array(
                    array(
                        'name'  => 'description',
                        'value' => 'Bug56719Test',
                    ),
                ),
            )
        );

        $this->assertNotContains('Pub1', $teams);
        $this->assertContains('Pub2', $teams);
    }

    private function getTeams(array $args = array())
    {
        $args = array_merge(
            array(
                'field_list' => array('name'),
            ),
            $args
        );

        $query = new QuicksearchQuery();
        $data = $query->get_non_private_teams_array($args);
        $data = json_decode($data, true);

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('fields', $data);
        $this->assertInternalType('array', $data['fields']);

        $teams = array();
        foreach ($data['fields'] as $row) {
            $teams[] = $row['name'];
        }

        return $teams;
    }
}
