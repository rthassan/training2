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

require_once "modules/Tasks/Task.php";

class TasksTest extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

	public static function tearDownAfterClass()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    public function setUp()
    {
        $_REQUEST['module'] = 'Tasks';
    }

    public function tearDown()
    {
        unset($_REQUEST['module']);
        if(!empty($this->taskid)) {
            $GLOBALS['db']->query("DELETE FROM tasks WHERE id='{$this->taskid}'");
        }
    }

    /**
     * @ticket 39259
     */
    public function testListviewTimeDueFieldProperlyHandlesDst()
    {
        $task = new Task();
        $task->name = "New Task";
        $task->date_due = $GLOBALS['timedate']->to_display_date_time("2010-08-30 15:00:00");
        $listViewFields = $task->get_list_view_data();
        $this->assertEquals($GLOBALS['timedate']->to_display_time("15:00:00"), $listViewFields['TIME_DUE']);
    }

    /**
     * @group bug40999
     */
    public function testTaskStatus()
    {
         $task = new Task();
         $this->taskid = $task->id = create_guid();
         $task->new_with_id = 1;
         $task->status = 'Done';
         $task->save();
         // then retrieve
         $task = new Task();
         $task->retrieve($this->taskid);
         $this->assertEquals('Done', $task->status);
    }

    /**
     * @group bug40999
     */
    public function testTaskEmptyStatus()
    {
         $task = new Task();
         $this->taskid = $task->id = create_guid();
         $task->new_with_id = 1;
         $task->save();
         // then retrieve
         $task = new Task();
         $task->retrieve($this->taskid);
         $this->assertEquals('Not Started', $task->status);
    }

}
