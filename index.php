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
          'title' => '{Reports}',
          'icon' => 'file',
          'level' => 3),
          
 /*   array('link' => 'batch',
          'title' => 'Batch',
          'icon' => '',
          'level' => 4)*/
);

$app = require('app/lib/f3/base.php');
$app->config('data/config.ini');
$app->config('data/users.ini');

/*$app->set('ONERROR', function() {
    if ($app->get('AJAX')) return;
    $m = new \app\Main;
    $m->e404();
});*/

$app->set('menu', $menu);

$app->run();
