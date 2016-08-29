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

require_once('include/workflow/glue.php');
/**
 * Time triggered workflow isn't working when condition checks a calculated field.
 */
class Bug52584Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $shell_object;
    private $focus;
    private $field;
    private $toClean = array();

    public function setUp()
    {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('current_user');

        $this->field = 'a';
        $this->value = 'test';

        $workflow = BeanFactory::getBean('WorkFlow');
        $workflow->base_module = 'Opportunities';
        $workflow->save();
        $this->toClean['workflow'] = $workflow->id;

        $workflowShell = BeanFactory::getBean('WorkFlowTriggerShells');
        $workflowShell->parent_id = $workflow->id;
        $workflowShell->field = $this->field;
        $workflowShell->save();
        $this->toClean['workflow_triggershells'] = $workflowShell->id;

        $this->shell_object = $workflowShell;

        $this->focus = new stdClass();
        $this->focus->{$this->field} = $this->value;
        $this->focus->fetched_row[$this->field] = $this->value;
    }

    public function tearDown()
    {
        foreach ($this->toClean as $key => $value) {
            $GLOBALS['db']->query("DELETE FROM $key WHERE id = '$value'");
        }

        SugarTestHelper::tearDown();
    }

    /**
     * Condition return TRUE if value doesn't changes
     */
    public function testConditionReturnTrueIfValueNotChanges()
    {
        $this->assertTrue($this->getConditionResult());
    }
    /**
     * Condition return FALSE if value changes
     */
    public function testConditionReturnFalseIfValueIsChanges()
    {
        $this->focus->{$this->field} = 'new value';
        $this->assertFalse($this->getConditionResult());
    }

    private function getConditionResult()
    {
        $glue = new WorkFlowGlue();
        $ret = $glue->glue_normal_compare_any_time($this->shell_object);
        $focus = $this->focus;
        return eval("return $ret;");
    }
}
