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

require_once('tests/rest/RestTestBase.php');

class RestServerInfoTest extends RestTestBase {
    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @group rest
     */
    public function testServerInfo() {
        // Test Server Fetch
        // SIDECAR-14 - Changed endpoint of the test to be consistent with moving 
        // server info into the metadata api
        $restReply = $this->_restCall("metadata?type_filter=server_info");

        $this->assertTrue(isset($restReply['reply']['server_info']['flavor']), "No Flavor Set");
        $this->assertTrue(isset($restReply['reply']['server_info']['version']), "No Version Set");
        $this->assertTrue(is_array($restReply['reply']['server_info']['fts']), "No FTS Info Set");
    }

}

