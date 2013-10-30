<?php

$menu = array(
    array('title' => 'Circ',
          'link' => 'circ',
          'icon' => 'repeat',
          'level' => 2,
    ),
    
    array('title' => '{Titles}',
          'icon' => 'book',
          'level' => 0,
          'submenu' => array(
        array('link' => 'title/new',
              'title' => '{New title}',
              'icon' => 'edit',
              'level' => 3),
        array('link' => 'title/all',
            'title' => '{Show titles}',
            'icon'=> 'tasks',
            'level' => 0)
        )
    ),
    
    array('title' => '{Users}',
          'icon' => 'user',
          'level' => 3,
          'submenu' => array(
        array('link' => 'user/new',
              'title' => '{New user}',
              'icon' => 'edit',
              'level' => 3),
        array('link' => 'user/import',
              'title' => '{Import users}',
              'icon' => 'import',
              'level' => 3),
        array('link' => 'user/all',
            'title' => '{Show users}',
            'icon'=> 'tasks',
            'level' => 3)
        )
    ),
    
    array('link' => 'barcode',
          'title' => '{Barcodes}',
          'icon' => 'barcode',
          'level' => 4),
    array('link' => 'report',
          'title' => 'Reports',
          'icon' => '',
          'level' => 3),
          
 /*   array('link' => 'batch',
          'title' => 'Batch',
          'icon' => '',
          'level' => 4)*/
);

$app = require('app/lib/f3/base.php');

$app->config('data/config.ini');

$app->set('CACHE', false);
$app->set('DEBUG', 3);

$app->set('AUTOLOAD', 'app/inc/');
$app->set('UI','app/ui/'); 
$app->set('TEMP', 'data/temp/');
$app->set('TZ', 'Europe/Stockholm');

/*$app->set('ONERROR', function() {
    if ($app->get('AJAX')) return;
    $m = new \controllers\Main;
    $m->e404();
});*/

$app->set('LOC','app/lang/');
$app->set('LANG', 'sv_SE');
$app->set('menu', $menu);

$app->route('GET /', '\controllers\Main->index');
//$app->route('GET /minify/@type', '\controllers\Main->minify', 3600);
$app->route('GET /@page', '\controllers\Main->@page');
$app->route('GET /report', '\controllers\Report');
$app->route('GET /batch', '\controllers\Batch');

$app->route('GET|POST /login/@type', '\controllers\Login->@type');
$app->route('GET /logout', '\controllers\Login->logout');

$app->route('GET /titles/@from/@to', '\controllers\Main->titles_ajax');
$app->route('GET /users/@from/@to', '\controllers\Main->users_ajax');

// REST-API
$app->map('/title/@id', '\controllers\Title');
$app->map('/title/@id/@action', '\controllers\Title');
$app->map('/user/@id', '\controllers\User');
$app->map('/copy/@id', '\controllers\Copy');
$app->map('/label/@type/@id','\controllers\Label');
$app->map('/label/@type/@group/@id','\controllers\Label');
$app->map('/report/@name', '\controllers\Report');
$app->map('/batch/@id', '\controllers\Batch');
$app->map('/image/@id', '\controllers\Image');
$app->map('/ajax/@what', '\controllers\Ajax');
$app->map('/circ', '\controllers\Circ');

$app->run();
