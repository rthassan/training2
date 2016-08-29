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

$beanList = array();
$beanFiles = array();
require('include/modules.php');
$GLOBALS['beanList'] = $beanList;
$GLOBALS['beanFiles'] = $beanFiles;
require_once 'modules/Quotas/Quota.php';

class SugarTestQuotaUtilities
{
    private static $_createdQuotas = array();
    private static $_createdUserIds = array();

    private function __construct() {}

    public static function createQuota($amount=500, $id = '')
    {
        $quota = new Quota();
        $quota->amount = $amount;
        $quota->currency_id = -99;
        $quota->committed = 1;
        if(!empty($id))
        {
            $quota->new_with_id = true;
            $quota->id = $id;
        }
        $quota->save();
        self::$_createdQuotas[] = $quota;
        return $quota;
    }

    public static function setCreatedQuota($quota_ids) {
    	foreach($quota_ids as $quota_id) {
    		$quota = new Quota();
    		$quota->id = $quota_id;
        	self::$_createdQuotas[] = $quota;
    	} // foreach
    } // fn
    
    public static function setCreatedUserIds($user_ids)
    {
    	self::$_createdUserIds = $user_ids;	
    }
    
    public static function getCreatedUserIds()
    {
    	return self::$_createdUserIds;
    }

    public static function removeAllCreatedQuotas()
    {
        $quota_ids = self::getCreatedQuotaIds();
        
        $GLOBALS['db']->query('DELETE FROM quotas WHERE id IN (\'' . implode("', '", $quota_ids) . '\')');
        //remove quotas generated in the worksheets by using the temporary user id's
        $GLOBALS['db']->query('DELETE FROM quotas WHERE user_id IN (\'' . implode("', '", self::getCreatedUserIds()) . '\')');

        self::$_createdQuotas = array();
    }

    public static function getCreatedQuotaIds()
    {
        $quota_ids = array();
        foreach (self::$_createdQuotas as $quota) {
            $quota_ids[] = $quota->id;
        }
        return $quota_ids;
    }
}
?>
