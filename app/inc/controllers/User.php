<?php

/**
 * User controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class User extends \controllers\Controller {
    function get($app, $params) {
        if ($params['id'] == 'login') {
            
            $this->login();
        } else if ($params['id'] == 'logon') {
            $this->logon();
        }
        echo 'User (GET): name '.$params['id'];
    }
    
    function logon() {
        $openid=new \Web\OpenID;
        if ($openid->verified()) {
            $response=$openid->response();
		}
	}
	
	function login() {
        $openid=new \Web\OpenID;
    	$openid->set('identity','https://www.google.com/accounts/o8/id');
		$openid->set('return_to',
		$this->app->get('SCHEME').'://'.$this->app->get('HOST').
		$this->app->get('BASE').'/user/logon');
	    // auth() should always redirect if successful; fail if displayed
	    $openid->auth(NULL, array(
			'email'=>'http://axschema.org/contact/email',
			'firstname'=>'http://axschema.org/namePerson/first',
			'lastname'=>'http://axschema.org/namePerson/last'),
			array('email','firstname','lastname'));
    }
    
    function post($app, $params) {
        echo 'User (POST):'.print_r($app->get('POST.email'), true);
    }
    function put() {}
    function delete() {}
}