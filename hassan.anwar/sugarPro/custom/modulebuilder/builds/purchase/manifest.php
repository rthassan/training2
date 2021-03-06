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

// THIS CONTENT IS GENERATED BY MBPackage.php
$manifest = array (
  'built_in_version' => '7.6.0.0',
  'acceptable_sugar_versions' => 
  array (
    0 => '',
  ),
  'acceptable_sugar_flavors' => 
  array (
    0 => 'PRO',
    1 => 'CORP',
    2 => 'ENT',
    3 => 'ULT',
  ),
  'readme' => '',
  'key' => 'rt',
  'author' => 'Hassan Anwar',
  'description' => '',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'purchase',
  'published_date' => '2016-09-15 13:16:28',
  'type' => 'module',
  'version' => 1473945388,
  'remove_tables' => 'prompt',
);


$installdefs = array (
  'id' => 'purchase',
  'beans' => 
  array (
    0 => 
    array (
      'module' => 'rt_purchase_line_item',
      'class' => 'rt_purchase_line_item',
      'path' => 'modules/rt_purchase_line_item/rt_purchase_line_item.php',
      'tab' => true,
    ),
    1 => 
    array (
      'module' => 'rt_purchase_order',
      'class' => 'rt_purchase_order',
      'path' => 'modules/rt_purchase_order/rt_purchase_order.php',
      'tab' => true,
    ),
  ),
  'layoutdefs' => 
  array (
  ),
  'relationships' => 
  array (
  ),
  'image_dir' => '<basepath>/icons',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/modules/rt_purchase_line_item',
      'to' => 'modules/rt_purchase_line_item',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/modules/rt_purchase_order',
      'to' => 'modules/rt_purchase_order',
    ),
  ),
  'language' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
  ),
);