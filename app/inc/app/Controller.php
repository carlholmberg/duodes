<?php

/**
 * Main controller class
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;

// Include RedbeanPHP
require_once('app/lib/rb.php');
\R::setup('sqlite:data/db.db');

// Include Stamp template system
require_once('app/lib/StampTE.php');

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
	} /*else {
	    trigger_error("{$key} not in ".$app->get('LANG'), E_USER_NOTICE);
    }*/
    
    if (is_array($str)) {
        return $str[0];
    }
    return $str;
}

class Controller
{

    public $lvl = 0; // 0: guest, 1: normal user, 2: lending, 3: teacher, 4: admin
    public $uid = false;
    public $json = false;
    public $data;
    
    function __construct() {
        $this->app = \Base::instance();
        if ($this->app->get('SESSION.user')) {
            $user = $this->app->get('SESSION.user');
            $this->lvl = $user['level'];
            $this->uid = $user['id'];
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
    
    function loadTpl($tpl) {
        $file = $this->app->get('UI').$tpl.'.tpl';
        if (file_exists($file)) {
            return new \StampTE(file_get_contents($file));
        }
        return false;
    }
    
    function addMessage($tpl, $data=array()) {
        $msgs = $this->loadTpl('messages');
        $tpl = $msgs->get($tpl);
        $tpl->injectAll($data);
        $this->app->set('SESSION.msg', $tpl);
    }
    
    
    function hasLevel($lvl=0) {
        return ($this->lvl >= $lvl);    
    }
    
    
    function __destruct() {
        if ($this->json !== false) {
            // skicka ut jsondata
            echo json_encode($this->data);
            //echo preg_replace_callback('|\{.+?\}|', '\app\__', $this->tpl);

        }
    }
}
