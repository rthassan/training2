<?php

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
?>