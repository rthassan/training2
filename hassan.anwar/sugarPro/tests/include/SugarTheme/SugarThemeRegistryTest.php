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

require_once 'include/SugarTheme/SugarTheme.php';
require_once 'include/dir_inc.php';

class SugarThemeRegistryTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_themeName;
    private $_oldDefaultTheme;

    public function setup()
    {
        $this->_themeName = SugarTestThemeUtilities::createAnonymousTheme();
        if ( isset($GLOBALS['sugar_config']['default_theme']) ) {
            $this->_oldDefaultTheme = $GLOBALS['sugar_config']['default_theme'];
        }
        $GLOBALS['sugar_config']['default_theme'] = $this->_themeName;
        SugarThemeRegistry::buildRegistry();
    }

    public function tearDown()
    {
        SugarTestThemeUtilities::removeAllCreatedAnonymousThemes();
        if ( isset($this->_oldDefaultTheme) ) {
            $GLOBALS['sugar_config']['default_theme'] = $this->_oldDefaultTheme;
            SugarThemeRegistry::set($GLOBALS['sugar_config']['default_theme']);
        }
    }

    public function testThemesRegistered()
    {
        $this->assertTrue(SugarThemeRegistry::exists($this->_themeName));
    }

    public function testGetThemeObject()
    {
        $object = SugarThemeRegistry::get($this->_themeName);

        $this->assertInstanceOf('SugarTheme',$object);
        $this->assertEquals($object->__toString(),$this->_themeName);
    }

    /**
     * @ticket 41635
     */
    public function testGetDefaultThemeObject()
    {
        $GLOBALS['sugar_config']['default_theme'] = $this->_themeName;

        $object = SugarThemeRegistry::getDefault();

        $this->assertInstanceOf('SugarTheme',$object);
        $this->assertEquals($object->__toString(),$this->_themeName);
    }

    /**
     * @ticket 60210
     */
    public function testGetDefaultDisabledThemeObject()
    {
        if ( isset($GLOBALS['sugar_config']['disabled_themes']) ) {
            $disabled_themes = $GLOBALS['sugar_config']['disabled_themes'];
            unset($GLOBALS['sugar_config']['disabled_themes']);
        }

        $GLOBALS['sugar_config']['disabled_themes'] = $this->_themeName;
        $GLOBALS['sugar_config']['default_theme'] = $this->_themeName;

        $object = SugarThemeRegistry::getDefault();
        $this->assertNotEquals($object->__toString(),$this->_themeName);

        if ( isset($disabled_themes) ) {
            $GLOBALS['sugar_config']['disabled_themes'] = $disabled_themes;
        }
    }

    /**
     * @ticket 41635
     */
    public function testGetDefaultThemeObjectWhenDefaultThemeIsNotSet()
    {
        unset($GLOBALS['sugar_config']['default_theme']);

        $themename = 'RacerX';

        $object = SugarThemeRegistry::getDefault();

        $this->assertInstanceOf('SugarTheme',$object);
        $this->assertEquals($object->__toString(),$themename);
    }

    public function testSetCurrentTheme()
    {
        SugarThemeRegistry::set($this->_themeName);

        $this->assertInstanceOf('SugarTheme',SugarThemeRegistry::current());
        $this->assertEquals(SugarThemeRegistry::current()->__toString(),$this->_themeName);
    }

    public function testInListOfAvailableThemes()
    {
        if ( isset($GLOBALS['sugar_config']['disabled_themes']) ) {
            $disabled_themes = $GLOBALS['sugar_config']['disabled_themes'];
            unset($GLOBALS['sugar_config']['disabled_themes']);
        }

        $themes = SugarThemeRegistry::availableThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::unAvailableThemes();
        $this->assertTrue(!isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::allThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));

        if ( isset($disabled_themes) ) {
            $GLOBALS['sugar_config']['disabled_themes'] = $disabled_themes;
        }
    }

    public function testDisabledThemeNotInListOfAvailableThemes()
    {
        if ( isset($GLOBALS['sugar_config']['disabled_themes']) ) {
            $disabled_themes = $GLOBALS['sugar_config']['disabled_themes'];
            unset($GLOBALS['sugar_config']['disabled_themes']);
        }

        $GLOBALS['sugar_config']['disabled_themes'] = $this->_themeName;

        $themes = SugarThemeRegistry::availableThemes();
        $this->assertTrue(!isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::unAvailableThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::allThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));

        if ( isset($disabled_themes) )
            $GLOBALS['sugar_config']['disabled_themes'] = $disabled_themes;
    }

    public function testCustomThemeLoaded()
    {
        $customTheme = SugarTestThemeUtilities::createAnonymousCustomTheme($this->_themeName);

        SugarThemeRegistry::buildRegistry();

        $this->assertEquals(
            SugarThemeRegistry::get($customTheme)->name,
            'custom ' . $customTheme
            );
    }

    public function testDefaultThemedefFileHandled()
    {
        create_custom_directory('themes/default/');
        sugar_file_put_contents('custom/themes/default/themedef.php','<?php $themedef = array("group_tabs" => false);');

        SugarThemeRegistry::buildRegistry();

        $this->assertEquals(
            SugarThemeRegistry::get($this->_themeName)->group_tabs,
            false
            );

        unlink('custom/themes/default/themedef.php');
    }

    public function testClearCacheAllThemes()
    {
        SugarThemeRegistry::get($this->_themeName)->getCSSURL('style.css');
        $this->assertTrue(isset(SugarThemeRegistry::get($this->_themeName)->_cssCache['style.css']),
                            'File style.css should exist in cache');

        SugarThemeRegistry::clearAllCaches();
        SugarThemeRegistry::buildRegistry();

        $this->assertFalse(isset(SugarThemeRegistry::get($this->_themeName)->_cssCache['style.css']),
                            'File style.css shouldn\'t exist in cache');
    }

    /**
     * @ticket 35307
     */
    public function testOldThemeIsNotRecognized()
    {
        $themename = SugarTestThemeUtilities::createAnonymousOldTheme();

        $this->assertNull(SugarThemeRegistry::get($themename));
    }
}
