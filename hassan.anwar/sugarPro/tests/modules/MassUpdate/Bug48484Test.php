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
 * @ticket 48484
 */
class Bug48484Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * Existing module name used to perform the test
     *
     * @var string
     */
    protected $moduleName = 'Accounts';

    /**
     * Custom field name that is tested to be considered
     *
     * @var string
     */
    protected $customFieldName = 'bug48484test_c';

    /**
     * Stub of the mass update object being tested.
     * @var
     */
    protected $massUpdate;

    /**
     * Basic range used to perform the test
     *
     * @var string
     */
    protected $range = 'this_year';

    public function setUp()
    {
         $this->massUpdate = new MassUpdateStub($this->customFieldName);
         global $current_user;
         $current_user = SugarTestUserUtilities::createAnonymousUser();
    }

    public function tearDown()
    {
         SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * Verify whether custom field values are considered during mass update
     */
    public function testModuleCustomFieldsAreConsidered()
    {
        // create search query
        $query = array(
            'searchFormTab'                                => 'basic_search',
            $this->customFieldName . '_basic_range_choice' => $this->range,
            'range_' . $this->customFieldName . '_basic'   => '[' . $this->range . ']',
        );

        // encode the query as the MassUpdate::generateSearchWhere requires
        $query = base64_encode(serialize($query));

        // generate SQL where clause
        $this->massUpdate->generateSearchWhere($this->moduleName, $query);

        // ensure that field name is contained in SQL where clause
        $this->assertContains($this->customFieldName, $this->massUpdate->where_clauses);
    }


}

require_once 'include/MassUpdate.php';

class MassUpdateStub extends MassUpdate
{
    protected $customFieldName = 'bug48484test_c';

    public function __construct($customFieldName)
    {
        $this->customFieldName = $customFieldName;
    }

    protected function getSearchDefs($module, $metafiles = array())
    {
        return array($module => array(
            'layout' => array(
                'basic_search' => array(
                    $this->customFieldName => array(
                        'type' => 'date',
                        'name' => $this->customFieldName,
                    ),
                ),
            ),
        ));
    }

    protected function getSearchFields($module, $metafiles = array())
    {
         $customFields = array(
            'range_'       . $this->customFieldName,
            'start_range_' . $this->customFieldName,
            'end_range_'   . $this->customFieldName,
        );

        $searchFields = array();
        foreach ($customFields as $field)
        {
            $searchFields[$field] = array(
                'query_type'          => 'default',
                'enable_range_search' => true,
                'is_date_field'       => true,
            );
        }
        return array($module => $searchFields);
    }
}
