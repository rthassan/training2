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
 
require_once 'modules/ProductBundles/ProductBundle.php';

class SugarTestProductBundleUtilities
{
    private static $_createdProductBundles = array();

    private function __construct() {}

    public static function createProductBundle($id = '') 
    {
        $time = mt_rand();
    	$name = 'SugarProductBundle';
    	$productbundle = new ProductBundle();
        $productbundle->name = $name . $time;
        $productbundle->bundle_stage = 'draft';
        if(!empty($id))
        {
            $productbundle->new_with_id = true;
            $productbundle->id = $id;
        }
        $productbundle->save();
        self::$_createdProductBundles[] = $productbundle;
        return $productbundle;
    }

    public static function setCreatedProductBundle($productbundle_ids) {
    	foreach($productbundle_ids as $productbundle_id) {
    		$productbundle = new ProductBundle();
    		$productbundle->id = $productbundle_id;
        	self::$_createdProductBundles[] = $productbundle;
    	} // foreach
    } // fn
    
    public static function removeAllCreatedProductBundles() 
    {
        $productbundle_ids = self::getCreatedProductBundleIds();
        $GLOBALS['db']->query('DELETE FROM product_bundles WHERE id IN (\'' . implode("', '", $productbundle_ids) . '\')');
        $GLOBALS['db']->query('DELETE FROM product_bundle_product WHERE bundle_id IN (\'' . implode("', '", $productbundle_ids) . '\')');
        $GLOBALS['db']->query('DELETE FROM product_bundle_quote WHERE bundle_id IN (\'' . implode("', '", $productbundle_ids) . '\')');
    }
        
    public static function getCreatedProductBundleIds() 
    {
        $productbundle_ids = array();
        foreach (self::$_createdProductBundles as $productbundle) {
            $productbundle_ids[] = $productbundle->id;
        }
        return $productbundle_ids;
    }
}
?>
