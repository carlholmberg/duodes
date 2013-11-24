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
    
    
    function outreport($app, $params) {
        $class = $app->get('POST.class');
        $collection = $app->get('POST.collection');
        $subject = $app->get('POST.subject');
        $order = $app->get('POST.order');
        
        $query = ' user_id > 0 ';
        $values = array();
        
        if ($collection != 'all') {
            $query .= ' AND collection_id = :collection ';
            $values['collection'] = $collection;
        }
        
        $copies = \R::findAll('copy', $query, $values);
        if (!$copies) {
            $this->addMessage('report_none');
            $app->reroute('/report');
        }
        $chosen = array();

        foreach ($copies as $copy) {
            $pick = true;
            if ($class != 'all' && $copy->user->class != $class) $pick = false;            
            if ($subject != 'all' && $copy->title->code != $subject) $pick = false;
            if ($pick) {
                if ($order == 'title') $key = $copy->title->title.$copy->id;
                else $key = $copy->user->class.$copy->user->lastname.$copy->user->firstname.$copy->id;
                $chosen[$key] = $copy;
            }
        }
        ksort($chosen);
        
        $odd = false;
        $html = '<h1>Utlånade böcker</h1>';
        
        if ($order == 'title') {
            $title = '';
            
            foreach ($chosen as $copy) {
                if ($title != $copy->title->title) {
                    if ($title != '') $html .= '<tr><th colspan="4"></th></tr></table>';
                
                    $html .= '<table><tr><td colspan="4" class="left"><h2>'.$copy->title->title.'</h2></td></tr><tr><td colspan="4" class="left"><p><em>'.$copy->title->author.'</em></p></td></tr>';
                    $html .= '<tr><th>BG</th><th class="wide">Namn</th><th>Återlämnas</th><th>Bokens streckkod</th></tr>';

                    $title = $copy->title->title;
                    $odd = false;
                }
                $html .= '<tr'.($odd? ' class="odd"' : '').'><td>'.$copy->user->class.'</td><td class="left">'.$copy->user->firstname. ' '.$copy->user->lastname.'</td><td>'.$copy->return_date.'</td><td>'.$copy->barcode.'</td></tr>';
                $odd = !$odd;
            }
            $html .= '<tr><th colspan="4"></th></tr></table>';
        } else {
            $user = '';
            $username = '';
        
            foreach ($chosen as $copy) {
                if ($user != $copy->user_id) {
                    if ($user != '') $html .= '<tr><th colspan="3"></th></tr></table>';
                    $username = User::formatName($copy->user);
                    
                    $html .= '<table><tr><td colspan="3" class="left"><h2>'.$username.'</h2></td></tr>';
                    $html .= '<tr><th class="wide">Titel</th><th>Återlämnas</th><th>Bokens streckkod</th></tr>';

                    $user = $copy->user_id;
                    $odd = false;
                }
                $html .= '<tr'.($odd? ' class="odd"' : '').'><td class="left"><strong>'.$copy->title->title.'</strong><br /><em>'.$copy->title->author.'</em></td><td>'.$copy->return_date.'</td><td>'.$copy->barcode.'</td></tr>';
                $odd = !$odd;
            }
            $html .= '<tr><th colspan="3"></th></tr></table>';
        }
        
        $stylesheet = file_get_contents($this->app->get('UI').'reports-css.tpl');
        
        self::output($stylesheet, $html, true); 
    }


    function expired($app, $params) {
        $class = $app->get('POST.class');
        $collection = $app->get('POST.collection');
        $subject = $app->get('POST.subject');
        $order = $app->get('POST.order');
        $date = date('Y-m-d');
        
        $query = ' user_id > 0 AND return_date < :date ';
        $values = array('date' => $date);
        
        if ($collection != 'all') {
            $query .= ' AND collection_id = :collection ';
            $values['collection'] = $collection;
        }
        
        $copies = \R::findAll('copy', $query, $values);
        
        if (!$copies) {
            $this->addMessage('report_none');
            $app->reroute('/report');
        }
        $chosen = array();

        foreach ($copies as $copy) {
            $pick = true;
            if ($class != 'all' && $copy->user->class != $class) $pick = false;            
            if ($subject != 'all' && $copy->title->code != $subject) $pick = false;
            if ($pick) {
                if ($order == 'title') $key = $copy->title->title.$copy->id;
                else $key = $copy->user->class.$copy->user->lastname.$copy->user->firstname.$copy->id;
                $chosen[$key] = $copy;
            }
        }
        ksort($chosen);
        
        $odd = false;
        $html = '<h1>Utgångna lån</h1>';
        
        if ($order == 'title') {
            $title = '';
            
            foreach ($chosen as $copy) {
                if ($title != $copy->title->title) {
                    if ($title != '') $html .= '<tr><th colspan="4"></th></tr></table>';
                
                    $html .= '<table><tr><td colspan="4" class="left"><h2>'.$copy->title->title.'</h2></td></tr><tr><td colspan="4" class="left"><p><em>'.$copy->title->author.'</em></p></td></tr>';
                    $html .= '<tr><th>BG</th><th class="wide">Namn</th><th>Återlämnas</th><th>Bokens streckkod</th></tr>';

                    $title = $copy->title->title;
                    $odd = false;
                }
                $html .= '<tr'.($odd? ' class="odd"' : '').'><td>'.$copy->user->class.'</td><td class="left">'.$copy->user->firstname. ' '.$copy->user->lastname.'</td><td>'.$copy->return_date.'</td><td>'.$copy->barcode.'</td></tr>';
                $odd = !$odd;
            }
            $html .= '<tr><th colspan="4"></th></tr></table>';
        } else {
            $user = '';
            $username = '';
        
            foreach ($chosen as $copy) {
                if ($user != $copy->user_id) {
                    if ($user != '') $html .= '<tr><th colspan="3"></th></tr></table>';
                    $username = User::formatName($copy->user);
                    
                    $html .= '<table><tr><td colspan="3" class="left"><h2>'.$username.'</h2></td></tr>';
                    $html .= '<tr><th class="wide">Titel</th><th>Återlämnas</th><th>Bokens streckkod</th></tr>';

                    $user = $copy->user_id;
                    $odd = false;
                }
                $html .= '<tr'.($odd? ' class="odd"' : '').'><td class="left"><strong>'.$copy->title->title.'</strong><br /><em>'.$copy->title->author.'</em></td><td>'.$copy->return_date.'</td><td>'.$copy->barcode.'</td></tr>';
                $odd = !$odd;
            }
            $html .= '<tr><th colspan="3"></th></tr></table>';
        }
        
        $stylesheet = file_get_contents($this->app->get('UI').'reports-css.tpl');
        
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