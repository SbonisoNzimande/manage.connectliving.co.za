<?php
/**
 * ContractorsAndSuppliers Controller
 * 
 * @package 
 * @author  
 */
class ContractorsAndSuppliersController
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
		$subRequest		= (isset($request->url_elements[1])) ? 
							$request->url_elements[1] : '';

		self::$app_controller->set_session_start();
		$email 			= $_SESSION['email'];

		switch ($subRequest) {
			case 'GetTable':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_contructor_table ($prop_id, $company_id);

				return json_encode($table);
			break;

			case 'GetTableArchived':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_archived_resident_table ($prop_id, $company_id);

				return json_encode($table);
			break;

			case 'GetTableTrustees':
				$company_id 	= $_SESSION['company_id'];
				$prop_id 		= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 			= self::get_trustees_table ($prop_id, $company_id);

				return json_encode($table);
			break;
			case 'GetContractorByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$contr 			= self::$app_controller->get_contractor_byid ($ID);

				return json_encode($contr[0]);
			break;

			case 'GetSupplierTypeByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$contr 			= self::$app_controller->get_service_types_by_id ($ID);

				return json_encode($contr[0]);
			break;

			case 'GetTimeline':
				$prop_array 	= self::$app_controller->get_propery_array ($_SESSION['modules']);
				$company_id 	= $_SESSION['company_id'];
				$ResID 			= self::$app_controller->sanitise_string ($request->parameters['ResID']);
				$graph 			= self::set_up_timeline ($prop_array, $company_id, $ResID);

			return json_encode ($graph);
			case 'GetAllSupplierTypesTable':
				$types 	= self::set_up_supplier_types ();
				return json_encode($types);
			break;
			case 'UploadEmailImage':

				$upload 	= self::upload_image($_FILES);
			return stripslashes(json_encode($upload));
			case 'GetAllServiceTypes':
				$types 	= self::$app_controller->get_all_service_types ();
				return json_encode($types);
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 			= $_SESSION['email'];
					$first_name		= $_SESSION['first_name'];
					$last_name		= $_SESSION['last_name'];
					$modules		= $_SESSION['modules'];



					$this_page 		= 'property' . self::$property_id;
					$current 		= 'contractorsandsuppliers' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Contractors And Suppliers',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
										);

						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('ContractorsAndSuppliers', $pass);
						self::$app_controller->get_footer (array('page' => 'contractorsandsuppliers'));
						exit();
					}else{
						self::$app_controller->redirect_to ('/Login');
					}
				}else{
					self::$app_controller->redirect_to('Login');
				}
				
			break;
		}
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

			
			case 'CreateContractor':
				$PropertyID   	= self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$ServiceType   	= self::$app_controller->sanitise_string($request->parameters['ServiceType']);
				$CompanyName   	= self::$app_controller->sanitise_string($request->parameters['CompanyName']);
				$Address   		= self::$app_controller->sanitise_string($request->parameters['Address']);
				$PhoneNumber   	= self::$app_controller->sanitise_string($request->parameters['PhoneNumber']);
				$Email   		= self::$app_controller->sanitise_string($request->parameters['Email']);
				

				$save 			= self::create_contractor (
											$PropertyID,
											$ServiceType,
											$CompanyName,
											$Address,
											$PhoneNumber,
											$Email
										);

				
				return json_encode($save);

			break;

			case 'UpdateContractor':
				$ContractorID   	= self::$app_controller->sanitise_string($request->parameters['ContractorID']);
				$ServiceType   		= self::$app_controller->sanitise_string($request->parameters['ServiceType']);
				$CompanyName   		= self::$app_controller->sanitise_string($request->parameters['CompanyName']);
				$Address   			= self::$app_controller->sanitise_string($request->parameters['Address']);
				$PhoneNumber   		= self::$app_controller->sanitise_string($request->parameters['PhoneNumber']);
				$Email   			= self::$app_controller->sanitise_string($request->parameters['Email']);


				$save 				= self::edit_contractor (
											$ContractorID,
											$ServiceType,
											$CompanyName,
											$Address,
											$PhoneNumber,
											$Email
										);

				
				return json_encode($save);

			break;


			case 'DeleteContractor': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['id']);
				$delete    			= self::delete_contractor ($ID);

				return json_encode($delete);
			break;
			case 'SaveComment': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);

				
				$send    	= self::save_comment ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SaveEmail': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['EmailText']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$UserID 	= $_SESSION['user_id'];
				$CompanyID 	= $_SESSION['company_id'];
				$FileData 	= array_map(self::$app_controller->sanitise_string, $_FILES);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);


				
				$send    	= self::save_email ($Message, $ResID, $FileData, $UserID, $CompanyID, $PropertyID);
				return json_encode($send);
			break;

			case 'SendSMS': 
				$Message    = self::$app_controller->sanitise_string($request->parameters['CommentSMS']);
				$ResID 		= self::$app_controller->sanitise_string($request->parameters['ResID']);
				$PropertyID = self::$app_controller->sanitise_string($request->parameters['PropertyID']);
				$UserID 	= $_SESSION['user_id'];

				$send    	= self::send_sms (
								$Message,
								$ResID,
								$PropertyID,
								$UserID
							  );
				return json_encode($send);
			break;


			case 'DeleteSupplierType': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['id']);
				$save    			= self::delete_supplier_type ($ID);

				return json_encode($save);
			break;
			case 'CreateSupplierType': 
				$SupplierTypeName   = self::$app_controller->sanitise_string($request->parameters['SupplierTypeName']);
				$save    			= self::save_supplier_type ($SupplierTypeName);

				return json_encode($save);
			break;

			case 'EditSupplierType': 
				$ID   				= self::$app_controller->sanitise_string($request->parameters['CategoryID']);
				$SupplierTypeName   = self::$app_controller->sanitise_string($request->parameters['SupplierTypeName']);
				$save    			= self::edit_supplier_type ($ID, $SupplierTypeName);

				return json_encode($save);
			break;

			case 'DuplicateContractor':
				$ContractorID 		= self::$app_controller->sanitise_string($request->parameters['DuplicateContractorID']);
				$PropertyName 	= self::$app_controller->sanitise_string($request->parameters['PropertyName']);
				
				$edit 			= self::duplicate_contractor ($ContractorID, $PropertyName);
				return json_encode($edit);
			break;

			case 'UploadThumbnail':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$supplier_id		= self::$app_controller->sanitise_string($request->parameters['supplier_id']);
				$UploadLogoFile 	= $_FILES['UploadLogoFile'];
				$company_id 		= $_SESSION['company_id'];

				$save 				= self::upload_thumbnail ($supplier_id, $company_id, $prop_id, $UploadLogoFile);
				return json_encode($save);
				break;

			


			// UpdateSaveLease
		}
	}


	static public function upload_thumbnail ($supplier_id, $company_id, $prop_id, $UploadLogoFile) {

		if (!isset($UploadLogoFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadLogoFile["name"]);
		$ext 		= end($temp);


		if ($ext !== 'jpg' AND $ext !== 'png' AND $ext !== 'jpeg') {
			return array('status'  => false, 'text' => 'Please upload only jpg or png file');
		}

		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id .'/suppliers';

		self::$app_controller->created_directory ($dir);


		$name 		= $UploadLogoFile["name"];

		$upload     = self::$app_controller->upload_file_name ($UploadLogoFile, $name, $dir);
		$save     	= self::$app_controller->update_supplier_thum ($supplier_id, $name);


		if ($upload AND $save) {
			return array('status'  => true, 'text' => 'File uploaded');
		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

	
	/*** save query ***/
	static public function create_contractor (
										$PropertyID,
										$ServiceType,
										$CompanyName,
										$Address,
										$PhoneNumber,
										$Email
									) {

		if (!is_numeric($PropertyID)) {
			return array('status'  => false, 'text' => 'Invalid Property ID');
		}

		if (!is_numeric($ServiceType)) {
			return array('status'  => false, 'text' => 'Invalid Service Type');
		}

		// if (!self::$app_controller->validate_variables ($CompanyName, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Company Name');
		// }

		// if (!self::$app_controller->validate_variables ($Address, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Address');
		// }

		// if (!self::$app_controller->validate_variables ($PhoneNumber, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Phone Number');
		// }

		// if (!self::$app_controller->validate_variables ($Email, 18)) {
		// 	return array('status'  => false, 'text' => 'Invalid Email');
		// }



		
		$save 		= self::$app_controller->insert_contractor (
								$PropertyID,
								$ServiceType,
								$CompanyName,
								$Address,
								$PhoneNumber,
								$Email
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	static public function save_supplier_type ($SupplierTypeName) {


		if (!self::$app_controller->validate_variables ($SupplierTypeName, 3)) {
			return array('status'  => false, 'text' => 'Please enter name');
		}

		$save 	= self::$app_controller->insert_service_types (
									$SupplierTypeName
								);

		if ($save) {
			return array('status'  => true, 'text' => 'Supplier Category Saved');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}

	static public function edit_supplier_type ($ID, $SupplierTypeName) {

		$doc 			= self::$app_controller->get_service_types_by_id ($ID);

		if (count($doc) !== 1) {
			return array('status'  => false, 'text' => 'Invalid record');
		}

		if (!self::$app_controller->validate_variables ($SupplierTypeName, 3)) {
			return array('status'  => false, 'text' => 'Please enter name');
		}

		$save 	= self::$app_controller->update_supplier_category (
									$ID,
									$SupplierTypeName
								);

		if ($save) {
			return array('status'  => true, 'text' => 'Supplier Category Saved');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}

	/*** edit query ***/
	static public function edit_contractor (
											$ContractorID,
											$ServiceType,
											$CompanyName,
											$Address,
											$PhoneNumber,
											$Email
										) {

		$get_ress = self::$app_controller->get_contractor_byid ($ContractorID);


		if (count($get_ress) === 0) {
			return array('status'  => false, 'text' => 'Invalid Contractor ID');
		}

		if (!self::$app_controller->validate_variables ($ServiceType, 3)) {
			return array('status'  => false, 'text' => 'Invalid Service Type');
		}

		// if (!self::$app_controller->validate_variables ($CompanyName, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Company Name');
		// }

		// if (!self::$app_controller->validate_variables ($Address, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Address');
		// }

		// if (!self::$app_controller->validate_variables ($PhoneNumber, 3)) {
		// 	return array('status'  => false, 'text' => 'Invalid Phone Number');
		// }

		// if (!self::$app_controller->validate_variables ($Email, 18)) {
		// 	return array('status'  => false, 'text' => 'Invalid Email');
		// }

		
		$save 		= self::$app_controller->update_contractor (
								$ContractorID,
								$ServiceType,
								$CompanyName,
								$Address,
								$PhoneNumber,
								$Email
							);

		if ($save === true) {
			return array('status' => true, 'text' => 'Updated');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

	static public function delete_contractor ($ID) {

		$get_contr  = self::$app_controller->get_contractor_byid ($ID);

		if (!is_numeric ($ID) OR count($get_contr) == 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save 		= self::$app_controller->delete_this_contractor ($ID);

		if ($save === true) {
			return array('status' => true, 'text'  => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}

	}


	static public function delete_supplier_type ($ID) {

		$get_company  = self::$app_controller->get_service_types_by_id ($ID);

		if (!is_numeric ($ID) OR count($get_company) == 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save 		= self::$app_controller->delete_service_type ($ID);

		if ($save === true) {
			return array('status' => true, 'text'  => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}

	}


	static public function get_contructor_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_all_contructors ($property_id);

		

		foreach ($residents as $c) {
			$return[] 	= array(
				'service_name'  	=> $c['service_name'],
				'company_name'  	=> $c['company_name'],
				'address'  			=> $c['address'],
				'phone_number' 		=> $c['phone_number'],
				'email' 			=> $c['email'],
				
				'buttons' 			=> '<button class="btn btn-info btn-sm" data-title="Edit" data-toggle="modal" data-target="#EditContractorModal" data-res-id="'. $c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>
										<button class="btn btn-danger btn-sm" data-title="Delete" data-toggle="modal" data-target="#DeleteContractorModal" data-res-id="'.$c['id'].'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></button>
										<button rel="tooltip" data-original-title="Duplicate Contractors" data-toggle="modal" data-target="#DuplicateContractorsModal" data-res-id="'.$c['id'].'" data-form-name="Contractors And Suplier" data-unit-num="" data-res-name="" data-res-cell="" class="btn btn-warning btn-sm">	<span class="glyphicon glyphicon-copy"></span> </button>

										<button rel="tooltip" data-original-title="Supplier Thumbnail" data-toggle="modal" data-target="#SupplierThumbnailModal" data-res-id="'.$c['id'].'" data-form-name="Contractors And Suplier" data-unit-num="" data-res-name="" data-res-cell="" class="btn btn-warning btn-sm">	<span class="glyphicon glyphicon-log-in"></span> </button>
										'
				);
		}
		
		return $return;

	}

	static public function set_up_supplier_types () {

		$return 	 = array();

		$table   	 = self::$app_controller->get_all_service_types();

		foreach ($table as $c) {

			$button 	= '<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#EditSupplierTypesModal" data-doc-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-pencil-square-o"></span></button>';


			$button 	.= '<button class="btn btn-danger btn-xs " data-title="Delete" data-toggle="modal" data-target="#DeleteSupplierTypeModal" data-doc-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-times"></span></button>';


			$return[] 	= array(
				'document_name'  	=> $c['service_name'],
				'created'  			=> $c['created'],
				'buttons' 			=> $button
				);
		}
		
		

		return $return;

	}

	static public function set_up_timeline ($prop_array, $company_id, $ResID) {
		$ret_array 		= array();

		$sms_list 		= self::$app_controller->get_res_sms_coms ($ResID);
		$comm_list 		= self::$app_controller->get_res_comment_coms ($ResID);
		$email_list 	= self::$app_controller->get_res_email_coms ($ResID);
		$dir 			= '../companies/' .$company_id. '/residents/' .$ResID;

		// work out return array
		if (!empty($sms_list)) {// notifications

			foreach ($sms_list as $n) {
				$ret_array[] = array(
					'sort' 	  		=> $n['created'],
					'id' 	  		=> $n['id'],
					'subject' 		=> $n['full_name'] . ' sent an SMS',
					'message' 		=> $n['sms_text'],
					'type' 	  		=> 'SMS',
					'file' 	  		=> '',
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($n['created']) . ' ago'
					);
			}

		}

		if (!empty($comm_list)) {// notifications

			foreach ($comm_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}

				
				$ret_array[] = array(
					'sort' 	  		=> $c['date_created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' commented: ',
					'message' 		=> $c['comment_text'],
					'type' 	  		=> 'Comment',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['date_created']) . ' ago'
					);
			}

		}

		if (!empty($email_list)) {// notifications

			foreach ($email_list as $c) {

				if (!empty($c['file'])) {
					$file 		 = $c['file'];
				}else{
					$file 		 = '';
				}
				
				$ret_array[] = array(
					'sort' 	  		=> $c['created'],
					'id' 	  		=> $c['id'],
					'subject' 		=> $c['full_name'] . ' sent an email: ',
					'message' 		=> $c['email_text'],
					'type' 	  		=> 'Email',
					'file' 	  		=> $file,
					'resident_id'	=> $ResID,
					'date'    		=> self::$app_controller->human_timing($c['created']) . ' ago'
					);
			}

		}

		$sorted = self::$app_controller->array_orderby ($ret_array, 'sort', SORT_DESC);

		return $sorted;

	}
	
	static public function get_archived_resident_table ($property_id, $company_id) {

		$return 	 = array();

		$residents   = self::$app_controller->get_archived_residents ($property_id);

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus'],
				'buttons' 				=> '<button class="btn btn-default btn-sm" data-title="Communicate" data-toggle="modal" data-target="#ArchivedComHistoryModal" data-res-id="'.$c['id'].'" data-res-name="'.$c['residentName'].'" aria-expanded="false"><span class="fa fa-comment-o"></span></button>'
				);
		}
		
		return $return;

	}

	static public function get_trustees_table ($property_id, $company_id) {

		$return 	 = array();

		// echo 1;

		$residents   = self::$app_controller->get_trustees_residents ($property_id);

		foreach ($residents as $c) {
			$return[] 	= array(
				'unitNumber'  			=> $c['unitNumber'],
				'residentName'  		=> $c['residentName'],
				'residentPhone'  		=> $c['residentPhone'],
				'residentCellphone' 	=> $c['residentCellphone'],
				'residentNotifyEmail' 	=> $c['residentNotifyEmail'],
				'residentType' 			=> $c['residentType'],
				'residentTrustee' 		=> $c['residentTrustee'],
				'residentStatus' 		=> $c['residentStatus']
				);
		}
		
		return $return;

	}


	/*** archive unit ***/
	static public function archive_unit ($ID) {
		$check_unit 	= self::$app_controller->get_resident_byid ($ID);

		if (count($check_unit) === 0) {
			return array('status'  => false, 'text' => 'Resident doesnt exist');
		}

		$archive 		= self::$app_controller->archive_record ($ID);

		if ($archive === true) {
			return array('status' => true, 'text' => 'Inserted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}


	static public function duplicate_contractor ($ContractorID, $PropertyName) {
		
		if (!is_numeric($PropertyName)) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}

		if (!is_numeric($ContractorID)) {
			return array('status'  => false, 'text' => 'Invalid Contractor ID');
		}

		
		$save = self::$app_controller->copy_contractor ($ContractorID, $PropertyName);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Copied');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

}