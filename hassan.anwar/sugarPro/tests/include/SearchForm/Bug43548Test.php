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
 
require_once 'include/SearchForm/SugarSpot.php';

class Bug43548Test extends Sugar_PHPUnit_Framework_TestCase
{

    public function setUp()
    {
    	if(file_exists('custom/modules/Accounts/metadata/SearchFields.php'))
    	{
    	   copy('custom/modules/Accounts/metadata/SearchFields.php', 'custom/modules/Accounts/metadata/SearchFields.php.bak');
    	} else {
    	   if(!file_exists('custom/modules/Accounts/metadata'))
    	   {
    	      mkdir_recursive('custom/modules/Accounts/metadata');
    	   }
    	}    	

    }
    
    public function tearDown()
    {
        if(file_exists('custom/modules/Accounts/metadata/SearchFields.php'))
    	{
    	   unlink('custom/modules/Accounts/metadata/SearchFields.php');
    	} 

    	if(file_exists('custom/modules/Accounts/metadata/SearchFields.php.bak'))
    	{
    	   copy('custom/modules/Accounts/metadata/SearchFields.php.bak', 'custom/modules/Accounts/metadata/SearchFields.php');
    	   unlink('custom/modules/Accounts/metadata/SearchFields.php.bak');
    	}
    }

    
    public function testSugarSpotSearchGetSearchFieldsWithInline()
    {
    	//Load custom file with inline style of custom overrides
    if( $fh = @fopen('custom/modules/Accounts/metadata/SearchFields.php', 'w+') )
	{
$string = <<<EOQ
<?php
\$searchFields['Accounts']['account_type'] = array('query_type'=>'default', 'options' => 'account_type_dom', 'template_var' => 'ACCOUNT_TYPE_OPTIONS');
?>
EOQ;
       fputs( $fh, $string);
       fclose( $fh );
    }        	
    	$spot = new SugarSpotMock();
    	$searchFields = $spot->getTestSearchFields('Accounts');
    	$this->assertTrue(isset($searchFields['Accounts']['name']), 'Assert that name field is still set');
    	$this->assertTrue(isset($searchFields['Accounts']['account_type']), 'Assert that account_type field is still set');
    }
    
    public function testSugarSpotGetSearchFieldsWithCustomOverride()
    {
    	//Load custom file with override style of custom overrides
    if( $fh = @fopen('custom/modules/Accounts/metadata/SearchFields.php', 'w+') )
	{
$string = <<<EOQ
<?php

\$searchFields['Accounts'] = 
	array (
		'name' => array( 'query_type'=>'default'),
		'account_type'=> array('query_type'=>'default', 'options' => 'account_type_dom', 'template_var' => 'ACCOUNT_TYPE_OPTIONS'),
    );

?>
EOQ;
       fputs( $fh, $string);
       fclose( $fh );
    }    
    
    	$spot = new SugarSpotMock();
    	$searchFields = $spot->getTestSearchFields('Accounts');
    	$this->assertTrue(isset($searchFields['Accounts']['name']), 'Assert that name field is still set');
    	$this->assertTrue(isset($searchFields['Accounts']['account_type']), 'Assert that account_type field is still set');    	
    }
    
    
}

//Create SugarSpotMock since getSearchFields is protected
class SugarSpotMock extends SugarSpot {
	function getTestSearchFields($moduleName)
	{
		return parent::getSearchFields($moduleName);
	}
}

?>