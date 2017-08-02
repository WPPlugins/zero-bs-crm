<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.20
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 01/11/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;







      function zeroBS_getOwnerEmail($postID=-1){

   		if ($postID !== -1){

   			   			$userID = get_post_field( 'post_author', $postID );

   			if (!empty($userID)){

	   				        	return get_the_author_meta( 'user_email', $userID );

	        }

   		}

   		return false;

   }

       function zeroBS_getQuoteStatus( $item ) {

                $acceptedArr = false;
        if (isset($item['meta']) && isset($item['meta']['accepted'])) $acceptedArr = $item['meta']['accepted'];

                                
        if (is_array($acceptedArr)){

            $td = '<strong>'.__w('Accepted','zerobscrm').' ' . date(zeroBSCRM_getDateFormat(),$acceptedArr[0]) . '</strong>';

        } else {
                
                        $zbsTemplated = get_post_meta($item['id'], 'templated', true);
            if (!empty($zbsTemplated)) {

                                $td = '<strong>'.__w('Created, not yet accepted','zerobscrm').'</strong>';

            } else {

                                $td = '<strong>'.__w('Not yet published','zerobscrm').'</strong>';

            }


        }


        return $td;
    }



		function zeroBSCRM_getSetting($key){

		global $zeroBSCRM_Settings, $zeroBSCRM_Conf_Setup;

		if (!isset($zeroBSCRM_Settings)) $zeroBSCRM_Settings = new WHWPConfigLib($zeroBSCRM_Conf_Setup);

		return $zeroBSCRM_Settings->get($key);

	}

	function zeroBSCRM_getNextQuoteID(){

				$defaultStartingQuoteID = zeroBSCRM_getQuoteOffset();

				return (int)get_option('quoteindx',$defaultStartingQuoteID)+1;

	}

	function zeroBSCRM_setMaxQuoteID($newMax=0){

		$existingMax = zeroBSCRM_getNextQuoteID();

		if ($newMax >= $existingMax){

						return (int)update_option('quoteindx',$newMax);

		}

		return false;
	}

	function zeroBSCRM_getNextInvoiceID(){

				$defaultStartingInvoiceID = zeroBSCRM_getInvoiceOffset();

				return (int)get_option('invoiceindx',$defaultStartingInvoiceID)+1;

	}

	function zeroBSCRM_setMaxInvoiceID($newMax=0){

		$existingMax = zeroBSCRM_getNextInvoiceID();

		if ($newMax >= $existingMax){

						return (int)update_option('invoiceindx',$newMax);

		}

		return false;

	}

		function zeroBSCRM_getQuoteOffset(){

		global $zeroBSCRM_Settings;
		$offset = (int)$zeroBSCRM_Settings->get('quoteoffset');

		if (empty($offset) || $offset < 0) $offset = 0;

		return $offset;

	}

		function zeroBSCRM_getInvoiceOffset(){

		global $zeroBSCRM_Settings;
		$offset = (int)$zeroBSCRM_Settings->get('invoffset');

		if (empty($offset) || $offset < 0) $offset = 0;

		return $offset;

	}

		function zeroBSCRM_getCurrencyChr(){

			    $theCurrency = zeroBSCRM_getSetting('currency');
	    $theCurrencyChar = '&pound;';
	    if (isset($theCurrency) && isset($theCurrency['chr'])) {
	        
	        $theCurrencyChar = $theCurrency['chr']; 

	    }

	    return $theCurrencyChar;
	}
	function zeroBSCRM_getCurrencyStr(){

			    $theCurrency = zeroBSCRM_getSetting('currency');
	    $theCurrencyStr = 'GBP';
	    if (isset($theCurrency) && isset($theCurrency['chr'])) {
	        
	        $theCurrencyStr = $theCurrency['strval'];

	    }

	    return $theCurrencyStr;
	}
	function zeroBSCRM_getTimezoneOffset(){

		return get_option('gmt_offset');

	}
	function zeroBSCRM_getCurrentTime(){
		return current_time();
	}
	function zeroBSCRM_getContactOrCustomer(){

			    $b2bMode = zeroBSCRM_getSetting('companylevelcustomers');
	    $theStr = 'Customer'; if ($b2bMode) $theStr = 'Contact';

	    return $theStr;
	}
	function zeroBSCRM_getCompanyOrOrg(){

			    $coOrOrg = zeroBSCRM_getSetting('coororg');
	    $theStr = 'Company'; if ($coOrOrg == 'org') $theStr = 'Organisation';

	    return $theStr;
	}
	function zeroBSCRM_getCompanyOrOrgPlural(){

			    $coOrOrg = zeroBSCRM_getSetting('coororg');
	    $theStr = 'Companies'; if ($coOrOrg == 'org') $theStr = 'Organisations';

	    return $theStr;
	}
	
	function zeroBS_getCustomerCount($companyID=false){

		if ($companyID){

				        return count(zeroBS_getCustomers(false,1000,0,false,false,'',false,false,$companyID));


		} else {

			
			$counts = wp_count_posts('zerobs_customer');

			if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

			return 0;

		}
	}









function zeroBS_addUpdateCustomer(

		$cID = -1,

		

		$cFields = array(),

		$externalSource='',
		$externalID='',
		$customerDate='',

		$fallBackLog = false,
		$extraMeta = false,
		$automatorPassthrough = false

		){


		$ret = false;


			if (isset($cFields) && count($cFields) > 0){ 
				$newCustomer = false;


			if ($cID > 0){

																$postID = $cID;

								$existingMeta = zeroBS_getCustomerMeta($postID);

			} else {

								$newCustomer = true;

								$headerPost = array(
										'post_type' => 'zerobs_customer',
										'post_status' => 'publish',
										'comment_status' => 'closed'
									);

								if (!empty($customerDate)) $headerPost['post_date'] = $customerDate;

								$postID = wp_insert_post($headerPost);

								$existingMeta = array();

			}

						if (isset($existingMeta) && is_array($existingMeta) && !isset($existingMeta['zbsc_status'])) $existingMeta['zbsc_status'] = 'Lead';

						global $zbscrmApprovedExternalSources;

						$approvedExternalSource = ''; 			if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

								$approvedExternalSource = $externalSource;

				                update_post_meta($postID, 'zbs_customer_ext_'.$approvedExternalSource, $externalID);

			} 
						if (!empty($postID)){

								$zbsCustomerMeta = zeroBS_buildCustomerMeta($cFields,$existingMeta);

								if (!isset($zbsCustomerMeta['zbsc_status']) || empty($zbsCustomerMeta['zbsc_status'])){

					$defaultStatusStr = zeroBSCRM_getSetting('defaultstatus');

					if (!empty($defaultStatusStr)) $zbsCustomerMeta['zbsc_status'] = $defaultStatusStr;

				}

				                update_post_meta($postID, 'zbs_customer_meta', $zbsCustomerMeta);

                                if (isset($extraMeta) && is_array($extraMeta)) foreach ($extraMeta as $k => $v){

                	                	$cleanKey = strtolower(str_replace(' ','_',$k));

                	                	update_post_meta($postID, 'zbs_customer_extra_'.$cleanKey, $v);

                }

			}

												if ($newCustomer){

								zeroBSCRM_FireInternalAutomator('customer.new',array(
	                'id'=>$postID,
	                'customerMeta'=>$zbsCustomerMeta,
	                'extsource'=>$approvedExternalSource,
	                'automatorpassthrough'=>$automatorPassthrough 	            ));

			} else {

								

																				if (
					isset($fallBackLog) && is_array($fallBackLog) 
					&& isset($fallBackLog['type']) && !empty($fallBackLog['type'])
					&& isset($fallBackLog['shortdesc']) && !empty($fallBackLog['shortdesc'])
				){

					
										$zbsNoteLongDesc = ''; if (isset($fallBackLog['longdesc']) && !empty($fallBackLog['longdesc'])) $zbsNoteLongDesc = $fallBackLog['longdesc'];

												$newOrUpdatedLogID = zeroBS_addUpdateLog($postID,-1,-1,array(
														'type' => $fallBackLog['type'],
							'shortdesc' => $fallBackLog['shortdesc'],
							'longdesc' => $zbsNoteLongDesc
						));


				}


			}




							        

						$ret = $postID;

			do_action('zbs_new_customer', $postID);   

	}



	return $ret;

}



function zeroBS_addUpdateCompany(

		$coID = -1,
		$coFields = array(),
		$externalSource='',
		$externalID='',
		$companyDate='',
		$fallBackLog = false,
		$extraMeta = false,
		$automatorPassthrough = false

		){


		$ret = false;


			if (isset($coFields) && count($coFields) > 0){ 
				$newCompany = false;


			if ($coID > 0){

																$postID = $coID;

								$existingMeta = zeroBS_getCompanyMeta($postID);

			} else {

								$newCompany = true;

								$headerPost = array(
										'post_type' => 'zerobs_company',
										'post_status' => 'publish',
										'comment_status' => 'closed'
									);

								if (!empty($companyDate)) $headerPost['post_date'] = $companyDate;

								$postID = wp_insert_post($headerPost);

								$existingMeta = array();

			}

						if (isset($existingMeta) && is_array($existingMeta) && !isset($existingMeta['zbsc_status'])) $existingMeta['zbsc_status'] = 'Lead';

						global $zbscrmApprovedExternalSources;

						$approvedExternalSource = ''; 			if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

								$approvedExternalSource = $externalSource;

				                update_post_meta($postID, 'zbs_company_ext_'.$approvedExternalSource, $externalID);

			} 
						if (!empty($postID)){

								$zbsCompanyMeta = zeroBS_buildCompanyMeta($coFields,$existingMeta);

				                update_post_meta($postID, 'zbs_company_meta', $zbsCompanyMeta);

                                if (isset($extraMeta) && is_array($extraMeta)) foreach ($extraMeta as $k => $v){

                	                	$cleanKey = strtolower(str_replace(' ','_',$k));

                	                	update_post_meta($postID, 'zbs_company_extra_'.$cleanKey, $v);

                }

			}

												if ($newCompany){

								zeroBSCRM_FireInternalAutomator('company.new',array(
	                'id'=>$postID,
	                'companyMeta'=>$zbsCompanyMeta,
	                'extsource'=>$approvedExternalSource,
	                'automatorpassthrough'=>$automatorPassthrough 	            ));

			} else {

								

																				if (
					isset($fallBackLog) && is_array($fallBackLog) 
					&& isset($fallBackLog['type']) && !empty($fallBackLog['type'])
					&& isset($fallBackLog['shortdesc']) && !empty($fallBackLog['shortdesc'])
				){

					
										$zbsNoteLongDesc = ''; if (isset($fallBackLog['longdesc']) && !empty($fallBackLog['longdesc'])) $zbsNoteLongDesc = $fallBackLog['longdesc'];

												$newOrUpdatedLogID = zeroBS_addUpdateLog($postID,-1,-1,array(
														'type' => $fallBackLog['type'],
							'shortdesc' => $fallBackLog['shortdesc'],
							'longdesc' => $zbsNoteLongDesc
						));


				}


			}




							        

						$ret = $postID;


	}



	return $ret;

}

function zbsCRM_addUpdateCustomerCompany($customerID=-1,$companyID=-1){

	if ($customerID > 0){

				return update_post_meta((int)$customerID,'zbs_company',(int)$companyID);		

	}

	return false;

}


function zeroBS_buildCustomerMeta($arraySource=array(),$startingArray=array()){

		$zbsCustomerMeta = array();

		if (isset($startingArray) && is_array($startingArray)) $zbsCustomerMeta = $startingArray;

	        global $zbsCustomerFields;

        $i=0;

        foreach ($zbsCustomerFields as $fK => $fV){
        	$i++;

            if (!isset($zbsCustomerMeta[$fK])) $zbsCustomerMeta[$fK] = '';

            if (isset($arraySource['zbsc_'.$fK])) {

                switch ($fV[0]){


                    case 'tel':

                                                $zbsCustomerMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);
                        preg_replace("/[^0-9 ]/", '', $zbsCustomerMeta[$fK]);
                        break;

                    case 'price':

                        $zbsCustomerMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);
                        $zbsCustomerMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsCustomerMeta[$fK]);
                        $zbsCustomerMeta[$fK] = floatval($zbsCustomerMeta[$fK]);
                        break;


                    case 'textarea':

                        $zbsCustomerMeta[$fK] = zeroBSCRM_textProcess($arraySource['zbsc_'.$fK]);

                        break;

                    case 'date':

                        $zbsCustomerMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);

                        break;


                    default:

                        $zbsCustomerMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);

                        break;


                }


            }


        }

    return $zbsCustomerMeta;
}
function zeroBS_buildCompanyMeta($arraySource=array()){

	$zbsCompanyMeta = array();

        global $zbsCompanyFields;

        foreach ($zbsCompanyFields as $fK => $fV){

            $zbsCompanyMeta[$fK] = '';

            if (isset($arraySource['zbsc_'.$fK])) {

                switch ($fV[0]){

                    case 'tel':

                                                $zbsCompanyMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);
                        preg_replace("/[^0-9 ]/", '', $zbsCompanyMeta[$fK]);
                        break;

                    case 'price':

                                                $zbsCompanyMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);
                        $zbsCompanyMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsCompanyMeta[$fK]);
                        $zbsCompanyMeta[$fK] = floatval($zbsCompanyMeta[$fK]);
                        break;


                    case 'textarea':

                        $zbsCompanyMeta[$fK] = zeroBSCRM_textProcess($arraySource['zbsc_'.$fK]);

                        break;


                    default:

                        $zbsCompanyMeta[$fK] = sanitize_text_field($arraySource['zbsc_'.$fK]);

                        break;


                }


            }


        }

    return $zbsCompanyMeta;
}




function zeroBS_addUpdateLog(

		$cID = -1,
		$logID = -1,
		$logDate = -1,

		

		$noteFields = array()

		){


		$ret = false;


		if (isset($cID) && $cID > 0 && isset($noteFields) && count($noteFields) > 0 && isset($noteFields['type']) && isset($noteFields['shortdesc'])){


						if (isset($logID) && !empty($logID) && $logID > 0){

								$logPostID = $logID;

										update_post_meta($logPostID,'zbs_log_meta',$noteFields);
					
			} else {

									if($logDate == -1){
					$logPostID = wp_insert_post(array(
						'post_type' => 'zerobs_log',
						'post_status' => 'publish',
						'comment_status' => 'closed',
						'page_title' => $noteFields['type'].': '.sanitize_text_field($noteFields['shortdesc'].substr(0,150))
					));			
					}else{
					$logPostID = wp_insert_post(array(
						'post_type' => 'zerobs_log',
						'post_status' => 'publish',
						'comment_status' => 'closed',
						'post_date'		=> $logDate,
						'page_title' => $noteFields['type'].': '.sanitize_text_field($noteFields['shortdesc'].substr(0,150))
					));							
					}
		

					if ($logPostID){

												update_post_meta($logPostID,'zbs_log_meta',$noteFields);
						update_post_meta($logPostID,'zbs_logowner',$cID);


					}


			}


						$ret = $logPostID;


	}



	return $ret;

}



function zeroBS_getCustomerExternalSource($cID=-1){

	$ret = array();

	if ($cID !== -1){

				global $zbscrmApprovedExternalSources;

		if (count($zbscrmApprovedExternalSources)) foreach ($zbscrmApprovedExternalSources as $srcKey => $srcDeet){
        
        	$possMeta = get_post_meta($cID,'zbs_customer_ext_'.$srcKey,true);

        	if (!empty($possMeta)){

        		        		$ret[$srcKey] = array($srcDeet[0],$possMeta);

        	}

        }

	} 


	return $ret;


}

function zeroBS_getCustomerIcoHTML($cID=-1){

	$thumbHTML = '<i class="fa fa-user" aria-hidden="true"></i>';

	if ($cID > 0 && has_post_thumbnail( $cID )){

								$thumb_urlArr = wp_get_attachment_image_src( get_post_thumbnail_id($cID, 'single-post-thumbnail'));
		$thumb_url = ''; if (is_array($thumb_urlArr)) $thumb_url = $thumb_urlArr[0];

		if (!empty($thumb_url)){

			$thumbHTML = '<img src="'.$thumb_url.'" alt="" />';

		}

	}

	return '<div class="zbs-co-img">'.$thumbHTML.'</div>';
}


function zeroBS_getCustomerMeta($cID=-1){

	if (!empty($cID)) return get_post_meta($cID, 'zbs_customer_meta', true);

	return false;

}


function zeroBS_getCustomerName($cID=-1){

	if (!empty($cID)) return get_the_title($cID);

	return false;
}

function zeroBS_getCustomerNameShort($cID=-1){

	if (!empty($cID)) return zeroBS_customerName($cID,false,false,false);

	return false;
}


function zeroBS_searchCustomers($querystring){

	
		$q1 = new WP_Query( array(
		    'post_type' => 'zerobs_customer',
		    'posts_per_page' => -1,
		    's' => $querystring
		));

		$q2 = new WP_Query( array(
		    'post_type' => 'zerobs_customer',
		    'posts_per_page' => -1,
		    'meta_query' => array(
		        array(
		           'key' => 'zbs_customer_meta',
		           'value' => $querystring,
		           'compare' => 'LIKE'
		        )
		     )
		));

		$result = new WP_Query();
		$result->posts = array_unique( array_merge( $q1->posts, $q2->posts ), SORT_REGULAR );

		$customer_matches = array();
		foreach($result->posts as $customer){
			
									$objToAdd = zeroBS_getCustomerMeta($customer->ID);
			$objToAdd['id'] = $customer->ID;

						$customer_matches[] = $objToAdd;

									
		}

		return $customer_matches;
}


function zeroBS_searchLogs($querystring){

	
		zbs_write_log("LOG QUERY STRING IS " . $querystring);

		$q1 = new WP_Query( array(
		    'post_type' => 'zerobs_log',
		    'posts_per_page' => 100,
		    's' => $querystring
		));

		$q2 = new WP_Query( array(
		    'post_type' => 'zerobs_log',
		    'posts_per_page' => 100,
		    'meta_query' => array(
		        array(
		           'key' => 'zbs_log_meta',
		           'value' => $querystring,
		           'compare' => 'LIKE'
		        )
		     )
		));

		$result = new WP_Query();
		$result->posts = array_unique( array_merge( $q1->posts, $q2->posts ), SORT_REGULAR );

		$customer_matches = array();
		foreach($result->posts as $logs){
			$customer_matches[] = zeroBSCRM_getLog($logs->ID);
		}

		return $customer_matches;
}

function zeroBS_allLogs(){

	
		$q1 = new WP_Query( array(
		    'post_type' => 'zerobs_log',
		    'posts_per_page' => 100
		));



		$result = new WP_Query();
		$result->posts = array_unique( $q1->posts, SORT_REGULAR );

		$customer_matches = array();
		foreach($result->posts as $logs){
			$customer_matches[] = zeroBSCRM_getLog($logs->ID);
		}

		return $customer_matches;
}

function zeroBS_getCustomer($cID=-1,$withInvoices=false,$withQuotes=false,$withTransactions=false){

	if ($cID !== -1){

		$retObj = array(
						'id'=>$cID,
						'meta'=>get_post_meta($cID, 'zbs_customer_meta', true),
						
												'created' => get_the_date('',$cID),
												'name' =>get_the_title($cID)
					);

		
						if ($withInvoices){
				
												$retObj['invoices'] 		= zeroBS_getInvoicesForCustomer($cID,true,100);

			}

						if ($withQuotes){
				
								$retObj['quotes'] 		= zeroBS_getQuotesForCustomer($cID,false,100);

			}

						if ($withTransactions){
				
								$retObj['transactions'] = zeroBS_getTransactionsForCustomer($cID,false,100);

			}
		
		return $retObj;

	} else return false;


}

function zeroBS_getCustomerIDWithEmail($custEmail=''){

	$ret = false;

		if (!empty($custEmail)){

				$args = array (
			'post_type'              => 'zerobs_customer',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',
			'meta_query' => array( 								array(
					'key' => 'zbs_customer_meta',
					'value' => sprintf(':"%s";', $custEmail), 
					'compare' => 'LIKE'
				)
			)

		);

		$potentialCustomerList = get_posts( $args );

		if (count($potentialCustomerList) > 0){

			if (isset($potentialCustomerList[0]) && isset($potentialCustomerList[0]->ID)){

				$ret = $potentialCustomerList[0]->ID;

			}

		}

	}


	return $ret;


}

function zeroBS_getCustomerIDWithExternalSource($externalSource='',$externalID=''){

	global $zbscrmApprovedExternalSources;

	$ret = false;

		if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

				$approvedExternalSource = $externalSource;

				$args = array (
			'post_type'              => 'zerobs_customer',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

			   'meta_key'   => 'zbs_customer_ext_'.$approvedExternalSource,
			   'meta_value' => $externalID
		);

		$potentialCustomerList = get_posts( $args );

		if (count($potentialCustomerList) > 0){

			if (isset($potentialCustomerList[0]) && isset($potentialCustomerList[0]->ID)){

				$ret = $potentialCustomerList[0]->ID;

			}

		}

	}


	return $ret;

}

function zeroBS_getCustomerCompanyID($cID=-1){

	if (!empty($cID)) return get_post_meta($cID,'zbs_company',true);

	return false;
}

function zeroBS_setCustomerCompanyID($cID=-1,$coID=-1){

	if (!empty($cID) && !empty($coID)) return update_post_meta($cID,'zbs_company',$coID);

	return false;
}


function zeroBS_getExternalSourceCustomerCount($externalSource=''){

	global $zbscrmApprovedExternalSources;

		if (!empty($externalSource) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

				$approvedExternalSource = $externalSource;

				$args = array (
			'post_type'              => 'zerobs_customer',
			'post_status'            => 'publish',
			'posts_per_page'         => 10000000,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',
			'meta_query' => array(
				array(
					'key' => 'zbs_customer_ext_'.$approvedExternalSource,
					'value' => '',
					'compare' => '!='
				)
			)
		);

		$potentialCustomerList = get_posts( $args );

		return count($potentialCustomerList);

	}

	return 0;

}

function zeroBS_getExternalSourceCustomers($externalSource='',$withFullDetails=false){

	global $zbscrmApprovedExternalSources;

	$ret = false;

		if (!empty($externalSource) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

				$approvedExternalSource = $externalSource;

				$args = array (
			'post_type'              => 'zerobs_customer',
			'post_status'            => 'publish',
			'posts_per_page'         => 10000000,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',
			'meta_query' => array(
				array(
					'key' => 'zbs_customer_ext_'.$approvedExternalSource,
					'value' => '',
					'compare' => '!='
				)
			)
		);

		$potentialCustomerList = get_posts( $args );

		if (count($potentialCustomerList) > 0){

			$ret = array();

			foreach ($potentialCustomerList as $customerEle){

				$retObj = array(

					'id' => 	$customerEle->ID,
					'created' => $customerEle->post_date_gmt,
										'name' => 	$customerEle->post_title

				);

								if ($withFullDetails) {
					
					$retObj['meta'] 		= get_post_meta($customerEle->ID, 'zbs_customer_meta', true);

				}

				$ret[] = $retObj;

			}

		}

	}


	return $ret;

}


function zeroBS_getCompanyMeta($coID=-1){

	if (!empty($coID)) return get_post_meta($coID, 'zbs_company_meta', true);

	return false;

}


function zeroBS_getCompanyExternalSource($cID=-1){

	$ret = array();

	if ($cID !== -1){

				global $zbscrmApprovedExternalSources;

		if (count($zbscrmApprovedExternalSources)) foreach ($zbscrmApprovedExternalSources as $srcKey => $srcDeet){
        
        	$possMeta = get_post_meta($cID,'zbs_company_ext_'.$srcKey,true);

        	
        	if (!empty($possMeta)){

        		        		$ret[$srcKey] = array($srcDeet[0],'Created from "'.$possMeta.'"');
        	}

        }

	} 


	return $ret;


}

function zeroBS_getCompanyIDWithName($coName=''){

	$ret = false;

		if (!empty($coName)){

						
				

				$args = array (
			'post_type'              => 'zerobs_company',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',
			'meta_query' => array( 
												array(
					'key' => 'zbs_company_nameperm',
					'value' => $coName, 
					'compare' => '='
				)
			)

		);

		$potentialCustomerList = get_posts( $args );

		if (count($potentialCustomerList) > 0){

			if (isset($potentialCustomerList[0]) && isset($potentialCustomerList[0]->ID)){

				$ret = $potentialCustomerList[0]->ID;

			}

		}
		

	}


	return $ret;


}

function zeroBS_getCompanyIDWithExternalSource($externalSource='',$externalID=''){

	global $zbscrmApprovedExternalSources;

	$ret = false;

		if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

				$approvedExternalSource = $externalSource;

				$args = array (
			'post_type'              => 'zerobs_company',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

			   'meta_key'   => 'zbs_company_ext_'.$approvedExternalSource,
			   'meta_value' => $externalID
		);

		$potentialCompanyList = get_posts( $args );

		if (count($potentialCompanyList) > 0){

			if (isset($potentialCompanyList[0]) && isset($potentialCompanyList[0]->ID)){

				$ret = $potentialCompanyList[0]->ID;

			}

		}

	}


	return $ret;

}



function zeroBS_getCompany($cID=-1){

	if ($cID !== -1){

		$retObj = array(
						'id'=>$cID,
						'meta'=>get_post_meta($cID, 'zbs_company_meta', true),
						
												'created' => get_the_date('',$cID),
												'name' =>get_the_title($cID)
					);
			
		
		return $retObj;

	} 

	return false;


}


function zeroBS_getCompanies($withFullDetails=false,$perPage=10,$page=0,$searchPhrase='',$argsOverride=false){

	
				if (is_array($argsOverride)){

			$args = $argsOverride;

		} else {

				
										if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

					$args = array (
						'post_type'              => 'zerobs_company',
						'post_status'            => 'publish',
						'posts_per_page'         => $perPage,
						'order'                  => 'DESC',
						'orderby'                => 'post_date'
					);
					
										$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
					if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

										if (!empty($searchPhrase)) $args['s'] = $searchPhrase;


					
		}

		$companyList = get_posts( $args );

						
		$ret = array();

		foreach ($companyList as $coEle){

			$retObj = array(

				'id' => 	$coEle->ID,
				'created' => $coEle->post_date_gmt,
								'name' => 	$coEle->post_title

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($coEle->ID, 'zbs_company_meta', true);

				$retObj['name'] = $retObj['meta']['coname'];

								$retObj['contacts'] = zeroBS_getCustomers(false,1000,0,false,false,'',false,false,$coEle->ID);
			}


			$ret[] = $retObj;


		}



		return $ret;

}


function zeroBS_getCustomers($withFullDetails=false,$perPage=10,$page=0,$withInvoices=false,$withQuotes=false,$searchPhrase='',$withTransactions=false,$argsOverride=false,$companyID=false, $hasTagIDs='', $inArr = ''){

			

				if (is_array($argsOverride)){

			$args = $argsOverride;

		} else {

				
										if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

					$args = array (
						'post_type'              => 'zerobs_customer',
						'post_status'            => 'publish',
						'posts_per_page'         => $perPage,
						'order'                  => 'DESC',
						'orderby'                => 'post_date'
					);
					
					
																					$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
					if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

										if (!empty($searchPhrase)) $args['s'] = $searchPhrase;

										if ($companyID > 0){

					   $args['meta_key']   = 'zbs_company';
					   $args['meta_value'] = (int)$companyID;

					}

										if(!empty($hasTagIDs)){
						$args['tax_query'] = array(
                                array(
                                'taxonomy' => 'zerobscrm_customertag',
       							'field' => 'term_id',
                				'terms' => $hasTagIDs,
                                )
                            );
					}

					if(!empty($inArr)){    						$args['post__in'] = $inArr;
					}

					
					
					
		}

		$customerList = get_posts( $args );

		



						
		$ret = array();

		$args['posts_per_page'] = -1;
		$args['offset'] = 0;

				$filterList = count(get_posts($args));

		foreach ($customerList as $customerEle){



			$retObj = array(

				'id' => 	$customerEle->ID,
				'created' => $customerEle->post_date_gmt,
								'name' => 	$customerEle->post_title

			);

			$retObj['filterTot'] = $filterList;
			$retObj['filterPages'] = (int)ceil($filterList / $perPage);


						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($customerEle->ID, 'zbs_customer_meta', true);

			}

						if ($withInvoices && $withQuotes && $withTransactions){

				$custDeets = zeroBS_getCustomerExtrasViaSQL($customerEle->ID);
				$retObj['quotes'] = $custDeets['quotes'];
				$retObj['invoices'] = $custDeets['invoices'];
				$retObj['transactions'] = $custDeets['transactions'];


			} else {

								if ($withInvoices){
					
															$retObj['invoices'] 		= zeroBS_getInvoicesForCustomer($customerEle->ID,true,100);

				}

								if ($withQuotes){
					
										$retObj['quotes'] 		= zeroBS_getQuotesForCustomer($customerEle->ID,false,100);

				}

								if ($withTransactions){
					
										$retObj['transactions'] = zeroBS_getTransactionsForCustomer($customerEle->ID,false,100);

				}

			}

			$ret[] = $retObj;

		}


				

		return $ret;



}



function zeroBS_getCustomerExtrasViaSQL($customerID=-1){

	global $wpdb;
	
	

		$query = "
	SELECT posts.post_type,posts.ID,posts.post_date_gmt,
	(SELECT meta_value FROM $wpdb->postmeta WHERE post_id = posts.ID AND meta_key = CONCAT('zbs_customer_' , substr(posts.post_type,8) , '_meta')) meta,
	(SELECT meta_value FROM $wpdb->postmeta WHERE post_id = posts.ID AND meta_key = 'zbs_transaction_meta') transmeta

	FROM $wpdb->posts posts
	LEFT JOIN $wpdb->postmeta postmeta ON posts.ID = postmeta.post_id
	WHERE 
	posts.post_type IN ('zerobs_quote','zerobs_invoice','zerobs_transaction')
	AND posts.post_status = 'publish'
	AND postmeta.meta_value = ".(int)$customerID." AND postmeta.meta_key IN ('zbs_customer_quote_customer','zbs_customer_invoice_customer','zbs_parent_cust')
	ORDER BY ID DESC limit 0,10000";

	$customersObjs = $wpdb->get_results($query);

		$ret = array('quotes'=>array(),'invoices'=>array(),'transactions'=>array());
	if (count($customersObjs) > 0) foreach ($customersObjs as $co){

		switch ($co->post_type){

			case 'zerobs_quote':

								$ret['quotes'][] = array(

					'id' => $co->ID,
					'created' => $co->post_date_gmt,
					'meta' => unserialize($co->meta),
					'customerid' => $customerID

				);

				break;

			case 'zerobs_invoice':

								$ret['invoices'][] = array(

					'id' => $co->ID,
					'created' => $co->post_date_gmt,
					'meta' => unserialize($co->meta),
					'customerid' => $customerID

				);

				break;

			case 'zerobs_transaction':

								$ret['transactions'][] = array(

					'id' => $co->ID,
					'created' => $co->post_date_gmt,
					'meta' => unserialize($co->transmeta), 					'customerid' => $customerID

				);

				break;


		}

	}


	return $ret;
}





function zeroBS_getCustomersNoQJ($withFullDetails=false,$perPage=10,$page=0,$withInvoices=false,$withQuotes=false,$searchPhrase='',$withTransactions=false,$argsOverride=false){

			

				if (is_array($argsOverride)){

			$args = $argsOverride;

		} else {

				
										if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

					$args = array (
						'post_type'              => 'zerobs_customer',
						'post_status'            => 'publish',
						'posts_per_page'         => $perPage,
						'order'                  => 'DESC',
						'orderby'                => 'post_date'
					);
					
										$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
					if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

										if (!empty($searchPhrase)) $args['s'] = $searchPhrase;

					
		}

		$customerList = get_posts( $args );

						
		$ret = array();

		foreach ($customerList as $customerEle){

			$retObj = array(

				'id' => 	$customerEle->ID,
				'created' => $customerEle->post_date_gmt,
								'name' => 	$customerEle->post_title

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($customerEle->ID, 'zbs_customer_meta', true);

			}

						$withQuotes = true;

						if ($withInvoices && $withQuotes && $withTransactions){

				$custDeets = zeroBS_getCustomerExtrasViaSQL($customerEle->ID);
				$retObj['quotes'] = $custDeets['quotes'];
				$retObj['invoices'] = $custDeets['invoices'];
				$retObj['transactions'] = $custDeets['transactions'];


			} else {

								if ($withInvoices){
					
															$retObj['invoices'] 		= zeroBS_getInvoicesForCustomer($customerEle->ID,true,100);

				}

								if ($withQuotes){
					
										$retObj['quotes'] 		= zeroBS_getQuotesForCustomer($customerEle->ID,false,100);

				}

								if ($withTransactions){
					
										$retObj['transactions'] = zeroBS_getTransactionsForCustomer($customerEle->ID,false,100);

				}

			}

						if (count($retObj['quotes']) == 0){
				$ret[] = $retObj;
			}

		}


				

		return $ret;
}

function zeroBSCRM_getLog($lID=-1){

	if ($lID !== -1){

		$retObj = array(
						'id'=>$lID,
						'meta'=>get_post_meta($lID, 'zbs_log_meta', true),
						'owner'=>get_post_meta($lID, 'zbs_logowner', true),
						
												'created' => get_the_date('',$lID),
												'name' =>get_the_title($lID)
					);
			
		
		return $retObj;

	} 

	return false;
}


function zeroBSCRM_getLogs($customerID=false,$withFullDetails=false,$perPage=100,$page=0,$searchPhrase='',$argsOverride=false){

	
				if (is_array($argsOverride)){

			$args = $argsOverride;

		} else {

				
										if ($perPage < 0) $perPage = 100; else $perPage = (int)$perPage;

					$args = array (
						'post_type'              => 'zerobs_log',
						'post_status'            => 'publish',
						'posts_per_page'         => $perPage,
						'order'                  => 'DESC',
						'orderby'                => 'post_date'
					);
					
										$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
					if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

										if (!empty($searchPhrase)) $args['s'] = $searchPhrase;

										if ($customerID > 0){

					   $args['meta_key']   = 'zbs_logowner';
					   $args['meta_value'] = (int)$customerID;

					}

					
		}

		$logList = get_posts( $args );

						
		$ret = array();

		foreach ($logList as $logEle){

			$retObj = array(

				'id' => 	$logEle->ID,
				'created' => $logEle->post_date_gmt,
								'name' => 	$logEle->post_title

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($logEle->ID, 'zbs_log_meta', true);
				if ($customerID > 0){
										$retObj['owner'] = $customerID;
				} else {
					$retObj['owner'] 		= get_post_meta($logEle->ID, 'zbs_logowner', true);
				}

			}


			$ret[] = $retObj;


		}



		return $ret;
}


function zeroBS_getInvoices($withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_invoice',
			'post_status'            => 'any',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date'
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt,

								'zbsid'=>get_post_meta($ele->ID, 'zbsid', true)

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 			= get_post_meta($ele->ID, 'zbs_customer_invoice_meta', true);
				$retObj['customerid']		= get_post_meta($ele->ID, 'zbs_customer_invoice_customer', true);

				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}

			}

			$ret[] = $retObj;

		}

		return $ret;
}
function zeroBS_getQuotes($withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_quote',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date'
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt,

								'zbsid'=>get_post_meta($ele->ID, 'zbsid', true)

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 			= get_post_meta($ele->ID, 'zbs_customer_quote_meta', true);
				$retObj['customerid']		= get_post_meta($ele->ID, 'zbs_customer_quote_customer', true);

				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}
			}

			$ret[] = $retObj;

		}

		return $ret;
}


function zeroBS_getQuoteBuilderContent($qID=-1){

	if ($qID !== -1){

            $content = get_post_meta($qID, 'zbs_quote_content' , true ) ;
            $content = htmlspecialchars_decode($content);
		
			return array(
				'content'=>$content,
				'template_id' => get_post_meta($qID, 'zbs_quote_template_id' , true ) 
				);

	} else return false;
}

function zeroBS_getQuote($qID=-1,$withQuoteBuilderData=false){

	if ($qID !== -1){
		

		if (!$withQuoteBuilderData){
			
			return array(
				'id'=>$qID,
				'meta'=>get_post_meta($qID, 'zbs_customer_quote_meta', true),
				'customerid'=>get_post_meta($qID, 'zbs_customer_quote_customer', true)
				);

		} else {
			
			return array(
				'id'=>$qID,
				'meta'=>get_post_meta($qID, 'zbs_customer_quote_meta', true),
				'customerid'=>get_post_meta($qID, 'zbs_customer_quote_customer', true),
				'quotebuilder'=>zeroBS_getQuoteBuilderContent($qID)
				);


		}

	}

	return false;
}
function zeroBS_getQuoteByZBSID($zbsQuoteID=-1){

	if ($zbsQuoteID !== -1){

				$wpPostID = zeroBS_getQuotePostIDFromZBSID($zbsQuoteID);
		if (!empty($wpPostID)){
		
			return array(
				'id'=>$wpPostID,
				'meta'=>get_post_meta($wpPostID, 'zbs_customer_quote_meta', true),
				'customerid'=>get_post_meta($wpPostID, 'zbs_customer_quote_customer', true),
				'zbsid' => (int)$zbsQuoteID
				);

		}

	}
	
	return false; 
}

function zeroBS_getQuotePostIDFromZBSID($zbsQuoteID=-1){

		$args = array (
			'post_type'              => 'zerobs_quote',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

						   'meta_key'   => 'zbsid',
			   'meta_value' => $zbsQuoteID
		);

		$potentialQuote = get_posts( $args );

		if (isset($potentialQuote) && isset($potentialQuote[0]) && isset($potentialQuote->ID)) return $potentialQuote->ID;

		return false;

}
function zeroBS_getQuotesForCustomer($customerID=-1,$withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_quote',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

						   'meta_key'   => 'zbs_customer_quote_customer',
			   'meta_value' => $customerID
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt,

								'zbsid' => get_post_meta($ele->ID, 'zbsid', true)

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($ele->ID, 'zbs_customer_quote_meta', true);
				$retObj['customerid']	= get_post_meta($ele->ID, 'zbs_customer_quote_customer', true);
				$retObj['quotebuilder'] = zeroBS_getQuoteBuilderContent($ele->ID);

				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}

			}

			$ret[] = $retObj;

		}

		return $ret;
}

function zeroBS_markQuoteAccepted($qID=-1,$quoteSignedBy=''){

	if ($qID !== -1){
		
				$quoteMeta = get_post_meta($qID, 'zbs_customer_quote_meta', true);

				$quoteMeta['accepted'] = array(time(),$quoteSignedBy,zeroBSCRM_getRealIpAddr()); 
				return update_post_meta($qID,'zbs_customer_quote_meta',$quoteMeta);

	} 

	return false;

}




function zeroBS_getInvoice($iID=-1){

	if ($wpPostID !== -1){
		
		return array(
			'id'=>(int)$wpPostID,
			'meta'=>get_post_meta($wpPostID, 'zbs_customer_invoice_meta', true),
			'customerid'=>get_post_meta($wpPostID, 'zbs_customer_invoice_customer', true),
			'zbsid'=>get_post_meta($wpPostID, 'zbsid', true)
			);

	}
	
	return false; 
}
function zeroBS_getInvoiceByZBSID($zbsInvID=-1){

	if ($zbsInvID !== -1){

				$wpPostID = zeroBS_getInvoicePostIDFromZBSID($zbsInvID);
		if (!empty($wpPostID)){
		
			return array(
				'id'=>$wpPostID,
				'meta'=>get_post_meta($wpPostID, 'zbs_customer_invoice_meta', true),
				'customerid'=>get_post_meta($wpPostID, 'zbs_customer_invoice_customer', true),
				'zbsid' => (int)$zbsInvID
				);

		}

	}
	
	return false; 
}
function zeroBS_getInvoicePostIDFromZBSID($zbsInvID=-1){

		$args = array (
			'post_type'              => 'zerobs_invoice',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

						   'meta_key'   => 'zbsid',
			   'meta_value' => $zbsInvID
		);

		$potentialInvoice = get_posts( $args );

		if (isset($potentialInvoice) && isset($potentialInvoice[0]) && isset($potentialInvoice->ID)) return $potentialInvoice->ID;

		return false;

}
function zeroBS_getInvoicesForCustomer($customerID=-1,$withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_invoice',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

						   'meta_key'   => 'zbs_customer_invoice_customer',
			   'meta_value' => $customerID
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt,

								'zbsid'=>get_post_meta($ele->ID, 'zbsid', true)

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 		= get_post_meta($ele->ID, 'zbs_customer_invoice_meta', true);
				$retObj['customerid']		= get_post_meta($ele->ID, 'zbs_customer_invoice_customer', true);

				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}

			}

			$ret[] = $retObj;

		}

		return $ret;
}
function zeroBS_getTransaction($tID=-1){

	if ($tID !== -1){
		
		return array(
			'id'=>$tID,
			'meta'=>get_post_meta($tID, 'zbs_transaction_meta', true),
			'customerid'=>get_post_meta($tID, 'zbs_parent_cust', true)
			);

	} else return false;

} 

function zeroBS_getTransactions($withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_transaction',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date'
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 			= get_post_meta($ele->ID, 'zbs_transaction_meta', true);
				$retObj['customerid']		= get_post_meta($ele->ID, 'zbs_parent_cust', true);

				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}

			}

			$ret[] = $retObj;

		}

		return $ret;
}

function zeroBS_getTransactionsForCustomer($customerID=-1,$withFullDetails=false,$perPage=10,$page=0,$withCustomerDeets=false){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_transaction',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

						   'meta_key'   => 'zbs_parent_cust', 			   'meta_value' => $customerID
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'created' => $ele->post_date_gmt

			);

									
										
				$retObj['meta'] 		= get_post_meta($ele->ID, 'zbs_transaction_meta', true);
				$retObj['customerid']		= $customerID; 
				if ($withCustomerDeets && !empty($retObj['customerid'])){
					
					$retObj['customer']		= zeroBS_getCustomer($retObj['customerid']);
				
				}

								$retObj['type'] = get_post_meta($ele->ID,'zerobs_transaction_type',true);

			
			$ret[] = $retObj;

		}

		return $ret;
}


function zeroBS_getTransactionIDWithExternalSource($externalSource='',$externalID=''){

	global $zbscrmApprovedExternalSources;

	$ret = false;

		if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

				$approvedExternalSource = $externalSource;

				$args = array (
			'post_type'              => 'zerobs_transaction',
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'order'                  => 'DESC',
			'orderby'                => 'post_date',

			   'meta_key'   => 'zbs_trans_ext_'.$approvedExternalSource,
			   'meta_value' => $externalID
		);

		$potentialTransactionList = get_posts( $args );

		if (count($potentialTransactionList) > 0){

			if (isset($potentialTransactionList[0]) && isset($potentialTransactionList[0]->ID)){

				$ret = $potentialTransactionList[0]->ID;

			}

		}

	}


	return $ret;

}

function zeroBS_addUpdateTransaction(

		$tID = -1,

		

		$tFields = array(),

		$externalSource='',
		$externalID='',
		$transactionDate='',
		$transactionTags=array(), 

		$fallBackLog = false,
		$extraMeta = false,
		$automatorPassthrough = false

		){


		$ret = false;

		$zbsTransactionCustomer = ''; if (isset($tFields) && isset($tFields['customer']) && !empty($tFields['customer'])) $zbsTransactionCustomer = (int)$tFields['customer'];

		if (isset($tFields) && count($tFields) > 0 && isset($zbsTransactionCustomer) && !empty($zbsTransactionCustomer)){ 
				$newTrans = false;


			if ($tID > 0){

																$postID = $tID;

								$existingMeta = zeroBS_getTransactionMeta($postID);

			} else {

								$newTrans = true;

								$headerPost = array(
										'post_type' => 'zerobs_transaction',
										'post_status' => 'publish',
										'comment_status' => 'closed'
									);

								if (!empty($transactionDate)) $headerPost['post_date'] = $transactionDate;

								if (isset($tFields) && isset($tFields['orderid']) && !empty($tFields['orderid'])) $headerPost['post_title'] = $tFields['orderid'];
					
								$postID = wp_insert_post($headerPost);

								$existingMeta = array();

			}

						update_post_meta($postID,'zbs_parent_cust', $zbsTransactionCustomer);



						if (isset($existingMeta) && is_array($existingMeta) && !isset($existingMeta['status'])) $existingMeta['status'] = 'Unknown';



						global $zbscrmApprovedExternalSources;

						$approvedExternalSource = ''; 			if (!empty($externalSource) && !empty($externalID) && array_key_exists($externalSource,$zbscrmApprovedExternalSources)){

								$approvedExternalSource = $externalSource;

				                update_post_meta($postID, 'zbs_trans_ext_'.$approvedExternalSource, $externalID);

			} 


						if (!empty($postID)){

								$zbsTransactionMeta = zeroBS_buildTransactionMeta($tFields,$existingMeta);

								if (isset($tFields['trans_time']) && !empty($tFields['trans_time'])) $zbsTransactionMeta['trans_time'] = (int)$tFields['trans_time'];

				                update_post_meta($postID, 'zbs_transaction_meta', $zbsTransactionMeta);

                                if (isset($extraMeta) && is_array($extraMeta)) foreach ($extraMeta as $k => $v){

                	                	$cleanKey = strtolower(str_replace(' ','_',$k));

                	                	update_post_meta($postID, 'zbs_transaction_extra_'.$cleanKey, $v);

                }

                                if (isset($transactionTags) && is_array($transactionTags)) wp_set_object_terms( $postID, $transactionTags, 'zerobscrm_transactiontag', false );

			}



												if ($newTrans){

								zeroBSCRM_FireInternalAutomator('transaction.new',array(
	                'id'=>$postID,
	                'transactionMeta'=>$zbsTransactionMeta,
                    'againstid' => $zbsTransactionCustomer,
	                'extsource'=>$approvedExternalSource,
	                'automatorpassthrough'=>$automatorPassthrough 	            ));

			} else {

								

																				if (
					isset($fallBackLog) && is_array($fallBackLog) 
					&& isset($fallBackLog['type']) && !empty($fallBackLog['type'])
					&& isset($fallBackLog['shortdesc']) && !empty($fallBackLog['shortdesc'])
				){

					
										$zbsNoteLongDesc = ''; if (isset($fallBackLog['longdesc']) && !empty($fallBackLog['longdesc'])) $zbsNoteLongDesc = $fallBackLog['longdesc'];

												$newOrUpdatedLogID = zeroBS_addUpdateLog($postID,-1,-1,array(
														'type' => $fallBackLog['type'],
							'shortdesc' => $fallBackLog['shortdesc'],
							'longdesc' => $zbsNoteLongDesc
						));


				}


			}

							        

						$ret = $postID;


	}



	return $ret;

}


function zeroBS_getTransactionMeta($cID=-1){

	if (!empty($cID)) return get_post_meta($cID, 'zbs_transaction_meta', true);

	return false;

}


function zeroBS_buildTransactionMeta($arraySource=array(),$startingArray=array()){

		$zbsTransMeta = array();

		if (isset($startingArray) && is_array($startingArray)) $zbsTransMeta = $startingArray;


	        global $zbsTransactionFields;

        foreach ($zbsTransactionFields as $fK => $fV){

            if (!isset($zbsTransMeta[$fK])) $zbsTransMeta[$fK] = '';

                                    if (isset($arraySource[$fK])) {

                switch ($fV[0]){

                    case 'tel':

                                                $zbsTransMeta[$fK] = sanitize_text_field($arraySource[$fK]);
                        preg_replace("/[^0-9 ]/", '', $zbsTransMeta[$fK]);
                        break;

                    case 'price':

                                                $zbsTransMeta[$fK] = sanitize_text_field($arraySource[$fK]);
                        $zbsTransMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsTransMeta[$fK]);
                        $zbsTransMeta[$fK] = floatval($zbsTransMeta[$fK]);
                        break;


                    case 'textarea':

                        $zbsTransMeta[$fK] = zeroBSCRM_textProcess($arraySource[$fK]);

                        break;


                    default:

                        $zbsTransMeta[$fK] = sanitize_text_field($arraySource[$fK]);

                        break;


                }


            }


        }

    return $zbsTransMeta;
}



function zeroBS_getForm($fID=-1){

	if ($fID !== -1){
		
		return array(
			'id'=>$fID,
			
						'meta'=>get_post_meta($fID,'zbs_form_field_meta',true),
			'style'=>get_post_meta($fID, 'zbs_form_style', true),
			'views'=>get_post_meta($fID, 'zbs_form_views', true),
			'conversions'=>get_post_meta($fID, 'zbs_form_conversions', true)

			);

	} else return false;
}

function zeroBS_getForms($withFullDetails=false,$perPage=10,$page=0){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_form',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date'
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
								'meta'=>get_post_meta($ele->ID,'zbs_form_field_meta',true),
				'style'=>get_post_meta($ele->ID, 'zbs_form_style', true),
				'title'=>$ele->post_title

			);

						if ($withFullDetails) {
				
								$retObj['views'] 		= get_post_meta($ele->ID, 'zbs_form_views', true);
				$retObj['conversions']		= get_post_meta($ele->ID, 'zbs_form_conversions', true);

			}

			$ret[] = $retObj;

		}

		return $ret;
}




function zeroBS_getQuoteTemplate($tID=-1){

	if ($tID !== -1){
		
		return array(
			'id'=>$tID,
			'meta'=>get_post_meta($tID, 'zbs_quotemplate_meta', true),
			'zbsdefault'=>get_post_meta($tID, 'zbsdefault', true),
			'content'=> get_post_field('post_content', $tID) 			);

	} else return false;

} 

function zeroBS_getQuoteTemplates($withFullDetails=false,$perPage=10,$page=0){

				if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		$args = array (
			'post_type'              => 'zerobs_quo_template',
			'post_status'            => 'publish',
			'posts_per_page'         => $perPage,
			'order'                  => 'DESC',
			'orderby'                => 'post_date'
		);
		
				$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
		if ($actualPage > 0) $args['offset'] = $perPage*$actualPage;

		$list = get_posts( $args );

		$ret = array();

		foreach ($list as $ele){

			$retObj = array(

				'id' => 	$ele->ID,
				'name' => 	$ele->post_title,
				'created' => $ele->post_date_gmt,
				'content'=> $ele->post_content

			);

						if ($withFullDetails) {
				
				$retObj['meta'] 			= get_post_meta($ele->ID, 'zbs_quotemplate_meta', true);
				$retObj['zbsdefault'] 		= get_post_meta($ele->ID, 'zbsdefault', true);

			}

			$ret[] = $retObj;

		}

		return $ret;
}



function zeroBS_customerTotalValue($custID='',$customerInvoices=array(),$customerTransactions=array()){

	$tot = 0;
	
		if (isset($customerInvoices) && is_array($customerInvoices)) foreach ($customerInvoices as $inv){

            if (isset($inv['meta']) && isset($inv['meta']['val'])) $tot += floatval($inv['meta']['val']);

    }
	
			if (isset($customerTransactions) && is_array($customerTransactions)) foreach ($customerTransactions as $transact){

		        if (
    			isset($transact['meta']) && 
        		(
        			!isset($transact['meta']['invoice_id']) ||
        			(isset($transact['meta']['invoice_id']) && empty($transact['meta']['invoice_id']))
        		)
        ) {

            if (isset($transact['meta']) && isset($transact['meta']['total'])) $tot += floatval($transact['meta']['total']);

        }

    }

	return $tot;
}
function zeroBS_customerName($custID='',$customerMeta=false,$incFirstLineAddr=true,$incID=true){
	
	$ret = '';

		if (!is_array($customerMeta)) $customerMeta = get_post_meta($custID, 'zbs_customer_meta', true);

	if (isset($customerMeta['prefix']) && !empty($customerMeta['prefix'])) $ret = $customerMeta['prefix'].'.';
	if (isset($customerMeta['fname']) && !empty($customerMeta['fname'])) $ret .= ' '.$customerMeta['fname'];
	if (isset($customerMeta['lname']) && !empty($customerMeta['lname'])) $ret .= ' '.$customerMeta['lname'];

		if ($incFirstLineAddr) if (isset($customerMeta['addr1']) && !empty($customerMeta['addr1'])) $ret .= ' ('.$customerMeta['addr1'].')';

		if ($incID) $ret .= ' #'.$custID;

	return trim($ret);
}
function zeroBS_companyName($custID='',$companyMeta=array(),$incFirstLineAddr=true,$incID=true){
	
	$ret = '';

	if (isset($companyMeta['coname']) && !empty($companyMeta['coname'])) $ret .= $companyMeta['coname'];

		if ($incFirstLineAddr) if (isset($companyMeta['addr1']) && !empty($companyMeta['addr1'])) $ret .= ' ('.$companyMeta['addr1'].')';

		if ($incID) $ret .= ' #'.$custID;

	return trim($ret);
}

function zeroBS_customerAddr($custID='',$customerMeta=array(),$addrFormat = 'short',$delimiter= ', '){
	
	$ret = '';

	if ($addrFormat == 'short'){

		if (isset($customerMeta['addr1']) && !empty($customerMeta['addr1'])) $ret = $customerMeta['addr1'];
		if (isset($customerMeta['city']) && !empty($customerMeta['city'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$customerMeta['city'];

	} else if ($addrFormat == 'full'){

		if (isset($customerMeta['addr1']) && !empty($customerMeta['addr1'])) $ret = $customerMeta['addr1'];
		if (isset($customerMeta['addr2']) && !empty($customerMeta['addr2'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$customerMeta['addr2'];
		if (isset($customerMeta['city']) && !empty($customerMeta['city'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$customerMeta['city'];
		if (isset($customerMeta['county']) && !empty($customerMeta['county'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$customerMeta['county'];
		if (isset($customerMeta['postcode']) && !empty($customerMeta['postcode'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$customerMeta['postcode'];


	}

	return trim($ret);
}
function zeroBS_customerEmail($custID='',$customerMeta=false){
	
	$ret = false;

		if (!is_array($customerMeta)) $customerMeta = get_post_meta($custID, 'zbs_customer_meta', true);

	if (isset($customerMeta['email']) && !empty($customerMeta['email'])) $ret = $customerMeta['email'];

	return $ret;
}

function zeroBS_companyAddr($custID='',$companyMeta=array(),$addrFormat = 'short',$delimiter= ', '){
	
	$ret = '';

	if ($addrFormat == 'short'){

		if (isset($companyMeta['addr1']) && !empty($companyMeta['addr1'])) $ret = $companyMeta['addr1'];
		if (isset($companyMeta['city']) && !empty($companyMeta['city'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$companyMeta['city'];

	} else if ($addrFormat == 'full'){

		if (isset($companyMeta['addr1']) && !empty($companyMeta['addr1'])) $ret = $companyMeta['addr1'];
		if (isset($companyMeta['addr2']) && !empty($companyMeta['addr2'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$companyMeta['addr2'];
		if (isset($companyMeta['city']) && !empty($companyMeta['city'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$companyMeta['city'];
		if (isset($companyMeta['county']) && !empty($companyMeta['county'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$companyMeta['county'];
		if (isset($companyMeta['postcode']) && !empty($companyMeta['postcode'])) $ret .= zeroBS_delimiterIf($delimiter,$ret).$companyMeta['postcode'];


	}

	return trim($ret);
}



function zeroBS_getQuoteCount(){

	$counts = wp_count_posts('zerobs_quote');

	if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

	return 0;
}

function zeroBS_getInvoiceCount(){

	$counts = wp_count_posts('zerobs_invoice');

	if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

	return 0;
}

function zeroBS_getCompanyCount(){

	$counts = wp_count_posts('zerobs_company');

	if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

	return 0;
}

function zeroBS_getTransactionCount(){

	$counts = wp_count_posts('zerobs_transaction');

	if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

	return 0;
}


function zeroBS_getQuoteTemplateCount(){

	$counts = wp_count_posts('zerobs_quo_template');

	if (isset($counts) && isset($counts->publish)) return (int)$counts->publish;

	return 0;
}









function zbsCustomer_saveCustomerPostdata($post_id) {

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['save_zbscustomer_nce'])) return;
  if (!wp_verify_nonce($_POST['save_zbscustomer_nce'], 'save_zbscustomer')) return;
  if ('page' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id)) return;
  } else {
    if (!current_user_can('edit_post', $post_id)) return;
  }

  
  		  		global $zbsCurrentPostMeta; if (isset($zbsCurrentPostMeta)) 
  			$zbsMeta = $zbsCurrentPostMeta;
  		else
  			  			$zbsMeta = get_post_meta($post_id, 'zbs_customer_meta', true);

  	    if (isset($_POST['zbs_company'])) zbsCRM_addUpdateCustomerCompany($post_id,(int)sanitize_text_field($_POST['zbs_company'])); 

  	

        zbsCustomer_updateCustomerNameInPostTitle($post_id,$zbsMeta);


}
add_action('save_post', 'zbsCustomer_saveCustomerPostdata',200);
global $zbsCRMCustomerUpdatingArr; $zbsCRMCustomerUpdatingArr = array();
function zbsCustomer_updateCustomerNameInPostTitle($post_id=-1,$zbsMetaPassThrough=false){

		global $zbsCRMCustomerUpdatingArr;

	if ($post_id !== -1 && !isset($zbsCRMCustomerUpdatingArr[$post_id])){

				if (!is_array($zbsCRMCustomerUpdatingArr))
			$zbsCRMCustomerUpdatingArr = array($post_id);
		else
			$zbsCRMCustomerUpdatingArr[] = $post_id;

				if (isset($zbsMetaPassThrough) && $zbsMetaPassThrough !== false) 
			$zbsMeta = $zbsMetaPassThrough;
		else
						$zbsMeta = get_post_meta($post_id, 'zbs_customer_meta', true);

				$actionInPlace = has_action('save_post', 'zbsCustomer_saveCustomerPostdata');
		if ($actionInPlace > 0) $actionInPlace = true; 
	    	    if ($actionInPlace) remove_action('save_post', 'zbsCustomer_saveCustomerPostdata',200);

	        	    		        $newCName = zeroBS_customerName('',$zbsMeta,true,false);
	        wp_update_post(array(
	            'ID' => $post_id,
	            'post_title' => $newCName
	        ));

                                    if (isset($_POST['zbscrm_newcustomer']) && $_POST['zbscrm_newcustomer'] == 1){

                zeroBSCRM_FireInternalAutomator('customer.new',array(
                    'id'=>$post_id,
                    'customerMeta'=>$zbsMeta
                    ));
                
            }


	    	    if ($actionInPlace) add_action('save_post', 'zbsCustomer_saveCustomerPostdata',200);

	    	    unset($zbsCRMCustomerUpdatingArr[$post_id]);

	    return $newCName;

	} return false;

}


function zbsCustomer_saveTransactionPostdata($post_id) {

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['post_type']) && $_POST['post_type'] == 'page') {
    if (!current_user_can('edit_page', $post_id)) return;
  } else {
    if (!current_user_can('edit_post', $post_id)) return;
  }

    	if (isset($_POST['zbs_hidden_flag'])) {


	  	$newDate = ''; if (isset($_POST['transactionDate']) && !empty($_POST['transactionDate'])) $newDate = sanitize_text_field($_POST['transactionDate']);
	  	if (!empty($newDate) && strlen($newDate) == 10){ 
	  			  		$year = substr($newDate,6);
	  		$month = substr($newDate,3,2);
	  		$days = substr($newDate,0,2);
	  			  		$postDateStr = $year.'-'.$month.'-'.$days.' 00:09:00';

	  		
		  				zbsTransaction_updatePostDate($post_id,$postDateStr);

		}

	}

}
add_action('save_post', 'zbsCustomer_saveTransactionPostdata');
function zbsTransaction_updatePostDate($post_id=-1,$postDateStr=false){

	if ($post_id !== -1){

				$actionInPlace = has_action('save_post', 'zbsCustomer_saveTransactionPostdata');
		if ($actionInPlace > 0) $actionInPlace = true; 
	    	    if ($actionInPlace) remove_action('save_post', 'zbsCustomer_saveTransactionPostdata');

						wp_update_post(array('ID'=>$post_id,'post_date'=>$postDateStr));

	    	    if ($actionInPlace) add_action('save_post', 'zbsCustomer_saveTransactionPostdata');

	    return $post_id;

	} return false;

}



function zbsCustomer_saveCompanyPostdata($post_id) {

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['save_zbscompany_nce'])) return;
  if (!wp_verify_nonce($_POST['save_zbscompany_nce'], 'save_zbscompany')) return;
  if ('page' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id)) return;
  } else {
    if (!current_user_can('edit_post', $post_id)) return;
  }

  
  		  		global $zbsCurrentCompanyPostMeta; if (isset($zbsCurrentCompanyPostMeta)) 
  			$zbsMeta = $zbsCurrentCompanyPostMeta;
  		else
  			  			$zbsMeta = get_post_meta($post_id, 'zbs_company_meta', true);

        zbsCustomer_updateCompanyNameInPostTitle($post_id,$zbsMeta);


}
add_action('save_post', 'zbsCustomer_saveCompanyPostdata');

function zbsCustomer_updateCompanyNameInPostTitle($post_id=-1,$zbsMetaPassThrough=false){

	if ($post_id !== -1){

				if (isset($zbsMetaPassThrough) && $zbsMetaPassThrough !== false) 
			$zbsMeta = $zbsMetaPassThrough;
		else
						$zbsMeta = get_post_meta($post_id, 'zbs_company_meta', true);

				$actionInPlace = has_action('save_post', 'zbsCustomer_saveCompanyPostdata');
		if ($actionInPlace > 0) $actionInPlace = true; 
	    	    if ($actionInPlace) remove_action('save_post', 'zbsCustomer_saveCompanyPostdata');

	        	    		        $newCName = zeroBS_companyName('',$zbsMeta,true,false);
	        wp_update_post(array(
	            'ID' => $post_id,
	            'post_title' => $newCName
	        ));

	        	        	        $simpleCName = zeroBS_companyName('',$zbsMeta,false,false);
	        update_post_meta($post_id,'zbs_company_nameperm',$simpleCName);

                                    if (isset($_POST['zbscrm_newcompany']) && $_POST['zbscrm_newcompany'] == 1){

                zeroBSCRM_FireInternalAutomator('company.new',array(
                    'id'=>$post_id,
                    'companyMeta'=>$zbsMeta
                    ));
                
            }


	    	    if ($actionInPlace) add_action('save_post', 'zbsCustomer_saveCompanyPostdata');

	    return $newCName;

	} return false;

}

function zbsQuote_saveQuotePostdata($post_id) {

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['post_type']) && $_POST['post_type'] == 'page') {
    if (!current_user_can('edit_page', $post_id)) return;
  } else {
    if (!current_user_can('edit_post', $post_id)) return;
  }

  	if (isset($_POST['quo-ajax-nonce'])) {

	  			zbsQuote_updatePostDate($post_id);		

	}

}
add_action('save_post', 'zbsQuote_saveQuotePostdata');
function zbsQuote_updatePostDate($post_id=-1){

	if ($post_id !== -1){

				$actionInPlace = has_action('save_post', 'zbsQuote_saveQuotePostdata');
		if ($actionInPlace > 0) $actionInPlace = true; 
	    	    if ($actionInPlace) remove_action('save_post', 'zbsQuote_saveQuotePostdata');

						wp_update_post(array('ID'=>$post_id,'post_name'=>$post_id,'post_title'=>'Proposal'));

						update_post_meta($post_id,'zbshash',zeroBSCRM_GenerateHashForPost($post_id,12));

	    	    if ($actionInPlace) add_action('save_post', 'zbsQuote_saveQuotePostdata');

	    return $post_id;

	} return false;

}








         function zeroBSCRM_clearCPTAutoDrafts(){


	    							global $wpdb, $zbsCustomPostTypes;

								foreach ($zbsCustomPostTypes as $cpt){

					$del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft' AND post_type = '".$cpt."'");
					$del= $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type = 'revision'");
																																								
					
				}
					
				update_option('zbscptautodraftclear',time());

	}


function zeroBS_getCustomerWPID($cID=-1){

	if (!empty($cID)) return get_post_meta($cID,'zbs_wp_user_id',true);

	return false;
}

function zeroBS_getCustomerIDFromWPID($wpID=-1){
	global $wpdb;
	$ret = false;
		if (!empty($wpID)){
				$sql = $wpdb->prepare("select post_id from $wpdb->postmeta where meta_value = '%d' And meta_key='zbs_wp_user_id'", (int)$wpID);
		$potentialCustomerList = $wpdb->get_results($sql);
		if (count($potentialCustomerList) > 0){
			if (isset($potentialCustomerList[0]) && isset($potentialCustomerList[0]->post_id)){
				$ret = $potentialCustomerList[0]->post_id;
			}
		}	
	}
	return $ret;
}

function zeroBS_setCustomerWPID($cID=-1,$wpID=-1){

	if (!empty($cID) && !empty($wpID)) return update_post_meta($cID,'zbs_wp_user_id',$wpID);

	return false;
}


function zeroBS_getTransactionsForInvoice($invID=-1){
	global $wpdb;
	$ret = false;
		if (!empty($invID)){
				$sql = $wpdb->prepare("select post_id from $wpdb->postmeta where meta_value = '%d' And meta_key='zbs_invoice_partials'", $invID);
		$potentialTransactionList = $wpdb->get_results($sql);
		if (count($potentialTransactionList) > 0){
			if (isset($potentialTransactionList[0]) && isset($potentialTransactionList[0]->post_id)){
				$ret = $potentialTransactionList[0]->post_id;
			}
		}		
	}
	return $ret;	
}

function zeroBSCRM_invoice_canView($cID=-1, $invID=-1){
	if (!empty($cID) && !empty($invID)){
		$accessID = get_post_meta($invID, 'zbs_customer_invoice_customer', true);
		return $accessID;
	}

}

	





	




function zeroBSCRM_regenerateAPIKey(){
	$x = zeroBSCRM_API_generate_api_key();
	zeroBSCRM_storeAPIKey($x);
	return $x;
}

function zeroBSCRM_storeAPIKey($api_key=''){
	update_option('zbs_crm_api_key', $api_key);
}


function zeroBSCRM_updateAPIKey($api_key=''){
	update_option('zbs_crm_api_key', $api_key);
}

function zeroBSCRM_getAPIKey(){
    $api_key = get_option('zbs_crm_api_key');
    return $api_key;
}

function zeroBSCRM_getAPIKeys(){
    $api_key = get_option('zbs_crm_api_key');
    return $api_key;
}

function zeroBSCRM_regenerateAPISecret(){

		$x = zeroBSCRM_API_generate_api_key();
	zeroBSCRM_storeAPISecret($x);
	return $x;
}

function zeroBSCRM_storeAPISecret($api_secret=''){
	update_option('zbs_crm_api_secret', $api_secret);
}

function zeroBSCRM_getAPISecret(){
    $api_secret = get_option('zbs_crm_api_secret');
    return $api_secret;
}











function zeroBS_checkOwner($postID=-1,$potentialOwnerID=-1){

	if ($postID !== -1){

		$potentialOwner = zeroBS_getOwner($postID);

		if (isset($potentialOwner['ID']) && $potentialOwner['ID'] == $potentialOwnerID) return true;

	} 

	return false;
}


function zeroBS_getCustomerOwner($customerID=-1){

	if ($customerID !== -1){

		return zeroBS_getOwner($customerID);

	} 

	return false;
}


function zeroBS_getCompanyOwner($companyID=-1){

	if ($companyID !== -1){

		return zeroBS_getOwner($companyID);

	} 

	return false;
}



function zeroBS_getOwner($postID=-1){

	if ($postID !== -1){

		$retObj = false;

		$userIDofOwner = get_post_meta($postID, 'zbs_owner', true);

		
		if (isset($userIDofOwner) && !empty($userIDofOwner)){

						$retObj = array(

					'ID'=> $userIDofOwner,
					'OBJ'=> get_userdata($userIDofOwner)
			);

		}
			
		
		return $retObj;

	} 

	return false;
}


function zeroBS_setOwner($postID=-1,$ownerID=-1){

	if ($postID !== -1){
		
		return update_post_meta($postID, 'zbs_owner', (int)$ownerID);

	} 

	return false;
}

function zeroBS_getPossibleCustomerOwners(){ return zeroBS_getPossibleOwners('zerobs_customermgr'); }
function zeroBS_getPossibleCompanyOwners(){ return zeroBS_getPossibleOwners('zerobs_customermgr'); }
function zeroBS_getPossibleQuoteOwners(){ return zeroBS_getPossibleOwners('zerobs_quotemgr'); }
function zeroBS_getPossibleInvoiceOwners(){ return zeroBS_getPossibleOwners('zerobs_invoicemgr'); }
function zeroBS_getPossibleTransactionOwners(){ return zeroBS_getPossibleOwners('zerobs_transactionmgr'); }


function zeroBS_getPossibleOwners($permsReq=''){

		


	if (empty($permsReq) || !in_array($permsReq, array('zerobs_admin','zerobs_customermgr','zerobs_quotemgr','zerobs_invoicemgr','zerobs_transactionmgr'))){

				$args = array('role__in'     => array('administrator','zerobs_admin','zerobs_customermgr','zerobs_quotemgr','zerobs_invoicemgr','zerobs_transactionmgr'));

	} else {

				$args = array('role__in'     => array('administrator',$permsReq));


	}

	return get_users( $args );

}















		define('ZBSCRM_INC_DAL',true);  