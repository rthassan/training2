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

require_once 'modules/Reports/Report.php';

/**
 * CRYS-408:
 * The Account name is used in the empty amount field in the report.
 * It also covers SFA-2990. Since the bug itself was fixed by it.
 */
class CRYS408Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $report;
    private $user;
    private $account;

    /**
     * @var array Request for creating/deleting currency field for Accounts module
     */
    private $request = array(
        'comments' => '',
        'default' => '',
        'dependency' => '',
        'dependency_display' => '',
        'duplicate_merge' => 1,
        'enforced' => '',
        'formula' => '',
        'formula_display' => '',
        'help' => '',
        'importable' => true,
        'is_new' => 1,
        'is_update' => true,
        'label' => 'LBL_CUR_CRYS408',
        'labelValue' => 'cur_crys408',
        'module' => 'ModuleBuilder',
        'name' => 'cur_crys408',
        'new_dropdown' => '',
        'reportable' => 1,
        'reportableCheckbox' => 1,
        'to_pdf' => true,
        'type' => 'currency',
        'view_module' => 'Accounts',
    );

    public function setUp()
    {
        SugarTestHelper::setUp("beanList");
        SugarTestHelper::setUp("beanFiles");
        SugarTestHelper::setUp("app_strings");
        SugarTestHelper::setUp("app_list_strings");

        $this->user = SugarTestUserUtilities::createAnonymousUser(true, 1);
        $GLOBALS['current_user'] = $this->user;

        $this->request['action'] = 'saveField';
        $_REQUEST = $this->request;
        $_POST = $this->request;
        $mb = new ModuleBuilderController();
        $mb->action_saveField();

        $this->account = SugarTestAccountUtilities::createAccount();
        $this->account->name = 'CRYS408Account';
        $this->account->cur_crys408_c = null;
        $this->account->save();
    }

    public function tearDown()
    {
        $this->request['action'] = 'DeleteField';
        $this->request['name'] = 'cur_crys408_c';
        $_REQUEST = $this->request;
        $_POST = $this->request;
        $mb = new ModuleBuilderController();
        $mb->action_DeleteField();

        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestHelper::tearDown();
    }

    /**
     * Testing null currency will not use previous name field value
     */
    public function testNullValueFieldShouldNotTakePreviousFieldValue()
    {
        /**
         * Report defs for generating the report
         */
        $rep_defs = array(
            'display_columns' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'table_key' => 'self',
                ),
                1 => array(
                    'name' => 'cur_crys408_c',
                    'label' => 'cur_crys408',
                    'table_key' => 'self',
                ),
            ),
            'module' => 'Accounts',
            'group_defs' => array(),
            'summary_columns' => array(),
            'report_name' => 'CRYS408Report',
            'chart_type' => 'none',
            'do_round' => 1,
            'numerical_chart_column' => '',
            'numerical_chart_column_type' => '',
            'assigned_user_id' => '1',
            'report_type' => 'tabular',
            'full_table_list' => array(
                'self' => array(
                    'value' => 'Accounts',
                    'module' => 'Accounts',
                    'label' => 'Accounts',
                    'dependents' => array(),
                ),
            ),
            'filters_def' => array(
                'Filter_1' => array(
                    0 => array(
                        'name' => 'name',
                        'table_key' => 'self',
                        'qualifier_name' => 'is',
                        'input_name0' => '',
                        'input_name1' => '',
                        'column_name' => 'self:name',
                        'id' => 'rowid0',
                    ),
                    'operator' => 'AND',
                ),
            ),
        );

        $rep_defs['filters_def']['Filter_1']['0']['input_name0'] = $this->account->id;
        $rep_defs['filters_def']['Filter_1']['0']['input_name1'] = $this->account->name;
        $json = getJSONobj();
        $tmp = $json->encode($rep_defs);
        $this->report = new Report($tmp);
        $this->report->run_query();
        while (($row = $this->report->get_next_row()) != 0) {
            $this->assertNotEquals($row['cells']['0'], $row['cells']['1'], '2-nd field should not get 1-st field\'s value');
        }
    }
}
