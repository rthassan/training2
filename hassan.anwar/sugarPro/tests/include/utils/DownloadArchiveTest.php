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

require_once 'include/download_file.php';
require_once 'include/upload_file.php';
require_once 'include/utils/file_utils.php';
require_once 'include/SugarFields/SugarFieldHandler.php';

/**
 * Test DownloadFile:getArchive()
 */
class DownloadArchiveTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * Notes.
     *
     * @var array
     */
    public $notes = array();

    public function setUp()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
    }

    public function tearDown()
    {
        // Notes cleanup
        if (count($this->notes)) {
            $download = new DownloadFile();
            $noteIds = array();
            foreach ($this->notes as $note) {
                if (false !== $fileInfo = $download->getFileInfo($note, 'filename')) {
                    if (file_exists($fileInfo['path'])) {
                        @unlink($fileInfo['path']);
                    }
                }
                $noteIds[] = $note->id;
            }
            $noteIds = "('" . implode("','", $noteIds) . "')";
            $GLOBALS['db']->query("DELETE FROM notes WHERE id IN {$noteIds}");
        }
        $this->notes = array();

        SugarTestHelper::tearDown();
        parent::tearDown();
    }

    /**
     * Data provider for get archive test.
     *
     * @return array
     */
    public function dataProviderGetArchive()
    {
        return array(
            '4 files, force download, name: testarchive' => array(
                4,
                'testarchive',
                'testarchive.zip',
            ),
            '2 files, force download, name: someother.zip' => array(
                2,
                'someother.zip',
                'someother.zip',
            ),
            '3 files, not force download, name: three.zip' => array(
                3,
                'three',
                'three.zip',
            ),
            '4 files, force download, name:empty' => array(
                4,
                '', // empty
                'archive.zip',
            ),
            '5 files, not force download, name:empty' => array(
                5,
                '', // empty
                'archive.zip',
            ),
        );
    }

    /**
     * Test get archive.
     *
     * @dataProvider dataProviderGetArchive
     */
    public function testGetArchive($fileCounts, $outputName, $expectedOutputName)
    {
        $bean = BeanFactory::getBean('Notes');
        $sfh = new SugarFieldHandler();
        $def = $bean->field_defs['filename'];
        /* @var $sf SugarFieldFile */
        $sf = $sfh->getSugarField($def['type']);

        $notes = array();

        for ($i = 0; $i < $fileCounts; $i++) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'DownloadArchiveTest' . $i);
            file_put_contents($tmpFile, uniqid());

            $note = BeanFactory::newBean('Notes');
            $note->name = 'DownloadArchiveTest' . uniqid();

            $_FILES['uploadfile'] = array(
                'name' => 'DownloadArchiveTest' . $i . '.txt',
                'tmp_name' => $tmpFile,
                'size' => filesize($tmpFile),
                'error' => 0,
                '_SUGAR_API_UPLOAD' => true,
            );

            $sf->save($note, array(), 'filename', $def, 'DownloadArchiveTest_');

            $this->notes[] = $note;
            $notes[] = $note;
        }

        $unit = $this;

        $downloadMock = $this->getMock('DownloadFile', array('outputFile'));
        $downloadMock->expects($this->once())->method('outputFile')
                     ->with(
                         $this->logicalAnd($this->isType('bool'), $this->isTrue()),
                         $this->logicalAnd(
                             $this->isType('array'),
                             $this->arrayHasKey('path'),
                             $this->arrayHasKey('content-type'),
                             $this->arrayHasKey('content-length'),
                             $this->arrayHasKey('name')
                         )
                     )
                     ->will($this->returnCallback(function ($forceDownload, $info) use ($unit, $expectedOutputName, $fileCounts) {
                            $unit->assertNotEmpty($info['path'], 'File path is empty');
                            $unit->assertFileExists($info['path'], 'Archive file not exists');

                            $unit->assertEquals($expectedOutputName, $info['name']);

                            $contentType = mime_is_detectable() ? 'application/zip' : 'application/octet-stream';

                            $unit->assertEquals($contentType, $info['content-type'], 'Invalid content-type');
                            $unit->assertEquals(filesize($info['path']), $info['content-length'], 'Invalid content-length');

                            $zip = new ZipArchive();
                            $zip->open($info['path']);
                            $numFiles = $zip->numFiles;
                            $zip->close();

                            $unit->assertEquals($fileCounts, $numFiles, 'Invalid file counts in archive');
                      }));

        // get archived files
        $downloadMock->getArchive($notes, 'filename', $outputName);
    }

    /**
     * Test get archive when given empty bean list
     */
    public function testGetArchiveEmptyBeanList()
    {
        $downloadMock = $this->getMock('DownloadFile', array('outputFile'));
        $this->setExpectedException('Exception', 'Files could not be retrieved for this record');
        $downloadMock->getArchive(array(), 'filename');
    }
}
