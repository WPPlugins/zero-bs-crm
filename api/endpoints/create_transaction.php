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
		$new_trans = json_decode($json_params,true);

				$orderid	= sanitize_text_field($new_trans['orderid']);

		$email 		= sanitize_text_field($new_trans['email']);
		$customer 	= zeroBS_getCustomerIDWithEmail($email);
		$fname 		= sanitize_text_field($new_trans['fname']);

		if ($customer == ''){

						
			
				
					
												$newUserAPISourceShort = 'Created from API Action <i class="fa fa-random"></i>';
						$newUserAPISourceLong = 'API Action fired to create customer (New Transaction)';


												if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'Zapier'){
							
														
														$newUserAPISourceShort = 'Created by Zapier (API) <i class="fa fa-random"></i>';
							$newUserAPISourceLong = 'Zapier fired an API Action to create this customer (New Transaction)';

						}

												$fallBackLog = array();

												$internalAutomatorOverride = array(

									'note_override' => array(
								
												'type' => 'API Action',
												'shortdesc' => $newUserAPISourceShort,
												'longdesc' => $newUserAPISourceLong				

									)

								);

			$customer = zeroBS_integrations_addOrUpdateCustomer('api',$email,
				array(

			    	'zbsc_email' => $email,
		    		'zbsc_status' => 'Customer',
			    	'zbsc_fname' => $fname,

			    ),

			    '', 				
								$fallBackLog,

				false, 
								$internalAutomatorOverride
			);

		}


				$status	    = sanitize_text_field($new_trans['status']);
		$total	    = sanitize_text_field($new_trans['total']);
		$item_title = sanitize_text_field($new_trans['item_title']);
		$net 		= sanitize_text_field($new_trans['net']);
		$tax 		= sanitize_text_field($new_trans['tax']);
		$fee 		= sanitize_text_field($new_trans['fee']);
		$disc  		= sanitize_text_field($new_trans['disc']);
		$rate 		= sanitize_text_field($new_trans['tax_rate']);


		$tFields = array(
			'orderid' 	=> $orderid,
			'customer' 	=> $customer,
			'status' 	=> $status, 
			'total' 	=> $total,

			'item' 		=> $item_title,
			'net' 		=> $net,
			'tax' 		=> $tax,
			'fee' 		=> $fee,
			'discount' 	=> $disc,
			'tax_rate' 	=> $rate,
		);

						if (!empty($orderid)){

			
														$existingTransactionAPISourceShort = 'Transaction Updated by API Action <i class="fa fa-random"></i>';
					$existingTransactionAPISourceLong = 'API Action fired to update a transaction: #'.$orderid.' for '.zeroBSCRM_getCurrencyChr().$total.' (Status: '.$status.')';

										$newTransactionAPISourceShort = 'Transaction Created from API Action <i class="fa fa-random"></i>';
					$newTransactionAPISourceLong = 'API Action fired to create a transaction: #'.$orderid.' for '.zeroBSCRM_getCurrencyChr().$total.' (Status: '.$status.')';


								if (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'Zapier'){
					
															$existingTransactionAPISourceShort = 'Transaction Updated by Zapier <i class="fa fa-random"></i>';
					$existingTransactionAPISourceLong = 'Zapier fired an API Action to update a transaction: #'.$orderid.' for '.zeroBSCRM_getCurrencyChr().$total.' (Status: '.$status.')';

										$newTransactionAPISourceShort = 'Transaction Added by Zapier <i class="fa fa-random"></i>';
					$newTransactionAPISourceLong = 'Zapier fired an API Action to add a transaction: #'.$orderid.' for '.zeroBSCRM_getCurrencyChr().$total.' (Status: '.$status.')';

				}

										$fallBackLog = array(
								'type' => 'API Action',
								'shortdesc' => $existingTransactionAPISourceShort,
								'longdesc' => $existingTransactionAPISourceLong
							);

										$internalAutomatorOverride = array(

								'note_override' => array(
							
											'type' => 'API Action',
											'shortdesc' => $newTransactionAPISourceShort,
											'longdesc' => $newTransactionAPISourceLong				

								)

						);

		


			$trans = zeroBS_integrations_addOrUpdateTransaction('api',$orderid, $tFields,
				array(), 
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