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

require_once 'include/connectors/ConnectorsTestCase.php';
require_once 'include/utils.php';
require_once 'include/connectors/sources/default/source.php';

class ConnectorsOriginalMapping extends Sugar_Connectors_TestCase
{
    public function setUp()
    {
        $this->customMappingFile = 'custom/modules/Connectors/connectors/sources/ext/rest/twitter/mapping.php';
        $mapping = array();
        write_array_to_file('mapping', $mapping, $this->customMappingFile);
    }
    public function tearDown()
    {
        unlink($this->customMappingFile);
    }
    public function testOriginalMapping()
    {

        $source = SourceFactory::getSource('ext_rest_twitter');
        $originalMapping = $source->getOriginalMapping();

        // Sets $mapping
        require('modules/Connectors/connectors/sources/ext/rest/twitter/mapping.php');

        $this->assertEquals($mapping, $originalMapping);
    }
}

?>
