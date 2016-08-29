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

require_once('tests/rest/RestFileTestBase.php');

class RestFileTempTest extends RestFileTestBase
{
    /**
     * @group rest
     */
    public function testPostUploadImageTempToContact()
    {
        // Upload a temporary file
        $post = array('picture' => '@include/images/badge_256.png');
        $reply = $this->_restCall('Contacts/temp/file/picture', $post);
        $this->assertArrayHasKey('picture', $reply['reply'], 'Reply is missing field name key');
        $this->assertNotEmpty($reply['reply']['picture']['guid'], 'File guid not returned');

        // Grab the temporary file and make sure it is present
        $fetch = $this->_restCall('Contacts/temp/file/picture/' . $reply['reply']['picture']['guid']);
        $this->assertNotEmpty($fetch['replyRaw'], 'Temporary file is missing');

        // Grab the temporary file and make sure it's been deleted
        $fetch = $this->_restCall('Contacts/temp/file/picture/' . $reply['reply']['picture']['guid']);
        $this->assertArrayHasKey('error', $fetch['reply'], 'Temporary file is still here');
        $this->assertEquals('invalid_parameter', $fetch['reply']['error'], 'Expected error string not returned');
    }
}

