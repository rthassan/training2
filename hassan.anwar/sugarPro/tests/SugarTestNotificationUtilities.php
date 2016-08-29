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
 * SugarTestNotificationUtilities.php
 * This is a helper utility to create Notification bean instances for testing
 */
class SugarTestNotificationUtilities
{
    private static $_createdNotifications = array();

    public static function createNotification($id = '')
    {
        $time = mt_rand();
        $notification = BeanFactory::getBean('Notifications');
        $notification->name = 'SugarNotification' . $time;
        $notification->save();
        self::$_createdNotifications[] = $notification;
        return $notification;
    }

    public static function removeAllCreatedNotifications()
    {
        $notification_ids = self::getCreatedNotificationIds();
        
        if (!empty($Notification_ids))
        {
            $GLOBALS['db']->query('DELETE FROM notifications WHERE id IN (\'' . implode("', '", $notification_ids) . '\')');
            $GLOBALS['db']->query('DELETE FROM notifications_audit WHERE parent_id IN (\'' . implode("', '", $notification_ids) . '\')');
         }
    }
    
    public static function getCreatedNotificationIds()
    {
        $notification_ids = array();
        
        foreach (self::$_createdNotifications as $notification)
        {
            $notification_ids[] = $notification->id;
        }
        
        return $notification_ids;
    }
}
