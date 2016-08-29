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

require_once 'modules/Meetings/Meeting.php';

class Bug46294Test extends Sugar_PHPUnit_Framework_TestCase 
{
    var $dictionaryOptionsNotSet = array('Meeting' => array(
                                    'fields' => array(
                                      'type' => array(
                                        'options' => ''          
                                      )
                                    )
                                  )
                            );
    var $dictionaryOptionsEmpty = array('Meeting' => array(
                                    'fields' => array(
                                      'type' => array()
                                      //empty  
                                    )
                                 )
                            );
    var $dictionaryOptionsSet = array('Meeting' => array(
                                        'fields' => array(
                                          'type' => array(
                                            'options' => 'type_list'
                                          )
                                        )
                                   )
                                );
    var $dictionaryTypeListNotExists = array('Meeting' => array(
                                'fields' => array(
                                  'type' => array(
                                    'options' => 'type_not_exists'
                                  )
                                )
                              )
                        );
    var $appListStrings = array('type_list' => array(
                                    'breakfast' => 'breakfast',
                                    'lunch' => 'lunch',
                                    'dinner' => 'dinner'
                                 )
                            );
    
    var $appListStringsEmpty = array('type_list' => array());
       
    /**
    * @dataProvider provider
    */
    public function testGetMeetingTypeOptions($dictionary, $appList, $isEmpty)
    {
        $result = getMeetingTypeOptions($dictionary, $appList, $isEmpty);
        $this->assertEquals($isEmpty, empty($result));
    }

    public function provider()
    {
        return array(
            array($this->dictionaryOptionsSet, $this->appListStrings, false),
            array($this->dictionaryOptionsNotSet, $this->appListStrings, true),
            array($this->dictionaryOptionsEmpty, $this->appListStrings, true),
            array($this->dictionaryTypeListNotExists, $this->appListStrings, true),
            array($this->dictionaryOptionsSet, $this->appListStringsEmpty, true)
        );
    }
}