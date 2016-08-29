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

include_once 'modules/Categories/Category.php';

class SugarTestCategoryUtilities
{
    protected static $_rootBean;
    protected static $_createdBeans = array();

    private function __construct() {}

    public static function createRootBean($values = array())
    {
        $defaults = array(
            'name' => 'SugarCategoryRoot' . time(),
        );
        $values = array_merge($defaults, $values);
        $bean = new CategoryMock();
        $bean->populateFromRow($values);
        $bean->saveAsRoot();
        DBManagerFactory::getInstance()->commit();
        self::$_rootBean = $bean;
        return $bean;
    }

    public static function createBean($values = array())
    {
        if (!isset(self::$_rootBean)) {
            self::createRootBean();
        }
        $defaults = array(
            'name' => 'SugarCategory' . time(),
            'root' => self::$_rootBean->id
        );

        $values = array_merge($defaults, $values);
        $bean = new CategoryMock();
        $bean->populateFromRow($values);
        $bean->save();
        DBManagerFactory::getInstance()->commit();
        self::$_createdBeans[] = $bean;
        return $bean;
    }

    public static function removeAllCreatedBeans()
    {
        $db = DBManagerFactory::getInstance();
        $ids = self::getCreatedBeanIds();
        if (count($ids) > 0) {
            $conditions = implode(',', array_map(array($db, 'quoted'), $ids));
            $db->query('DELETE FROM categories WHERE id IN (' . $conditions . ')');
            self::$_createdBeans = array();
        }
        if (isset(self::$_rootBean)) {
            $db->query('DELETE FROM categories WHERE id = ' . $db->quoted(self::$_rootBean->id));
            self::$_rootBean = null;
        }
    }

    public static function getCreatedBeanIds()
    {
        $ids = array();
        foreach (self::$_createdBeans as $bean) {
            $ids[] = $bean->id;
        }
        return $ids;
    }

    public static function addCreatedBean($id)
    {
        $category = BeanFactory::retrieveBean('Categories', $id, array(
            'use_cache' => false,
        ));
        if ($category instanceof Category) {
            self::$_createdBeans[] = $category;
        }
    }
}

class CategoryMock extends Category
{

    /**
     * Public wrapper method to access protected Category::getQuery method.
     * @return SugarQuery
     */
    public function getQueryMock()
    {
        return parent::getQuery();
    }

    /**
     * Public wrapper method to access protected Category::getTreeData method.
     * @return array
     */
    public function getTreeDataMock($root)
    {
        return parent::getTreeData($root);
    }

    /**
     * Public wrapper method to access protected Category::shiftLeftRight method.
     * @return null
     */
    public function shiftLeftRightMock($key, $delta)
    {
        return parent::shiftLeftRight($key, $delta);
    }

    /**
     * Public wrapper method to access protected Category::addNode method.
     * @return null
     */
    public function addNodeMock($node, $key, $levelUp)
    {
        return parent::addNode($node, $key, $levelUp);
    }

    /**
     * Public wrapper method to access protected Category::moveNode method.
     * @return null
     */
    public function moveNodeMock($target, $key, $levelUp)
    {
        return parent::moveNode($target, $key, $levelUp);
    }
}
