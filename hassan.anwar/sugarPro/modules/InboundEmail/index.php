<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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
/*********************************************************************************
 * $Id: index.php 42345 2008-12-04 15:18:11Z jmertic $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $mod_strings;
echo getClassicModuleTitle('InboundEmail', array($mod_strings['LBL_MODULE_TITLE'], $mod_strings['LBL_HOME']), true);

//echo getClassicModuleTitle($mod_strings['LBL_MODULE_TITLE'], array($mod_strings['LBL_MODULE_TITLE'],$mod_strings['LBL_HOME']), true);
require_once('modules/InboundEmail/ListView.php');

?>
