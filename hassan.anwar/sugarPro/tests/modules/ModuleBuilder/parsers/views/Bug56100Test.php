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

require_once ('modules/ModuleBuilder/parsers/views/SubpanelMetaDataParser.php');

/**
 * Bug 56100 - Undefined property: SubpanelMetaDataParser
 */
class Bug56100Test extends Sugar_PHPUnit_Framework_TestCase {
    public function setUp() {
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('app_list_strings');
    }

    public function tearDown() {
        SugarTestHelper::tearDown();
    }

    public function testSubpanelParserHasViewPropertySet() {
        $parser = new SubpanelMetaDataParser('documents', 'Accounts');
        $test = property_exists($parser, 'view');
        $this->assertTrue($test, '$view does not exist in the Subpanel Meta Data Parser');
    }
}
