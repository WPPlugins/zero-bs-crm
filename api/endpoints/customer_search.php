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

		$zbs_cust_search =  sanitize_text_field($_GET['zbs_query']);
		$customer_matches = zeroBS_integrations_searchCustomers($zbs_cust_search);
		
		wp_send_json($customer_matches);

	}

	exit();

?>