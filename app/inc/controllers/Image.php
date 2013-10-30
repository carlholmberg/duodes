<?php

/**
 * Image controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Image extends \controllers\Controller {
    function get($app, $params) {
        $file = $params['id'];
        foreach(array('.png', '.jpg', '.jpeg') as $format) {
            if (file_exists('data/img/'.$file.$format)) {
                $img = new \Image($file.$format, false, 'data/img/');
                $img->render();
                return;
            }
        }
        $img = new \Image('missing.png', false, 'data/img/');
        $img->render();
    }
    
    function post() {}
    function put() {}
    function delete() {}
}