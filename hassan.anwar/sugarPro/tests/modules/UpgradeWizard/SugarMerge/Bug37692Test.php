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
require_once 'include/dir_inc.php';

class Bug37692Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;
var $has_dir;
var $modules;

function setUp() {
   $this->modules = array('Project');
   $this->has_dir = array();

   foreach($this->modules as $module) {
	   if(!file_exists("custom/modules/{$module}/metadata")){
		  mkdir_recursive("custom/modules/{$module}/metadata", true);
	   }

	   if(file_exists("custom/modules/{$module}")) {
	   	  $this->has_dir[$module] = true;
	   }

	   $files = array('editviewdefs','detailviewdefs');
	   foreach($files as $file) {
	   	   if(file_exists("custom/modules/{$module}/metadata/{$file}")) {
		   	  copy("custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php.bak");
		   }

		   if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
		      copy("custom/modules/{$module}/metadata/{$file}.php.suback.php", "custom/modules/{$module}/metadata/{$file}.php.suback.bak");
		   }

		   if(file_exists("tests/modules/UpgradeWizard/SugarMerge/od_metadata_files/custom/modules/{$module}/metadata/{$file}.php")) {
		   	  copy("tests/modules/UpgradeWizard/SugarMerge/od_metadata_files/custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php");
		   }
	   } //foreach
   } //foreach
}


function tearDown() {

   foreach($this->modules as $module) {
	   if(!$this->has_dir[$module]) {
	   	  rmdir_recursive("custom/modules/{$module}");
	   	  SugarAutoLoader::delFromMap("custom/modules/{$module}");
	   }  else {
	   	   $files = array('editviewdefs','detailviewdefs');
		   foreach($files as $file) {
		      if(file_exists("custom/modules/{$module}/metadata/{$file}.php.bak")) {
		      	 copy("custom/modules/{$module}/metadata/{$file}.php.bak", "custom/modules/{$module}/metadata/{$file}.php");
	             unlink("custom/modules/{$module}/metadata/{$file}.php.bak");
		      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php")) {
		      	 SugarAutoLoader::unlink("custom/modules/{$module}/metadata/{$file}.php");
		      }

		   	  if(file_exists("custom/modules/{$module}/metadata/{$module}.php.suback.bak")) {
		      	 copy("custom/modules/{$module}/metadata/{$file}.php.suback.bak", "custom/modules/{$module}/metadata/{$file}.php.suback.php");
	             unlink("custom/modules/{$module}/metadata/{$file}.php.suback.bak");
		      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
		      	 unlink("custom/modules/{$module}/metadata/{$file}.php.suback.php");
		      }
		   }
	   }
   } //foreach
}


function test_project_merge() {
   require_once('modules/UpgradeWizard/SugarMerge/SugarMerge.php');
   $sugar_merge = new SugarMerge('tests/modules/UpgradeWizard/SugarMerge/od_metadata_files/custom');
   $sugar_merge->mergeModule('Project');
   $this->assertTrue(file_exists('custom/modules/Project/metadata/detailviewdefs.php.suback.php'));
   $this->assertTrue(file_exists('custom/modules/Project/metadata/editviewdefs.php.suback.php'));
   require('custom/modules/Project/metadata/detailviewdefs.php');
   $this->assertTrue(isset($viewdefs['Project']['DetailView']['panels']['lbl_panel_1']), 'Assert that the original panel index is preserved');
   require('custom/modules/Project/metadata/editviewdefs.php');
   $this->assertTrue(isset($viewdefs['Project']['EditView']['panels']['default']), 'Assert that the original panel index is preserved');
}


}
?>