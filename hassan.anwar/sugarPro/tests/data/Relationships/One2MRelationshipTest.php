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

class One2MRelationshipTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @covers One2MRelationship::getType
     */
    public function testGetType()
    {
        $relationship = $this->getMock('One2MRelationship', null, array(), '', false);

        $this->assertEquals(REL_TYPE_MANY, $relationship->getType(REL_LHS));
        $this->assertEquals(REL_TYPE_ONE, $relationship->getType(REL_RHS));
    }
}
