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
 * $Id: index.php 55484 2010-03-19 14:56:16Z jmertic $
 ********************************************************************************/
if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);

include ('modules/TeamNotices/ListView.php');