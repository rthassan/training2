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

require_once 'modules/Expressions/Expression.php';

/**
 * @ticket 38903
 */
class Bug38903Test extends Sugar_PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	    require('include/modules.php');
	    $GLOBALS['beanList'] = $beanList;
	    $GLOBALS['beanFiles'] = $beanFiles;		
	}	
	
    public function testAccountNameExists()
    {
        //Reset moduleList, beanList and beanFiles
        global $beanList, $beanFiles, $moduleList;
        require('include/modules.php');
        
        $bean = new Expression();

        // just to remove php notice
        $_GET['opener_id'] = null;
        // wf condition: when a field in the target module changes to or from a specified value
        // module: Leads
        $options = strtolower($bean->get_selector_array(
            'field', null, 'Leads', false, 'normal_trigger', true, 'compare_specific', false));

        $this->assertRegExp('#<option value=\'account_name\'>[^>]+?</option>#', $options);
    }
}
