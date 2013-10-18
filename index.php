<?php

$menu = array(
    array('title' => 'Circ',
          'icon' => 'repeat',
          'level' => 1,
          'submenu' => array(
        array('link' => 'circ/borrow',
              'title' => '{Borrow}',
              'icon' => 'edit',
              'level' => 1),
        array('link' => 'circ/return',
            'title' => '{Return}',
            'icon'=> 'tasks',
            'level' => 1)
        )
    ),
    
    array('title' => '{Titles}',
          'icon' => 'book',
          'level' => 0,
          'submenu' => array(
        array('link' => 'title/new',
              'title' => '{New title}',
              'icon' => 'edit',
              'level' => 2),
        array('link' => 'titles',
            'title' => '{Show titles}',
            'icon'=> 'tasks',
            'level' => 0)
        )
    ),
    
    array('title' => '{Users}',
          'icon' => 'user',
          'level' => 2,
          'submenu' => array(
        array('link' => 'user',
              'title' => 'New title',
              'icon' => 'edit',
              'level' => 2),
        array('link' => 'user/all',
            'title' => 'Show users',
            'icon'=> 'tasks',
            'level' => 2)
        )
    ),
    
    array('link' => 'barcode',
          'title' => 'Barcodes',
          'icon' => 'barcode',
          'level' => 3),
    array('link' => 'report',
          'title' => 'Reports',
          'icon' => '',
          'level' => 2),
          
    array('link' => 'batch',
          'title' => 'Batch',
          'icon' => '',
          'level' => 3)
);

$app = require('app/lib/f3/base.php');

$app->config('data/config.ini');

$app->set('CACHE', false);
$app->set('DEBUG', 3);

$app->set('AUTOLOAD', 'app/inc/');
$app->set('UI','app/ui/'); 
$app->set('TEMP', 'data/temp/');
$app->set('TZ', 'Europe/Stockholm');

$app->set('ONERROR', function() {
    $m = new \controllers\Main;
    $m->e404();
});

$app->set('LOC','app/lang/');
$app->set('LANG', 'sv_SE');
$app->set('menu', $menu);
$app->set('autoaccount', array('carlsholmberg@gmail.com' => 3));

$app->route('GET /', '\controllers\Main->index');
//$app->route('GET /minify/@type', '\controllers\Main->minify', 3600);
$app->route('GET /@page', '\controllers\Main->@page');
$app->route('GET /report', '\controllers\Report');
$app->route('GET /batch', '\controllers\Batch');

$app->route('GET|POST /login/@type', '\controllers\Login->@type');
$app->route('GET /logout', '\controllers\Login->logout');


// REST-API
$app->map('/title/@id', '\controllers\Title');
$app->map('/user/@id', '\controllers\User');
$app->map('/account/@id', '\controllers\Account');
$app->map('/copy/@id', '\controllers\Copy');
$app->map('/label/@type/@id','\controllers\Label');
$app->map('/label/@type/@group/@id','\controllers\Label');
$app->map('/report/@name', '\controllers\Report');
$app->map('/log/@date', '\controllers\Log');
$app->map('/batch/@id', '\controllers\Batch');

$app->run();
