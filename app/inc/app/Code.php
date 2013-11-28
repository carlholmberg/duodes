<?php

/**
 * Code controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
/* Model: Code
    id [auto]
    code [str]
    text [str]
*/
    
namespace app;

class Code extends ViewController {
    
    static function getCodes() {
        return \R::getCol('SELECT code FROM code ORDER BY code');
    }
}