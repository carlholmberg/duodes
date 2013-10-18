<?php

/**
 * Login controller
 * 
 * @author Carl Holmberg
 * @copyright Copyright 2012
 * @license http://www.gnu.org/licenses/lgpl.txt
 *   
 */
namespace controllers;

class Login extends \controllers\Controller {

    function google($app, $params) {
        if ($app->get('GET.openid_mode') == 'cancel') {
            $app->reroute('/');
        }
        if ($app->get('GET.openid_mode') == 'id_res') {
            $openid = new \Web\OpenID;
            if ($openid->verified()) {
                $response = $openid->response();
                $name = $response['ext1.value.firstname'].' '.$response['ext1.value.lastname'];
                $data = array('name' => $name,
                              'email' => $response['ext1.value.email']);
                
                $acc = new \controllers\Account;
                $ok = $acc->fromGoogle($data);
                if ($ok) {
                    $app->set('SESSION.account', $ok);
                }
                $app->reroute('/');
		    }
        }
        
        $openid = new \Web\OpenID;
    	$openid->set('identity','https://www.google.com/accounts/o8/id');
		$openid->set('return_to',
		$app->get('SCHEME').'://'.$app->get('HOST').
		$app->get('BASE').'/login/google');
	    // auth() should always redirect if successful; fail if displayed
	    $openid->auth(NULL, array(
			'email'=>'http://axschema.org/contact/email',
			'firstname'=>'http://axschema.org/namePerson/first',
			'lastname'=>'http://axschema.org/namePerson/last'),
			array('email','firstname','lastname'));
    }
    
    function logout() {
        $this->app->set('SESSION.account', false);
        $this->app->reroute('/');
    }
    
    
    function local($app, $params) {
        $email = $app->get('POST.email');
        $password = $app->get('POST.password');
        
        $data = array('email' => $email,
                      'password' => $password);
        
        $acc = new \controllers\Account;
        $ok = $acc->fromLocal($data);
        if ($ok) {
            $app->set('SESSION.account', $ok);
        }
        $app->reroute('/');
    }

}