<?php

/**
 * Image controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

class Image extends \app\Controller {
    function get($app, $params) {
        if (isset($params['id'])) {
            $file = $params['id'];
            if (file_exists('data/img/'.$file)) {
                $size = getimagesize('data/img/'.$file);
                if ($size[0] > 1) {
                    $img = new \Image($file, false, 'data/img/');
                    $img->render();
                    return;
                }
            } else {
            
                $url = sprintf('http://covers.librarything.com/devkey/%s
/large/isbn/%s', $app->get('LIBDEVKEY'), $file);
                $img = 'data/img/'.$file;
                file_put_contents($img, file_get_contents($url));
    
                $size = getimagesize('data/img/'.$file);
                if ($size[0] > 1) {
                    $img = new \Image($file, false, 'data/img/');
                    $img->render();
                    return;
                }
            }
        }
        $img = new \Image('missing.png', false, 'data/img/');
        $img->render();
    }
    
    function post() {}
    function put() {}
    function delete() {}
}