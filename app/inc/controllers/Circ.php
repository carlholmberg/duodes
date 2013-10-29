<?php

/**
 * Circ controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Circ extends \controllers\ViewController {


    function get($app, $params) {
        $this->menu = true;
        $this->footer = true;
        $this->slots['pagetitle'] = '{Circ}';
        $this->setPage('circ');
    }
    
    
    function post($app, $params) {
        if ($app->get('POST.barcode')) {
            $cid = strtoupper(trim($app->get('POST.barcode')));
            $copy = new \models\Copy('barcode', $cid);
            if ($copy->exists) {
                if ($copy->data->user) {
                    $data = array('user' => $copy->data->user->id, 'date' => date('Y-m-d H:i'));
                    new \controllers\Log('return', 'Returned copy "'. $copy->data->barcode.'"', serialize($data));
                    $copy->returnCopy();
                } else {
                    $data = array('date' => date('Y-m-d H:i'));
                    new \controllers\Log('return', 'Tried to return copy "'. $copy->data->barcode.'"', serialize($data));
                }
            }
        } else {
            $b1 = strtoupper(trim($app->get('POST.bc1')));
            $b2 = strtoupper(trim($app->get('POST.bc2')));
            
            // b1: copy, b2: user
            $copy = new \models\Copy('barcode', $b1);
            if ($copy->exists) {
                $user = new \models\User('barcode', $b2);
                if ($copy->exists) {
                    $user->borrow($copy);
                }
            }
            
            // b2: copy, b1: user
            $copy = new \models\Copy('barcode', $b2);
            if ($copy->exists) {
                $user = new \models\User('barcode', $b1);
                if ($copy->exists) {
                    $user->borrow($copy);
                }
            }
        }
    
    
    }
    
    function put() {}
}
