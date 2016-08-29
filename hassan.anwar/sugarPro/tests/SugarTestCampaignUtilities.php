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
 
require_once 'modules/Campaigns/Campaign.php';
require_once 'modules/CampaignLog/CampaignLog.php';
require_once 'modules/CampaignTrackers/CampaignTracker.php';

class SugarTestCampaignUtilities
{
    private static $_createdCampaigns    = array();
    private static $_createdCampaignLogs = array();
    private static $_createdCampaignTrackers = array();

    private function __construct() {}

    public static function createCampaign($id = '', $class='Campaign')
    {
        $time = mt_rand();
    	$name = 'SugarCampaign';
    	$campaign = new $class();
        $campaign->name = $name . $time;
        $campaign->status = 'Active';
        $campaign->campaign_type = 'Email';
        $campaign->end_date = '2010-11-08';
        if(!empty($id))
        {
            $campaign->new_with_id = true;
            $campaign->id = $id;
        }
        $campaign->save();
        self::$_createdCampaigns[] = $campaign;
        return $campaign;
    }

    public static function removeAllCreatedCampaigns() 
    {
        $campaign_ids = self::getCreatedCampaignIds();
        $GLOBALS['db']->query('DELETE FROM campaigns WHERE id IN (\'' . implode("', '", $campaign_ids) . '\')');
    }
    
    public static function getCreatedCampaignIds() 
    {
        $campaign_ids = array();
        foreach (self::$_createdCampaigns as $campaign) {
            $campaign_ids[] = $campaign->id;
        }
        return $campaign_ids;
    }

    public static function setCreatedCampaign($ids)
    {
        $ids = is_array($ids) ? $ids : array($ids);
        foreach ( $ids as $id )
        {
            $campaign = new Campaign();
            $campaign->id = $id;
            self::$_createdCampaigns[] = $campaign;
        }
    }

    public static function createCampaignLog($campaignId, $activityType, $relatedBean)
    {
        $campaignLog                = BeanFactory::getBean("CampaignLog");
        $campaignLog->campaign_id   = $campaignId;
        $campaignLog->related_id    = $relatedBean->id;
        $campaignLog->related_type  = $relatedBean->module_dir;
        $campaignLog->activity_type = $activityType;
        $campaignLog->target_type   = $relatedBean->module_dir;
        $campaignLog->target_id     = $relatedBean->id;

        $campaignLog->save();
        $GLOBALS["db"]->commit();
        self::$_createdCampaignLogs[] = $campaignLog;

        return $campaignLog;
    }

    public static function removeAllCreatedCampaignLogs()
    {
        $campaignLogIds = self::getCreatedCampaignLogsIds();
        $GLOBALS["db"]->query("DELETE FROM campaigns WHERE id IN ('" . implode("', '", $campaignLogIds) . "')");
    }

    public static function getCreatedCampaignLogsIds()
    {
        $campaignLogIds = array();

        foreach (self::$_createdCampaignLogs as $campaignLog) {
            $campaignLogIds[] = $campaignLog->id;
        }

        return $campaignLogIds;
    }

    public static function createCampaignTracker($campaignId, $name = '', $url = '')
    {
        $time = mt_rand();
        if ($name == '') {
            $name = 'SugarCampaignTracker'.$time;
        }
        if ($url == '') {
            $url = 'http://www.foo.com/'.$time;
        }
        $campaignTracker = BeanFactory::getBean("CampaignTrackers");
        $campaignTracker->campaign_id   = $campaignId;
        $campaignTracker->tracker_name    = $name;
        $campaignTracker->tracker_url  = $url;

        $campaignTracker->save();
        $GLOBALS["db"]->commit();
        self::$_createdCampaignTrackers[] = $campaignTracker;

        return $campaignTracker;
    }

    public static function removeAllCreatedCampaignTrackers()
    {
        $campaignTrackerIds = self::getCreatedCampaignTrackerIds();
        $GLOBALS["db"]->query("DELETE FROM campaign_trkrs WHERE id IN ('" . implode("', '", $campaignTrackerIds) . "')");
    }

    public static function getCreatedCampaignTrackerIds()
    {
        $campaignTrackerIds = array();

        foreach (self::$_createdCampaignTrackers as $campaignTracker) {
            $campaignTrackerIds[] = $campaignTracker->id;
        }

        return $campaignTrackerIds;
    }
}

class CampaignMock extends Campaign
{
    public function getNotificationEmailTemplate($test = false)
    {
        if ($test) {
            $templateName = $this->getTemplateNameForNotificationEmail();
            return $this->createNotificationEmailTemplate($templateName);
        }

        return $this->createNotificationEmailTemplate($templateName);

    }
}
