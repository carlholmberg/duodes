<?php

date_default_timezone_set('Stockholm/Europe');

include('app/lib/rb.php');
R::setup('sqlite:data/db.db');

$step = isset($_GET['do'])? $_GET['do'] : 'first';
    
switch($step) {

    case 'coll':
        $val = array(
         array('name' => 'år', 'ts' => date('Y-m-d', strtotime("10 June 2014"))),
         array('name' => 'ht', 'ts' => date('Y-m-d', strtotime("10 January 2014")))
        );
        
        $colls = array(
            'Kurslitteratur' => array('type' => 'fixed', 'value' => serialize($val)),
            'Bibliotek' => array('type' => 'days', 'value' => 14),
            'WS-litteratur' => array('type' => 'ref', 'value' => 0)
        );
        foreach ($colls as $name => $args) {
            $coll = R::dispense('collection');
            $coll->name = $name;
            $coll->type = $args['type'];
            $coll->value = $args['value'];
            R::store($coll);
        }
        echo 'Collections complete';
        echo '<a href="fix.php?do=user">Step 2</a>';
        break;

    case 'user':

        $users = R::findAll('user');
        foreach($users as $user) {
            if (!$user->lastname) {
                list($lastname, $firstname) = explode(',', $user->name);
                $user->lastname = $lastname;
                $user->firstname = $firstname;
                $user->level = 1; // 0: guest, 1: student, 2: circ, 3: teacher, 4: admin
                $user->class = $user->usermeta->bg;
                $user->uid = $user->u_id;
                $user->status = 0;
                $user->email = '';
                R::store($user);
            }
        }
        echo 'Users complete';
        echo '<a href="fix.php?do=copies">Step 3</a>';
        break;
        
    case 'copies':
        $coll = array('course' => 'Kurslitteratur', 'library' => 'Bibliotek');

        $kurs = R::findOne('collection', ' name = ? ', array('Kurslitteratur'));
        $bibl = R::findOne('collection', ' name = ? ', array('Bibliotek'));
        R::begin();
        foreach($coll as $c=>$n) {
            $titles = R::findAll('title', ' type = :val', array(':val' => $c));
                foreach ($titles as $title) {
                if ($title->type) {
                    foreach($title->ownCopy as $copy) {
                        if ($c == 'course') {
                            $copy->collection = $kurs;
                        } else if ($c == 'library') {
                            $copy->collection = $bibl;
                        }
                        R::store($copy);
                    }
                }
            }
        }
        R::commit();
        echo 'Copies complete';
        echo '<a href="fix.php?do=titles">Step 4</a>';
        break;
    
    case 'titles':
        $ids = R::getCol('SELECT id FROM title');
        foreach ($ids as $id) {
            $title = R::load('title', $id);
            $meta = R::load('titlemeta', $title->titlemeta_id);
            if ($title->sab == "#sab#") $title->code = '';
            else $title->code = $title->sab;
            $title->url = $meta->url;
            $title->desc = $meta->desc;
            $title->keywords = $meta->keywords;
            $title->registered = '';
            R::store($title);    
        }
        echo 'Titles complete';
        break;
        
    case 'borrowed':
        $ids = R::getCol('SELECT id FROM title');
        foreach ($ids as $id) {
            $title = R::load('title', $id);
            $borrowed = 0;
	        foreach($title->ownCopy as $copy) {
	            if ($copy->user) $borrowed += 1;
	        }
	        $title->borrowed = $borrowed;
	        R::store($title);
        }
        echo 'Borrowed complete';
        break;


    default:
        echo '<a href="fix.php?do=coll">Step 1</a>';
        break;

}
