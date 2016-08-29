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


class SugarTestProductTemplatesUtilities
{
    protected static $createdProductTemplates = array();

    /**
     * @param string $id
     * @param array $fields         A key value pair to set field values on the created product template
     * @return ProductTemplate
     */
    public static function createProductTemplate($id = '', $fields = array())
    {
        $time = mt_rand();
        $name = 'SugarProductTemplate';
        /* @var $product_template ProductTemplate */
        $product_template = BeanFactory::getBean('ProductTemplates');
        $product_template->name = $name . $time;
        if (!empty($id)) {
            $product_template->new_with_id = true;
            $product_template->id = $id;
        }
        foreach ($fields as $key => $value) {
            $product_template->$key = $value;
        }
        $product_template->save();
        self::$createdProductTemplates[] = $product_template->id;
        return $product_template;
    }

    public static function setCreatedProductTemplate($ids)
    {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        foreach ($ids as $id) {
            self::$createdProductTemplates[] = $id;
        }
    }

    public static function removeAllCreatedProductTemplate()
    {
        $db = DBManagerFactory::getInstance();
        $conditions = implode(',', array_map(array($db, 'quoted'), self::getCreatedProductTemplateIds()));
        if (!empty($conditions)) {
            $db->query('DELETE FROM product_templates WHERE id IN (' . $conditions . ')');
        }

        self::$createdProductTemplates = array();
    }

    public static function getCreatedProductTemplateIds()
    {
        return self::$createdProductTemplates;
    }
}
