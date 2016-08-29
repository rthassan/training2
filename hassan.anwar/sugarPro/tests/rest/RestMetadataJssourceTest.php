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

class RestMetadataJssourceTest extends RestTestBase {

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
    public function testJssource() {
        $restReply = $this->_restCall('metadata?type_filter=jssource&platform=portal');
        // Hash should always be set
        $this->assertTrue(isset($restReply['reply']['jssource']), "Jssource is missing");
        
    }

    /**
     * @group rest
     */
    public function testNoJssource() {
        $restReply = $this->_restCall('metadata?type_filter=modules&module_filter=Contacts&platform=portal');
        // Hash should always be set
        $this->assertTrue(!isset($restReply['reply']['jssource']), "Jssource should not be here");
    }

}
