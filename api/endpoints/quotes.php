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

						$quotes = zeroBS_getQuotes(true, 20);
		echo json_encode($quotes);
		exit();

	}

	exit();

?>