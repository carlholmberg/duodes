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

class Login {

    function google($app, $params) {
        if ($app->get('GET.openid_mode') == 'cancel') {
            $app->reroute('/');
        }
        if ($app->get('GET.openid_mode') == 'id_res') {
            $openid = new \Web\OpenID;
            if ($openid->verified()) {
                $response = $openid->response();
                $data = array('lname' => $response['ext1.value.lastname'],
                              'fname' => $response['ext1.value.firstname'],
                              'email' => $response['ext1.value.email'],
                              'from' => 'google');
                $this->login($data);
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
        $app = \Base::instance();
        $app->set('SESSION.account', false);
        $app->reroute('/');
    }
    
    function login($data) {
        $app = \Base::instance();
        switch ($data['from']) {
            case 'google':
                $acc = new \models\Account($data);
                break;
            case 'local':
                $acc = array('name' => 'Noname', 'email' => $data['email'], 'level' => 3);
                break;
            default:
                break;
        }
        if ($acc) {
            $app->set('SESSION.account', $acc->info());
        }
    }
    
    function local($app, $params) {
        $email = $app->get('POST.email');
        $password = $app->get('POST.password');
        
        $data = array('email' => $email,
                      'from' => 'local');
        $this->login($data);
        $app->reroute('/');
    }

}