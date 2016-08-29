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
 
require_once 'modules/Calls/Call.php';

class SugarTestCallUtilities
{
    private static $_createdCalls = array();

    private function __construct() {}

    public static function createCall() 
    {
        global $current_user;
        $time = mt_rand();
    	$name = 'Call';
    	$call = new Call();
        $call->name = $name . $time;
        $call->date_start = TimeDate::getInstance()->getNow()->asDb();
        $call->assigned_user_id = $current_user->id;
        $call->save();
        self::$_createdCalls[] = $call;
        return $call;
    }

    public static function removeAllCreatedCalls() 
    {
        $call_ids = self::getCreatedCallIds();
        $GLOBALS['db']->query('DELETE FROM calls WHERE id IN (\'' . implode("', '", $call_ids) . '\')');
    }
    
    public static function removeCallContacts(){
    	$call_ids = self::getCreatedCallIds();
        $GLOBALS['db']->query('DELETE FROM calls_contacts WHERE call_id IN (\'' . implode("', '", $call_ids) . '\')');
    }
    
    public static function getCreatedCallIds() 
    {
        $call_ids = array();
        foreach (self::$_createdCalls as $call) {
            $call_ids[] = $call->id;
        }
        return $call_ids;
    }

    public static function addCallUserRelation($call_id, $user_id) {
        $id = create_guid();
        $GLOBALS['db']->query("INSERT INTO calls_users (id, call_id, user_id) values ('{$id}', '{$call_id}', '{$user_id}')");
        return $id;
    }

    public static function addCallContactRelation($call_id, $contact_id)
    {
        $result = $GLOBALS['db']->query(
            "SELECT id FROM calls_contacts WHERE call_id='{$call_id}' AND contact_id='{$contact_id}'"
        );
        $result = $GLOBALS['db']->fetchByAssoc($result);
        if (empty($result)) {
            $id = create_guid();
            $GLOBALS['db']->query(
                "INSERT INTO calls_contacts (id, call_id, contact_id) values ('{$id}', '{$call_id}', '{$contact_id}')"
            );
        } else {
            $id = $result['id'];
        }
        return $id;
    }

    public static function removeCallUsers()
    {
        $call_ids = self::getCreatedCallIds();
        $GLOBALS['db']->query(sprintf("DELETE FROM calls_users WHERE call_id IN ('%s')", implode("', '", $call_ids)));
    }
}
