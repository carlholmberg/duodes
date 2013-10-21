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
        acquired [date]
        state []
        barcode [str]
    */
}