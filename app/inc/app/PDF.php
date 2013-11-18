<?php

/**
 * PDF controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */

namespace app;

require_once('app/lib/mpdf/mpdf.php');

class PDF extends Controller {
    
    function barcode($app, $params) {
        $ids = array();
        foreach($_POST as $key => $value) {
            if (is_numeric($key) && $value == 'on') $ids[] = $key;
        }
        $ids = implode(',', $ids);
        $all = \R::findAndExport('barcode', ' type = "copy" AND b_id IN ('.$ids.') ORDER BY b_id ');
        if ($app->get('POST.user')) {
            $users = \R::findAndExport('barcode', ' type = "user" ');
            $all = array_merge($users, $all);
        }
        if (!$all) $app->reroute('/barcode');
        
        if (count($all) % 24 !== 0) {
	        $r = count($all) % 24;
	        $fill = array_fill(0, 24-$r, array('barcode' => '', 'text' => ''));
	        $all = array_merge($all, $fill);
        }
        
        $pages = array_chunk($all, 24);

        $html = '<body>';

        foreach ($pages as $page) {
	        $html .= '
<table cellpadding="0" cellspacing="0">
';
	        $rows = array_chunk($page, 3);
	        foreach ($rows as $row) {
		        $html .= '<tr>';
		        foreach ($row as $bc) {
			        if (strlen($bc['text']) > 40) {
				        $bc['text'] = substr($bc['text'], 0, 40).'...';
		        	}
			        if ($bc['barcode'] == '') {
				        $html .= '<td class="label">&nbsp;</td>';
			        } else {
				        $html .= '<td class="label"><barcode code="'.$bc['barcode'].'" type="C39" class="barcode" height="0.8" width="0.9"/><p><strong>'.$bc['barcode'].'</strong><br />'.$bc['text'].'</p></td>';
			        }
		        }
		        $html .= '</tr>';
	        }
	        $html .= '</table>';
        }
        $html .= '</body>';
        
        $stylesheet = '
table { width: 100%; border: 0; }
td { text-align: center; margin: 0; width: 70mm; height: 37.125 mm; padding: 10mm; font-size: 8pt; }
strong { font-size: 9pt; }
';
    self::output($stylesheet, $html);
}

    function userlabels($app, $params) {
        $value = $app->get('POST.userlables');
        if ($value == 'all') {
            $users = \R::findAll('user', ' ORDER BY class ');
        } else {
            $users = \R::findAll('user', ' class = ? ORDER BY class ', array($value));
        }
        $arr = array();
        foreach ($users as $user) {
		    $arr[] = array('barcode' => $user->barcode, 'text' => User::formatName($user));
		}
        if (count($arr) % 24 !== 0) {
	        $r = count($arr) % 24;
	        $fill = array_fill(0, 24-$r, array('barcode' => '', 'text' => ''));
	        $arr = array_merge($arr, $fill);
        }

        $pages = array_chunk($arr, 24);

        $html = '<body>';

        foreach ($pages as $page) {
	        $html .= '<table cellpadding="0" cellspacing="0">';
	        $rows = array_chunk($page, 3);
	        foreach ($rows as $row) {
		        $html .= '<tr>';
		        foreach ($row as $bc) {
			        if ($bc['barcode'] != '') {
				        $html .= '<td><barcode code="'.$bc['barcode'].'" type="C39" class="barcode" /><br /><strong>'.$bc['barcode'].'</strong> - '.$bc['text'].'</td>';
			        } else {
				        $html .= '<td>&nbsp;</td>';
			        }
		        }
		        $html .= '</tr>';
	        }
	        $html .= '</table>';
        }
        $html .= '</body>';

        $stylesheet = '
table { width: 100%; border: 0; }
td { width: 70mm; height: 37.125 mm; padding: 10mm; font-size: 8pt; text-align: center; overflow:hidden;}
strong { font-size: 9pt; }';
        self::output($stylesheet, $html);  
    }
    
    function userlist($app, $params) {
        $value = $app->get('POST.userlist');
        if ($value == 'all') {
            $users = \R::findAll('user', ' ORDER BY class ');
        } else {
            $users = \R::findAll('user', ' class = ? ORDER BY class ', array($value));
        }
        $arr = array();
        foreach ($users as $user) {
		    $arr[] = array('barcode' => $user->barcode, 'text' => User::formatName($user));
		}
        if (count($arr) % 24 !== 0) {
	        $r = count($arr) % 24;
	        $fill = array_fill(0, 24-$r, array('barcode' => '', 'text' => ''));
	        $arr = array_merge($arr, $fill);
        }

        $pages = array_chunk($arr, 24);

        $html = '<body>';

        foreach ($pages as $page) {
	        $html .= '<table cellpadding="0" cellspacing="0">';
	        $rows = array_chunk($page, 3);
	        foreach ($rows as $row) {
		        $html .= '<tr>';
		        foreach ($row as $bc) {
			        if ($bc['barcode'] != '') {
				        $html .= '<td><barcode code="'.$bc['barcode'].'" type="C39" class="barcode" /><br /><strong>'.$bc['barcode'].'</strong> - '.$bc['text'].'</td>';
			        } else {
				        $html .= '<td>&nbsp;</td>';
			        }
		        }
		        $html .= '</tr>';
	        }
	        $html .= '</table>';
        }
        $html .= '</body>';

        $stylesheet = '
table { width: 100%; border: 0; }
td { width: 70mm; height: 37.125 mm; padding: 10mm; font-size: 8pt; text-align: center; overflow:hidden;}
strong { font-size: 9pt; }';
        self::output($stylesheet, $html);;
    
    }


    static function output($css, $html) {
        $mpdf=new \mPDF('c','A4','','',0,0,0,0,0,0); 

        $mpdf->SetDisplayMode('fullpage');

        $mpdf->SetTitle('Streckkoder');
        $mpdf->SetAuthor('Duodes');

        $mpdf->WriteHTML($css, 1);

        $mpdf->WriteHTML($html, 2);

        $mpdf->Output('streckkoder.pdf','I');
    }

}