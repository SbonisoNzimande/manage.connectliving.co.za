<?php
/**
 * ForgotPassword controller
 * 
 * @package 
 * @author  
 */
class ForgotPasswordController extends AppModel
{
    static public $app_controller;
    
    public function __construct() {
        self::$app_controller = new AppController();

        // var_dump(self::$app_controller->hash_password('sboniso'));
        // die();
    }


    /**
     * GET Request
     *
     * @param  
     * @return 
     */
    public function get($request) {
        
        // Capture subrequest
        $subRequest = (isset($request->url_elements[1])) ? $request->url_elements[1] : '';
        $callback   = (isset($request->parameters['callback'])) ? $request->parameters['callback'] : '';
        
        
        switch ($subRequest) {
            
            
            
            default:
              	self::$app_controller->get_view('Header', array('page_title' => 'Password Recovery'));
              	self::$app_controller->get_view('ForgotPassword');
              	self::$app_controller->get_view('Footer', array('page' => 'forgot_password',));
                break;
        }
        exit();
        
    }


    /**
     * POST Request
     *
     * @param  
     * @return 
     */
    public function post ($request) {
    	$subRequest = (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

    	switch ($subRequest) {
    		case 'SendPasswordReminder': // Do login
    			$email    = self::$app_controller->sanitise_string($request->parameters['email']);
    			$login    = self::check_details ($email);
    			
    			return json_encode($login);

    		break;
    	}
    }


    /**
     * checks the user's login details
     * For: Admin
     */
    
    static protected function check_details($email) {
        
        $validate_return = array();
        
        // check if variables are empty
        if (!empty($email)) {
            
            // Clean input
            $email    = parent::clean_string($email);
            $userinfo = parent::get_userdetails($email); // get user details of the email


            if (count($userinfo) == 1) { // Email exists
                
                // $userPassword = self::$app_controller->hash_password ($password); // Encrypt Pass
                
                foreach ($userinfo as $user) { // Get user details
                    $user_id     	= $user['user_id'];
                    $first_name   	= $user['first_name'];
                    $last_name   	= $user['last_name'];
                    $full_name 		= $first_name .' '. $last_name;
                    $dbEmail     	= $user['email'];
                    $dbPassword  	= $user['password'];
                    $permission_id  = $user['permission_id'];
                    $perm_type      = $user['permission_type'];
                    $company_id     = $user['company_id'];
                    $password_text  = $user['passwordText'];
                    $modules  		= json_decode($user['modules'], true);
                }

                $redirect           = '/Login';

                // die(var_dump($password_text));

                $send_details       = self::send_logins_email ($full_name, $dbEmail, $password_text);

                $validate_return 	= $send_details;
                
               
                
            } else { // Email doesn't exist
                $validate_return = array(
                    'status' => false,
                    'text' => 'Email Does Not Exist' ,
                    'Code' => 103
                );
            }
            
        } else { // Empty value
            
            $validate_return = array(
                'status' => false,
                'text' => 'Email Does Not Exist',
                'Code' => 106
            );
        }
        
        return $validate_return;
    }


    static public function send_logins_email ($fullname, $email, $password) {

    	$html  = self::$app_controller->get_logins_email ($fullname, $email, $password);

    	$send  = self::$app_controller->send_email ($html, 'ConnectLIVING - Password Reminder', $email, $fullname);

    	if ($send == true) {
    		return array('status' => true, 'text'  => 'Email sent');
    	}else{
    		return array('status' => false, 'text' => 'Failed to send, ' . $send);
    	}

    }

}