<?php

include('app/lib/rb.php');
R::setup('sqlite:data/db.db');

$what = $_GET['do'];

if ($what == 'user') {

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
            R::store($user);
        }
    }
    echo 'complete';

} else if ($what == 'copies') {
    $coll = array('course' => 'Kurslitteratur', 'library' => 'Bibliotek');

    foreach($coll as $c=>$n) {
        $titles = R::findAll('title', ' type = :val', array(':val' => $c));
            foreach ($titles as $title) {
            if ($title->type) {
                foreach($title->ownCopy as $copy) {
                    $copy->collection = $n;
                    R::store($copy);
                }
            }
        }
    }
    echo 'complete';
} else if ($what == 'titles') {
    $rows = \R::getAll('SELECT id FROM title');
	$ids = array_map(function($a) { return $a['id']; }, $rows);
    foreach ($ids as $id) {
        $title = R::load('title', $id);
        $meta = R::load('titlemeta', $title->titlemeta_id);
        $title->code = $title->sab;
        $title->url = $meta->url;
        $title->desc = $meta->desc;
        $title->keywords = $meta->keywords;
        R::store($title);    
    }
    echo 'complete';
}
