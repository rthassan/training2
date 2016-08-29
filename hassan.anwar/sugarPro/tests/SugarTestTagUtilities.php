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

require_once 'modules/Tags/Tag.php';

/** 
 * SugarTestTagUtilities is a unit test class to test Tags 
 **/
class SugarTestTagUtilities
{
    private static $createdTagIds = array();
    private static $newTagNames = array();

    private function __construct()
    {
    }

    /**
     * Create a Tag for use in a Unit Test
     *
     * @param array $values - values you want to override
     *
     * @return SugarBean tag
     */
    public static function createTag($values = array())
    {
        $num = mt_rand();
        $defaults =
            array(
                'name' => 'SugarTag' . $num,
            );
        $values = array_merge($defaults, $values);
        $tag = BeanFactory::newBean('Tags');
        $tag->populateFromRow($values);
        self::$createdTagIds[] = $tag->save();
        return $tag;
    }

    /*
     * Create a new tag name without creating the actual tag record
     */
    public static function createNewTagName()
    {
        $num = mt_rand();
        $newTagName = 'SugarNameTag' . $num;
        self::$newTagNames[] = $newTagName;
        return $newTagName;
    }

    /**
     * Remove all Tags for use in a Unit Test
     *
     * @return null
     */
    public static function removeAllCreatedTags()
    {
        $tagIds = self::$createdTagIds;
        $tagNames = self::$newTagNames;
        $GLOBALS['db']->query(
            'DELETE FROM tags WHERE id IN (\'' . implode("', '", $tagIds) . '\')' .
            ' OR name IN (\'' . implode("', '", $tagNames) . '\')'
        );
    }

    /**
     * Delete tags M2M relationship data
     *
     * @param string $moduleName
     * @param string $beanId
     */
    public static function deleteM2MRelationships($moduleName, $beanId)
    {
        $sql = "DELETE FROM tag_bean_rel WHERE 
                bean_module = '$moduleName' AND 
                bean_id = '$beanId'";
        $GLOBALS['db']->query($sql);
    }
}
