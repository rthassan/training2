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
require_once('modules/Calls/Call.php');
require_once('modules/Calls/CallHelper.php');

class CallHelperTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setup()
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('app_list_strings');
        SugarTestHelper::setUp('current_user');
    }
    
    public function tearDown()
    {
        SugarTestHelper::tearDown();
    }
    
    public function providerGetDurationMinutesOptions()
    {
        return array(
            array('EditView',<<<EOHTML
<select id="duration_minutes" onchange="SugarWidgetScheduler.update_time();" name="duration_minutes">
<OPTION value='0'>00</OPTION>
<OPTION selected value='15'>15</OPTION>
<OPTION value='30'>30</OPTION>
<OPTION value='45'>45</OPTION></select>
EOHTML
                ),
            array('MassUpdate',<<<EOHTML
<select id="duration_minutes" name="duration_minutes">
<OPTION value='0'>00</OPTION>
<OPTION selected value='15'>15</OPTION>
<OPTION value='30'>30</OPTION>
<OPTION value='45'>45</OPTION></select>
EOHTML
                ),
            array('QuickCreate',<<<EOHTML
<select id="duration_minutes" onchange="SugarWidgetScheduler.update_time();" name="duration_minutes">
<OPTION value='0'>00</OPTION>
<OPTION selected value='15'>15</OPTION>
<OPTION value='30'>30</OPTION>
<OPTION value='45'>45</OPTION></select>
EOHTML
                ),

            array('wirelessedit',<<<EOHTML
<select id="duration_minutes" name="duration_minutes">
<OPTION value='0'>00</OPTION>
<OPTION selected value='15'>15</OPTION>
<OPTION value='30'>30</OPTION>
<OPTION value='45'>45</OPTION></select>
EOHTML
                ),

            array('DetailView','15'),
        );
    }
    
    /**
     * @dataProvider providerGetDurationMinutesOptions
     */
	public function testGetDurationMinutesOptions(
	    $view,
	    $returnValue
	    )
    {
        $focus = new Call();
        
        $this->assertEquals(
            getDurationMinutesOptions($focus,'','',$view),
            $returnValue
            );
    }
    
    public function testGetDurationMinutesOptionsNonDefaultValue()
    {
        $focus = new Call();
        $focus->duration_minutes = '30';
        
        $this->assertEquals(
            getDurationMinutesOptions($focus,'','','DetailView'),
            $focus->duration_minutes
            );
    }
    
    public function testGetDurationMinutesOptionsFromRequest()
    {
        $focus = new Call();
        $_REQUEST['duration_minutes'] = '45';
        
        $this->assertEquals(
            getDurationMinutesOptions($focus,'','','DetailView'),
            $_REQUEST['duration_minutes']
            );
        
        unset($_REQUEST['duration_minutes']);
    }
    
    public function testGetDurationMinutesOptionsOtherValues()
    {
        $focus = new Call();
        $focus->date_start = null;
        $focus->duration_hours = null;
        $focus->minutes_value_default = null;
        
        getDurationMinutesOptions($focus,'','','DetailView');
        
        $this->assertEquals($focus->date_start,$GLOBALS['timedate']->to_display_date(gmdate($GLOBALS['timedate']->get_date_time_format())));
        $this->assertEquals($focus->duration_hours,'0');
        $this->assertEquals($focus->duration_minutes,'1');
    }
    
    public function providerGetReminderTime()
    {
        return array(
            array('EditView',<<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
                ),
            array('MassUpdate',<<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
                ),
            array('SubpanelCreates',<<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
                ),
            array('QuickCreate',<<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
                ),

            array('wirelessedit',<<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
                ),

            array('DetailView',''),
        );
    }
    
    /**
     * @dataProvider providerGetReminderTime
     */
	public function testGetReminderTime($view,$returnValue)
    {
        $this->markTestSkipped("getReminderTime deprecated as of 6.5.0");
        $focus = new Call();
        
        $this->assertEquals( getReminderTime($focus,'','',$view),$returnValue);
    }
    
    public function testGetReminderTimeNonDefaultValue()
    {
        $this->markTestSkipped("getReminderTime deprecated as of 6.5.0");
        $focus = new Call();
        $focus->reminder_time = '600';
        
        $this->assertEquals(
            getReminderTime($focus,'','','EditView'),
            <<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION selected value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
            );
    }
    
    public function testGetReminderTimeNonDefaultValueDetailView()
    {
        $this->markTestSkipped("getReminderTime deprecated as of 6.5.0");

        $focus = new Call();
        $focus->reminder_time = '300';
        
        $this->assertEquals( getReminderTime($focus,'','','DetailView'),'5 minutes prior');
    }
    
    public function testGetReminderTimeFromRequest()
    {
        $this->markTestSkipped("getReminderTime deprecated as of 6.5.0");
        $focus = new Call();
        $_REQUEST['reminder_time'] = '900';
        $_REQUEST['full_form'] = true;
        
        $this->assertEquals(
            getReminderTime($focus,'','','EditView'),
            <<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION selected value='900'>15 minutes prior</OPTION>
<OPTION value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
            );
        
        unset($_REQUEST['reminder_time']);
        unset($_REQUEST['full_form']);
    }
    
    public function testGetReminderTimeFromValue()
    {
        $this->markTestSkipped("getReminderTime deprecated as of 6.5.0");
        $focus = new Call();
        unset($focus->reminder_time);
        
        $this->assertEquals(
            getReminderTime($focus,'','1800','EditView'),
            <<<EOHTML
<select id="reminder_time" name="reminder_time">
<OPTION value='60'>1 minute prior</OPTION>
<OPTION value='300'>5 minutes prior</OPTION>
<OPTION value='600'>10 minutes prior</OPTION>
<OPTION value='900'>15 minutes prior</OPTION>
<OPTION selected value='1800'>30 minutes prior</OPTION>
<OPTION value='3600'>1 hour prior</OPTION></select>
EOHTML
            );
    }
}
