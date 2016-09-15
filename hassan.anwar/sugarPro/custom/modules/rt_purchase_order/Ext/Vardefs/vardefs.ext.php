<?php
// WARNING: The contents of this file are auto-generated.

//Merged from custom/Extension/modules/rt_purchase_order/Ext/Vardefs/vardefs.php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dictionary["rt_purchase_order"]["fields"]["purchase_line_items"] = array(
    'name' => 'purchase_line_items',
    'type' => 'link',
    'relationship' => 'purchase_order_lineitems',
    'module' => 'rt_purchase_line_item',
    'bean_name' => 'rt_purchase_line_item',
    'source' => 'non-db',
    'vname' => 'Purchase Line Items',
);


//For Custom Text Field
$dictionary['rt_purchase_order']['fields']['total_price']['name'] = 'total_price';
$dictionary['rt_purchase_order']['fields']['total_price']['vname'] = 'Subtotal';
$dictionary['rt_purchase_order']['fields']['total_price']['type'] = 'currency';
$dictionary['rt_purchase_order']['fields']['total_price']['enforced'] = '';
$dictionary['rt_purchase_order']['fields']['total_price']['readonly'] = true;
$dictionary['rt_purchase_order']['fields']['total_price']['required'] = false;
$dictionary['rt_purchase_order']['fields']['total_price']['massupdate'] = '0';
$dictionary['rt_purchase_order']['fields']['total_price']['help'] = '';
$dictionary['rt_purchase_order']['fields']['total_price']['importable'] = 'true';
$dictionary['rt_purchase_order']['fields']['total_price']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_price']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_order']['fields']['total_price']['audited'] = false;
$dictionary['rt_purchase_order']['fields']['total_price']['reportable'] = true;
$dictionary['rt_purchase_order']['fields']['total_price']['unified_search'] = false;
$dictionary['rt_purchase_order']['fields']['total_price']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_price']['calculated'] = false;
$dictionary['rt_purchase_order']['fields']['total_price']['len'] = '26,2';
$dictionary['rt_purchase_order']['fields']['total_price']['size'] = '20';
$dictionary['rt_purchase_order']['fields']['total_price']['id'] = 'total_price';

//For Custom Text Field
$dictionary['rt_purchase_order']['fields']['total_final']['name'] = 'total_final';
$dictionary['rt_purchase_order']['fields']['total_final']['vname'] = 'Total';
$dictionary['rt_purchase_order']['fields']['total_final']['type'] = 'currency';
$dictionary['rt_purchase_order']['fields']['total_final']['enforced'] = '';
$dictionary['rt_purchase_order']['fields']['total_final']['readonly'] = true;
$dictionary['rt_purchase_order']['fields']['total_final']['required'] = false;
$dictionary['rt_purchase_order']['fields']['total_final']['massupdate'] = '0';
$dictionary['rt_purchase_order']['fields']['total_final']['help'] = '';
$dictionary['rt_purchase_order']['fields']['total_final']['importable'] = 'true';
$dictionary['rt_purchase_order']['fields']['total_final']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_final']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_order']['fields']['total_final']['audited'] = false;
$dictionary['rt_purchase_order']['fields']['total_final']['reportable'] = true;
$dictionary['rt_purchase_order']['fields']['total_final']['unified_search'] = false;
$dictionary['rt_purchase_order']['fields']['total_final']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_final']['calculated'] = false;
$dictionary['rt_purchase_order']['fields']['total_final']['len'] = '26,2';
$dictionary['rt_purchase_order']['fields']['total_final']['size'] = '20';
$dictionary['rt_purchase_order']['fields']['total_final']['id'] = 'total_final';

//For Custom Text Field
$dictionary['rt_purchase_order']['fields']['total_discount']['name'] = 'total_discount';
$dictionary['rt_purchase_order']['fields']['total_discount']['vname'] = 'Discount';
$dictionary['rt_purchase_order']['fields']['total_discount']['type'] = 'currency';
$dictionary['rt_purchase_order']['fields']['total_discount']['enforced'] = '';
$dictionary['rt_purchase_order']['fields']['total_discount']['readonly'] = true;
$dictionary['rt_purchase_order']['fields']['total_discount']['required'] = false;
$dictionary['rt_purchase_order']['fields']['total_discount']['massupdate'] = '0';
$dictionary['rt_purchase_order']['fields']['total_discount']['help'] = '';
$dictionary['rt_purchase_order']['fields']['total_discount']['importable'] = 'true';
$dictionary['rt_purchase_order']['fields']['total_discount']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_discount']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_order']['fields']['total_discount']['audited'] = false;
$dictionary['rt_purchase_order']['fields']['total_discount']['reportable'] = true;
$dictionary['rt_purchase_order']['fields']['total_discount']['unified_search'] = false;
$dictionary['rt_purchase_order']['fields']['total_discount']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['total_discount']['calculated'] = false;
$dictionary['rt_purchase_order']['fields']['total_discount']['len'] = '26,2';
$dictionary['rt_purchase_order']['fields']['total_discount']['size'] = '20';
$dictionary['rt_purchase_order']['fields']['total_discount']['id'] = 'total_discount';

$dictionary['rt_purchase_order']['fields']['tax_rate']['name'] = 'tax_rate';
$dictionary['rt_purchase_order']['fields']['tax_rate']['vname'] = 'Tax Rate';
$dictionary['rt_purchase_order']['fields']['tax_rate']['type'] = 'custom-tax-rate';
$dictionary['rt_purchase_order']['fields']['tax_rate']['dbType'] = 'varchar';
$dictionary['rt_purchase_order']['fields']['tax_rate']['required'] = true;
$dictionary['rt_purchase_order']['fields']['tax_rate']['massupdate'] = '0';
$dictionary['rt_purchase_order']['fields']['tax_rate']['options'] = 'tax_list';
$dictionary['rt_purchase_order']['fields']['tax_rate']['comments'] = 'Dropdown';
$dictionary['rt_purchase_order']['fields']['tax_rate']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['tax_rate']['audited'] = false;
$dictionary['rt_purchase_order']['fields']['tax_rate']['reportable'] = true;
$dictionary['rt_purchase_order']['fields']['tax_rate']['importable'] = true;
$dictionary['rt_purchase_order']['fields']['tax_rate']['duplicate_merge'] = false;


$dictionary['rt_purchase_order']['fields']['tax_value']['name'] = 'tax_value';
$dictionary['rt_purchase_order']['fields']['tax_value']['vname'] = 'Tax Value';
$dictionary['rt_purchase_order']['fields']['tax_value']['type'] = 'currency';
$dictionary['rt_purchase_order']['fields']['tax_value']['readonly'] = true;
$dictionary['rt_purchase_order']['fields']['tax_value']['required'] = false;
$dictionary['rt_purchase_order']['fields']['tax_value']['massupdate'] = '0';
$dictionary['rt_purchase_order']['fields']['tax_value']['help'] = '';
$dictionary['rt_purchase_order']['fields']['tax_value']['importable'] = 'true';
$dictionary['rt_purchase_order']['fields']['tax_value']['duplicate_merge'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['tax_value']['duplicate_merge_dom_value'] = '0';
$dictionary['rt_purchase_order']['fields']['tax_value']['audited'] = false;
$dictionary['rt_purchase_order']['fields']['tax_value']['reportable'] = true;
$dictionary['rt_purchase_order']['fields']['tax_value']['unified_search'] = false;
$dictionary['rt_purchase_order']['fields']['tax_value']['merge_filter'] = 'disabled';
$dictionary['rt_purchase_order']['fields']['tax_value']['len'] = '26,2';
$dictionary['rt_purchase_order']['fields']['tax_value']['size'] = '20';
$dictionary['rt_purchase_order']['fields']['tax_value']['id'] = 'tax_value';


