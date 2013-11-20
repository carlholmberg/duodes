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
        
        $this->labelsheets($all);
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
        $this->labelsheets($arr);
    }
    

    function userlist($app, $params) {
        $value = $app->get('POST.userlist');
        if ($value == 'all') {
            $users = \R::findAll('user', ' ORDER BY class,lastname,firstname ');
        } else {
            $users = \R::findAll('user', ' class = ? ORDER BY class,lastname,firstname ', array($value));
        }

        $html = '';
        $curclass = '';
        
        foreach ($users as $user) {
            if ($user->class !== $curclass) {
                if ($curclass !== '') $html .= '</div>';
                $curclass = $user->class;
                $html .= '<div><h1>Basgrupp '.$curclass.'</h1>';
            }
            $barcode = $user->barcode;
            $text = User::formatName($user);
            $html .= '<p><barcode code="'.$barcode.'" type="C39" class="barcode" height="0.8" width="0.9"/><br /><strong>'.$barcode.'</strong> - '.$text.'</p><p></p>';
        }
        
        $html .= '</div>';
        
        $stylesheet = 'h1 { margin: 30px; } p { text-align: center; }';
        
        self::output($stylesheet, $html, true); 
    }

    
    function labelsheets($arr) {
        if (count($arr) % 24 !== 0) {
	        $r = count($arr) % 24;
	        $fill = array_fill(0, 24-$r, array('barcode' => '', 'text' => ''));
	        $arr = array_merge($arr, $fill);
        }

        $html = '';
                        
        foreach (array_chunk($arr, 24) as $page) {
            $table = $this->loadTpl('labels');
	        $rows = array_chunk($page, 3);
	        foreach ($rows as $row) {
	            $rowtpl = $table->get('row');

		        foreach ($row as $bc) {
			        if (strlen($bc['text']) > 40) {
				        $bc['text'] = substr($bc['text'], 0, 40).'...';
		        	}
			        if ($bc['barcode'] == '') {
			            $rowtpl->glue('label', $table->get('row.elabel'));
			        } else {
			            $rowtpl->glue('label', $table->get('row.label')->injectAll($bc));
			        }
		        }
		        $table->glue('row', $rowtpl);
	        }
	        $html .= $table;
        }

        $stylesheet = file_get_contents($this->app->get('UI').'labels-css.tpl');
        self::output($stylesheet, $html);  
    }
    
    static function output($css, $html, $margin=false) {
        if ($margin) {
            $mpdf=new \mPDF('c','A4','',''); 
        } else {
            $mpdf=new \mPDF('c','A4','','',0,0,0,0,0,0); 
        }
        
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->SetTitle('Streckkoder');
        $mpdf->SetAuthor('Duodes');

        $mpdf->WriteHTML($css, 1);

        $mpdf->WriteHTML($html, 2);

        $mpdf->Output('streckkoder.pdf','I');
    }

}