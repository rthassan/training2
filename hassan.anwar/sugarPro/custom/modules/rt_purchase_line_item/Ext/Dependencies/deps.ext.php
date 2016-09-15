<?php
// WARNING: The contents of this file are auto-generated.

//Merged from custom/Extension/modules/rt_purchase_line_item/Ext/Dependencies/custom_discount.php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dependencies['rt_purchase_line_item']['calculate_discount'] = array(
    'hooks' => array('edit', 'view'),
    'trigger' => 'true',
    'triggerFields' => array('discount_amount','discount_check'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'SetValue',
            'params' => array(
                'target' => 'discount_price',
                'value' => 'ifElse(
                $discount_check,
                multiply(
                    subtract(
                        $unit_price,
                        multiply(
                            $unit_price,
                            divide(
                                $discount_amount,
                                100
                            )
                        )
                    ),
                    $quantity
                ),
                multiply(
                    subtract(
                        $unit_price,
                        $discount_amount
                    ),
                    $quantity
                )
            )',
            ),
        ),
    ),
);



//Merged from custom/Extension/modules/rt_purchase_line_item/Ext/Dependencies/custom_discount_calculation.php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dependencies['rt_purchase_line_item']['calculate_discount_amount'] = array(
    'hooks' => array('edit', 'view'),
    'trigger' => 'true',
    'triggerFields' => array('discount_amount', 'discount_check'),
    'onload' => true,
    //Actions is a list of actions to fire when the trigger is true
    'actions' => array(
        array(
            'name' => 'SetValue',
            'params' => array(
                'target' => 'discount_less',
                'value' => 'ifElse(
                $discount_check,
                multiply(
              
                        multiply(
                            $unit_price,
                            divide(
                                $discount_amount,
                                100
                            )
                        
                    ),
                    $quantity
                ),
                multiply(
                    $discount_amount,
                    $quantity   
                )
                )',
            ),
        ),
    ),
);
