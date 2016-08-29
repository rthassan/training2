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

require_once 'modules/ForecastWorksheets/ForecastWorksheet.php';

class SugarTestWorksheetUtilities
{
    private static $_createdWorksheets = array();

    private function __construct() {}

    public static function createWorksheet($id = '')
    {
        $time = mt_rand();
        $name = 'SugarWorksheet';
        $worksheet = BeanFactory::newBean("ForecastWorksheets");
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
            $worksheet = BeanFactory::newBean("ForecastWorksheets");
            $worksheet->id = $worksheet_id;
            self::$_createdWorksheets[] = $worksheet;
        }
    }

    public static function removeAllCreatedWorksheets()
    {
        $db = DBManagerFactory::getInstance();
        //clean up any worksheets and draft versions as well.  Some were made by code, not the tests,
        //so we have to do some shenanigans to find them.
        $db->query("delete from forecast_worksheets where name like 'Sugar%'");
    }
    
    public static function removeSpecificCreatedWorksheets($ids)
    {
        /* @var $db DBManager */
        $db = DBManagerFactory::getInstance();
        $query = "delete from forecast_worksheets where id in('" . implode("', '", $ids) . "')";
        $db->query($query);
        $db->commit();
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

    public static function removeAllWorksheetsForParentIds(array $ids)
    {
        $db = DBManagerFactory::getInstance();
        $query = "delete from forecast_worksheets where parent_id in('" . implode("', '", $ids) . "')";
        $db->query($query);
        $db->commit();
    }

    public static function loadWorksheetForBeans($bean, array $ids, $isCommit = false)
    {
        if($bean instanceof SugarBean) {
            $bean = $bean->module_name;
        }

        $worksheets = array();

        /* @var $worksheet ForecastWorksheet */
        foreach($ids as $id) {
            $worksheet = BeanFactory::getBean('ForecastWorksheets');
            $worksheet->retrieve_by_string_fields(
                array(
                    'parent_type' => $bean,
                    'parent_id' => $id,
                    'draft' => ($isCommit === false) ? 1 : 0,
                    'deleted' => 0,
                )
            );

            if (empty($worksheet->id)) {
                continue;
            }

            $worksheets[] = $worksheet;
        }

        return $worksheets;
    }

    /**
     * Utility method to find a specific worksheet for a passed in bean
     *
     * @param SugarBean $bean
     * @param bool $isCommit
     * @param bool $isDeleted
     * @return ForecastWorksheet|boolean        Return the worksheet if found, otherwise return false
     */
    public static function loadWorksheetForBean($bean, $isCommit = false, $isDeleted = false)
    {
        /* @var $worksheet ForecastWorksheet */
        $worksheet = BeanFactory::getBean('ForecastWorksheets');
        $worksheet->retrieve_by_string_fields(
            array(
                'parent_type' => $bean->module_name,
                'parent_id' => $bean->id,
                'draft' => ($isCommit === false) ? 1 : 0,
                'deleted' => ($isDeleted === false) ? 0 : 1
            ),
            true,
            false
        );

        if (empty($worksheet->id)) {
            return false;
        }

        self::$_createdWorksheets[] = $worksheet;

        return $worksheet;
    }
}
