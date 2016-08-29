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
 
require_once('modules/Reports/schedule/ReportSchedule.php');

class Bug9170Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $rs;
    
	public function setUp() 
    {
		$this->rs = new ReportSchedule();
	}

    /**
     * Data provider for test create schedule.
     *
     * @return array
     */
	public function testCreateScheduleProvider()
	{
	    global $timedate;

	    $today = gmdate($timedate->get_db_date_time_format(), mktime());
        $yesterday = gmdate($timedate->get_db_date_time_format(), mktime() - 3600 * 24);

        return array(
            array($yesterday, 3600 * 24, $yesterday),
            array($today, 3600 * 24, $today),
        );
	}


	/**
     * @dataProvider testCreateScheduleProvider
     */
	public function testCreateSchedule($start_date, $interval, $expected_date)
	{
        global $timedate;
        $reportID = uniqid();
	    $id = $this->rs->save_schedule("","1",$reportID,$start_date, $interval,1,'pro');
	    $results = $this->rs->get_report_schedule($reportID);
        $next_run = '';
        foreach($results as $ur){
            $next_run = $ur['next_run'];
        }

        $next_run_ts = strtotime($next_run);
        $expected_date_ts = strtotime($expected_date);

        //Assert that the timestamps are within a minute of each other
        $this->assertTrue(($next_run_ts + 60) > $expected_date_ts && $expected_date_ts > ($next_run_ts - 60), "Unable to schedule report");
	}

    /**
     * Data provider for test update schedule.
     *
     * @return array
     */
    public function testUpdateScheduleProvider()
    {
        global $timedate;

        $today = gmdate($timedate->get_db_date_time_format(), mktime());
        $tomorrow = gmdate($timedate->get_db_date_time_format(), mktime() + 3600 * 24);
        $nextWeek = gmdate($timedate->get_db_date_time_format(), mktime() + 3600 * 24 * 7);
        $yesterday = gmdate($timedate->get_db_date_time_format(), mktime() - 3600 * 24);

	    return array(
           array($yesterday, 3600 * 24, $tomorrow),
           array($yesterday, 3600 * 24 * 8, $nextWeek),
           array($today, 3600 * 24, $tomorrow),
           array($today, 3600 * 24 * 7, $nextWeek),
	    );
    }

    /**
     * @dataProvider testUpdateScheduleProvider
     */
	public function testUpdateNextRun($startDate, $interval, $expectedDate)
	{
	    $reportID = uniqid();
	    $id = $this->rs->save_schedule("", "1", $reportID, $startDate, $interval,1,'pro');
	    
	    //Update the report schedule
	    $results = $this->rs->get_report_schedule($reportID);
        $next_run = '';
        foreach($results as $ur){
            $next_run = $ur['next_run'];
        }
	    $this->rs->update_next_run_time($id,$next_run,$results[0]['time_interval'] );
	    
	    //Get the update
	    $updatedResults = $this->rs->get_report_schedule($reportID);
        $nextRun = '';
        foreach($updatedResults as $ur){
            $nextRun = $ur['next_run'];
        }
        $nextRunTimestamp = strtotime($nextRun);
        $expectedDateTimestamp = strtotime($expectedDate);

        $this->assertTrue(($nextRunTimestamp + 60) > $expectedDateTimestamp &&
            $expectedDateTimestamp > ($nextRunTimestamp - 60), "Unable to update scheduled report.");
	}
	
	public function tearDown() 
    {
        $GLOBALS['db']->truncateTableSQL($this->rs->table_name);
	}

}
