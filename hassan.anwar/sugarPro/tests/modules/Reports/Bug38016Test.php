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

require_once 'modules/Reports/Report.php';
require_once 'modules/Reports/sugarpdf/sugarpdf.summary.php';

class Bug38016Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected static $fixturesPath;

    private $report;
    private $summaryView;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$fixturesPath = __DIR__ . '/Fixtures/';
    }

    protected function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require 'include/modules.php';
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $fixture = file_get_contents(self::$fixturesPath . get_class($this) . '.json');
        $this->report = new Report($fixture);
        $GLOBALS['module'] = 'Reports';
        $this->summaryView = new ReportsSugarpdfSummary();
        $this->summaryView->bean = & $this->report;
    }

    protected function tearDown()
    {
        unset($GLOBALS['module']);
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
    }

    public function testSummationQueryMadeWithoutCountColumn()
    {
        // FIXME we shouldn't be suppressing errors
        @$this->summaryView->display();
        $this->assertTrue(!empty($this->report->total_query));
    }
}
