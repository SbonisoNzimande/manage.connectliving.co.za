<?php
/**
 * Documentation Controller
 * 
 * @package 
 * @author  
 */
class DocumentationController
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

			case 'GetDocumentByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$doc 			= self::$app_controller->get_document_by_id ($ID);

				return json_encode($doc[0]);
			break;
			case 'GetDocumentTypeByID':
				$ID 			= self::$app_controller->sanitise_string($request->parameters['ID']);
				$doc 			= self::$app_controller->get_document_type_by_id ($ID);

				return json_encode($doc[0]);
			break;

			case 'GetAllDocumentTypes':
				$types 	= self::$app_controller->get_all_doc_types ();
				return json_encode($types);
			break;

			case 'GetAllDocumentTypesTable':
				$types 	= self::set_up_document_types ();
				return json_encode($types);
			break;

			case 'GetTable':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$table 				= self::get_table ($prop_id);
				return json_encode($table);
			break;
			case 'DownloadFile':
				$file_name 		= self::$app_controller->sanitise_string ($request->parameters['file_name']);
				$prop_id 		= self::$app_controller->sanitise_string ($request->parameters['prop_id']);
				$company_id 	= $_SESSION['company_id'];

				$download  		= self::dowload_file ($file_name, $company_id, $prop_id);
			return $download;
			exit();
			break;
			default:
				if (self::$app_controller->check_if_logged($email)) {
					
					$email 				= $_SESSION['email'];
					$first_name			= $_SESSION['first_name'];
					$last_name			= $_SESSION['last_name'];
					$modules			= $_SESSION['modules'];

					$this_page 			= 'property' 		 . self::$property_id;
					$current 			= 'documentation' 	 . self::$property_id;

					$aside_menu 		= self::$app_controller->get_aside_menu ($modules, $current);
					self::$prop_array 	= self::$app_controller->get_propery_array ($modules);


					/*** validate if assigned for this module ***/
					if (in_array($this_page, $aside_menu['validate'])) {
						$pass 		= array(
										'full_name'  => $first_name.' '.$last_name, 
										'email' 	 => $email,
										'page_title' => 'Documentation',
										'page'		 => $current,
										'prop_id'	 => self::$property_id,
										'prop_name'	 => self::$property_name,
										'aside_menu' => $aside_menu['html']
									);

						
						self::$app_controller->get_header ($pass);
						self::$app_controller->get_view   ('Asidemenu', $pass);
						self::$app_controller->get_view   ('Documentation', $pass);
						self::$app_controller->get_footer (array('page' => 'documentation'));
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
			

			case 'UploadDocument':
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$DocumentType 		= self::$app_controller->sanitise_string($request->parameters['DocumentType']);
				$UploadLogoFile 	= $_FILES['UploadLogoFile'];
				$company_id 		= $_SESSION['company_id'];


				$save 				= self::upload_document ($company_id, $prop_id, $DocumentType, $UploadLogoFile);
				return json_encode($save);
				break;

			case 'EditUploadDocument':

				$ID 				= self::$app_controller->sanitise_string($request->parameters['editID']);
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$DocumentType 		= self::$app_controller->sanitise_string($request->parameters['DocumentType']);
				$UploadLogoFile 	= $_FILES['UploadLogoFile'];
				$company_id 		= $_SESSION['company_id'];


				$save 				= self::edit_document ($company_id, $prop_id, $ID, $DocumentType, $UploadLogoFile);
				return json_encode($save);
				break;

			case 'DeleteDoc': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['id']);
				$save    			= self::delete_document ($ID);

				return json_encode($save);
			break;

			case 'DeleteDocType': 
				$ID    				= self::$app_controller->sanitise_string($request->parameters['id']);
				$save    			= self::delete_document_type ($ID);

				return json_encode($save);
			break;
			case 'CreateDocumentType': 
				$DocumentTypeName   = self::$app_controller->sanitise_string($request->parameters['DocumentTypeName']);
				$save    			= self::save_document_type ($DocumentTypeName);

				return json_encode($save);
			break;

			case 'EditDocumentType': 
				$ID   				= self::$app_controller->sanitise_string($request->parameters['CategoryID']);
				$DocumentTypeName   = self::$app_controller->sanitise_string($request->parameters['DocumentTypeName']);
				$save    			= self::edit_document_type ($ID, $DocumentTypeName);

				return json_encode($save);
			break;

			case 'DuplicateDocument':
				$DocumentID 		= self::$app_controller->sanitise_string($request->parameters['DuplicateDocumentID']);
				$company_id 		= $_SESSION['company_id'];
				$prop_id 			= self::$app_controller->sanitise_string($request->parameters['prop_id']);
				$PropertyName 		= self::$app_controller->sanitise_string($request->parameters['PropertyName']);
				
				$edit 				= self::duplicate_document ($company_id, $prop_id, $DocumentID, $PropertyName);
				return json_encode($edit);
			break;
		}
	}


	static public function delete_document ($ID) {

		$get_company  = self::$app_controller->get_document_by_id ($ID);

		if (!is_numeric ($ID) OR count($get_company) == 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save 		= self::$app_controller->delete_document ($ID);

		if ($save === true) {
			return array('status' => true, 'text'  => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}

	}

	static public function delete_document_type ($ID) {

		$get_company  = self::$app_controller->get_document_type_by_id ($ID);

		if (!is_numeric ($ID) OR count($get_company) == 0) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}

		$save 		= self::$app_controller->delete_document_type ($ID);

		if ($save === true) {
			return array('status' => true, 'text'  => 'Deleted');
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $edit);
		}

	}

	static public function get_table ($prop_id) {

		$return 	 = array();

		$table   	 = self::$app_controller->get_all_documents($prop_id);

		foreach ($table as $c) {

			$button 	= '<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#editDocumentModal" data-doc-id="'.$c['id'].'"  rel="tooltip" data-original-title="Edit File" aria-expanded="false"><span class="fa fa-pencil-square-o"></span></button>';

			$button 	.= '<a href="Documentation/DownloadFile?file_name=' .$c['doc_name']. '&prop_id=' .$c['prop_id']. '" class="btn btn-warning btn-xs " rel="tooltip" data-original-title="Download File" aria-expanded="false"><span class="fa fa-cloud-download"></span></a>';

			$button 	.= '<button class="btn btn-danger btn-xs " data-title="Delete" rel="tooltip" data-original-title="Delete" data-toggle="modal" data-target="#deleteDocumentModal" data-doc-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-times"></span></button>';

			$button 	.= '<button rel="tooltip" data-original-title="Duplicate Documents" data-toggle="modal" data-target="#DuplicateDocumentsModal" data-doc-id="'.$c['id'].'" data-form-name="Documents"  class="btn btn-warning btn-xs ">	<span class="glyphicon glyphicon-copy"></span> </button>';


			$return[] 	= array(
				'document_type'  	=> $c['name'],
				'property_name'  	=> $c['propertyName'],
				'doc_name'  		=> $c['doc_name'],
				'created'  			=> $c['created'],
				'buttons' 			=> $button
				);
		}
		
		

		return $return;

	}

	static public function set_up_document_types () {

		$return 	 = array();

		$table   	 = self::$app_controller->get_all_doc_types();

		foreach ($table as $c) {

			$button 	= '<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#EditDocumentationTypesModal" data-doc-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-pencil-square-o"></span></button>';


			$button 	.= '<button class="btn btn-danger btn-xs " data-title="Delete" data-toggle="modal" data-target="#DeleteDocumentTypeModal" data-doc-id="'.$c['id'].'" aria-expanded="false"><span class="fa fa-times"></span></button>';


			$return[] 	= array(
				'document_name'  	=> $c['name'],
				'created'  			=> $c['created'],
				'buttons' 			=> $button
				);
		}
		
		

		return $return;

	}


	static public function set_get_image ($prop_id, $company_id){
		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

		$cpant 		= $dir.'/logo.jpg';
		$cdata 		= file_get_contents($cpant);
		$cbase64 	= 'data:image/jpg;base64,' . base64_encode($cdata);

		$epant 		= $dir.'/homeImage.jpg';
		$edata 		= file_get_contents($epant);
		$ebase64 	= 'data:image/jpg;base64,' . base64_encode($edata);

		// $ename 		= file_get_contents($dir.'/homeImage.jpg');

		// die($cbase64);

		return array('company_image' => $cbase64, 'estate_image' => $ebase64);
	}


	static public function upload_document ($company_id, $prop_id, $DocumentType, $UploadLogoFile) {

		if (!is_numeric($DocumentType)) {
			return array('status'  => false, 'text' => 'Please select document type');
		}

		if (!isset($UploadLogoFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadLogoFile["name"]);
		$ext 		= end($temp);

		// if ($ext !== 'jpg') {
		// 	return array('status'  => false, 'text' => 'Please upload only jpg file');
		// }

		$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

		self::$app_controller->created_directory($dir);

		$file_name  = self::$app_controller->upload_file ($UploadLogoFile, $dir);


		if ($file_name) {
			$save 	= self::$app_controller->insert_new_document (
										$prop_id,
										$file_name,
										$DocumentType
									);

			if ($save) {
				return array('status'  => true, 'text' => 'Document uploaded');
			}else{
				return array('status'  => false, 'text' => 'Failed to insert ' . $save);
			}

		}else{
			return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
		}

		
	}

	static public function save_document_type ($DocumentTypeName) {


		if (!self::$app_controller->validate_variables ($DocumentTypeName, 3)) {
			return array('status'  => false, 'text' => 'Please enter name');
		}

		$save 	= self::$app_controller->insert_new_document_category (
									$DocumentTypeName
								);

		if ($save) {
			return array('status'  => true, 'text' => 'Document Category Saved');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}

	static public function edit_document_type ($ID, $DocumentTypeName) {

		$doc 			= self::$app_controller->get_document_type_by_id ($ID);

		if (count($doc) !== 1) {
			return array('status'  => false, 'text' => 'Invalid record');
		}

		if (!self::$app_controller->validate_variables ($DocumentTypeName, 3)) {
			return array('status'  => false, 'text' => 'Please enter name');
		}

		$save 	= self::$app_controller->update_document_category (
									$ID,
									$DocumentTypeName
								);

		if ($save) {
			return array('status'  => true, 'text' => 'Document Category Saved');
		}else{
			return array('status'  => false, 'text' => 'Failed to insert ' . $save);
		}

		
	}

	static public function edit_document ($company_id, $prop_id, $ID, $DocumentType, $UploadLogoFile) {

		if (!is_numeric($DocumentType)) {
			return array('status'  => false, 'text' => 'Please select document type');
		}

		if (!isset($ID)) {
			return array('status'  => false, 'text' => 'Invalid ID');
		}


		if (isset($UploadLogoFile)) {

			$temp 		= explode(".", $UploadLogoFile["name"]);
			$ext 		= end($temp);
 
			$dir 		= '../companies/' . $company_id .'/properties/' . $prop_id;

			self::$app_controller->created_directory($dir);

			$file_name  = self::$app_controller->upload_file ($UploadLogoFile, $dir);


			if ($file_name) {
				$save 	= self::$app_controller->update_document_file (
											$ID,
											$prop_id,
											$file_name,
											$DocumentType
										);

				if ($save) {
					return array('status'  => true, 'text' => 'Document uploaded');
				}else{
					return array('status'  => false, 'text' => 'Failed to insert ' . $save);
				}

			}else{
				return array('status'  => false, 'text' => 'File not uploaded: ' .$upload);
			}


		}else{
			$save 	= self::$app_controller->update_document (
										$ID,
										$prop_id,
										$DocumentType
									);

			if ($save) {
				return array('status'  => true, 'text' => 'Document updated');
			}else{
				return array('status'  => false, 'text' => 'Failed to insert ' . $save);
			}
		}

		
	}

	static public function upload_estate_logo ($company_id, $prop_id, $UploadLogoFile) {

		if (!isset($UploadLogoFile)) {
			return array('status'  => false, 'text' => 'Please select file');
		}

		$temp 		= explode(".", $UploadLogoFile["name"]);
		$ext 		= end($temp);

		// if ($ext !== 'jpg') {
		// 	return array('status'  => false, 'text' => 'Please upload only jpg file');
		// }

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

	static public function dowload_file ($file_name, $company_id, $prop_id) {
		$file_url 			= '../companies/' .$company_id. '/properties/' . $prop_id .'/'. $file_name;

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		readfile($file_url);
	}


	static public function duplicate_document ($company_id, $prop_id, $DocumentID, $PropertyName) {
		
		if (!is_numeric($PropertyName)) {
			return array('status'  => false, 'text' => 'Invalid Property');
		}

		if (!is_numeric($DocumentID)) {
			return array('status'  => false, 'text' => 'Invalid Document ID');
		}


		$dir 		  = '../companies/' . $company_id .'/properties/' . $prop_id;
		$dir_d 		  = '../companies/' . $company_id .'/properties/' . $PropertyName;

		self::$app_controller->created_directory($dir_d);

		$doc 		  = self::$app_controller->get_document_by_id ($DocumentID);

		foreach ($doc as $d) {
			$doc_name = $d['doc_name'];
		}

		$source_file  = $dir.'/'.$doc_name;
		$dest_file    = $dir_d.'/'.$doc_name;

		$copy 		  = copy($source_file, $dest_file);

		
		$save 		  = self::$app_controller->copy_document ($DocumentID, $PropertyName);
		
		if ($save === true) {
			return array('status' => true, 'text' => 'Copied', 'copied' => $copy, 'saved' => $save );
		}else{
			return array('status' => false, 'text' => 'Failed to insert, ' . $save);
		}
	}

}