<?php

/**
 * Copy model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Copy extends \models\DBModel {
    /* Model: Title
        id [auto]
        title [Model.Title]
        borrowed_by [Model.User]
        return_date [date]
        collection [Model.Collection]
        barcode [str]
    */
    
    function __construct() {
    
    }
    
    static function getHeader($lvl) {
        $header = array(
            'collection' => array(
                'class' => 'group-word filter-false', 'placeholder' => '', 'name' => ''),
            'barcode' => array(
                'class' => '', 'placeholder' => '', 'name' => '{Barcode}'),
            'borrowed_by' => array(
                'class' => '', 'placeholder' => '', 'name' => '{Borrowed}'),
            'return_date' => array(
                'class' => '', 'placeholder' => '', 'name' => '{Return date}'),
            'bc_print' => array(
                'class' => '', 'placeholder' => '', 'name' => '{Print barcode}'));
       
        if ($lvl < 2) {
            unset($header['borrowed_by']);
            unset($header['bc_print']);
        }
        return $header;
    }
}