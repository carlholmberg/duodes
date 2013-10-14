<?php

date_default_timezone_set('Europe/Stockholm');

$menu = array(
    'opac' => array('link'=> 'opac',
                    'title' => 'OPAC',
                    'icon' => 'search'),
    'circ' => array('link' => 'circ',
                    'title' => 'Circ',
                    'icon' => 'repeat'),
    'titles' => array('link' => '#',
                      'title' => 'Title',
                      'icon' => 'book',
                      'submenu' => array(
        'newtitle' => array(
            'link' => 'add',
            'title' => 'New title',
            'icon' => 'edit'),
        'showtitles' => array(
            'link' => 'titles',
            'title' => 'Show titles',
            'icon'=> 'tasks')
        )
    ),
    'users' => array('link' => 'users',
                     'title' => 'Show users',
                     'icon' => ''),
    'barcode' => array('link' => 'barcode',
                       'title' => 'Barcodes',
                       'icon' => ''),
    'report' => array('link' => 'report',
                      'title' => 'Reports',
                      'icon' => '')
);

$app = require('../lib/f3/base.php');

$app->config('../data/config.ini');

$app->set('CACHE', false);
$app->set('DEBUG', 3);

$app->set('AUTOLOAD', '../inc/');
$app->set('UI','../ui/'); 
$app->set('TEMP', '../temp/');


$app->set('LOC','../lang/');
$app->set('LANG', 'sv_SE');


$app->route('GET /', '\controllers\Main->index');
//$app->route('GET /minify/@type', '\controllers\Main->minify', 3600);
$app->route('GET /@page', '\controllers\Main->@page');
$app->route('GET /report', '\controllers\Report');


// REST-API
$app->map('/title/@id', '\controllers\Title');
$app->map('/user/@id', '\controllers\User');
$app->map('/copy/@id', '\controllers\Copy');
$app->map('/label/@type/@id','\controllers\Label');
$app->map('/label/@type/@group/@id','\controllers\Label');
$app->map('/report/@name', '\controllers\Report');
$app->map('/log/@date', '\controllers\Log');

$app->run();
