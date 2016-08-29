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

require_once('modules/Import/sources/ImportFile.php');
require_once 'modules/Import/ImportFileSplitter.php';
require_once 'modules/Import/ImportCacheFiles.php';

class ImportFileSplitterTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_goodFile;
    protected $_badFile;

    public function setUp()
    {
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    	$this->_goodFile = SugarTestImportUtilities::createFile(2000, 3);
		$this->_badFile  = ImportCacheFiles::getImportDir().'/thisfileisntthere'.date("YmdHis");
		$this->_whiteSpaceFile  = SugarTestImportUtilities::createFileWithWhiteSpace();
    }

    public function tearDown()
    {
  //      SugarTestImportUtilities::removeAllCreatedFiles();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }

    public function testLoadNonExistantFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_badFile);
        $this->assertFalse($importFileSplitter->fileExists());
    }

    public function testLoadGoodFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $this->assertTrue($importFileSplitter->fileExists());
    }

    public function testSplitSourceFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"');

        $this->assertEquals(2000, $importFileSplitter->getRecordCount());
        $this->assertEquals(2, $importFileSplitter->getFileCount());
    }

    public function testSplitSourceFileNoEnclosure()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','');

        $this->assertEquals(2000, $importFileSplitter->getRecordCount());
        $this->assertEquals(2, $importFileSplitter->getFileCount());
    }

    public function testSplitSourceFileWithHeader()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"',true);

        $this->assertEquals(1999, $importFileSplitter->getRecordCount());
        $this->assertEquals(2, $importFileSplitter->getFileCount());
    }

    public function testSplitSourceFileWithThreshold()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile,500);
        $importFileSplitter->splitSourceFile(',','"');

        $this->assertEquals(2000, $importFileSplitter->getRecordCount());
        $this->assertEquals(4, $importFileSplitter->getFileCount());
    }

    public function testGetSplitFileName()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"');

        $this->assertEquals($importFileSplitter->getSplitFileName(0),"{$this->_goodFile}-0");
        $this->assertEquals($importFileSplitter->getSplitFileName(1),"{$this->_goodFile}-1");
        $this->assertEquals($importFileSplitter->getSplitFileName(2),false);
    }

	/**
	 * @ticket 25119
	 */
    public function testTrimSpaces()
    {
        $splitter = new ImportFileSplitter($this->_whiteSpaceFile);
        $splitter->splitSourceFile(',',' ',false);

        $this->assertEquals(
            trim(file_get_contents("{$this->_whiteSpaceFile}-0")),
            trim(file_get_contents("{$this->_whiteSpaceFile}"))
            );
    }
}
