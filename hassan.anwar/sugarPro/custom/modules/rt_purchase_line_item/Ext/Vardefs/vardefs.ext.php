<?php
// WARNING: The contents of this file are auto-generated.

//Merged from custom/Extension/modules/rt_purchase_line_item/Ext/Vardefs/vardefs.php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//--------------------------------Relationship with Purchase Order--------------------------------
$dictionary['rt_purchase_line_item']['fields']['purchase_order'] = array(
    'studio' => true,
    'required' => false,
    'source' => 'non-db',
    'name' => 'purchase_order',
    'vname' => 'Purchase Order',
    'type' => 'relate',
    'rname' => 'name',
    'id_name' => 'purchase_order_id',
    'join_name' => 'rt_purchase_order',
    'link' => 'purchaseOrder',
    'table' => 'rt_purchase_order',
    'isnull' => 'true',
    'module' => 'rt_purchase_order',
);


$dictionary['rt_purchase_line_item']['fields']['purchase_order_id'] = array(
    'name' => 'purchase_order_id',
    'rname' => 'id',
    'id_name' => 'purchase_order_id',
    'vname' => 'Purchase Order Id',
    'type' => 'id',
    'table' => 'rt_purchase_order',
    'isnull' => 'true',
    'module' => 'rt_purchase_order',
    'dbType' => 'id',
    'reportable' => false,
    'massupdate' => false,
    'duplicate_merge' => 'disabled',
);

$dictionary['rt_purchase_line_item']['fields']['purchaseOrder'] = array(
    'name' => 'purchaseOrder',
    'type' => 'link',
    'relationship' => 'purchase_order_lineitems',
    'module' => 'rt_purchase_order',
    'bean_name' => 'rt_purchase_order',
    'source' => 'non-db',
    'vname' => 'Purchase Order',
);


// Relationship Definition
$dictionary["rt_purchase_line_item"]["relationships"]['purchase_order_lineitems'] = array(
    'lhs_module' => 'rt_purchase_order',
    'lhs_table' => 'rt_purchase_order',
    'lhs_key' => 'id', 'rhs_module' => 'rt_purchase_line_item',
    'rhs_table' => 'rt_purchase_line_item',
    'rhs_key' => 'purchase_order_id',
    'relationship_type' => 'one-to-many',
);



//--------------------------------Relationship with Product Templates aka Catalog--------------------------------

$dictionary['rt_purchase_line_item']['fields']['product_template'] = array(
    'studio' => true,
    'required' => false,
    'source' => 'non-db',
    'name' => 'product_template',
    'vname' => 'Product Template',
    'type' => 'relate',
    'rname' => 'name',
    'default' => '',
    'id_name' => 'product_template_id',
    'join_name' => 'ProductTemplates',
    'link' => 'productTemplate',
    'table' => 'product_templates',
    'isnull' => 'true',
    'module' => 'ProductTemplates',
    'auto_populate' => true,
    'populate_list' => array(
        'discount_price' => 'unit_price',
        'name' => 'name',
        'tax_class' => 'tax_class',
    ),
);


$dictionary['rt_purchase_line_item']['fields']['product_template_id'] = array(
    'name' => 'product_template_id',
    'rname' => 'id',
    'id_name' => 'product_template_id',
    'vname' => 'Product Template Id',
    'type' => 'id',
    'table' => 'product_templates',
    'isnull' => 'true',
    'module' => 'ProductTemplates',
    'dbType' => 'id',
    'reportable' => false,
    'massupdate' => false,
    'duplicate_merge' => 'disabled',
);

$dictionary['rt_purchase_line_item']['fields']['productTemplate'] = array(
    'name' => 'productTemplate',
    'type' => 'link',
    'relationship' => 'product_template_lineitems',
    'module' => 'ProductTemplates',
    'bean_name' => 'ProductTemplate',
    'source' => 'non-db',
    'vname' => 'Product Template',
);


// Relationship Definition
$dictionary["rt_purchase_line_item"]["relationships"]['product_template_lineitems'] = array(
    'lhs_module' => 'ProductTemplates',
    'lhs_table' => 'product_templates',
    'lhs_key' => 'id', 'rhs_module' => 'rt_purchase_line_item',
    'rhs_table' => 'rt_purchase_line_item',
    'rhs_key' => 'product_template_id',
    'relationship_type' => 'one-to-many',
);


//---------------------------------------------------------------------------------------
//For Custom Text Field
$dictionary['rt_purchase_line_item']['fields']['unit_price']['name'] = 'unit_price';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['vname'] = 'Unit Price';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['type'] = 'currency';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['enforced'] = '';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['readonly'] = true;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['default'] = '';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['calculated'] = false;
$dictionary['rt_purchase_line_item']['fields']['unit_price']['len'] = '26,';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['unit_price']['id'] = 'unit_price';

$dictionary['rt_purchase_line_item']['fields']['quantity']['name'] = 'quantity';
$dictionary['rt_purchase_line_item']['fields']['quantity']['vname'] = 'Quantity';
$dictionary['rt_purchase_line_item']['fields']['quantity']['type'] = 'decimal';
$dictionary['rt_purchase_line_item']['fields']['quantity']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['quantity']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['quantity']['default'] = '1';
$dictionary['rt_purchase_line_item']['fields']['quantity']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['quantity']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['quantity']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['quantity']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['quantity']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['quantity']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['quantity']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['quantity']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['quantity']['calculated'] = false;
$dictionary['rt_purchase_line_item']['fields']['quantity']['len'] = '11';
$dictionary['rt_purchase_line_item']['fields']['quantity']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['quantity']['id'] = 'quantity';


$dictionary['rt_purchase_line_item']['fields']['discount_check'] = array('name' => 'discount_check',
    'label' => 'Discount in %',
    'type' => 'bool',
    'module' => 'rt_purchase_line_item',
    'default_value' => true, // true or false
    'audited' => false, // true or false
    'mass_update' => false, // true or false
    'duplicate_merge' => false, // true or false
    'reportable' => true, // true or false
    'importable' => 'true', // 'true', 'false' or 'required'
);


$dictionary['rt_purchase_line_item']['fields']['discount_amount']['name'] = 'discount_amount';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['vname'] = 'Discount Amount';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['type'] = 'discount';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['dbtype'] = 'double';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['calculated'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['len'] = '26,2';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['discount_amount']['id'] = 'discount_amount';



$dictionary['rt_purchase_line_item']['fields']['discount_price']['name'] = 'discount_price';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['vname'] = 'Discounted Price';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['type'] = 'currency';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['readonly'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['calculated'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_price']['len'] = '26,2';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['discount_price']['id'] = 'discount_price';



$dictionary['rt_purchase_line_item']['fields']['discount_less']['name'] = 'discount_less';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['vname'] = 'Discount';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['type'] = 'currency';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['readonly'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['calculated'] = true;
$dictionary['rt_purchase_line_item']['fields']['discount_less']['len'] = '26,2';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['discount_less']['id'] = 'discount_less';


$dictionary['rt_purchase_line_item']['fields']['tax_class']['name'] = 'tax_class';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['vname'] = 'Tax Class';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['type'] = 'varchar';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['readonly'] = true;
$dictionary['rt_purchase_line_item']['fields']['tax_class']['required'] = false;
$dictionary['rt_purchase_line_item']['fields']['tax_class']['massupdate'] = '0';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['help'] = '';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['importable'] = 'true';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['audited'] = false;
$dictionary['rt_purchase_line_item']['fields']['tax_class']['reportable'] = true;
$dictionary['rt_purchase_line_item']['fields']['tax_class']['unified_search'] = false;
$dictionary['rt_purchase_line_item']['fields']['tax_class']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['len'] = '26';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['size'] = '20';
$dictionary['rt_purchase_line_item']['fields']['tax_class']['id'] = 'tax_class';


