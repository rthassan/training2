<?php

/**
 * Created by PhpStorm.
 * User: hassan.anwar
 * Date: 7/15/16
 * Time: 4:52 PM
 */

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule=new Capsule();


$capsule->addConnection([
    'driver'=> 'mysql',
    'host'=> 'localhost',
    'username'=> 'root',
    'password'=> '123',
    'database'=> 'School',
    'charset'=> 'utf8',
    'collation'=> 'utf8_unicode_ci',
    'prefix'=> ''

]);



$capsule->bootEloquent();