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
require_once('app/lib/StampTE.php');

class ViewController extends \controllers\Controller
{
    public $tpl;
    public $page;
    public $slots;
    public $pieces = array();
    public $menu = false;
    public $footer = false;
    

    function __construct() {
        parent::__construct();
        $this->tpl = new \StampTE(file_get_contents($this->app->get('UI').'main.tpl'));
        $this->slots = array('base_href' => 'http://'.$this->app->get('HOST').$this->app->get('BASE').'/');
        if ($this->app->get('SESSION.account')) {
            $acc = $this->app->get('SESSION.account');
            $this->slots['user'] = $acc['name'];
        } else {
            $this->slots['user'] = '';
        }
        
    }
    

    function setPage($page) {
        if (file_exists($this->app->get('UI').$page.'.tpl')) {
            $this->page = new \StampTE(file_get_contents($this->app->get('UI').$page.'.tpl'));
        }
    }
    
    function addPiece($tpl, $what, $where, $slots=array()) {
        if (!isset($this->pieces[$tpl])) $this->pieces[$tpl] = array();
        $this->pieces[$tpl][] = array($what, $where, $slots);
    }
    
    function addPieces() {
        foreach ($this->pieces as $tpl=>$pieces) {
            foreach ($pieces as $piece) {
                list($what, $where, $slots) = $piece;
                if (is_string($what)) {
                    if ($tpl == 'main')
                        $this->tpl->glue($where, $this->tpl->get($what)->injectAll($slots));
                    else if ($tpl == 'page')
                        $this->page->glue($where, $this->page->get($what)->injectAll($slots));
                } else {
                    if ($tpl == 'main')
                        $this->tpl->glue($where, $what->injectAll($slots));
                    else if ($tpl == 'page') 
                        $this->page->glue($where, $what->injectAll($slots));       
                }
            }
        }
    }
    
    function buildMenu() {
        $themenu = $this->tpl->get('menu');
        $menudata = $this->app->get('menu');
        
        if ($this->lvl == 0) {
            $menudata[] = array('title' => '{Log in}',
                            'link' => 'login',
                            'icon' => 'star',
                            'level' => 0);
        } else {
            $menudata[] = array('title' => $this->slots['user'],
                            'icon' => 'star',
                            'level' => 0,
                            'submenu' => array(
                                array('link' => '#',
                                      'title' => '{Group}',
                                      'icon' => 'th-list',
                                      'level' => 2),
                                array('link' => '#',
                                      'title' => '{Profile}',
                                      'icon'=> 'cog',
                                      'level' => 0),
                                array('link' => 'logout',
                                      'title' => '{Log out}',
                                      'icon'=> 'lock',
                                      'level' => 0)
                                 )
                            );
        }
        
        foreach($menudata as $menus) {
            if ($menus['level'] > $this->lvl) continue;
            if (isset($menus['submenu'])) {
                $menu = $this->tpl->get('menu.dropdown')
                    ->inject('title', $menus['title'])
                    ->inject('icon', $menus['icon']);
                    
                foreach($menus['submenu'] as $menuitem) {
                    if ($menuitem['level'] > $this->lvl) continue;
                    $menuitem = array_merge(array('active' => ''), $menuitem);
                    $menu->glue('submenu', $this->tpl
                                            ->get('menu.menuitem')
                                            ->injectAll($menuitem));
                }
                $themenu->glue('menuitem', $menu);
            } else {
                $menus = array_merge(array('active' => ''), $menus);
                $themenu->glue('menuitem', $this->tpl
                                                ->get('menu.menuitem')
                                                ->injectAll($menus));
            }
        }
         
        $this->tpl->glue('menu', $themenu);
    }
    
    function __destruct() {
        if ($this->tpl) {
            $this->addPieces();
            if ($this->page) {
                $this->tpl->glue('page', $this->page);
            }
            if ($this->menu) {
                $this->buildMenu();
            }
            if ($this->footer) {
                $this->tpl->glue('footer', $this->tpl->get('footer'));
            }

            $this->tpl->injectAll($this->slots);
            echo preg_replace_callback('|\{.+?\}|', '\controllers\__', $this->tpl);
        }
    }
}
