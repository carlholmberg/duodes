<?php

class Label {
    function get($f3, $params) {
        echo '('.$params['collection'].') Item (GET): type '.$params['type'].' id '.$params['id'];
        if ($params['type'] == 'user') {
            if (isset($params['group'])) {
                
            }
        } else if ($params['type'] == 'copy') {
            if ($params['id'] == 'selected') {
                echo 'Get labels for selected copies';
            } else if (is_numeric($params['id'])) {
                echo 'Get label for '.$params['id'];
            } else {
                $f3->reroute('/'.$params['collection']);
            }
        } else {
            $f3->reroute('/'.$params['collection'].'/label');
        }
    }
    function post() {}
    function put() {}
    function delete() {}
}

class Report {
    function get($f3, $params) {
        echo '('.$params['collection'].') Report (GET): name '.$params['name'];
    }
    function post() {}
    function put() {}
    function delete() {}
}

class Title {
    function get($f3, $params) {
        echo $params['collection']."<br>";
        switch ($params['id']) {
            case 'all':
                echo 'Show all titles';
                break;
            case 'new':
                echo 'New title';
                break;
            default:
                if (!is_numeric($params['id']))
                    $f3->reroute('/'.$params['collection']);
                echo 'Get title:'.$params['id'];
                break;
        }
    }
    function post() {}
    function put() {}
    function delete() {}
}

class Copy {
    function get($f3, $params) {
        echo 'Copy (GET): name '.$params['id'];
    }
    function post() {}
    function put() {}
    function delete() {}
}

class User {
    function get($f3, $params) {
        echo 'User (GET): name '.$params['id'];
    }
    function post() {}
    function put() {}
    function delete() {}
}

class Main {
    function index() {
        echo 'Index page';
    }
    
    function collection($f3, $params) {
        echo 'Start for '.$params['collection'];
    }
    
    function label($f3, $params) {
        echo 'Label-page';
    }
    
    function copy($f3, $params) {
        echo 'Copy-page';
    }

    function title($f3, $params) {
        echo 'Title-page';
    }
    
    function user($f3, $params) {
        echo 'User-page';
    }
    
    function report($f3, $params) {
        echo 'Report-page';
    }
}

$f3=require('../lib/base.php');

$f3->config('../data/config.ini');


$f3->route('GET /', 'Main->index');
$f3->route('GET /@collection', 'Main->collection');
$f3->route('GET /@collection/@page', 'Main->@page');
 
// REST-API
$f3->map('/@collection/title/@id', 'Title');
$f3->map('/@collection/user/@id', 'User');
$f3->map('/@collection/copy/@id', 'Copy');
$f3->map('/@collection/label/@type/@id','Label');
$f3->map('/@collection/label/@type/@group/@id','Label');
$f3->map('/@collection/report/@name', 'Report');


$f3->run();
