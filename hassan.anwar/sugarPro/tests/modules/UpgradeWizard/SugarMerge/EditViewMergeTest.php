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

require_once 'modules/UpgradeWizard/SugarMerge/MergeUtils.php';
require_once 'modules/UpgradeWizard/SugarMerge/EditViewMerge.php';

class EditViewMergeTest extends Sugar_PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider deepMergeProvider
     * @group unit
     *
     */
    function testDeepMergeDef($old, $new, $custom, $expected)
    {
        $this->assertEquals($expected, MergeUtils::deepMergeDef($old, $new, $custom));
    }

    public function deepMergeProvider()
    {
        return array(
            array(
                "old" => array(
                    'buttons' => array(
                        'B1',
                        'B2',
                        'B6',
                        'B3',
                    ),
                ),
                "new" => array(
                    'buttons' => array(
                        'B1',
                        'B4',
                        'B6',
                        'B2',
                    ),
                ),
                "custom" => array(
                    'buttons' => array(
                        'B1',
                        'B2',
                        'B6',
                        'B3',
                    ),
                ),
                "expected" => array(
                    'buttons' => array(
                        'B1',
                        'B4',
                        'B6',
                        'B2',
                    ),
                )
            ),
            array(
                "old" => array(
                    'buttons' => array(
                        'B1',
                        array(
                            'type' => 'divider',
                        ),
                        'B2',
                        array(
                            'type' => 'divider',
                        ),
                        'B6',
                        'B3',
                    ),
                ),
                "new" => array(
                    'buttons' => array(
                        'B1',
                        array(
                            'type' => 'divider',
                        ),
                        'B4',
                        array(
                            'type' => 'divider',
                        ),
                        'B6',
                        'B2',
                    ),
                ),
                "custom" => array(
                    'buttons' => array(
                        'B1',
                        array(
                            'type' => 'divider',
                        ),
                        'B2',
                        array(
                            'type' => 'divider',
                        ),
                        'CUSTOM',
                        array(
                            'type' => 'divider',
                        ),
                        'B6',
                        'B3',
                    ),
                ),
                "expected" => array(
                    'buttons' => array(
                        'B1',
                        array(
                            'type' => 'divider',
                        ),
                        'B4',
                        'B2',
                        array(
                            'type' => 'divider',
                        ),
                        'CUSTOM',
                        array(
                            'type' => 'divider',
                        ),
                        'B6',
                    ),
                )
            ),
            //Test 1, custom footer TPL, custom button with added button during upgrade (BR-1888)
            array(
                "old" => array(
                    'goingToBeOutDated' => array(
                        'key' => 'value'
                    ),
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                    ),
                    'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl',
                ),
                "new" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DELETE',
                    ),
                    'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl',
                ),
                "custom" => array(
                    'goingToBeOutDated' => array(
                        'key' => 'myCustomValue'
                    ),
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'CUSTOM',
                    ),
                    'footerTpl' => 'custom/modules/Quotes/tpls/DetailViewFooter.tpl',
                ),
                "expected" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DELETE',
                        'CUSTOM',
                    ),
                    'footerTpl' => 'custom/modules/Quotes/tpls/DetailViewFooter.tpl',
                )
            ),

            //Test2 custom has removed "SHARE" and replaceed it with custom. anonymous button with custom code updated
            array(
                "old" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        array(
                            'customCode' => 'some custom <b>HTML!</b>'
                        ),
                        array(
                            'name' => 'foo',
                            'customCode' => 'some custom <b>HTML!</b>'
                        )
                    ),
                    'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl',
                ),
                "new" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        array(
                            'customCode' => 'some other custom <b>HTML!</b>'
                        ),
                        array(
                            'name' => 'foo',
                            'customCode' => 'Again some changed custom <b>HTML!</b>'
                        )
                    ),
                ),
                "custom" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'CUSTOM',
                    ),
                    'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl',
                ),
                "expected" => array(
                    'closeFormBeforeCustomButtons' => true,
                    'buttons' => array(
                        'EDIT',
                        'CUSTOM',
                        array(
                            'customCode' => 'some other custom <b>HTML!</b>'
                        )
                    ),
                )
            ),

            //Test3  Old/new same, no custom... take old/new
            array(
                'old' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                    ),
                ),
                'new' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                    ),
                ),
                'cst' => array(),
                'expect' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                    )
                ),
            ),

            // Old/New same, Custom different, merge custom with changes
            array(
                'old' => array(
                    'a1' => array(
                        'id' => 'record_view',
                        'defaults' => array(
                            'show_more' => 'more'
                        ),
                    ),
                    'a2' => array(
                        'node' => array(
                            'foo' => 'bar',
                        ),
                    ),
                ),
                'new' => array(
                    'a1' => array(
                        'id' => 'record_view',
                        'defaults' => array(
                            'show_more' => 'more'
                        ),
                    ),
                    'a2' => array(
                        'node' => array(
                            'foo' => 'bar',
                        ),
                    ),
                ),
                'cst' => array(
                    'a1' => array(
                        'id' => 'list_view',
                    ),
                ),
                'expect' => array(
                    'a1' => array(
                        'id' => 'list_view',
                    ),
                ),
            ),

            // Old/New different, no Custom, take changes between new and old
            array(
                'old' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                    ),
                ),
                'new' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'modify_button',
                            'label' => 'LBL_MODIFY_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                        ),
                    ),
                ),
                'cst' => array(),
                'expect' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'modify_button',
                            'label' => 'LBL_MODIFY_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                        ),
                    ),
                ),
            ),

            // Old, new and Custom all different, merge custom with changes
            array(
                'old' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                    ),
                ),
                'new' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'modify_button',
                            'label' => 'LBL_MODIFY_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                        ),
                    ),
                ),
                'cst' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'send_button',
                            'label' => 'LBL_SEND_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => array('edit', 'record'),
                        ),
                    ),
                ),
                'expect' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'modify_button',
                            'label' => 'LBL_MODIFY_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                        ),
                        array(
                            'type' => 'button',
                            'name' => 'send_button',
                            'label' => 'LBL_SEND_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => array('edit', 'record'),
                        ),
                    ),
                ),
            ),

            // From ticket # BR-1804...
            // old is 7.2.0 OOTB Accounts record viewdefs
            // new is 7.2.1 OOTB Accounts record viewdefs
            // cst is 7.2.0 custom Accounts record viewdefs
            // expect contains panels, last_state and buttons
            array(
                'old' => array(
                    'panels' => array(),
                ),
                'new' => array(
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                        array(
                            'type' => 'actiondropdown',
                            'name' => 'main_dropdown',
                            'primary' => true,
                            'showOn' => 'view',
                            'buttons' => array(
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:edit_button:click',
                                    'name' => 'edit_button',
                                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                                    'acl_action' => 'edit',
                                ),
                                array(
                                    'type' => 'shareaction',
                                    'name' => 'share',
                                    'label' => 'LBL_RECORD_SHARE_BUTTON',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'pdfaction',
                                    'name' => 'download-pdf',
                                    'label' => 'LBL_PDF_VIEW',
                                    'action' => 'download',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'pdfaction',
                                    'name' => 'email-pdf',
                                    'label' => 'LBL_PDF_EMAIL',
                                    'action' => 'email',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'divider',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:find_duplicates_button:click',
                                    'name' => 'find_duplicates_button',
                                    'label' => 'LBL_DUP_MERGE',
                                    'acl_action' => 'edit',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:duplicate_button:click',
                                    'name' => 'duplicate_button',
                                    'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                                    'acl_module' => 'Accounts',
                                    'acl_action' => 'create',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:historical_summary_button:click',
                                    'name' => 'historical_summary_button',
                                    'label' => 'LBL_HISTORICAL_SUMMARY',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:audit_button:click',
                                    'name' => 'audit_button',
                                    'label' => 'LNK_VIEW_CHANGE_LOG',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'divider',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:delete_button:click',
                                    'name' => 'delete_button',
                                    'label' => 'LBL_DELETE_BUTTON_LABEL',
                                    'acl_action' => 'delete',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'sidebar_toggle',
                            'type' => 'sidebartoggle',
                        ),
                    ),
                    'panels' => array(),
                ),
                'cst' => array(
                    'panels' => array(
                        array(
                            'name' => 'panel_header',
                            'label' => 'LBL_PANEL_HEADER',
                            'header' => true,
                            'fields' => array(
                                array(
                                    'name' => 'picture',
                                    'type' => 'avatar',
                                    'size' => 'large',
                                    'dismiss_label' => true,
                                    'readonly' => true,
                                ),
                                'name',
                                array(
                                    'name' => 'favorite',
                                    'label' => 'LBL_FAVORITE',
                                    'type' => 'favorite',
                                    'dismiss_label' => true,
                                ),
                                array(
                                    'name' => 'follow',
                                    'label' => 'LBL_FOLLOW',
                                    'type' => 'follow',
                                    'readonly' => true,
                                    'dismiss_label' => true,
                                ),
                            )
                        ),
                    ),
                    'last_state' => array(
                        'id' => 'record_view',
                        'defaults' => array(
                            'show_more' => 'more'
                        ),
                    ),
                ),
                'expect' => array(
                    'panels' => array(
                        array(
                            'name' => 'panel_header',
                            'label' => 'LBL_PANEL_HEADER',
                            'header' => true,
                            'fields' => array(
                                array(
                                    'name' => 'picture',
                                    'type' => 'avatar',
                                    'size' => 'large',
                                    'dismiss_label' => true,
                                    'readonly' => true,
                                ),
                                'name',
                                array(
                                    'name' => 'favorite',
                                    'label' => 'LBL_FAVORITE',
                                    'type' => 'favorite',
                                    'dismiss_label' => true,
                                ),
                                array(
                                    'name' => 'follow',
                                    'label' => 'LBL_FOLLOW',
                                    'type' => 'follow',
                                    'readonly' => true,
                                    'dismiss_label' => true,
                                ),
                            )
                        ),
                    ),
                    'buttons' => array(
                        array(
                            'type' => 'button',
                            'name' => 'cancel_button',
                            'label' => 'LBL_CANCEL_BUTTON_LABEL',
                            'css_class' => 'btn-invisible btn-link',
                            'showOn' => 'edit',
                        ),
                        array(
                            'type' => 'rowaction',
                            'event' => 'button:save_button:click',
                            'name' => 'save_button',
                            'label' => 'LBL_SAVE_BUTTON_LABEL',
                            'css_class' => 'btn btn-primary',
                            'showOn' => 'edit',
                            'acl_action' => 'edit',
                        ),
                        array(
                            'type' => 'actiondropdown',
                            'name' => 'main_dropdown',
                            'primary' => true,
                            'showOn' => 'view',
                            'buttons' => array(
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:edit_button:click',
                                    'name' => 'edit_button',
                                    'label' => 'LBL_EDIT_BUTTON_LABEL',
                                    'acl_action' => 'edit',
                                ),
                                array(
                                    'type' => 'shareaction',
                                    'name' => 'share',
                                    'label' => 'LBL_RECORD_SHARE_BUTTON',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'pdfaction',
                                    'name' => 'download-pdf',
                                    'label' => 'LBL_PDF_VIEW',
                                    'action' => 'download',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'pdfaction',
                                    'name' => 'email-pdf',
                                    'label' => 'LBL_PDF_EMAIL',
                                    'action' => 'email',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'divider',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:find_duplicates_button:click',
                                    'name' => 'find_duplicates_button',
                                    'label' => 'LBL_DUP_MERGE',
                                    'acl_action' => 'edit',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:duplicate_button:click',
                                    'name' => 'duplicate_button',
                                    'label' => 'LBL_DUPLICATE_BUTTON_LABEL',
                                    'acl_module' => 'Accounts',
                                    'acl_action' => 'create',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:historical_summary_button:click',
                                    'name' => 'historical_summary_button',
                                    'label' => 'LBL_HISTORICAL_SUMMARY',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:audit_button:click',
                                    'name' => 'audit_button',
                                    'label' => 'LNK_VIEW_CHANGE_LOG',
                                    'acl_action' => 'view',
                                ),
                                array(
                                    'type' => 'divider',
                                ),
                                array(
                                    'type' => 'rowaction',
                                    'event' => 'button:delete_button:click',
                                    'name' => 'delete_button',
                                    'label' => 'LBL_DELETE_BUTTON_LABEL',
                                    'acl_action' => 'delete',
                                ),
                            ),
                        ),
                        array(
                            'name' => 'sidebar_toggle',
                            'type' => 'sidebartoggle',
                        ),
                    ),
                    'last_state' => array(
                        'id' => 'record_view',
                        'defaults' => array(
                            'show_more' => 'more'
                        ),
                    ),
                ),
            ),

            //Calls Style complex data
            array(
                'old' => array(
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DUPLICATE',
                        'DELETE',
                        array(
                            'customCode' => 'custom code 1',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                                    'name' => 'button',
                                    'id' => 'close_create_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
                            ),

                        ),
                        array(
                            'customCode' => 'custom code 2',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                    'accesskey' => '{$APP.LBL_CLOSE_BUTTON_KEY}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isSave.value=true;this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.isSaveFromDetailView.value=true',
                                    'name' => 'button1',
                                    'id' => 'close_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}'
                            ),

                        ),
                    ),
                    'hidden' => array(
                        '<input type="hidden" name="isSaveAndNew">',
                        '<input type="hidden" name="status">',
                        '<input type="hidden" name="isSaveFromDetailView">',
                        '<input type="hidden" name="isSave">'

                    ),
                    'headerTpl' => 'modules/Calls/tpls/detailHeader.tpl',
                    'maxColumns' => '2',
                    'widths' => array(
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                    ),
                    'useTabs' => false,
                    '<input type="hidden" name="isSaveAndNew">',
                    '<input type="hidden" name="status">',
                    '<input type="hidden" name="isSaveFromDetailView">',
                    '<input type="hidden" name="isSave">'
                ),
                'new' => array(
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DUPLICATE',
                        'DELETE',
                        array(
                            'customCode' => 'custom code 1 has changed',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                                    'name' => 'button',
                                    'id' => 'close_create_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
                            ),

                        ),
                        array(
                            'customCode' => 'custom code 2',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                    'accesskey' => '{$APP.LBL_CLOSE_BUTTON_KEY}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isSave.value=true;this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.isSaveFromDetailView.value=true',
                                    'name' => 'button1',
                                    'id' => 'close_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}'
                            ),

                        ),
                    ),
                    'hidden' => array(
                        '<input type="hidden" name="isSaveAndNew">',
                        '<input type="hidden" name="aDifferentField">',
                        '<input type="hidden" name="isSaveFromDetailView">',
                        '<input type="hidden" name="isSave">'

                    ),
                    'headerTpl' => 'modules/Calls/tpls/detailHeader.tpl',
                    'maxColumns' => '2',
                    'widths' => array(
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                    ),
                    'useTabs' => false,
                    '<input type="hidden" name="isSaveAndNew">',
                    '<input type="hidden" name="aDifferentField">',
                    '<input type="hidden" name="isSaveFromDetailView">',
                    '<input type="hidden" name="isSave">'
                ),
                'custom' => array(
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DUPLICATE',
                        'DELETE',
                        array(
                            'customCode' => 'custom code 1',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                                    'name' => 'button',
                                    'id' => 'close_create_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
                            ),

                        ),
                        array(
                            'customCode' => 'custom code 2',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                    'accesskey' => '{$APP.LBL_CLOSE_BUTTON_KEY}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isSave.value=true;this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.isSaveFromDetailView.value=true',
                                    'name' => 'button1',
                                    'id' => 'close_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}'
                            ),

                        ),
                    ),
                    'hidden' => array(
                        '<input type="hidden" name="isSaveAndNew">',
                        '<input type="hidden" name="status">',
                        '<input type="hidden" name="isSaveFromDetailView">',
                        '<input type="hidden" name="isSave">'

                    ),
                    'headerTpl' => 'custom/modules/Calls/tpls/detailHeader.tpl',
                    'maxColumns' => '2',
                    'widths' => array(
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                    ),
                    'useTabs' => false,
                    '<input type="hidden" name="isSaveAndNew">',
                    '<input type="hidden" name="status">',
                    '<input type="hidden" name="isSaveFromDetailView">',
                    '<input type="hidden" name="isSave">'
                ),
                'expected' => array(
                    'buttons' => array(
                        'EDIT',
                        'SHARE',
                        'DUPLICATE',
                        'DELETE',
                        array(
                            'customCode' => 'custom code 1 has changed',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_AND_CREATE_BUTTON_TITLE}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.isSaveFromDetailView.value=true; this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isDuplicate.value=true;this.form.isSaveAndNew.value=true;this.form.return_action.value=\'EditView\'; this.form.return_id.value=\'{$fields.id.value}\'',
                                    'name' => 'button',
                                    'id' => 'close_create_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}',
                            ),

                        ),
                        array(
                            'customCode' => 'custom code 2',
                            'sugar_html' => array(
                                'type' => 'submit',
                                'value' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                'htmlOptions' => array(
                                    'title' => '{$APP.LBL_CLOSE_BUTTON_TITLE}',
                                    'accesskey' => '{$APP.LBL_CLOSE_BUTTON_KEY}',
                                    'class' => 'button',
                                    'onclick' => 'this.form.status.value=\'Held\'; this.form.action.value=\'Save\';this.form.return_module.value=\'Calls\';this.form.isSave.value=true;this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\';this.form.isSaveFromDetailView.value=true',
                                    'name' => 'button1',
                                    'id' => 'close_button',
                                ),
                                'template' => '{if $fields.status.value != "Held" && $bean->aclAccess("edit")}[CONTENT]{/if}'
                            ),

                        ),
                    ),
                    'hidden' => array(
                        '<input type="hidden" name="isSaveAndNew">',
                        '<input type="hidden" name="aDifferentField">',
                        '<input type="hidden" name="isSaveFromDetailView">',
                        '<input type="hidden" name="isSave">'
                    ),
                    'headerTpl' => 'custom/modules/Calls/tpls/detailHeader.tpl',
                    'maxColumns' => '2',
                    'widths' => array(
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                    ),
                    'useTabs' => false,
                    '<input type="hidden" name="isSaveAndNew">',
                    '<input type="hidden" name="aDifferentField">',
                    '<input type="hidden" name="isSaveFromDetailView">',
                    '<input type="hidden" name="isSave">'
                ),
            )
        );
    }


    /**
     * @dataProvider mergeTemplateProvider
     * @group unit
     *
     */
    function testMergeTemplateMeta($old, $new, $custom, $expected)
    {
        $module = "TestModule";
        $viewDefs = "TestView";
        $merge = new MockEditViewMerge($module, $viewDefs, $old, $new, $custom);
        $this->assertEquals($expected, $merge->testMergeTemplateMeta());
    }

    public function mergeTemplateProvider()
    {
        return array(
            array(
                "old" => array(
                    'form' => array(
                        'closeFormBeforeCustomButtons' => true,
                        'buttons' => array(
                            'EDIT',
                            'SHARE',
                        ),
                        'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    'maxColumns' => '2',
                    'shouldUseNew' => '2',
                    'useTabs' => true,
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '10', 'field' => '30')
                    ),
                ),
                "new" => array(
                    'form' => array(
                        'closeFormBeforeCustomButtons' => true,
                        'buttons' => array(
                            'EDIT',
                            'SHARE',
                            'DELETE',
                        ),
                        'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    'maxColumns' => '2',
                    'shouldUseNew' => '3',
                    'useTabs' => false,
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '20', 'field' => '30')
                    ),
                ),
                "custom" => array(
                    'form' => array(
                        'closeFormBeforeCustomButtons' => false,
                        'buttons' => array(
                            'EDIT',
                            'SHARE',
                        ),
                        'footerTpl' => 'custom/modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    'maxColumns' => '4',
                    'shouldUseNew' => '4',
                    'useTabs' => true,
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '10', 'field' => '30')
                    ),
                ),
                "expected" => array(
                    'form' => array(
                        'closeFormBeforeCustomButtons' => false,
                        'buttons' => array(
                            'EDIT',
                            'SHARE',
                            'DELETE',
                        ),
                        //Non-arrays should allow for custom entry to remain
                        'footerTpl' => 'custom/modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    //unknown non-array items that haven't changed should stay with the custom value
                    'maxColumns' => '4',
                    //unknown non-array items that changed should default to the 'new' value
                    'shouldUseNew' => '3',
                    //Items in templateMetaVarsToMerge should always use 'custom'
                    'useTabs' => true,
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        //Items that were the same in custom and old should use the "new" if it changed
                        array('label' => '20', 'field' => '30')
                    ),
                )
            ),
            //Verify removed items are removed from custom as well
            array(
                "old" => array(
                    'form' => array(
                        'footerTpl' => 'modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    'maxColumns' => '2',
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '10', 'field' => '30')
                    ),
                ),
                "new" => array(
                    'maxColumns' => '2',
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '10', 'field' => '30')
                    ),
                ),
                "custom" => array(
                    'form' => array(
                        'footerTpl' => 'custom/modules/Quotes/tpls/DetailViewFooter.tpl'
                    ),
                    'maxColumns' => '2',
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        array('label' => '10', 'field' => '30')
                    ),
                ),
                "expected" => array(
                    //unknown non-array items that haven't changed should stay with the custom value
                    'maxColumns' => '2',
                    'widths' => array(
                        array('label' => '10', 'field' => '30'),
                        //Items that were the same in custom and old should use the "new" if it changed
                        array('label' => '10', 'field' => '30')
                    ),
                )
            ),
        );
    }
}

class MockEditViewMerge extends EditViewMerge
{


    public function  __construct($module, $viewdefs, $old, $new, $custom)
    {
        $this->module = $module;
        $this->viewDefs = $viewdefs;
        $this->originalData = array($module => array($viewdefs => array($this->templateMetaName => $old)));
        $this->newData = array($module => array($viewdefs => array($this->templateMetaName => $new)));
        $this->customData = array($module => array($viewdefs => array($this->templateMetaName => $custom)));

    }

    function testMergeTemplateMeta()
    {
        $this->mergeTemplateMeta();

        return $this->newData[$this->module][$this->viewDefs][$this->templateMetaName];
    }
}
