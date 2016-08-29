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

class RestMetadataRelationshipsTest extends RestTestBase {
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
    public function testMetadataGetRelationships() {
        $this->_clearMetadataCache();
        $restReply = $this->_restCall('metadata?type_filter=relationships');

        $this->assertTrue(isset($restReply['reply']['relationships']['_hash']),'There is no full relationship list');
        $this->assertTrue(isset($restReply['reply']['relationships']['opportunities_contacts']),'There is no opportunities contacts relationship in the full list');
    }

    /**
     * @group rest
     */
    public function testMetadataGetFilteredRelationships() {
        $moduleList = array('Accounts','Contacts','Cases');

        $GLOBALS['db']->commit();
        $this->_clearMetadataCache();
        $restReply = $this->_restCall('metadata?type_filter=relationships&module_filter='.implode(',',$moduleList));

        $this->assertTrue(isset($restReply['reply']['relationships']['_hash']),'There is no filtered relationship list, reply looked like: '.var_export($restReply['replyRaw'],true));
        $this->assertTrue(isset($restReply['reply']['relationships']['opportunities_contacts']),'There is no opportunities contacts relationship in the filtered list');

        foreach ( $restReply['reply']['relationships'] as $relName => $relData ) {
            if ( $relName == '_hash' ) {
                continue;
            }
            $this->assertTrue(
                (in_array($relData['lhs_module'],$moduleList)||in_array($relData['rhs_module'],$moduleList)),
                "$relName does not have a LHS [$relData[lhs_module] or RHS module [$relData[rhs_module]] that is in (Accounts, Contacts or Cases)"
            );
        }
    }

}