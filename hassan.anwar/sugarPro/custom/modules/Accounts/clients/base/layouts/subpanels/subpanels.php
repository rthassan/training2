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
$viewdefs['Accounts']['base']['layout']['subpanels'] = array (
    'components' => array (
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_CALLS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'calls',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_MEETINGS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'meetings',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_TASKS_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'tasks',
          ),
      ),
      array(
          'layout' => 'subpanel',
          'label' => 'LBL_NOTES_SUBPANEL_TITLE',
          'context' => array(
              'link' => 'notes',
          ),
      ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_MEMBER_ORG_SUBPANEL_TITLE',
      'context' => array (
        'link' => 'members',
      ),
    ),
    array(
        'layout' => 'subpanel',
        'label' => 'LBL_EMAILS_SUBPANEL_TITLE',
        'context' => array (
            'link' => 'archived_emails',
        ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_LEADS_SUBPANEL_TITLE',
      'context' => array (
        'link' => 'leads',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_CONTACTS_SUBPANEL_TITLE',
      'override_subpanel_list_view' => 'subpanel-for-accounts',
      'context' => array (
        'link' => 'contacts',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_OPPORTUNITIES_SUBPANEL_TITLE',
      'override_subpanel_list_view' => 'subpanel-for-accounts',
      'context' => array (
        'link' => 'opportunities',
      ),
    ),
  
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_CASES_SUBPANEL_TITLE',
      'override_subpanel_list_view' => 'subpanel-for-accounts',
      'context' => array (
        'link' => 'cases',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_BUGS_SUBPANEL_TITLE',
      'context' => array (
        'link' => 'bugs',
      ),
    ),
      array (
          'layout' => 'subpanel',
          'label' => 'LBL_PRODUCTS_SUBPANEL_TITLE',
          'context' => array (
              'link' => 'products',
          ),
      ),
      array (
      'layout' => 'subpanel',
      'label' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
      'context' => array (
        'link' => 'documents',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_QUOTES_SUBPANEL_TITLE',
      'override_subpanel_list_view' => 'subpanel-for-accounts',
      'context' => array (
        'link' => 'quotes',
        'collectionOptions' => array(
          'params' => array(
            'ignore_role' => 1,  //See SP-1305 and BR-344. Load Quotes from all link types (both quotes, quotes_shipto).
          ),
        ),
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_CAMPAIGN_LIST_SUBPANEL_TITLE',
      'context' => array (
          'link' => 'campaigns',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_CONTRACTS_SUBPANEL_TITLE',
      'override_subpanel_list_view' => 'subpanel-for-accounts',
      'context' => array (
          'link' => 'contracts',
      ),
    ),
    array (
      'layout' => 'subpanel',
      'label' => 'LBL_PROJECTS_SUBPANEL_TITLE',
      'context' => array (
          'link' => 'project',
      ),
    ),
  ),
  'type' => 'subpanels',
  'span' => 12,
);
