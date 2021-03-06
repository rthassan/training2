<?php
$clientCache['Bugs']['base']['view'] = array (
  'record' => 
  array (
    'controller' => 
    array (
      'base' => '/*
     * Your installation or use of this SugarCRM file is subject to the applicable
     * terms available at
     * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
     * If you do not agree to all of the applicable terms or do not have the
     * authority to bind the entity as an authorized representative, then do not
     * install or use this SugarCRM file.
     *
     * Copyright (C) SugarCRM Inc. All rights reserved.
     *//*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
/**
 * @class View.Views.Base.Bugs.RecordView
 * @alias SUGAR.App.view.views.BaseBugsRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: \'RecordView\',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], [\'HistoricalSummary\']);
        this._super(\'initialize\', [options]);
    }
})
',
    ),
    'meta' => 
    array (
      'buttons' => 
      array (
        0 => 
        array (
          'type' => 'button',
          'name' => 'cancel_button',
          'label' => 'LBL_CANCEL_BUTTON_LABEL',
          'css_class' => 'btn-invisible btn-link',
          'showOn' => 'edit',
        ),
        1 => 
        array (
          'type' => 'rowaction',
          'event' => 'button:save_button:click',
          'name' => 'save_button',
          'label' => 'LBL_SAVE_BUTTON_LABEL',
          'css_class' => 'btn btn-primary',
          'showOn' => 'edit',
          'acl_action' => 'edit',
        ),
        2 => 
        array (
          'type' => 'actiondropdown',
          'name' => 'main_dropdown',
          'primary' => true,
          'showOn' => 'view',
          'buttons' => 
          array (
            0 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:edit_button:click',
              'name' => 'edit_button',
              'label' => 'LBL_EDIT_BUTTON_LABEL',
              'primary' => true,
              'acl_action' => 'edit',
            ),
            1 => 
            array (
              'type' => 'shareaction',
              'name' => 'share',
              'label' => 'LBL_RECORD_SHARE_BUTTON',
              'acl_action' => 'view',
            ),
            2 => 
            array (
              'type' => 'pdfaction',
              'name' => 'download-pdf',
              'label' => 'LBL_PDF_VIEW',
              'action' => 'download',
              'acl_action' => 'view',
            ),
            3 => 
            array (
              'type' => 'pdfaction',
              'name' => 'email-pdf',
              'label' => 'LBL_PDF_EMAIL',
              'action' => 'email',
              'acl_action' => 'view',
            ),
            4 => 
            array (
              'type' => 'divider',
            ),
            5 => 
            array (
              'type' => 'rowaction',
              'route' => '#bwc/index.php?module=KBDocuments&action=EditView',
              'name' => 'create_kbdocument_button',
              'label' => 'LBL_CREATE_KB_DOCUMENT',
              'acl_module' => 'KBDocuments',
              'acl_action' => 'create',
            ),
            6 => 
            array (
              'type' => 'divider',
            ),
            7 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:find_duplicates_button:click',
              'name' => 'find_duplicates_button',
              'label' => 'LBL_DUP_MERGE',
              'acl_action' => 'edit',
            ),
            8 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:duplicate_button:click',
              'name' => 'duplicate_button',
              'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
              'acl_module' => 'Bugs',
              'acl_action' => 'create',
            ),
            9 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:historical_summary_button:click',
              'name' => 'historical_summary_button',
              'label' => 'LBL_HISTORICAL_SUMMARY',
              'acl_action' => 'view',
            ),
            10 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:audit_button:click',
              'name' => 'audit_button',
              'label' => 'LNK_VIEW_CHANGE_LOG',
              'acl_action' => 'view',
            ),
            11 => 
            array (
              'type' => 'divider',
            ),
            12 => 
            array (
              'type' => 'rowaction',
              'event' => 'button:delete_button:click',
              'name' => 'delete_button',
              'label' => 'LBL_DELETE_BUTTON_LABEL',
              'acl_action' => 'delete',
            ),
          ),
        ),
        3 => 
        array (
          'name' => 'sidebar_toggle',
          'type' => 'sidebartoggle',
        ),
      ),
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'header' => true,
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'picture',
              'type' => 'avatar',
              'size' => 'large',
              'dismiss_label' => true,
              'readonly' => true,
            ),
            1 => 'name',
            2 => 
            array (
              'name' => 'favorite',
              'label' => 'LBL_FAVORITE',
              'type' => 'favorite',
              'dismiss_label' => true,
            ),
            3 => 
            array (
              'name' => 'follow',
              'label' => 'LBL_FOLLOW',
              'type' => 'follow',
              'readonly' => true,
              'dismiss_label' => true,
            ),
          ),
        ),
        1 => 
        array (
          'name' => 'panel_body',
          'label' => 'LBL_RECORD_BODY',
          'columns' => 2,
          'labelsOnTop' => true,
          'placeholders' => true,
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'bug_number',
              'readonly' => true,
            ),
            1 => 'priority',
            2 => 'status',
            3 => 'type',
            4 => 'source',
            5 => 'product_category',
            6 => 'resolution',
            7 => 'assigned_user_name',
            8 => 
            array (
              'name' => 'description',
              'nl2br' => true,
              'span' => 12,
            ),
          ),
        ),
        2 => 
        array (
          'name' => 'panel_hidden',
          'label' => 'LBL_RECORD_SHOWMORE',
          'columns' => 2,
          'hide' => true,
          'labelsOnTop' => true,
          'placeholders' => true,
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'portal_viewable',
              'span' => 12,
            ),
            1 => 'found_in_release',
            2 => 'fixed_in_release',
            3 => 
            array (
              'name' => 'date_entered_by',
              'readonly' => true,
              'inline' => true,
              'type' => 'fieldset',
              'label' => 'LBL_DATE_ENTERED',
              'fields' => 
              array (
                0 => 
                array (
                  'name' => 'date_entered',
                ),
                1 => 
                array (
                  'type' => 'label',
                  'default_value' => 'LBL_BY',
                ),
                2 => 
                array (
                  'name' => 'created_by_name',
                ),
              ),
            ),
            4 => 
            array (
              'name' => '',
              'readonly' => true,
            ),
            5 => 
            array (
              'name' => 'date_modified_by',
              'readonly' => true,
              'inline' => true,
              'type' => 'fieldset',
              'label' => 'LBL_DATE_MODIFIED',
              'fields' => 
              array (
                0 => 
                array (
                  'name' => 'date_modified',
                ),
                1 => 
                array (
                  'type' => 'label',
                  'default_value' => 'LBL_BY',
                ),
                2 => 
                array (
                  'name' => 'modified_by_name',
                ),
              ),
            ),
            6 => 
            array (
              'name' => 'work_log',
              'nl2br' => true,
              'span' => 12,
            ),
          ),
        ),
      ),
    ),
  ),
  'selection-list' => 
  array (
    'meta' => 
    array (
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'label' => 'LBL_PANEL_1',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'bug_number',
              'enabled' => true,
              'default' => true,
              'readonly' => true,
            ),
            1 => 
            array (
              'name' => 'name',
              'enabled' => true,
              'default' => true,
            ),
            2 => 
            array (
              'name' => 'status',
              'enabled' => true,
              'default' => true,
            ),
            3 => 
            array (
              'name' => 'type',
              'enabled' => true,
              'default' => true,
            ),
            4 => 
            array (
              'name' => 'priority',
              'enabled' => true,
              'default' => false,
            ),
            5 => 
            array (
              'name' => 'fixed_in_release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            6 => 
            array (
              'name' => 'assigned_user_name',
              'enabled' => true,
              'default' => false,
            ),
            7 => 
            array (
              'name' => 'release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            8 => 
            array (
              'name' => 'resolution',
              'enabled' => true,
              'default' => false,
            ),
            9 => 
            array (
              'name' => 'team_name',
              'enabled' => true,
              'default' => false,
            ),
          ),
        ),
      ),
    ),
  ),
  'list' => 
  array (
    'meta' => 
    array (
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'label' => 'LBL_PANEL_1',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'bug_number',
              'enabled' => true,
              'default' => true,
              'readonly' => true,
            ),
            1 => 
            array (
              'name' => 'name',
              'enabled' => true,
              'default' => true,
            ),
            2 => 
            array (
              'name' => 'status',
              'enabled' => true,
              'default' => true,
            ),
            3 => 
            array (
              'name' => 'type',
              'enabled' => true,
              'default' => true,
            ),
            4 => 
            array (
              'name' => 'priority',
              'enabled' => true,
              'default' => true,
            ),
            5 => 
            array (
              'name' => 'fixed_in_release_name',
              'enabled' => true,
              'default' => true,
              'link' => false,
            ),
            6 => 
            array (
              'name' => 'assigned_user_name',
              'enabled' => true,
              'default' => true,
            ),
            7 => 
            array (
              'name' => 'release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            8 => 
            array (
              'name' => 'resolution',
              'enabled' => true,
              'default' => false,
            ),
            9 => 
            array (
              'name' => 'team_name',
              'enabled' => true,
              'default' => false,
            ),
            10 => 
            array (
              'name' => 'date_modified',
              'enabled' => true,
              'default' => true,
            ),
            11 => 
            array (
              'name' => 'date_entered',
              'enabled' => true,
              'default' => true,
            ),
          ),
        ),
      ),
    ),
  ),
  'subpanel-list' => 
  array (
    'meta' => 
    array (
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'label' => 'LBL_PANEL_1',
          'fields' => 
          array (
            0 => 
            array (
              'label' => 'LBL_LIST_NUMBER',
              'enabled' => true,
              'default' => true,
              'readonly' => true,
              'name' => 'bug_number',
            ),
            1 => 
            array (
              'label' => 'LBL_LIST_SUBJECT',
              'enabled' => true,
              'default' => true,
              'name' => 'name',
            ),
            2 => 
            array (
              'label' => 'LBL_LIST_STATUS',
              'enabled' => true,
              'default' => true,
              'name' => 'status',
            ),
            3 => 
            array (
              'label' => 'LBL_LIST_TYPE',
              'enabled' => true,
              'default' => true,
              'name' => 'type',
            ),
            4 => 
            array (
              'label' => 'LBL_LIST_PRIORITY',
              'enabled' => true,
              'default' => true,
              'name' => 'priority',
            ),
            5 => 
            array (
              'name' => 'assigned_user_name',
              'target_record_key' => 'assigned_user_id',
              'target_module' => 'Employees',
              'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
              'enabled' => true,
              'default' => true,
            ),
          ),
        ),
      ),
    ),
  ),
  'dupecheck-list' => 
  array (
    'meta' => 
    array (
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'label' => 'LBL_PANEL_1',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'bug_number',
              'enabled' => true,
              'default' => true,
              'readonly' => true,
            ),
            1 => 
            array (
              'name' => 'name',
              'enabled' => true,
              'default' => true,
            ),
            2 => 
            array (
              'name' => 'status',
              'enabled' => true,
              'default' => true,
            ),
            3 => 
            array (
              'name' => 'type',
              'enabled' => true,
              'default' => true,
            ),
            4 => 
            array (
              'name' => 'priority',
              'enabled' => true,
              'default' => false,
            ),
            5 => 
            array (
              'name' => 'fixed_in_release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            6 => 
            array (
              'name' => 'assigned_user_name',
              'enabled' => true,
              'default' => false,
            ),
            7 => 
            array (
              'name' => 'release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            8 => 
            array (
              'name' => 'resolution',
              'enabled' => true,
              'default' => false,
            ),
            9 => 
            array (
              'name' => 'team_name',
              'enabled' => true,
              'default' => false,
            ),
          ),
        ),
      ),
    ),
  ),
  'resolve-conflicts-list' => 
  array (
    'meta' => 
    array (
      'panels' => 
      array (
        0 => 
        array (
          'name' => 'panel_header',
          'label' => 'LBL_PANEL_1',
          'fields' => 
          array (
            0 => 
            array (
              'name' => 'bug_number',
              'enabled' => true,
              'default' => true,
              'readonly' => true,
            ),
            1 => 
            array (
              'name' => 'name',
              'enabled' => true,
              'default' => true,
            ),
            2 => 
            array (
              'name' => 'status',
              'enabled' => true,
              'default' => true,
            ),
            3 => 
            array (
              'name' => 'type',
              'enabled' => true,
              'default' => true,
            ),
            4 => 
            array (
              'name' => 'priority',
              'enabled' => true,
              'default' => false,
            ),
            5 => 
            array (
              'name' => 'fixed_in_release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            6 => 
            array (
              'name' => 'assigned_user_name',
              'enabled' => true,
              'default' => false,
            ),
            7 => 
            array (
              'name' => 'release_name',
              'enabled' => true,
              'default' => false,
              'link' => false,
            ),
            8 => 
            array (
              'name' => 'resolution',
              'enabled' => true,
              'default' => false,
            ),
            9 => 
            array (
              'name' => 'team_name',
              'enabled' => true,
              'default' => false,
            ),
          ),
        ),
      ),
    ),
  ),
  'massupdate' => 
  array (
    'meta' => 
    array (
      'buttons' => 
      array (
        0 => 
        array (
          'type' => 'button',
          'value' => 'cancel',
          'css_class' => 'btn-link btn-invisible cancel_button',
          'label' => 'LBL_CANCEL_BUTTON_LABEL',
          'primary' => false,
        ),
        1 => 
        array (
          'name' => 'update_button',
          'type' => 'button',
          'label' => 'LBL_UPDATE',
          'acl_action' => 'massupdate',
          'css_class' => 'btn-primary',
          'primary' => true,
        ),
      ),
      'panels' => 
      array (
        0 => 
        array (
          'fields' => 
          array (
          ),
        ),
      ),
    ),
  ),
  '_hash' => '4c1db862c5cf4126e2be0573b0ef6fcf',
);

