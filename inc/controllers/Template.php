<?php

// Include Stamp template system
require(INV_PATH.'lib/StampTE.php');

class Tpl {

    public function __construct($view) {
        global $levels;
        global $settings;
		
		$this->pieces = new StampTE(file_get_contents(INV_PATH.'tpl/parts/pieces.tpl'));
		$this->modals = new StampTE(file_get_contents(INV_PATH.'tpl/parts/modals.tpl'));
		
		$this->tpl = new StampTE(file_get_contents(INV_PATH.'tpl/main.tpl'));
		
		if (file_exists(INV_PATH.'tpl/'.$view.'.tpl')) {
		    $this->view = new StampTE(file_get_contents(INV_PATH.'tpl/'.$view.'.tpl'));
		} else {
		    $this->view = new StampTE(file_get_contents(INV_PATH.'tpl/index.tpl'));
		}
		
		if (file_exists(INV_PATH.'tpl/custom/'.$view.'.tpl')) {
		    $this->custom = new StampTE(file_get_contents(INV_PATH.'tpl/custom/'.$view.'.tpl'));
		}
		
		if (file_exists(INV_PATH.'tpl/custom/modals.tpl')) {
		    $this->custom_modals = new StampTE(file_get_contents(INV_PATH.'tpl/custom/modals.tpl'));
		}

		if (!isset($_SESSION['level'])) { $_SESSION['level'] = INV_ACCOUNT_GUEST; }
		
		foreach ($settings['menu'] as $name => $menuitem) {
		    $this->addMenu($name, $menuitem);
		}
		$this->tpl = Plugins::filter('render_menu', $this->tpl);
		
		if (isset($_SESSION['user'])) {
		    $this->tpl->glue('userstat', $this->pieces->get('logout'));
		    $this->tpl->glue('modals', $this->modals->get('logout'));
		    $this->tpl->inject('loginout', 'logout');
		} else {
		    $this->tpl->glue('userstat', $this->pieces->get('login'));
		    $this->tpl->glue('modals', $this->modals->get('login'));
		    $this->tpl->inject('loginout', 'login');
		}
		
	}
	
	public function addMenu($name, $menuitem) {
	    global $levels;
	    if (!in_array($name, $levels[$_SESSION['level']]))
	        return;
	    
	    if (isset($menuitem['submenu'])) {
	        $submenu = $menuitem['submenu'];
	        unset($menuitem['submenu']);
	        $menu = $this->pieces->get('dropdown')->injectAll($menuitem);
	        foreach($submenu as $sname => $smenuitem) {
	            $this->addMenuitem($menu, 'submenu', $smenuitem);
	        }
	        $this->tpl->glue('menus', $menu);
	    } else {
	        $this->addMenuitem($this->tpl, 'menus', $menuitem);
	    }
	}
	
	private function addMenuitem(&$where, $key, $item) {
	    if (strpos($item['link'], ':')) {
	        list($type, $action) = explode(':#', $item['link']);
	        $item['action'] = '#'.$action;
	        $item['type'] = $type;
		    if ($type == 'modal') {
		        $modal = $this->modals->get($action);
		        $modal->glue('content', Plugins::action($action));
		        $this->tpl->glue('modals', $modal);
		    }
		    $where->glue($key, $this->pieces->get('menuitemType')->injectAll($item));
	    } else {
    	    $where->glue($key, $this->pieces->get('menuitem')->injectAll($item));
    	}
	}
    
    public function setTitle($title) {
        $this->title = __($title);
    }
    
    public function getModal($id) {
        if (in_array($id, $this->custom_modals->getCatalogue())) {
            return $this->custom_modals->get($id);
        } else {
            return $this->modals->get($id);
        }
    }
    
    public function get($id) {
        if (isset($this->custom) && in_array($id, $this->custom->getCatalogue())) {
            return $this->custom->get($id);
        } else {
            return $this->view->get($id);
        }
    }
    
    public function addMessage($text, $status='') {
        $msg = $this->pieces->get('message')->copy();
        $this->tpl->glue('message', $msg->inject('msg', $text, true)->inject('status', $status));
    }

	public function out($main=NULL) {
        global $settings;
        
        if ($main !== NULL) {
            $this->tpl->glue('main', $main);
	    } else {
	        $this->tpl->glue('main', $this->view);
	    }
	    
	    $this->tpl->injectAll(array(
		    'pagetitle' => isset($this->title)? $this->title : __('Library'),
		    'base_href' => 'http://'.INV_URL,
		    'base_name' => $settings['name'],
		    'username' => INV_ACTIVE_USER));
		$this->tpl->glue('custom_footer', Plugins::action('footer', true), true);
		
	    $s = new StampTE(preg_replace_callback('|\{.+?\}|', '__', $this->tpl));
        echo $s;
	}

}