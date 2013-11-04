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
/*        array('link' => 'user/import',
              'title' => '{Import users}',
              'icon' => 'import',
              'level' => 3),*/
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
    $m = new \app\Main;
    $m->e404();
});*/

$app->set('LOC','app/lang/');
$app->set('LANG', 'sv_SE');
$app->set('menu', $menu);

$app->route('GET /', '\app\Main->index');
//$app->route('GET /minify/@type', '\app\Main->minify', 3600);
$app->route('GET /@page', '\app\Main->@page');
$app->route('GET /report', '\app\Report');
//$app->route('GET /batch', '\app\Batch');

$app->route('GET|POST /login/@type', '\app\Login->@type');
$app->route('GET /logout', '\app\Login->logout');

$app->route('GET /titles/@from/@to', '\app\Main->titles_ajax');
$app->route('GET /users/@from/@to', '\app\Main->users_ajax');

// REST-API
$app->map('/title/@id', '\app\Title');
$app->map('/title/@id/@action', '\app\Title');
$app->map('/user/@id', '\app\User');
$app->map('/copy/@id', '\app\Copy');
$app->map('/label/@type/@id','\app\Label');
$app->map('/label/@type/@group/@id','\app\Label');
$app->map('/report/@name', '\app\Report');
$app->map('/batch/@id', '\app\Batch');
$app->map('/image/@id', '\app\Image');
$app->map('/image', '\app\Image');

$app->map('/ajax/@what', '\app\Ajax');
$app->map('/circ', '\app\Circ');

$app->run();
