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

require_once 'modules/SchedulersJobs/SchedulersJob.php';

/**
 * Bug #56537 : Schedule Jobs don't work with classes
 *
 * @ticket 56537
 */
class Bug56573Test extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @var string job id
     */
    protected $id;

    public function setUp()
    {
        $this->id = null;
        SugarTestHelper::setUp('current_user');
    }

    public function tearDown()
    {
        if (!empty($this->id)) {
            $job = new SchedulersJob();
            $job->mark_deleted($this->id);
        }
        SugarTestHelper::tearDown();
    }

    protected function execJob($name, $data)
    {
        require_once('include/SugarQueue/SugarJobQueue.php');

        $job = new SchedulersJob();
        $job->name = "Bug56573Test Alert Job - '{$name}'";
        $job->target = "class::Bug56573TestJob";
        $job->data = $data;
        $job->assigned_user_id = $GLOBALS['current_user']->id;

        // Add the Job the the job Queue
        $jq = new SugarJobQueue();
        $jq->submitJob($job);
        // Run the job
        $job->runJob();

        // Save id for cleaning
        $this->id = $job->id;

        return $job;
    }

    public static function provider()
    {
        return array(
            array(
                'Success',
                implode(
                    ',',
                    array(
                        'status' =>  SchedulersJob::JOB_STATUS_RUNNING,
                        'resolution' => SchedulersJob::JOB_SUCCESS,
                        'return' => true
                    )
                ),
                SchedulersJob::JOB_STATUS_DONE,
                SchedulersJob::JOB_SUCCESS
            ),
            array(
                'Failure',
                implode(
                    ',',
                    array(
                        'status' => SchedulersJob::JOB_STATUS_RUNNING,
                        'resolution' => SchedulersJob::JOB_FAILURE,
                        'return' => false
                    )
                ),
                SchedulersJob::JOB_STATUS_DONE,
                SchedulersJob::JOB_FAILURE

            ),
            array(
                'Queue',
                implode(
                    ',',
                    array(
                        'status' => SchedulersJob::JOB_STATUS_QUEUED,
                        'resolution' => SchedulersJob::JOB_PARTIAL,
                        'return' => false
                    )
                ),
                SchedulersJob::JOB_STATUS_QUEUED,
                SchedulersJob::JOB_PARTIAL
            ),
        );
    }

    /**
     * Test if runJob() sets proper values of status/resolution
     *
     * @dataProvider provider
     * @group 56537
     */
    public function testJob($name, $data, $status, $resolution)
    {
        $job = $this->execJob($name, $data);
        $this->assertEquals($resolution, $job->resolution, "Wrong resolution");
        $this->assertEquals($status, $job->status, "Wrong status");
    }
}

/**
 * Job Class for testing SchedulersJob
 */
class Bug56573TestJob implements RunnableSchedulerJob
{
    public function run($data)
    {
        // Pull all the test data
        $data = explode(',', $data);

        // Set status and resolution
        $this->job->status = $data[0];
        $this->job->resolution = $data[1];

        return $data[2];
    }

    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }
}
