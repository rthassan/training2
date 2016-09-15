<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dictionary["ProductTemplates"]["fields"]["product_purchase_line_items"] = array(
    'name' => 'product_purchase_line_items',
    'type' => 'link',
    'relationship' => 'product_template_lineitems',
    'module' => 'rt_purchase_line_item',
    'bean_name' => 'rt_purchase_line_item',
    'source' => 'non-db',
    'vname' => 'Purchase Line Items',
);

?>