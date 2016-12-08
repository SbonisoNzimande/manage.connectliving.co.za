<?php
/**
 * All Queries Controller
 * 
 * @package 
 * @author  
 */
class EmergencyContactsController
{
	static public $app_controller;
	static public $property_id;
	static public $property_name;
	static public $prop_array;
	static public $company_id;


	public function __construct() {
		self::$app_controller = new AppController();
		self::$property_id = self::$app_controller->sanitise_string($_REQUEST['prop_id']);
		self::$property_name = self::$app_controller->sanitise_string($_REQUEST['prop_name']);

		self::$app_controller->set_session_start();
		self::$company_id = $_SESSION['company_id'];
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
			

			case 'CreateContactForm':
				$PropID 				= self::$app_controller->sanitise_string($request->parameters['PropID']);
				$ContactName 			= self::$app_controller->sanitise_string($request->parameters['ContactName']);
				$ContactType 			= self::$app_controller->sanitise_string($request->parameters['ContactType']);
				$ContactPhone 			= self::$app_controller->sanitise_string($request->parameters['ContactPhone']);
				$ContactIcon 			= self::$app_controller->sanitise_string($request->parameters['ContactIcon']);
				$ContactColor 			= self::$app_controller->sanitise_string($request->parameters['ContactColor']);
				
				$save 				= self::save_contact ($PropID,
											$ContactName,
											$ContactType,
											$ContactPhone,
											$ContactIcon,
											$ContactColor);
				return json_encode($save);
				break;

			case 'EditContactForm':
				$ID 					= self::$app_controller->sanitise_string($request->parameters['EditID']);
				$ContactName 			= self::$app_controller->sanitise_string($request->parameters['ContactName']);
				$ContactType 			= self::$app_controller->sanitise_string($request->parameters['ContactType']);
				$ContactPhone 			= self::$app_controller->sanitise_string($request->parameters['ContactPhone']);
				$ContactIcon 			= self::$app_controller->sanitise_string($request->parameters['ContactIcon']);
				$ContactColor 			= self::$app_controller->sanitise_string($request->parameters['ContactColor']);
				
				$save 				= self::edit_contact ($ID,
											$ContactName,
											$ContactType,
											$ContactPhone,
											$ContactIcon,
											$ContactColor);
				return json_encode($save);
				break;

			case 'DeleteContact': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['DeleteID']);
				$save    			= self::delete_contact ($ID);

				return json_encode($save);
			break;
		}
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
			case 'GetPropertyContact':
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$prop 			= self::get_contact_table ($prop_id);

				return json_encode($prop);
			break;

			case 'GetContactByID':
				$ID 		= self::$app_controller->sanitise_string($request->parameters['ContactID']);
				$cont 		= self::$app_controller->get_contact_table ($ID);

				return json_encode($cont[0]);
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'property' 			 . self::$property_id;
					$current 			= 'emergency_contacts' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Emergency Contacts',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);
						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('EmergencyContacts', $pass);
						self::$app_controller->get_footer (array('page' => 'emergency_contacts'));
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


	static public function save_contact ($PropID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {

		if (!is_numeric($PropID)) {
			return array('status'  => false, 'text' => 'Invalid PropID');
		}

		if (!self::$app_controller->validate_variables ($ContactName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Contact Name');
		}



		$save = self::$app_controller->insert_emergency_contact ($PropID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Saved');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}

		
	}

	static public function edit_contact ($ID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		if (!self::$app_controller->validate_variables ($ContactName, 3)) {
			return array('status'  => false, 'text' => 'Invalid Contact Name');
		}



		$save = self::$app_controller->update_emergency_contact ($ID,
										$ContactName,
										$ContactType,
										$ContactPhone,
										$ContactIcon,
										$ContactColor);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Saved');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}

		
	}

	static public function delete_contact ($ID) {

		if (!is_numeric($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save = self::$app_controller->delete_emergency_contact ($ID);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}

		
	}



	// get_contact_table ($prop_id)

	static public function get_contact_table ($prop_id) {

		$return 	 = array();

		$table   	 = self::$app_controller->get_all_emergency_contacts($prop_id);

		foreach ($table as $c) {

			$button 	= '<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#EditContactModal" data-contact-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-pencil-square-o"></span></button>';


			$button 	.= '<button class="btn btn-danger btn-xs " data-title="Delete" data-toggle="modal" data-target="#DeleteContactModal" data-contact-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-times"></span></button>';

			$contact_icon  = '<div class="fa '.$c['contact_icon'].'"></div>';
			$contact_color = $c['contact_color'];


			$return[] 	= array(
				'id'  				=> $c['id'],
				'propertyId'  		=> $c['propertyId'],
				'contact_name'  	=> $c['contact_name'],
				'contact_type'  	=> $c['contact_type'],
				'contact_phone'  	=> $c['contact_phone'],
				'contact_icon'  	=> $contact_icon,
				'contact_color'  	=> $contact_color,
				
				'buttons' 			=> $button
				);
		}
		
		

		return $return;

	}

}