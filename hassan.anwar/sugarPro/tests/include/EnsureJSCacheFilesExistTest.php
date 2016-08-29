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
require_once("include/utils.php");

class EnsureJSCacheFilesExistTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $testFile = "cache/include/javascript/sugar_sidecar.min.js";
    protected $testFiles = array(
        "cache/include/javascript/sugar_grp1.js",
        "cache/include/javascript/sugar_grp1_jquery.js",
    );

    protected function setup() 
    {
        // Remove all current javascript cache files
        $files = glob("cache/include/javascript/*.js");
        foreach ($files as $file) {
            unlink($file);
        }
    }

    public function testEnsureJSCacheFilesExistSingle()
    {
        // Sanity check
        $this->assertFileNotExists($this->testFile, "Test file was not removed during setup");

        // Run the new method and ensure it was run
        $actual = ensureJSCacheFilesExist();
        $expect = "./{$this->testFile}";

        // Real assertions
        $this->assertFileExists($this->testFile, "Test file was created");
        $this->assertEquals($expect, $actual, "File returned was not what was expected");
    }

    public function testEnsureJSCacheFilesExistArray()
    {
        // Sanity check
        $this->assertFileNotExists($this->testFile, "Test file was not removed during setup");

        // Run the new method and ensure it was run against an array of files
        $actual = ensureJSCacheFilesExist($this->testFiles, '.', false);
        foreach ($this->testFiles as $f) {
            $expect[] = "./$f";
        }

        // Real assertions
        $this->assertFileExists($this->testFile, "Test file was created");
        $this->assertEquals($expect, $actual, "Files returned were not what was expected");
    }
}
