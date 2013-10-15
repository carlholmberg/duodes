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
    $lkey = 'LANG_STRS'.$app->get('LANG');
    if (!$app->get($lkey)) {
        $app->set($lkey, include($app->get('LOC').$app->get('LANG').'.php'));
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
    public $ajax;
    public $tpl;
    public $page;
    public $slots;
    
    function __construct() {
        $this->app = \Base::instance();
        $this->ajax = $this->app->get('AJAX');

        if ($this->ajax) {
            $this->slots = array();
        } else {
            $this->tpl = new \StampTE(file_get_contents($this->app->get('UI').'main.tpl'));
            $this->slots = array('base_href' => 'http://localhost/duodes/public/');
        }
        
    }
    
    function setPage($page) {
        if (file_exists($this->app->get('UI').$page.'.tpl')) {
            $this->page = new \StampTE(file_get_contents($this->app->get('UI').$page.'.tpl'));
        }
    }
    
    function reqLevel($lvl=0) {
        if ($this->app->get('SESSION.account')) {
            $account = $this->app->get('SESSION.account');
            if ($account['level'] < $lvl) {
                $this->tpl = new \StampTE(file_get_contents($this->app->get('UI').'login.tpl'));
                $this->ajax = true;
            }
        } else {
            $this->tpl = new \StampTE(file_get_contents($this->app->get('UI').'login.tpl'));
            $this->ajax = true;
        }
    }
    
    function buildMenu() {
        foreach($this->app->get('menu') as $menus) {
            if (isset($menus['submenu'])) {
                $menu = $this->tpl->get('dropdown')
                    ->inject('title', $menus['title'])
                    ->inject('icon', $menus['icon']);
                    
                foreach($menus['submenu'] as $menuitem) {
                    $menu->glue('submenu', $this->tpl
                                            ->get('menuitem')
                                            ->injectAll($menuitem));
                }
                $this->tpl->glue('menuitem', $menu);
            } else {
                $this->tpl->glue('menuitem', $this->tpl
                                                ->get('menuitem')
                                                ->injectAll($menus));
            }
        }
    }
    
    function __destruct() {
        if (!$this->ajax) {
            if ($this->page) {
                $this->tpl->glue('page', $this->page);
            }
            $this->buildMenu();
        }
        if ($this->tpl) {
            $this->tpl->injectAll($this->slots);
 //           echo $this->tpl;
            echo preg_replace_callback('|\{.+?\}|', '\controllers\__', $this->tpl);
        }
    }
}
