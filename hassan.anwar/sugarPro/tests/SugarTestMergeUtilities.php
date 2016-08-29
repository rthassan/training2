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

class SugarTestMergeUtilities
{
    private static $modules = array();
    private static $files = array();
    private static $has_dir = array();

    private function __construct() {}

    public static function setupFiles($modules, $files, $custom_directory)
    {

		   self::$modules = $modules;
		   self::$files = $files;
		   self::$has_dir = array();

		   foreach(self::$modules as $module) {
			   if(!file_exists("custom/modules/{$module}/metadata")){
				  mkdir_recursive("custom/modules/{$module}/metadata", true);
			   }

			   if(file_exists("custom/modules/{$module}")) {
			   	  self::$has_dir[$module] = true;
			   }

			   foreach(self::$files as $file) {
			   	   if(file_exists("custom/modules/{$module}/metadata/{$file}")) {
				   	  copy("custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php.bak");
				   }

				   if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
				      copy("custom/modules/{$module}/metadata/{$file}.php.suback.php", "custom/modules/{$module}/metadata/{$file}.php.suback.bak");
				   }

				   if(file_exists("{$custom_directory}/custom/modules/{$module}/metadata/{$file}.php")) {
				   	  copy("{$custom_directory}/custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php");
				   	  SugarAutoLoader::addToMap("custom/modules/{$module}/metadata/{$file}.php", false);
				   }
			   } //foreach
		   } //foreach

    }

    public static function teardownFiles()
    {
		   foreach(self::$modules as $module) {
			   if(!self::$has_dir[$module]) {
			   	  rmdir_recursive("custom/modules/{$module}");
			   	  SugarAutoLoader::delFromMap("custom/modules/{$module}");
			   }  else {
				   foreach(self::$files as $file) {
				      if(file_exists("custom/modules/{$module}/metadata/{$file}.php.bak")) {
				      	 copy("custom/modules/{$module}/metadata/{$file}.php.bak", "custom/modules/{$module}/metadata/{$file}.php");
			             unlink("custom/modules/{$module}/metadata/{$file}.php.bak");
				      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php")) {
				      	 SugarAutoLoader::unlink("custom/modules/{$module}/metadata/{$file}.php", true);
				      }

				   	  if(file_exists("custom/modules/{$module}/metadata/{$module}.php.suback.bak")) {
				      	 copy("custom/modules/{$module}/metadata/{$file}.php.suback.bak", "custom/modules/{$module}/metadata/{$file}.php.suback.php");
			             unlink("custom/modules/{$module}/metadata/{$file}.php.suback.bak");
				      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
				      	 SugarAutoLoader::unlink("custom/modules/{$module}/metadata/{$file}.php.suback.php");
				      }
				   }
			   }
		   } //foreach
    }

}