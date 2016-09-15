<?php
// WARNING: The contents of this file are auto-generated.



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
$module_name = 'rt_purchase_line_item';
$viewdefs[$module_name]['base']['view']['subpanel-list'] = array(
    'panels' =>
    array(
        array(
            'name' => 'panel_header',
            'label' => 'LBL_PANEL_1',
            'fields' =>
            array(
                array(
                    'label' => 'LBL_NAME',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'name',
                    'link' => true,
                ),
                array(
                    'label' => 'LBL_DATE_MODIFIED',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'date_modified',
                ),
                array(
                    'label' => 'Quantity',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'quantity',
                ),
                array(
                    'label' => 'Product Template',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'product_template',
                    'link' => true,
                ),
                array(
                    'label' => 'Unit Price',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'unit_price',
                ),
                array(
                    'label' => 'Tax Class',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'tax_class',
                ),
                array(
                    'label' => 'Discount in %',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'discount_check',
                ),
                array(
                    'label' => 'Discount Amount',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'discount_amount',
                ),
                array(
                    'label' => 'Discounted Price',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'discount_price',
                ),
                array(
                    'label' => 'Discount',
                    'enabled' => true,
                    'default' => true,
                    'name' => 'discount_less',
                ),
            ),
        ),
    ),
    'orderBy' => array(
        'field' => 'date_modified',
        'direction' => 'desc',
    ),
);
