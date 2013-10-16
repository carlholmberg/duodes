<?php

$menu = array(
    array('title' => 'Circ',
          'icon' => 'repeat',
          'submenu' => array(
        array('link' => 'circ/borrow',
              'title' => '{Borrow}',
              'icon' => 'edit'),
        array('link' => 'circ/return',
            'title' => '{Return}',
            'icon'=> 'tasks')
        )
    ),
    
    array('title' => '{Titles}',
          'icon' => 'book',
          'submenu' => array(
        array('link' => 'title/new',
              'title' => '{New title}',
              'icon' => 'edit'),
        array('link' => 'titles',
            'title' => '{Show titles}',
            'icon'=> 'tasks')
        )
    ),
    
    array('title' => '{Users}',
          'icon' => 'user',
          'submenu' => array(
        array('link' => 'user',
              'title' => 'New title',
              'icon' => 'edit'),
        array('link' => 'user/all',
            'title' => 'Show users',
            'icon'=> 'tasks')
        )
    ),
    
    array('link' => 'barcode',
          'title' => 'Barcodes',
          'icon' => 'barcode'),
    array('link' => 'report',
          'title' => 'Reports',
          'icon' => '')
);

$app = require('app/lib/f3/base.php');

$app->config('data/config.ini');

$app->set('CACHE', false);
$app->set('DEBUG', 3);

$app->set('AUTOLOAD', 'app/inc/');
$app->set('UI','app/ui/'); 
$app->set('TEMP', 'data/temp/');
$app->set('TZ', 'Europe/Stockholm');


$app->set('LOC','app/lang/');
$app->set('LANG', 'sv_SE');
$app->set('menu', $menu);
$app->set('autoaccount', array('carlsholmberg@gmail.com'));

$app->route('GET /', '\controllers\Main->index');
//$app->route('GET /minify/@type', '\controllers\Main->minify', 3600);
$app->route('GET /@page', '\controllers\Main->@page');
$app->route('GET /report', '\controllers\Report');

$app->route('GET|POST /login/@type', '\controllers\Login->@type');
$app->route('GET /logout', '\controllers\Login->logout');


// REST-API
$app->map('/title/@id', '\controllers\Title');
$app->map('/user/@id', '\controllers\User');
$app->map('/copy/@id', '\controllers\Copy');
$app->map('/label/@type/@id','\controllers\Label');
$app->map('/label/@type/@group/@id','\controllers\Label');
$app->map('/report/@name', '\controllers\Report');
$app->map('/log/@date', '\controllers\Log');

$app->run();
