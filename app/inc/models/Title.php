<?php

/**
 * Title model
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace models;

class Title extends \models\DBModel {
    /* Model: Title
        id [auto]
        title [str]
        authors [Model.Author][]
        isbn [str]
        lang [str]
        publisher [str]
        date [int] 
        code [str]
        url [str]
        desc [str]
        keywords [str]
        copies [Model.Copy][]
        total [int]
        borrowed [int]
    */
    function __construct($id=false) {
        $this->load('title', $id);
    }


    
    static function getHeader($lvl) {
        $header = array(
            'title'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Title}', 'href'=>'title'),
            'author'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Author}'),
            'isbn'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'ISBN'),
            'date'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Year}'),
            'code'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Subject}'),    
            'total'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Total}'),
            'borrowed'=>array(
                'class'=>'', 'placeholder'=>'', 'name'=>'{Borrowed}'));
        if ($lvl < 2) {
            unset($header['borrowed']);
        }
        return $header;
    }
    
}