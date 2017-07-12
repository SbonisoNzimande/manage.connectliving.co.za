<?php
/**
 * JobQuotes TrusteeVoting Controller
 * 
 * @package 
 * @author  
 */
class JobQuotesTrusteeVotingController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;

	public function __construct() {
		self::$app_controller = new AppController();
		self::$property_id = self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name = self::$app_controller->sanitise_string($_REQUEST['prop_name']);
	}

	/**
	 * GET Request
	 *
	 * @param
	 * @return
	 */
	public function get($request) {
		$subRequest		= (isset($request->url_elements[1])) ? 
							$request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];
		// $company_id 	= $_SESSION['company_id'];

		// var_dump($request);
		// die();

		switch ($subRequest) {
			default:

				$company_id 		= self::$app_controller->sanitise_string ($request->parameters['company_id']);
				$prop_id 			= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$property_name 		= self::$app_controller->sanitise_string ($request->parameters['property_name']);
				$property_address 	= self::$app_controller->sanitise_string ($request->parameters['property_address']);
				$job_id 			= self::$app_controller->sanitise_string ($request->parameters['job_id']);
				$quote_id 			= self::$app_controller->sanitise_string ($request->parameters['quote_id']);
				$user_id 			= self::$app_controller->sanitise_string ($request->parameters['user_id']);
				$trustee_name 		= self::$app_controller->sanitise_string ($request->parameters['trustee_name']);
				$file_name 			= self::$app_controller->sanitise_string ($request->parameters['file_name']);
				$job_id 			= self::$app_controller->sanitise_string ($request->parameters['job_id']);
				$job_description 	= self::$app_controller->sanitise_string ($request->parameters['job_description']);

				if (isset($company_id) AND isset($prop_id) AND isset($user_id) AND isset($job_id)) {
					

					$pass 		= array(
									'company_id'  		=> $company_id, 
									'prop_id' 	 		=> $prop_id,
									'job_id' 			=> $job_id,
									'quote_id'			=> $quote_id,
									'user_id'	 		=> $user_id,
									'trustee_name'		=> $trustee_name,
									'file_name'	 		=> $file_name,
									'property_name' 	=> $property_name,
									'property_address' 	=> $property_address,
									'job_id' 			=> $job_id,
									'job_description' 	=> $job_description,
									'page_title' 		=> 'Quotations ' .$property_name
									);
					self::$app_controller->get_header ($pass);
					self::$app_controller->get_view 	('JobQuotesTrusteeVoting', $pass);
					self::$app_controller->get_footer (array('page'=>'jobs'));
					exit();
				}else{
					self::$app_controller->redirect_to('Login');
				}
				
			break;
		}
	}

}
