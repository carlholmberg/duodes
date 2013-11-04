<?php

/**
 * Query controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2013
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Query extends \controllers\Controller {
    static function Libris($isbn) {
		try {
		    $query = "http://libris.kb.se/xsearch?query=isbn:(".$isbn.")&format_level=full&format=json";
			$res = json_decode(file_get_contents($query), true);
			$res = $res['xsearch']['list'];
			if (!is_array($res)) return false;
            return self::cleanListing($res[0], $isbn);
		} catch (Exception $e) {
			return false;
		}
	}
	
    
    static function OpenLib($isbn) {
        try {
            $query = "https://openlibrary.org/api/books?bibkeys=ISBN:".$isbn."&jscmd=data";
			$res = json_decode(file_get_contents($query), true);
			die(var_dump($res));
			if (!is_array($res)) return false;
			$result = array();
			foreach ($res as $listing) {
				$result[] = self::cleanListing($listing, $isbn);
			}
			return $result;
		} catch (Exception $e) {
			return false;
		}
    
    }


	private static function cleanText($array, $text) {
		if (!isset($array[$text])) return '';
		$text = $array[$text];
		if (is_array($text)) { $text = implode("\n", $text); }
		return trim($text);
	}
	
	private static function cleanListing($listing, $isbn) {
		$new = array();
		$new['title'] = self::cleanText($listing, 'title');
		$new['author'] = self::cleanText($listing, 'creator');
		$new['isbn'] = $isbn;
		$new['date'] = self::cleanText($listing, 'date');
		$new['publisher'] = self::cleanText($listing, 'publisher');
		$new['url'] = self::cleanText($listing, 'identifier');
		$new['desc'] = self::cleanText($listing, 'description');
		$new['lang'] = self::cleanText($listing, 'lang');
		if (isset($listing['classification'])) {
		    $code = explode(' ', self::cleanText($listing['classification'], 'sab'));
			$new['code'] = $code[0];
		} else {
			$new['code'] = '';
		}
		$new['keywords'] = self::cleanText($listing, 'subject');

		return $new;
	}
}
