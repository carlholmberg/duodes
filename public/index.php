<?php

$app=require('../lib/f3/base.php');

$app->config('../data/config.ini');

$app->set('CACHE', false);
$app->set('DEBUG', 3);

$app->set('AUTOLOAD', '../inc/');
$app->set('GUI','../gui/'); 
$app->set('TEMP', '../temp/');
$app->set('LOCALES','../lang/');
$app->set('LANGUAGE', 'sv_SE');

$app->route('GET /', '\controllers\Main->index');
$app->route('GET /@collection', '\controllers\Main->collection');
$app->route('GET /@collection/@page', '\controllers\Main->@page');
$app->route('GET /report', '\controllers\Report');
 
// REST-API
$app->map('/@collection/title/@id', '\controllers\Title');
$app->map('/@collection/user/@id', '\controllers\User');
$app->map('/@collection/copy/@id', '\controllers\Copy');
$app->map('/@collection/label/@type/@id','\controllers\Label');
$app->map('/@collection/label/@type/@group/@id','\controllers\Label');
$app->map('/report/@name', '\controllers\Report');
$app->map('/@collection/report/@name', '\controllers\Report');


$app->run();
