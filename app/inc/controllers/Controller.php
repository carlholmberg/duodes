<?php

/**
 * Main controller class
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

function __($str, $values=false) {
    
    $app = \Base::instance();
    $lkey = 'LANG_STRS'.$app->get('LANG');
    if (!$app->get($lkey)) {
        $app->set($lkey, include($app->get('ROOT').'/'.$app->get('BASE').'/'.$app->get('LOC').$app->get('LANG').'.php'));
    }
    $lang = $app->get($lkey);
    
    $key = is_array($str)? substr($str[0], 1, -1) : $str;

	if (array_key_exists($key, $lang)) {
		$str = $lang[$key];
		if ($values && is_array($values)) {
		    return vsprintf($str, $values);
	    }
	    return $str;
	} else {
	    trigger_error("{$key} not in ".$app->get('LANG'), E_USER_NOTICE);
    }
    
    if (is_array($str)) {
        return $str[0];
    }
    return $str;
}

class Controller
{

    public $lvl = 0;
    public $json = false;
    public $data;
    
    function __construct() {
        $this->app = \Base::instance();
        if ($this->app->get('SESSION.account')) {
            $account = $this->app->get('SESSION.account');
            $this->lvl = $account['level'];
        }
    }
    
    
    function reqLevel($lvl=0) {
        if ($this->lvl < $lvl) {
            if ($this->lvl == 0) {
                $this->app->reroute('/login');
            } else {
                $this->app->reroute('/noaccess');
            }
        }
    }
    
    
    function __destruct() {
        if ($this->json !== false) {
            // skicka ut jsondata
            echo json_encode($this->data);
            //echo preg_replace_callback('|\{.+?\}|', '\controllers\__', $this->tpl);

        }
    }
}
