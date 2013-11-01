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
        $this->setPage('circ');
        $this->slots['pagetitle'] = '{Circ}';
        
        if ($app->get('SESSION.circ')) {
            $this->slots['active'] = $app->get('SESSION.circ');
            $app->set('SESSION.circ', false);
        } else {
            $this->slots['active'] = 'return';
        }
    }
    
    
    function borrow($user, $copy) {
        $data = array('user' => $user->data->email, 'copy' => $copy->data->id, date('Y-m-d H:i'));
        if ($copy->data->user) {
            new \controllers\Log('borrow', $user->data->email.' tried to borrowed copy "'. $copy->data->barcode.'"', serialize($data));
            $this->app->set('SESSION.msg', 'Boken är redan utlånad');
            $this->app->set('SESSION.circ', 'borrow');
            $this->app->reroute('/circ');
        }
        
        new \controllers\Log('borrow', $user->data->email.' borrowed copy "'. $copy->data->barcode.'"', serialize($data));
        $user->borrow($copy);
        $name = $user->data->firstname.' '.$user->data->lastname;
        $this->app->set('SESSION.msg', $name.' lånade '.$copy->data->title->title);
        $app->set('SESSION.circ', 'borrow');
        $this->app->reroute('/circ');
    }
    
    function post($app, $params) {
        if ($app->get('POST.barcode')) {
            $cid = strtoupper(trim($app->get('POST.barcode')));
            $copy = new \models\Copy('barcode', $cid);
            if ($copy->exists) {
                if ($copy->data->user) {
                    $data = array('user' => $copy->data->user->email, 'date' => date('Y-m-d H:i'));
                    new \controllers\Log('return', 'Returned copy "'. $copy->data->barcode.'"', serialize($data));
                    $user = $copy->data->user;
                    $copy->returnCopy();
                    $name = $user->firstname.' '.$user->lastname;
                    $this->app->set('SESSION.msg', $name.' återlämnade '.$copy->data->title->title);
                    $app->set('SESSION.circ', 'return');
                    $this->app->reroute('/circ');
                } else {
                    $data = array('date' => date('Y-m-d H:i'));
                    new \controllers\Log('return', 'Tried to return copy "'. $copy->data->barcode.'"', serialize($data));
                    $this->app->set('SESSION.msg', 'Försökte återlämna '.$copy->data->title->title.' som inte är utlånad');
                    $app->set('SESSION.circ', 'return');
                    $this->app->reroute('/circ');
                }
            }
            
            $this->app->set('SESSION.msg', 'Felaktig streckkod');
            $app->set('SESSION.circ', 'return');
            $this->app->reroute('/circ');
        } else {
            $b1 = strtoupper(trim($app->get('POST.bc1')));
            $b2 = strtoupper(trim($app->get('POST.bc2')));
            
            // b1: copy, b2: user
            $copy = new \models\Copy('barcode', $b1);
            if ($copy->exists) {
                $user = new \models\User('barcode', $b2);
                if ($user->exists) {    
                    $this->borrow($user, $copy);
                }
            }
            
            // b2: copy, b1: user
            $copy = new \models\Copy('barcode', $b2);
            if ($copy->exists) {
                $user = new \models\User('barcode', $b1);
                if ($user->exists) {
                     $this->borrow($user, $copy);
                }
            }
            
            $this->app->set('SESSION.msg', 'Felaktiga streckkoder');
            $app->set('SESSION.circ', 'borrow');
            $this->app->reroute('/circ');
        }
    
    }
    
    function put() {}
}
