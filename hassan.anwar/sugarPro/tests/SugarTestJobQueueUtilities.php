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

require_once("include/SugarQueue/SugarJobQueue.php");

/**
 * SugarTestJobQueueUtilities
 *
 * utility class for job queues
 *
 */
class SugarTestJobQueueUtilities
{
    private static $_jobQueue;
    private static $_createdJobs = array();

    private function __construct() {}

    /**
     * createAndRunJob
     *
     * This creates and executes the job, returns a new job object
     *
     * @param $name the name of the job
     * @param $target the target function/method
     * @param $data any extra data for the job
     * @param $user the user object to assign to this job
     * @return new job object
     */
    public static function createAndRunJob($name, $target, $data, $user)
    {
        $job = BeanFactory::getBean('SchedulersJobs');
        $job->name = $name;
        $job->target = $target;
        $job->data = $data;
        $job->retry_count = 0;
        $job->assigned_user_id = $user->id;
        self::$_jobQueue = new SugarJobQueue();
        self::$_jobQueue->submitJob($job);
        $job->runJob();
        self::$_createdJobs[] = $job;
        return $job;
    }

    /**
     * removeAllCreatedJobs
     *
     * remove jobs created by this test utility
     *
     * @return boolean true on successful removal
     */
    public static function removeAllCreatedJobs()
    {
        if(empty(self::$_createdJobs))
            return true;
        $jobIds = self::getCreatedJobIds();
        $GLOBALS['db']->query(
            sprintf("DELETE FROM job_queue WHERE id IN ('%s')",
                implode("','", $jobIds))
        );
        self::$_createdJobs = array();
        return true;
    }

    /**
     * getCreatedJobIds
     *
     * get array of job ids created by this utility
     *
     * @return array list of job ids
     */
    public static function getCreatedJobIds()
    {
        $jobIds = array();
        foreach (self::$_createdJobs as $job) {
            // handle the use case where $job could be an array
            if ($job instanceof SchedulersJob) {
                $jobIds[] = $job->id;
            } else {
                $jobIds[] = $job;
            }
        }
        return $jobIds;
    }

    public static function setCreatedJobs(array $jobs)
    {
        foreach ($jobs as $job) {
            self::$_createdJobs[] = $job;
        }
    }
}
?>