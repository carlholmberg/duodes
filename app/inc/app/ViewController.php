<?php

/**
 * Main controller class
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace app;


class ViewController extends \app\Controller
{
    public $tpl;
    public $type;
    public $page;
    public $slots;
    public $pieces = array();
    public $menu = false;
    public $footer = false;
    

    function __construct() {
        parent::__construct();
        $this->tpl = $this->loadTpl('main');
        $this->slots = array('base_href' => 'http://'.$this->app->get('HOST').$this->app->get('BASE').'/');
        
        if ($this->app->get('SESSION.msg')) {
            $this->addPiece('main', $this->app->get('SESSION.msg'), 'msg');
            $this->app->set('SESSION.msg', false);
        }
        
        if ($this->app->get('SESSION.user')) {
            $acc = $this->app->get('SESSION.user');
            $this->slots['user'] = $acc['firstname'].' '.$acc['lastname'];
        } else {
            $this->slots['user'] = '';
        }   
    }
    
    
    function buildCopyTable($header, $data) {
    
        foreach ($header as $key=>$val) {
            $what = 'hcell';
            if (isset($val['row-icon'])) {
                $what = 'hicell';
                $val['icon'] = $val['row-icon'];
            }
            $this->addPiece('page', $what, 'hcell', $val);
            $this->addPiece('page', 'fcell', 'fcell', $val);
        }
        $tpl = $this->loadTpl('tablesorter-row');
        
        foreach ($data as $item) {
            $row = $tpl->get('row');
            $id = $item['id'];
            foreach(array_keys($header) as $cell) {
                if (!isset($item[$cell])) $item[$cell] = '';
                
                if (isset($header[$cell]['tid'])) {
                    $nid = (isset($item['tid']))? $item['tid'] : $id;
                } else if (isset($header[$cell]['tid'])) {
                    $nid = (isset($item['uid']))? $item['uid'] : $id;
                } else {
                    $nid = $id;
                }
                
                if (isset($header[$cell]['row-icon'])) {
                    $row->glue('cell', $row->get('icell')->injectAll(
                        array('href' => $header[$cell]['href'],
                              'id' => $nid,
                              'type' => $cell,
                              'value' => $header[$cell]['row-icon'],
                              'name' => $item[$cell])));
                    continue;
                
                }
                
                if ($cell == 'borrowed_by' && $item[$cell] !== '') {
                    $nid = (isset($header[$cell]['uid']))? $item['uid'] : $id;
                    if (isset($header[$cell]['href']) && $item[$cell] !== '-') {
                        $row->glue('cell', $row->get('acell')->injectAll(array('href' => $header[$cell]['href'], 'id'=>$nid, 'cell'=>$item[$cell])));
                    } else {
                        $row->glue('cell', $row->get('cell')->injectAll(array('id'=>$id, 'cell'=>$item[$cell])));
                    }
                } else {
                    $type = 'scell';
                    $data = array('href' => 'copy',
                                  'field' => $cell,
                                  'id' => $id,
                                  'value' => $item[$cell],
                                  'name' => $header[$cell]['name']);
                    
                    if ($cell == 'borrowed_by') {
                        $data['source'] = 'ajax/users';
                    } else if ($cell == 'collection') {
                        $data['source'] = 'ajax/collections';
                        $data['value'] = $item['collection_id'];
                    } else if ($cell == 'bc_print') {
                        $data['source'] = '{1: "Ja", 0: "Nej"}';
                    } else {
                        $type = 'ecell';
                    }

                    $row->glue('cell', $row->get($type)->injectAll($data));
                }
            }
            $tpl->glue('row', $row);
        }
        $this->addPiece('page', $tpl, 'tbody');
    }
    
    
    function buildTable($header, $data, $return=false) {
        
        foreach ($header as $key=>$val) {
            $this->addPiece('page', 'hcell', 'hcell', $val);
            $this->addPiece('page', 'fcell', 'fcell', $val);
        }
        $tpl = $this->loadTpl('tablesorter-row');
        
        foreach ($data as $item) {
            $row = $tpl->get('row');
            $id = $item['id'];
            foreach(array_keys($header) as $cell) {
                if (!isset($item[$cell])) $item[$cell] = '';
                
                if (isset($header[$cell]['tid'])) {
                    $nid = (isset($item['tid']))? $item['tid'] : $id;
                } else if (isset($header[$cell]['tid'])) {
                    $nid = (isset($item['uid']))? $item['uid'] : $id;
                } else {
                    $nid = $id;
                }
                
                if ($cell == 'bc_print') {
                    $row->glue('cell', $row->get('scell')->injectAll(
                        array('href' => 'user',
                              'field' => $cell,
                              'id' => $nid,
                              'value' => $item[$cell],
                              'source' => '{1: "Ja", 0: "Nej"}',
                              'name' => $header[$cell]['name'])));
                    continue;
                }
                
                if (isset($header[$cell]['row-icon'])) {
                    $row->glue('cell', $row->get('icell')->injectAll(
                        array('href' => $header[$cell]['href'],
                              'id' => $nid,
                              'type' => $cell,
                              'value' => $header[$cell]['row-icon'],
                              'name' => $item[$cell])));
                    continue;
                
                }
                
                if ($item[$cell] == '' && isset($header[$cell]['href']) && !isset($header[$cell]['id'])) $item[$cell] = 'Tom';
                if ($item[$cell] == '') $item[$cell] = '-';
                
                if (isset($header[$cell]['href']) && $item[$cell] !== '-') {
                    $row->glue('cell', $row->get('acell')->injectAll(array('href' => $header[$cell]['href'], 'id'=>$nid, 'cell'=>$item[$cell])));
                } else {
                    $row->glue('cell', $row->get('cell')->injectAll(array('id'=>$id, 'cell'=>$item[$cell])));
                }
            }
            $tpl->glue('row', $row);
        }
        if ($return) {
            return $tpl;
        }
        $this->addPiece('page', $tpl, 'tbody');
    }

    function setPage($page) {
        $this->type = $page;
        if (file_exists($this->app->get('UI').$page.'.tpl')) {
            $this->page = new \StampTE(file_get_contents($this->app->get('UI').$page.'.tpl'));
        }
    }
    
    function addSlots($arr) {
        if (is_array($arr)) {
            foreach($arr as $k => $v) {
                if (is_string($v) || is_numeric($v))
                    $this->slots[$k] = $v;
            }
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
            $menudata[1] = $menudata[1]['submenu'][1];
            $menudata[] = array('title' => '{Log in}',
                            'link' => 'login',
                            'icon' => 'star',
                            'level' => 0);
        } else if ($this->lvl == 2) {
            $menudata[1] = $menudata[1]['submenu'][1];
            $menudata[] = array('link' => 'logout',
                                'title' => '{Log out}',
                                'icon'=> 'lock',
                                'level' => 0);
        } else {
            $menudata[] = array('title' => $this->slots['user'],
                            'icon' => 'star',
                            'level' => 0,
                            'submenu' => array(
                                /*array('link' => '#',
                                      'title' => '{Group}',
                                      'icon' => 'th-list',
                                      'level' => 2), */
                                array('link' => 'user/'.$this->uid,
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
            echo preg_replace_callback('|\{.+?\}|', '\app\__', $this->tpl);
        }
    }
}
