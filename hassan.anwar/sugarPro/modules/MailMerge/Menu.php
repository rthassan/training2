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
 * $Id: Menu.php 13782 2006-06-06 17:58:55Z majed $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=MailMerge&action=index&reset=true", $mod_strings['LNK_NEW_MAILMERGE'],"MailMerge"),
	Array("index.php?module=Documents&action=EditView&return_module=MailMerge&return_action=EditView", $mod_strings['LNK_UPLOAD_TEMPLATE'],"MailMerge"),
	);
?>