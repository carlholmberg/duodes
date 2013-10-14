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

// Include Stamp template system
require_once('../lib/StampTE.php');

function __($str, $values=false) {
    
    $app = \Base::instance();
    if (!$app->get('LANG_STRS'.$app->get('LANG'))) {
        $app->set('LANG_STRS'.$app->get('LANG'), include($app->get('LOC').$app->get('LANG').'.php'));
    }
    $lang = $app->get('LANG_STRS'.$app->get('LANG'));
    
    if (is_array($str)) {
        $key = substr($str[0], 1, -1);
    } else {
        $key = $str;
    }

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
    public $tpl;
    
    function __construct() {
        $app = \Base::instance();
        $this->tpl = new \StampTE(file_get_contents($app->get('UI').'main.tpl'));
    }
    
    function __destruct() {
        echo preg_replace_callback('|\{.+?\}|', '\controllers\__', $this->tpl);
    }
}
