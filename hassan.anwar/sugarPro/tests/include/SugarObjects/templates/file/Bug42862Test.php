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
 
class Bug42862Test extends Sugar_PHPUnit_Framework_TestCase  {

public function testDefaultPublishDate()
{
	global $timedate;
	$doc = new Document();
	$nowDate = $timedate->nowDbDate();
	$docPublishDate = $timedate->to_db_date($doc->active_date, true);
	$this->assertEquals($nowDate, $docPublishDate, "Assert that active_date field in new Document defaults to current date");
}

}

?>