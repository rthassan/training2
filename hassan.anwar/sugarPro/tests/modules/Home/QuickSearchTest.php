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
 
class QuickSearchTest extends Sugar_PHPUnit_Framework_TestCase
{
	private $quickSearch;
	
	public function setUp() 
    {
        SugarTestHelper::setUp('beanFiles');
        SugarTestHelper::setUp('beanList');
        SugarTestHelper::setUp('current_user', array(true, 1));
    }
    
    public function tearDown() 
    {
        unset($_REQUEST['data']);
        unset($_REQUEST['query']);
        $q = "delete from product_templates where name = 'MasonUnitTest'";
        $GLOBALS['db']->query($q);
        SugarTestHelper::tearDown();
    }
	
    public function testFormatResults()
    {
    	$tempPT = new ProductTemplate();
    	$tempPT->name = 'MasonUnitTest';
    	$tempPT->description = "Unit'test";
    	$tempPT->cost_price = 1000;
    	$tempPT->discount_price = 800;
    	$tempPT->list_price = 1100;
    	$tempPT->save();
    	
    	$_REQUEST['data'] = '{"conditions":[{"end":"%","name":"name","op":"like_custom","value":""}],"field_list":["name","id","type_id","mft_part_num","cost_price","list_price","discount_price","pricing_factor","description","cost_usdollar","list_usdollar","discount_usdollar","tax_class_name"],"form":"EditView","group":"or","id":"EditView_product_name[1]","limit":"30","method":"query","modules":["ProductTemplates"],"no_match_text":"No Match","order":"name","populate_list":["name_1","product_template_id_1"],"post_onblur_function":"set_after_sqs"}';
        $_REQUEST['query'] = 'MasonUnitTest';
        require('modules/Home/quicksearchQuery.php');
        
        $json = getJSONobj();
		$data = $json->decode(html_entity_decode($_REQUEST['data']));
		if(isset($_REQUEST['query']) && !empty($_REQUEST['query'])){
    		foreach($data['conditions'] as $k=>$v){
    			if(empty($data['conditions'][$k]['value'])){
       				$data['conditions'][$k]['value']=$_REQUEST['query'];
    			}
    		}
		}
 		$this->quickSearch = new quicksearchQuery();
		$result = $this->quickSearch->query($data);
		$resultBean = $json->decodeReal($result);
		$this->assertEquals($resultBean['fields'][0]['description'], $tempPT->description);
        // this is to suppress output. Need to fix properly with a good unit test.
        $this->expectOutputRegex('//');
    }
}
