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

require_once 'tests/upgrade/UpgradeTestCase.php';
require_once 'upgrade/scripts/post/8_ComposerConfig.php';

/**
 *
 * ComposerConfig post script test suite
 *
 */
class ComposerConfigTest extends UpgradeTestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();

        // Disable logging
        unset($this->upgrader->context['log']);
    }

    /**
     * @group unit
     * @covers SugarUpgradeComposerConfig::run
     *
     * @dataProvider dataProviderTestRun
     * @param array $files
     */
    public function testRun(array $files)
    {
        $sut = $this->getMockSut(array('restoreFile'));

        $sut->expects($this->exactly(count($files)))
            ->method('restoreFile')
            ->will($this->returnValue(true));

        $sut->upgrader->state['composer_custom'] = $files;

        $sut->run();
    }

    public function dataProviderTestRun()
    {
        return array(
            array(array()),
            array(array('first', 'second')),
        );
    }

    /**
     * Get mock for subject under test
     * @param null|array $method
     * @return SugarUpgradeComposerConfig
     */
    protected function getMockSut($method = null)
    {
        return $this->getMockBuilder('SugarUpgradeComposerConfig')
            ->setConstructorArgs(array($this->upgrader))
            ->setMethods($method)
            ->getMock();
    }
}
