<?php

/**
 * Log controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Log extends \controllers\Controller {
    function __construct($action, $message="", $data="") {
        parent::__construct();
        
        $account = $this->app->get('SESSION.account');
        
        $info = array(
            'ts' => time(),
            'uri' => $this->app->get('URI'),
            'action' => $action,
            'user' => $account['email'],
            'msg' => $message,
            'data' => $data);
        $log = new \models\Log($info);
    }
}