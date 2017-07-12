<?php
/**
 * Branding Controller
 * 
 * @package 
 * @author  
 */
class BrandingController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller 	= new AppController();
		self::$property_id   	= (isset($_REQUEST['prop_id'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_id']) : '';
		self::$property_name 	= (isset($_REQUEST['prop_name'])) ? self::$app_controller->sanitise_string($_REQUEST['prop_name']) : '';

		self::$app_controller->set_session_start();
		self::$company_id = $_SESSION['company_id'];

		// die(self::$app_controller->hash_password('janey3'));
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

		switch ($subRequest) {
			case 'GetImages':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$company_id 	= $_SESSION['company_id'];

				$responces 		= self::set_get_image ($ID, $company_id);

				return json_encode($responces);
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'property' . self::$property_id;
					$current 			= 'branding' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Branding',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						// die(var_dump($pass));
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Branding', $pass);
						self::$app_controller->get_footer (array('page' => 'branding'));
						exit();
					}else{
						self::$app_controller->redirect_to ('/Login');
					}
				}else{
					self::$app_controller->redirect_to('/Login');
				}
				
			break;
		}
	}


	/**
	 * Post Request
	 *
	 * @param
	 * @return
	 */
	public function post($request) {
		$subRequest		= (isset($request->url_elements[1])) ? $request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			

			case 'UploadCompanyLogo':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$UploadLogoFile 	= $_FILES['UploadLogoFile'];
				$company_id 		= $_SESSION['company_id'];


				$save 				= self::upload_company_logo ($company_id, $prop_id, $UploadLogoFile);
				return json_encode($save);
				break;

			case 'UploadEstateLogo':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$UploadLogoFile 	= $_FILES['UploadLogoFile'];
				$company_id 		= $_SESSION['company_id'];

				$save 				= self::upload_estate_logo ($company_id, $prop_id, $UploadLogoFile);
				return json_encode($save);
				break;
			case 'SaveMarketingLink':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$MarketingURL 		= self::$app_controller->sanitise_string($request->parameters['MarketingURL']);
				$company_id 		= $_SESSION['company_id'];

				$save 				= self::set_up_property_link ($company_id, $prop_id, $MarketingURL);
				return json_encode($save);
				break;
		}
	}


	static public function set_get_image ($prop_id, $company_id){
		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

		$property 			= self::$app_controller->get_property_byid ($prop_id);
		$marketing_link 	= $property[0]['marketing_link'];

		// marketing_link

		$cpant 		= $dir.'/logo.jpg';
		$cdata 		= file_get_contents($cpant);
		$epant 		= $dir.'/homeImage.jpg';
		$edata 		= file_get_contents($epant);

		$cbase64 	= false;
		if ($cdata) {
			$cbase64 	= 'data:image/jpg;base64,' . base64_encode($cdata);
		}


		$ebase64 	= false;
		if ($edata) {
			$ebase64 	= 'data:image/jpg;base64,' . base64_encode($edata);
		}
	

		return array('company_image' => $cbase64, 'estate_image' => $ebase64, 'marketing_link' => $marketing_link);
	}


	static public function upload_company_logo ($company_id, $prop_id, $UploadLogoFile) {

		if (!isset($UploadLogoFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadLogoFile["name"]);
		$ext 		= end($temp);

		if ($ext !== 'jpg') {
			return array('status'  => false, 'text' => 'Please upload only jpg file');
		}

		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

		self::$app_controller->created_directory($dir);


		$name 		= 'logo.jpg';

		$upload     = self::$app_controller->upload_file_name ($UploadLogoFile, $name, $dir);


		if ($upload) {
			return array('status'  => true, 'text' => 'File uploaded');
		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

	static public function upload_estate_logo ($company_id, $prop_id, $UploadLogoFile) {

		if (!isset($UploadLogoFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadLogoFile["name"]);
		$ext 		= end($temp);

		if ($ext !== 'jpg') {
			return array('status'  => false, 'text' => 'Please upload only jpg file');
		}

		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

		self::$app_controller->created_directory($dir);

		$name 		= 'homeImage.jpg';

		$upload     = self::$app_controller->upload_file_name ($UploadLogoFile, $name, $dir);

		if ($upload) {
			return array('status'  => true, 'text' => 'File uploaded');
		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

	static public function set_up_property_link ($company_id, $prop_id, $MarketingURL) {

		if (filter_var($MarketingURL, FILTER_VALIDATE_URL) === FALSE) {
		    return array('status'  => false, 'text' => 'Invalid URL');
		}

		
		$save     = self::$app_controller->update_markting_url ($prop_id, $MarketingURL);

		if ($save === true) {
			return array('status'  => true, 'text' => 'Property Link Updated');
		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

}