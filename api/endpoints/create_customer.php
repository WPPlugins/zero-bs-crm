<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V2.0
 *
 * Copyright 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 06/04/17
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;


	if (!zeroBSCRM_API_is_zbs_api_authorised()){

		   		   zeroBSCRM_API_AccessDenied(); 
		   exit();

	} else {

		$json_params = file_get_contents("php://input");
		$new_customer = json_decode($json_params,true);

		$fname 		= sanitize_text_field($new_customer['fname']);
		$lname 		= sanitize_text_field($new_customer['lname']);
		$email 		= sanitize_text_field($new_customer['email']);
		$status 	= sanitize_text_field($new_customer['status']);
		$prefix     = sanitize_text_field($new_customer['prefix']);

		$addr1 		= sanitize_text_field($new_customer['addr1']);
		$addr2 		= sanitize_text_field($new_customer['addr2']);
		$city 		= sanitize_text_field($new_customer['city']);
		$county     = sanitize_text_field($new_customer['county']);
		$post       = sanitize_text_field($new_customer['post']);

		$hometel    = sanitize_text_field($new_customer['hometel']);
		$worktel    = sanitize_text_field($new_customer['worktel']);
		$mobtel     = sanitize_text_field($new_customer['mobtel']);
		$notes      = sanitize_text_field($new_customer['notes']);

				$custom_fields_array = array();

				foreach($new_customer as $new_customer_fieldK => $new_customer_fieldV){
				if (substr($new_customer_fieldK,0,2) == "cf"){
					$cf_indexname = 'zbsc_' . $new_customer_fieldK;
					$custom_fields_array[$cf_indexname] =  sanitize_text_field($new_customer_fieldV);
				}
		}


		
											$existingUserAPISourceShort = 'Updated by API Action <i class="fa fa-random"></i>';
				$existingUserAPISourceLong = 'API Action fired to update customer';

								$newUserAPISourceShort = 'Created from API Action <i class="fa fa-random"></i>';
				$newUserAPISourceLong = 'API Action fired to create customer';


						if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'Zapier'){
				
												$existingUserAPISourceShort = 'Updated by Zapier (API) <i class="fa fa-random"></i>';
				$existingUserAPISourceLong = 'Zapier fired an API Action to update this customer';

								$newUserAPISourceShort = 'Created by Zapier (API) <i class="fa fa-random"></i>';
				$newUserAPISourceLong = 'Zapier fired an API Action to create this customer';

			}

								$fallBackLog = array(
							'type' => 'API Action',
							'shortdesc' => $existingUserAPISourceShort,
							'longdesc' => $existingUserAPISourceLong
						);

								$internalAutomatorOverride = array(

							'note_override' => array(
						
										'type' => 'API Action',
										'shortdesc' => $newUserAPISourceShort,
										'longdesc' => $newUserAPISourceLong				

							)

						);


				if (!empty($email) && zeroBSCRM_validateEmail($email)){
					   

			$customer_array = array(
		    	'zbsc_email' => $email,
		    	'zbsc_status' => $status,
		    	'zbsc_prefix' => $prefix,
		    	'zbsc_fname' => $fname,
		    	'zbsc_lname' => $lname,
		    	'zbsc_addr1' => $addr1,
		    	'zbsc_addr2' => $addr2,
		    	'zbsc_city' => $city,
		    	'zbsc_county' => $county,
		    	'zbsc_postcode' => $post,
		    	'zbsc_hometel' => $hometel,
		    	'zbsc_worktel' => $worktel,
		    	'zbsc_mobtel' => $mobtel,
		    	'zbsc_notes' => $notes
		    );

			$update_args = array_merge($customer_array, $custom_fields_array);


			$newCust = zeroBS_integrations_addOrUpdateCustomer('api',$email,$update_args,

		    '', 			
						$fallBackLog,

			false, 
						$internalAutomatorOverride
			);
		    wp_send_json($json_params);  
		}

	}
	
	exit();

?>