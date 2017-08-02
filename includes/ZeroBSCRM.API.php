<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V2.0
 *
 * Copyright 2016 - 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 05/04/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;



add_rewrite_endpoint( 'zbs_api', EP_ROOT );    



function zeroBSCRM_API_AccessDenied(){

		echo zeroBSCRM_API_error('Access Denied',403);
	exit();
	
}

function zeroBSCRM_API_error($errorMsg='Error',$headerCode=400){

		
	status_header( $headerCode );
	echo '{"error":"'.$errorMsg.'"}';
	exit();
	
}




function zeroBSCRM_API_locate_api_endpoint( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) :
		$template_path = 'zerobscrm-plugin-templates/';
	endif;
		if ( ! $default_path ) :
		$default_path = ZEROBSCRM_PATH . 'api/endpoints/'; 	endif;
		$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
		if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'zeroBSCRM_API_locate_api_endpoint', $template, $template_name, $template_path, $default_path );
}


function zeroBSCRM_API_get_api_endpoint( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = zeroBSCRM_API_locate_api_endpoint( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}

function zeroBSCRM_API_is_zbs_api_authorised(){

		
	$zbsAPIKey = ''; $zbsAPISecret = ''; 
	if (isset($_GET['api_key'])) $zbsAPIkey = sanitize_text_field($_GET['api_key']);    	if (isset($_GET['api_secret'])) $zbsAPISecret = sanitize_text_field($_GET['api_secret']);
	
		$APIkey_req = $zbsAPIkey;
	$APIkey_allowed = zeroBSCRM_getAPIKey();


	if(hash_equals($APIkey_req, $APIkey_allowed)){   

		
		    	$api_secret = zeroBSCRM_getAPISecret();
    
    	if(hash_equals($zbsAPISecret, $api_secret)) return true;	
	}
	

		return false;	

}

function zeroBSCRM_getAPIEndpoint(){
  return site_url( '/zbs_api/'); }

function zeroBSCRM_API_generate_api_key(){
	$api_key = zeroBSCRM_API_random_hash(22); 	return $api_key;
}

function zeroBSCRM_API_random_hash($str_length = 22) {
  $third = wp_generate_password($str_length);
  return 'zbscrm_' . $third;

}





add_filter( 'template_include', 'zeroBSCRM_API_api_endpoint', 99 );

function zeroBSCRM_API_api_endpoint( $template ) {

	$zbsAPIQuery = get_query_var( 'zbs_api' );

	
			if (isset($zbsAPIQuery) && !empty($zbsAPIQuery)){

				if (strpos($zbsAPIQuery,'/'))
			$zbsAPIRequest = explode('/',$zbsAPIQuery);
		else
						$zbsAPIRequest = array($zbsAPIQuery);


		zbs_write_log($zbsAPIRequest);

		

				if (!empty($zbsAPIRequest[0]) && $zbsAPIRequest[0] == 'create_customer'){

									if(!zeroBSCRM_API_is_zbs_api_authorised()){
				return zeroBSCRM_API_get_api_endpoint('denied.php');
			}else{
				return zeroBSCRM_API_get_api_endpoint('create_customer.php');
			}

		}
				else if (!empty($zbsAPIRequest[0]) && $zbsAPIRequest[0] == 'create_transaction'){

									if(!zeroBSCRM_API_is_zbs_api_authorised()){
				return zeroBSCRM_API_get_api_endpoint('denied.php');
			}else{
				return zeroBSCRM_API_get_api_endpoint('create_transaction.php');
			}

		}
		else if (!empty($zbsAPIRequest[0]) && $zbsAPIRequest[0] == 'customer_search'){

									if(!zeroBSCRM_API_is_zbs_api_authorised()){
				return zeroBSCRM_API_get_api_endpoint('denied.php');
			}else{
				return zeroBSCRM_API_get_api_endpoint('customer_search.php');
			}

		}else {

			
						if ( !empty( $zbsAPIRequest[0] ) ) {

								if ( $zbsAPIRequest[0] == 'customers' ) {
					if(!zeroBSCRM_API_is_zbs_api_authorised()){
						return zeroBSCRM_API_get_api_endpoint('denied.php');
					}else{
						return zeroBSCRM_API_get_api_endpoint('customers.php');
					}
				}

				if ( $zbsAPIRequest[0] == 'invoices' ) {
					if(!zeroBSCRM_API_is_zbs_api_authorised()){
						return zeroBSCRM_API_get_api_endpoint('denied.php');
					}else{
						return zeroBSCRM_API_get_api_endpoint('invoices.php');
					}
				}
	
				if ( $zbsAPIRequest[0] == 'quotes' ) {
					if(!zeroBSCRM_API_is_zbs_api_authorised()){
						return zeroBSCRM_API_get_api_endpoint('denied.php');
					}else{
						return zeroBSCRM_API_get_api_endpoint('quotes.php');
					}
				}
				if ( $zbsAPIRequest[0] == 'transactions' ) {
					if(!zeroBSCRM_API_is_zbs_api_authorised()){
						return zeroBSCRM_API_get_api_endpoint('denied.php');
					}else{
						return zeroBSCRM_API_get_api_endpoint('transactions.php');
					}
				}


			}

		} 
	}

	return $template;
}


if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
  }
}





define('ZBSCRM_INC_API',true);