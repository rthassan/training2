<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




$dictionary["TaxRate"]["fields"]["purchase_order"] = array (
    'name' => 'purchase_order',
            'type' => 'link',
            'relationship' => 'taxRate_purchases',
            'module' => 'rt_purchase_order',
            'bean_name' => 'rt_purchase_order',
            'source' => 'non-db',
            'vname' => 'Purchase Order',
            
      
  );

?>