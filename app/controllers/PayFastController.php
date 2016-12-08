<?php
/**
 * Pay Fast Controller
 * 
 * @package 
 * @author  
 */
class PayFastController
{

	const PAYFAST_SERVER 			 = 'LIVE';
	const USER_AGENT 	 			 = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)';// User Agent for cURL

	const PF_ERR_AMOUNT_MISMATCH 	 = 'Amount mismatch';// User Agent for cURL
	const PF_ERR_BAD_SOURCE_IP 	 	 = 'Bad source IP address';// User Agent for cURL
	const PF_ERR_CONNECT_FAILED	 	 = 'Failed to connect to PayFast';// User Agent for cURL
	const PF_ERR_BAD_ACCESS 	 	 = 'Bad access of page';// User Agent for cURL
	const PF_ERR_INVALID_SIGNATURE 	 = 'Security signature mismatch';// User Agent for cURL
	const PF_ERR_CURL_ERROR 	 	 = 'An error occurred executing cURL';// User Agent for cURL
	const PF_ERR_INVALID_DATA 	 	 = 'The data received is invalid';// User Agent for cURL
	const PF_ERR_UKNOWN 	 		 = 'Unkown error occurred';// User Agent for cURL



	public function capture_payment ($post_variables) {
		// Notify PayFast that information has been received
		header( 'HTTP/1.0 200 OK' );
		flush();

		// Variable initialization
		$pfError 		= false;
		$pfErrMsg 		= '';
		$filename 		= 'notify.txt'; // DEBUG
		$output 		= ''; // DEBUG
		$pfParamString 	= '';
		$pfHost 		= ( self::PAYFAST_SERVER == 'LIVE' ) ?
		 'www.payfast.co.za' : 'sandbox.payfast.co.za';

		 //// Dump the submitted variables and calculate security signature
		 if( !$pfError ) {
			 $output = "Posted Variables:\n\n"; // DEBUG
		  
			 // Strip any slashes in data
			 foreach( $post_variables as $key => $val )
				 $pfData[$key] = stripslashes( $val );
		  
			 // Dump the submitted variables and calculate security signature

			 foreach( $pfData as $key => $val ) {

				if( $key != 'signature'){

					if ($key == 'return_url' OR $key == 'cancel_url' OR $key == 'notify_url') {
						$pfParamString .= $key .'='. rawurlencode($val) .'&';// Do not url encode link
					}else{
						$pfParamString .= $key .'='. urlencode( $val ) .'&';
					}
				}
			 }


		  
			 // Remove the last '&' from the parameter string
			 $pfParamString = substr( $pfParamString, 0, -1 );
			 $pfTempParamString = $pfParamString;

			 // If a passphrase has been set in the PayFast Settings, then it needs to be included in the signature string.
			 $passPhrase = 'XXXXX'; //You need to get this from a constant or stored in you website
			 if( !empty( $passPhrase ) ) {
				 $pfTempParamString .= '&passphrase='.urlencode( $passPhrase );
			 }

			 // die(var_dump($pfTempParamString));

			 $signature = md5( $pfTempParamString );
		  
			 $result = ( $post_variables['signature'] == $signature );

			 // die(var_dump($post_variables['signature'));
		  
			 $output .= "Security Signature:\n\n"; // DEBUG
			 $output .= "- posted     = ". $post_variables['signature'] ."\n"; // DEBUG
			 $output .= "- calculated = ". $signature ."\n"; // DEBUG
			 $output .= "- result     = ". ( $result ? 'SUCCESS' : 'FAILURE' ) ."\n"; // DEBUG
		 }

		//// Verify source IP
		if( !$pfError ) {
		    $validHosts = array(
		        'www.payfast.co.za',
		        'sandbox.payfast.co.za',
		        'w1w.payfast.co.za',
		        'w2w.payfast.co.za',
		        '196.213.87.162',
		        );
		 
		    $validIps = array();
		 
		    foreach( $validHosts as $pfHostname ) {
		        $ips = gethostbynamel( $pfHostname );
		 
		        if( $ips !== false )
		            $validIps = array_merge( $validIps, $ips );
		    }
		 
		    // Remove duplicates
		    $validIps = array_unique( $validIps );
		 
		    if( !in_array( $_SERVER['REMOTE_ADDR'], $validIps ) ) {
		        $pfError = true;
		        $pfErrMsg = self::PF_ERR_BAD_SOURCE_IP;
		    }
		}


		
		 
		//// Connect to server to validate data received
		if( !$pfError ) {
		    // Use cURL (If it's available)
		    // if( function_exists( 'curl_init' ) ) {
		    //     $output .= "\n\nUsing cURL\n\n"; // DEBUG
		 
		    //     // Create default cURL object
		    //     $ch = curl_init();
		 
		    //     // Base settings
		    //     $curlOpts = array(
		    //         // Base options
		    //         CURLOPT_USERAGENT => self::USER_AGENT, // Set user agent
		    //         CURLOPT_RETURNTRANSFER => true,  // Return output as string rather than outputting it
		    //         CURLOPT_HEADER => false,         // Don't include header in output
		    //         CURLOPT_SSL_VERIFYHOST => true,
		    //         CURLOPT_SSL_VERIFYPEER => false,
		 
		    //         // Standard settings
		    //         CURLOPT_URL => 'https://'. $pfHost . '/eng/process',
		    //         CURLOPT_POST => true,
		    //         CURLOPT_POSTFIELDS => $pfParamString,
		    //     );
		    //     curl_setopt_array( $ch, $curlOpts );
		 
		    //     // Execute CURL
		    //     $res = curl_exec( $ch );
		    //     curl_close( $ch );
		 
		    //     if( $res === false ) {
		    //         $pfError = true;
		    //         $pfErrMsg = self::PF_ERR_CURL_ERROR;
		    //     }
		    //     // die(var_dump('https://'. $pfHost . '/eng/process?'.$pfParamString));
		    // }

		    // // Use fsockopen
		    // else {
		    //     $output .= "\n\nUsing fsockopen\n\n"; // DEBUG
		 
		    //     // Construct Header
		    //     $header = "POST /eng/process HTTP/1.0\r\n";
		    //     $header .= "Host: ". $pfHost ."\r\n";
		    //     $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		    //     $header .= "Content-Length: " . strlen( $pfParamString ) . "\r\n\r\n";
		 
		    //     // Connect to server
		    //     $socket = fsockopen( 'ssl://'. $pfHost, 443, $errno, $errstr, 10 );
		 
		    //     // Send command to server
		    //     fputs( $socket, $header . $pfParamString );
		 
		    //     // Read the response from the server
		    //     $res = '';
		    //     $headerDone = false;
		 
		    //     while( !feof( $socket ) ) {
		    //         $line = fgets( $socket, 1024 );
		 
		    //         // Check if we are finished reading the header yet
		    //         if( strcmp( $line, "\r\n" ) == 0 ) {
		    //             // read the header
		    //             $headerDone = true;
		    //         }
		    //         // If header has been processed
		    //         else if( $headerDone ) {
		    //             // Read the main response
		    //             $res .= $line;
		    //         }
		    //     }
		    // }

		    $return_link = 'https://'. $pfHost . '/eng/process?'.$pfParamString;
		}
		 
		// //// Get data from server
		// if( !$pfError ) {
		//     // Parse the returned data
		//     $lines = explode( "\n", $res );
		 
		//     $output .= "\n\nValidate response from server:\n\n"; // DEBUG
		 
		//     foreach( $lines as $line ) // DEBUG
		//         $output .= $line ."\n"; // DEBUG
		// }
		 
		// //// Interpret the response from server
		// if( !$pfError ) {
		//     // Get the response from PayFast (VALID or INVALID)
		//     $result = trim( $lines[0] );
		 
		//     $output .= "\nResult = ". $result; // DEBUG
		 
		//     // If the transaction was valid
		//     if( strcmp( $result, 'VALID' ) == 0 ) {
		//         // Process as required
		//     }
		//     // If the transaction was NOT valid
		//     else {
		//         // Log for investigation
		//         $pfError = true;
		//         $pfErrMsg = self::PF_ERR_INVALID_DATA;
		//     }
		// }
		 
		// If an error occurred
		if( $pfError ) {

			return array('status' => false, 'text' => 'An error occurred: '.$pfErrMsg);
		    // $output .= "\n\nAn error occurred!";
		    // $output .= "\nError = ". $pfErrMsg;
		}else{
			return array('status' => true, 'text' => $return_link);
		 
		// //// Write output to file // DEBUG
		// file_put_contents( $filename, $output ); // DEBUG

		// return $output;
		}

	}
}