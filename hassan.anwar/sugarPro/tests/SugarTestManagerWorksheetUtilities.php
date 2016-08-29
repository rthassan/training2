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

require_once 'modules/ForecastManagerWorksheets/ForecastManagerWorksheet.php';

class SugarTestManagerWorksheetUtilities
{
    private static $_createdWorksheets = array();

    public static function createWorksheet($id = '')
    {
        $time = mt_rand();
        $name = 'SugarManagerWorksheet';
        $worksheet = BeanFactory::newBean("ForecastManagerWorksheets");
        $worksheet->name = $name . $time;

        if(!empty($id))
        {
            $worksheet->new_with_id = true;
            $worksheet->id = $id;
        }
        $worksheet->save();
        self::$_createdWorksheets[] = $worksheet;
        return $worksheet;
    }

    public static function setCreatedWorksheet($worksheet_ids)
    {
        foreach($worksheet_ids as $worksheet_id)
        {
            $worksheet = BeanFactory::newBean("ForecastManagerWorksheets");
            $worksheet->id = $worksheet_id;
            self::$_createdWorksheets[] = $worksheet;
        }
    }

    public static function removeAllCreatedWorksheets()
    {
        $db = DBManagerFactory::getInstance();
        //clean up any worksheets and draft versions as well.  Some were made by code, not the tests,
        //so we have to do some shenanigans to find them.
        $db->query("delete from forecast_manager_worksheets where name like 'Sugar%'");
    }

    public static function removeSpecificCreatedWorksheets($ids)
    {
        $GLOBALS["db"]->query("delete from forecast_manager_worksheets where id in('" . implode("', '", $ids) . "')" );
    }

    public static function getCreatedWorksheetIds()
    {
        $worksheet_ids = array();
        foreach (self::$_createdWorksheets as $worksheet)
        {
            $worksheet_ids[] = $worksheet->id;
        }
        return $worksheet_ids;
    }

    /**
     * @param $user_id
     * @param $timeperiod_id
     * @param bool $isCommit
     * @return bool|ForecastManagerWorksheet
     */
    public static function getManagerWorksheetForUserAndTimePeriod($user_id, $timeperiod_id, $isCommit = false)
    {
        /* @var $worksheet ForecastManagerWorksheet */
        $worksheet = BeanFactory::getBean('ForecastManagerWorksheets');
        $worksheet->retrieve_by_string_fields(
            array(
                'user_id' => $user_id,
                'timeperiod_id' => $timeperiod_id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => 0,
            )
        );

        if (empty($worksheet->id)) {
            return false;
        }

        self::$_createdWorksheets[] = $worksheet;

        return $worksheet;
    }
}
