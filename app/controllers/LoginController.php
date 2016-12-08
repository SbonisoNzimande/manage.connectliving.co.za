<?php
/**
 * Login controller
 * 
 * @package 
 * @author  
 */
class LoginController extends AppModel
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
            
            case 'logout': // Do login
                self::$app_controller->log_out_user();
                self::$app_controller->get_view('Login');
                break;
            
            default:
              	self::$app_controller->get_view('Header', array('page_title' => 'Login'));
              	self::$app_controller->get_view('Login');
              	self::$app_controller->get_view('Footer', array('page' => 'login',));
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
    		case 'Admin': // Do login
    			$email    = self::$app_controller->sanitise_string($request->parameters['email']);
    			$password = self::$app_controller->sanitise_string($request->parameters['password']);
    			$login    = self::check_details ($email, $password);
    			
    			return json_encode($login);

    		break;
    	}
    }

    /**
     * checks the user's login details
     * For: Admin
     */
    
    static protected function check_details($email, $password) {
        
        $validate_return = array();
        
        // check if variables are empty
        if (!empty($email) AND !empty($password)) {
            
            // Clean input
            $email    = parent::clean_string($email);
            $password = parent::clean_string($password);
            $userinfo = parent::get_userdetails($email); // get user details of the email


            if (count($userinfo) == 1) { // Email exists
                
                $userPassword = self::$app_controller->hash_password ($password); // Encrypt Pass
                
                foreach ($userinfo as $user) { // Get user details
                    $user_id     	= $user['user_id'];
                    $first_name   	= $user['first_name'];
                    $last_name   	= $user['last_name'];
                    $dbEmail     	= $user['email'];
                    $dbPassword  	= $user['password'];
                    $permission_id  = $user['permission_id'];
                    $perm_type      = $user['permission_type'];
                    $company_id     = $user['company_id'];
                    $modules  		= json_decode($user['modules'], true);
                }

                
                // Test password
                if ($userPassword === $dbPassword) {
                    
                    self::$app_controller->set_session_start();
                    $_SESSION['login_strg']     = md5($dbEmail . '+' . $dbPassword . '+' . $first_name . '+' . $last_name);
                    $_SESSION['first_name']     = $first_name;
                    $_SESSION['last_name']      = $last_name;
                    $_SESSION['email']          = $dbEmail;
                    $_SESSION['permission_id']  = $permission_id;
                    $_SESSION['user_id']        = $user_id;
                    $_SESSION['modules']        = $modules;
                    $_SESSION['company_id']     = $company_id;

                    

                    $redirect                   = '/Dashboard';

                    $validate_return = array(
                        'status' => true,
                        'redirect'=> $redirect
                    );

                } else {
                    // Record attempt
                    $validate_return = array(
                        'status' => false,
                        'text' => 'Username or Password Incorrect',
                        'Code' => 102
                    );
                }
                
            } else { // Email doesn't exist
                $validate_return = array(
                    'status' => false,
                    'text' => 'Username or Password Incorrect',
                    'Code' => 103
                );
            }
            
        } else { // Empty value
            
            $validate_return = array(
                'status' => false,
                'text' => 'Username or Password Incorrect',
                'Code' => 106
            );
        }
        
        return $validate_return;
    }

}