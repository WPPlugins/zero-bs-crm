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






   	add_action('wp_ajax_zbs_new_user', 'zbs_new_user');
	function zbs_new_user(){
		
		check_ajax_referer( 'newwp-ajax-nonce', 'security' );

	  	if ( current_user_can( 'manage_options' ) ) {   
			$email = $_POST['email'];
			$email_address = zeroBSCRM_validateEmail($email);
			if( null == username_exists( $email ) && $email_address) {
				$password = wp_generate_password( 12, false );
				$user_id = wp_create_user( $email, $password, $email);
				wp_update_user(
					array(
						'ID'          =>    $user_id,
						'nickname'    =>    $email
					)
				);
				$user = new WP_User( $user_id );
				$user->set_role( 'zerobs_customer' );   				$body = zeroBSCRM_Portal_generateNotificationHTML($password,true, $email);
				$subject = 'Welcome to your Client Portal';
				$headers = array('Content-Type: text/html; charset=UTF-8');	
				wp_mail(  $email, $subject, $body, $headers );		  
				$m['message'] = 'User Created';
				$m['success'] = true;
				$m['user_id'] = $user_id;
				echo json_encode($m);
				die();
			}else{
				$m['message'] = 'User Already Exists or Invalid Email';
				$m['success'] = false;
				$m['email'] = $email_address;
				echo json_encode($m);
				die();
			}

		}
	}



			add_action('wp_ajax_nopriv_zbs_wizard_fin','zbs_wizard_fin');
	add_action( 'wp_ajax_zbs_wizard_fin', 'zbs_wizard_fin' );
	function zbs_wizard_fin(){
	  check_ajax_referer( 'zbswf-ajax-nonce', 'security' );  	  if ( current_user_can( 'manage_options' ) ) {   
	  	$runCount = get_option('zbs_wizard_run',0);

			

	  		
	  		
	        $crm_name = sanitize_text_field($_POST['zbs_crm_name']);
	        $crm_curr = sanitize_text_field($_POST['zbs_crm_curr']); 	        $crm_type = sanitize_text_field($_POST['zbs_crm_type']);
			$crm_other = sanitize_text_field($_POST['zbs_crm_other']);
			$crm_menu_style = (int)sanitize_text_field($_POST['zbs_crm_menu_style']); 	        						$crm_share = 0; if (isset($_POST['zbs_crm_share_essentials']) && $_POST['zbs_crm_share_essentials'] == "1") $crm_share = 1;

	  		
	  					$crm_enable_quotes = 0; if (isset($_POST['zbs_quotes']) && $_POST['zbs_quotes'] == "1") $crm_enable_quotes = 1;
			$crm_enable_invoices = 0; if (isset($_POST['zbs_invoicing']) && $_POST['zbs_invoicing'] == "1") $crm_enable_invoices = 1;
			$crm_enable_forms = 0; if (isset($_POST['zbs_forms']) && $_POST['zbs_forms'] == "1") $crm_enable_forms = 1;

			

	  				    $bn = sanitize_text_field($_POST['zbs_crm_subblogname']);
		    $fn = sanitize_text_field($_POST['zbs_crm_first_name']);
		    $ln = sanitize_text_field($_POST['zbs_crm_last_name']);
		    $em = sanitize_text_field($_POST['zbs_crm_email']);
		    $emv = zeroBSCRM_validateEmail($em);
			$crm_sub = 0; if (isset($_POST['zbs_crm_subscribed']) && $_POST['zbs_crm_subscribed'] == "1") $crm_sub = 1;

						$crm_override = 0;


			#} Note: this only shares if "share essentials" has been ticked...
			#} ... or email subscribe (where upon our server ignores customer data except email sub details)
			if (is_callable('curl_init') && ($crm_share == 1 || $crm_sub == 1)){   
								global $zeroBSCRM_urls, $zeroBSCRM_version;


				$crm_url = home_url();
				$current_user = wp_get_current_user();

							    $m = array( 'share'=> $crm_share, 'bn' => $bn, 'fn' => $fn , 'ln' => $ln, 'em' => $em, 'emv' => $emv, 'smm' => time(), 'n' => $crm_name, 'u' => $crm_url, 'o' => $crm_other, 's' => $crm_sub, 't' => $crm_type, 'ov' => $crm_override, 'eq' => $crm_enable_quotes, 'ei' => $crm_enable_invoices, 'ef' => $crm_enable_forms, 'ems' => $crm_menu_style, 'v' => $zeroBSCRM_version, 'cu' => $crm_curr);

			    $postData = http_build_query($m);
			    

			  	$ch = curl_init();
			  	curl_setopt($ch, CURLOPT_URL,  $zeroBSCRM_urls['smm'] );
			    curl_setopt( $ch, CURLOPT_POST, true );
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			    curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData);
			    $x = curl_exec( $ch );
			    
			    curl_close( $ch );

	    	}



						global $zeroBSCRM_Settings;

								$zeroBSCRM_Settings->update( 'customheadertext' ,$crm_name);

								switch ($crm_menu_style){

					case 1: 

																		$zeroBSCRM_Settings->update( 'menulayout' , 1);

						break;

					case 2: 

																		$zeroBSCRM_Settings->update( 'menulayout' , 2);

						break;

					case 3: 

												$zeroBSCRM_Settings->update( 'wptakeovermodeforall' , 1);
						$zeroBSCRM_Settings->update( 'menulayout' , 3);

						break;

				}

								if ($crm_enable_quotes == 1) 
					zeroBSCRM_extension_install_quotebuilder();
				else
					zeroBSCRM_extension_uninstall_quotebuilder();

				if ($crm_enable_invoices == 1) {

					zeroBSCRM_extension_install_invbuilder();

										zeroBSCRM_extension_install_pdfinv();

				} else {

					zeroBSCRM_extension_uninstall_invbuilder();
					
				}

				if ($crm_enable_forms == 1) 
					zeroBSCRM_extension_install_forms();
				else
					zeroBSCRM_extension_uninstall_forms();
				


				  		update_option('zbs_wizard_run', $runCount+1);
	  	

	    $r['message'] = 'success';
	    $r['success'] = 1;
	    echo json_encode($r);
	    die();
	  } else {
	    $r['message'] = 'Unathorised to do this...';
	    $r['success'] = 0;
	    echo json_encode($r);
	    die();
	  }
	}

		add_action( 'wp_ajax_logclose', 'zeroBSCRM_AJAX_logClose' );
	function zeroBSCRM_AJAX_logClose(){

				check_ajax_referer( 'zbscrmjs-glob-ajax-nonce', 'sec' );  
						$potentialClosers = array('pdfinvinstall');
		$potentialKey = ''; if (isset($_POST['closing']) && !empty($_POST['closing']) && in_array($_POST['closing'],$potentialClosers)) $potentialKey = $_POST['closing'];

		
				update_option('zbs_closers_'.$potentialKey,time());


		header('Content-Type: application/json');
		echo json_encode(array('fini'=>1));
		exit();

	}


		add_action( 'wp_ajax_markFeedback', 'zeroBSCRM_AJAX_markFeedback' );
	function zeroBSCRM_AJAX_markFeedback(){

		$feedbackVal = 'nope'; if (isset($_POST['feedbackgiven'])) $feedbackVal = 'yep';

				update_option('zbsfeedback',$feedbackVal);


		header('Content-Type: application/json');
		echo json_encode(array('fini'=>1));
		exit();

	}



		add_action( 'wp_ajax_getinvs', 'zeroBSCRM_AJAX_getCustInvs' );
	function zeroBSCRM_AJAX_getCustInvs(){

				check_ajax_referer( 'zbscrmjs-glob-ajax-nonce', 'sec' );  
		$ret = array();

				if (zeroBSCRM_permsCustomers()){

						$cID = -1; if (isset($_POST['cid'])) $cID = (int)$_POST['cid'];

			if ($cID > 0){

								$ret = zeroBS_getInvoicesForCustomer($cID,true,100);

			}

		}

		header('Content-Type: application/json');
		echo json_encode($ret);
		exit();

	}



		add_action( 'wp_ajax_delFile', 'zeroBSCRM_removeFile' );
	function zeroBSCRM_removeFile(){

				$res = false;

				check_ajax_referer( 'zbscrmjs-ajax-nonce', 'sec' );

				if (
			($_POST['zbsfType'] == 'customer' && zeroBSCRM_permsCustomers()) ||
			($_POST['zbsfType'] == 'quotes' && zeroBSCRM_permsQuotes()) ||
			($_POST['zbsfType'] == 'invoices' && zeroBSCRM_permsInvoices()) 
			){

					    if (isset($_POST['zbsDel']) && !empty($_POST['zbsDel'])){

		    			    	if (isset($_POST['zbsCID']) && !empty($_POST['zbsCID'])) {

		    		$customerID = (int)$_POST['zbsCID'];
		    		$fileType = $_POST['zbsfType']; 
			        			        if (strpos('#'.$_POST['zbsDel'],',') > 0)
			            $delFiles = explode(',',$_POST['zbsDel']);
			        else
			            $delFiles = array($_POST['zbsDel']);

			        if (count($delFiles) > 0) foreach ($delFiles as $delFile){

			            zeroBS_removeFile($customerID,$fileType,$delFile);

			        }

			        $res = true;

			    }


		    }


		}


		header('Content-Type: application/json');
		echo json_encode($res);
		exit();

	} 



		add_action( 'wp_ajax_filterCustomers', 'zeroBSCRM_AJAX_filterCustomers' );
	function zeroBSCRM_AJAX_filterCustomers(){

				$res = false;

				check_ajax_referer( 'zbscrmjs-ajax-nonce', 'sec' );

		if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');


		
						global $zbsCustomerFiltersInEffect;
			$zbsCustomerFiltersInEffect = zbs_customerFiltersGetApplied(); 

						$res = zeroBS__customerFiltersRetrieveCustomerCountAndTopCustomers();
			$res['filters_in_effect'] = $zbsCustomerFiltersInEffect;


		header('Content-Type: application/json');
		echo json_encode($res);
		exit();

	}




		add_action( 'wp_ajax_zbsaddlog', 'zeroBSCRM_AJAX_addLog' );
	function zeroBSCRM_AJAX_addLog(){

		header('Content-Type: application/json');

				$res = -1;

				check_ajax_referer( 'zbscrmjs-ajax-nonce-logs', 'sec' );

			    if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');
	    

				if (isset($_POST['zbsnagainstid']) && !empty($_POST['zbsnagainstid'])) $zbsNoteAgainstPostID = (int)sanitize_text_field($_POST['zbsnagainstid']);
		if (isset($_POST['zbsntype']) && !empty($_POST['zbsntype'])) $zbsNoteType = sanitize_text_field($_POST['zbsntype']);
		if (isset($_POST['zbsnshortdesc']) && !empty($_POST['zbsnshortdesc'])) $zbsNoteShortDesc = sanitize_text_field($_POST['zbsnshortdesc']);
		if (isset($_POST['zbsnlongdesc']) && !empty($_POST['zbsnlongdesc'])) $zbsNoteLongDesc = zeroBSCRM_textProcess($_POST['zbsnlongdesc']);
		
				$zbsNoteIDtoUpdate = -1;
		if (isset($_POST['zbsnoverwriteid']) && !empty($_POST['zbsnoverwriteid'])) $zbsNoteIDtoUpdate = (int)sanitize_text_field($_POST['zbsnoverwriteid']);

				if (
			isset($zbsNoteAgainstPostID) &&
			isset($zbsNoteType) &&
			isset($zbsNoteShortDesc) &&
			!empty($zbsNoteAgainstPostID) && $zbsNoteAgainstPostID > 0 && 
			!empty($zbsNoteType) &&
			!empty($zbsNoteShortDesc)

		){

						$newOrUpdatedLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,$zbsNoteIDtoUpdate,-1,array(
								'type' => $zbsNoteType,
				'shortdesc' => $zbsNoteShortDesc,
				'longdesc' => $zbsNoteLongDesc,
			));

						$res = $newOrUpdatedLogID;

						if (!empty($res)){

				zeroBSCRM_FireInternalAutomator('log.new',array(
					'id'=>$newOrUpdatedLogID,
					'logagainst'=>$zbsNoteAgainstPostID,
					'logtype'=> $zbsNoteType,
					'logshortdesc' => $zbsNoteShortDesc,
					'loglongdesc' => $zbsNoteLongDesc
					));
			}


		}

		echo json_encode(array('processed'=>$res));
		exit();

	}


		add_action( 'wp_ajax_zbsupdatelog', 'zeroBSCRM_AJAX_updateLog' );
	function zeroBSCRM_AJAX_updateLog(){

		header('Content-Type: application/json');

				$res = -1;

				check_ajax_referer( 'zbscrmjs-ajax-nonce-logs', 'sec' );

			    if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');
	    

				if (isset($_POST['zbsnprevid']) && !empty($_POST['zbsnprevid'])) $zbsNoteID = (int)sanitize_text_field($_POST['zbsnprevid']);

		if (isset($_POST['zbsnagainstid']) && !empty($_POST['zbsnagainstid'])) $zbsNoteAgainstPostID = (int)sanitize_text_field($_POST['zbsnagainstid']);
		if (isset($_POST['zbsntype']) && !empty($_POST['zbsntype'])) $zbsNoteType = sanitize_text_field($_POST['zbsntype']);
		if (isset($_POST['zbsnshortdesc']) && !empty($_POST['zbsnshortdesc'])) $zbsNoteShortDesc = sanitize_text_field($_POST['zbsnshortdesc']);
		if (isset($_POST['zbsnlongdesc']) && !empty($_POST['zbsnlongdesc'])) $zbsNoteLongDesc = zeroBSCRM_textProcess($_POST['zbsnlongdesc']);
		
				if (
			isset($zbsNoteID) &&
			isset($zbsNoteAgainstPostID) &&
			isset($zbsNoteType) &&
			isset($zbsNoteShortDesc) &&
			!empty($zbsNoteID) && $zbsNoteID > 0 && 
			!empty($zbsNoteAgainstPostID) && $zbsNoteAgainstPostID > 0 && 
			!empty($zbsNoteType) &&
			!empty($zbsNoteShortDesc)

		){

						$newOrUpdatedLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,$zbsNoteID,-1,array(
								'type' => $zbsNoteType,
				'shortdesc' => $zbsNoteShortDesc,
				'longdesc' => $zbsNoteLongDesc,
			));

						$res = $newOrUpdatedLogID;

						if (!empty($res)){

				zeroBSCRM_FireInternalAutomator('log.update',array(
					'id'=>$zbsNoteID,
					'logagainst'=>$zbsNoteAgainstPostID,
					'logtype'=> $zbsNoteType,
					'logshortdesc' => $zbsNoteShortDesc,
					'loglongdesc' => $zbsNoteLongDesc
					));
			}

		}


		echo json_encode(array('processed'=>$res));
		exit();

	}

		add_action( 'wp_ajax_zbsdellog', 'zeroBSCRM_AJAX_deleteLog' );
	function zeroBSCRM_AJAX_deleteLog(){

		header('Content-Type: application/json');

				$res = -1;

				check_ajax_referer( 'zbscrmjs-ajax-nonce-logs', 'sec' );

			    if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');
	    

				if (isset($_POST['zbsnid']) && !empty($_POST['zbsnid'])) $zbsNoteID = (int)sanitize_text_field($_POST['zbsnid']);

				if (
			isset($zbsNoteID) &&
			!empty($zbsNoteID)
		){

						$res = wp_delete_post($zbsNoteID,false); 			if (isset($res) && isset($res->ID)) {

				$res = 1;

								zeroBSCRM_FireInternalAutomator('log.delete',array(
					'id'=>$zbsNoteID
					));
				
			} else
				$res = -1;

		}


		echo json_encode(array('processed'=>$res));
		exit();

	}











add_action( 'wp_ajax_zbs_get_quote_template', 'ZeroBSCRM_get_quote_template' );
function ZeroBSCRM_get_quote_template(){

		$content = array();

	    check_ajax_referer( 'quo-ajax-nonce', 'security' );  
	    if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');
    if (!zeroBSCRM_permsQuotes()) exit('{processed:-1}');

        $customer_ID = -1; if (isset($_POST['cust_id'])) $customer_ID =  (int)sanitize_text_field($_POST['cust_id']);
    $quote_template_ID = -1; if (isset($_POST['quote_type'])) $quote_template_ID =  (int)sanitize_text_field($_POST['quote_type']);
    $quote_title = ''; if (isset($_POST['quote_title'])) $quote_title =  sanitize_text_field($_POST['quote_title']);
    $quote_val = ''; if (isset($_POST['quote_val'])) $quote_val = zeroBSCRM_getCurrencyChr().sanitize_text_field($_POST['quote_val']);
    $quote_date = ''; if (isset($_POST['quote_dt'])) $quote_date = sanitize_text_field($_POST['quote_dt']);

        if ($customer_ID !== -1 && $quote_template_ID !== -1){

                $your_biz_name = zeroBSCRM_getSetting('businessname');       
        $customerName = zeroBS_getCustomerNameShort($customer_ID);
                                $bizState = '[STATE]';  
                if (empty($quote_title)) $quote_title = '[QUOTETITLE]';
        if (empty($quote_val)) $quote_val = '[QUOTEVALUE]';
        if (empty($quote_date)) $quote_date = date('d/m/Y',time());

                $quoteTemplate = zeroBS_getQuoteTemplate($quote_template_ID);

        if (isset($quoteTemplate) && is_array($quoteTemplate) && isset($quoteTemplate['content'])){

        	$workingHTML = $quoteTemplate['content'];

        	        	$workingHTML = str_replace('##CUSTOMERNAME##',$customerName,$workingHTML);
        	$workingHTML = str_replace('##BIZNAME##',$your_biz_name,$workingHTML);
        	$workingHTML = str_replace('##BIZSTATE##',$bizState,$workingHTML);
        	$workingHTML = str_replace('##QUOTETITLE##',$quote_title,$workingHTML);
        	$workingHTML = str_replace('##QUOTEVALUE##',$quote_val,$workingHTML);
        	$workingHTML = str_replace('##QUOTEDATE##',$quote_date,$workingHTML);


	        	        $content['html'] = $workingHTML;

	        			header('Content-Type: application/json');
		    echo json_encode($content);
		    exit();

	    } 
    } 
	header('Content-Type: application/json');
	echo json_encode(array('error'=>1));
	exit();

}




add_action( 'wp_ajax_zbs_quotes_send_quote', 'ZeroBSCRM_send_quote_email' );
function ZeroBSCRM_send_quote_email(){

		$content = array();

		check_ajax_referer( 'zbscrmjs-ajax-nonce', 'sec' );

	    if (!zeroBSCRM_permsCustomers()) exit('{processed:-1}');
    if (!zeroBSCRM_permsQuotes()) exit('{processed:-1}');

        $quoteID = -1; if (isset($_POST['qid'])) $quoteID =  (int)sanitize_text_field($_POST['qid']);
    $customerEmail = ''; if (isset($_POST['em'])) $customerEmail =  sanitize_text_field($_POST['em']);

		if (!zeroBSCRM_validateEmail($customerEmail)){
		$r['message'] = 'Not a valid email';
		echo json_encode($r);
		die();
	}

		if ($quoteID == -1 || empty($customerEmail)){
		die();
	}
    
        $body = zeroBSCRM_quote_generateNotificationHTML($quoteID,true);
	$biz_name = zeroBSCRM_getSetting('businessname');

	$subject = 'Your Proposal';
	if (!empty($biz_name)) $subject .= ' (from ' . $biz_name.')';
	$headers = array('Content-Type: text/html; charset=UTF-8');	

	wp_mail( $customerEmail, $subject, $body, $headers );

		
		$r['message'] = 'All done OK';
	echo json_encode($r);
	die(); }


add_action( 'wp_ajax_zbs_quotes_accept_quote', 'ZeroBSCRM_accept_quote' );
function ZeroBSCRM_accept_quote(){

		$content = array();

		check_ajax_referer( 'zbscrmquo-nonce', 'sec' );

        $quoteID = -1; if (isset($_POST['qid'])) $quoteID =  (int)sanitize_text_field($_POST['qid']);
    $quoteHash = ''; if (isset($_POST['qhash'])) $quoteHash =  sanitize_text_field($_POST['qhash']);
    $quoteSignedBy = ''; if (isset($_POST['signer'])) $quoteSignedBy =  sanitize_text_field($_POST['signer']);

            
        $zbsQuoteData = zeroBS_getQuote($quoteID,true);
    if (isset($zbsQuoteData) && isset($zbsQuoteData['meta']) && is_array($zbsQuoteData['meta'])){

    	
    	    	$correctHash = get_post_meta($quoteID,'zbshash',true);
        if ($correctHash === $quoteHash) {

        	
        		        		zeroBS_markQuoteAccepted($quoteID,$quoteSignedBy);

        		        		$quoteCreatorEmail = zeroBS_getOwnerEmail($quoteID);

        		        		if (!empty($quoteCreatorEmail)){

				    				    $body = zeroBSCRM_quote_generateAcceptNotifHTML($quoteID,$quoteSignedBy,true);

					$subject = __w('Proposal Accepted','zerobscrm');
					if (!empty($quoteSignedBy)) $subject .= ' (by ' . $quoteSignedBy.')';
					$headers = array('Content-Type: text/html; charset=UTF-8');	

					wp_mail( $quoteCreatorEmail, $subject, $body, $headers );

										
				}

						header('Content-Type: application/json');
			echo json_encode(array('success'=>1));
			exit();

		}

	}

		header('Content-Type: application/json');
	echo json_encode(array('error'=>1));
	exit();


}











	function zbs_lead_form_views(){
						$zbs_form_id = (int)sanitize_text_field($_POST['id']);   		$zbs_form_views = get_post_meta($zbs_form_id,'zbs_form_views',true);
		if($zbs_form_views == ''){
			$zbs_form_views = 1;
		}else{
			$zbs_form_views++;
		}
		update_post_meta($zbs_form_id, 'zbs_form_views', $zbs_form_views);
		$r['message'] = 'form view has been recorded';
		$r['view_count'] = $zbs_form_views;
		$r['form_id'] = $zbs_form_id;
		echo json_encode($r);
		exit();
	}
	add_action('wp_ajax_nopriv_zbs_lead_form_views','zbs_lead_form_views');
	add_action( 'wp_ajax_zbs_lead_form_views', 'zbs_lead_form_views' );

	#} Handle form submissions interesting to see how this works cross domain...
	function zbs_lead_form_capture() {
	     
	   
	   		    $r = array();

	    		$reCaptcha = zeroBSCRM_getSetting('usegcaptcha');
		$reCaptchaKey = zeroBSCRM_getSetting('gcaptchasitekey');
		$reCaptchaSecret = zeroBSCRM_getSetting('gcaptchasitesecret');

		if ($reCaptcha && !empty($reCaptchaKey) && !empty($reCaptchaSecret)){

						$reCaptchaOkay = false;
		   
						$possibleCaptchaResponse = ''; if (isset($_POST['recaptcha']) && !empty($_POST['recaptcha'])) $possibleCaptchaResponse = sanitize_text_field($_POST['recaptcha']);

						$gSays = zeroBSCRM_retrieve('https://www.google.com/recaptcha/api/siteverify',false,false,array(
				'secret' => $reCaptchaSecret,
				'response' => $possibleCaptchaResponse
							));

						if (!empty($gSays)) {

								$gSaysObj = json_decode($gSays);

				if (isset($gSaysObj->success) && $gSaysObj->success) $reCaptchaOkay = true;

			}

						if (!$reCaptchaOkay){

						    	$r['message'] = 'Nope.';
		    	$r['code'] = 'recaptcha';
		    	echo json_encode($r);
		    	wp_die();

			}

		}

			    $zbs_form_id = -1;
	    if (isset($_POST['zbs_form_id']) && !empty($_POST['zbs_form_id'])) $zbs_form_id = (int)sanitize_text_field($_POST['zbs_form_id']);  
				if (empty($zbs_form_id)){

				    	$r['message'] = 'Nope.';
	    	$r['code'] = 'form';
	    	echo json_encode($r);
	    	wp_die();

		}

	    	    $zbs_honey = $_POST['zbs_hpot_email'];  	    if($zbs_honey != ''){
	    		    	$r['message'] = 'This is a honeypot.. something has gone wrong can alert the member on response';
	    	$r['code'] = 'honey';
	    	echo json_encode($r);
	    	wp_die();
	    } else {


						if (isset($_POST['zbs_email']) && !empty($_POST['zbs_email']) && zeroBSCRM_validateEmail($_POST['zbs_email'])){

								
			} else {

						    	$r['message'] = 'Email Required.';
		    	$r['code'] = 'emailfail';
		    	echo json_encode($r);
		    	wp_die();

			}

	    		    	$zbs_form_style = (string)$_POST['zbs_form_style'];

	    		    	if (!in_array($zbs_form_style,array('zbs_simple','zbs_naked','zbs_cgrab'))) $zbs_form_style = '';

			



			
								$formTitle = get_the_title($zbs_form_id); if (empty($formTitle)) $formTitle = '#'.$zbs_form_id;

								$pageID = ''; if (isset($_POST['pid']) && !empty($_POST['pid'])) $pageID = (int)sanitize_text_field($_POST['pid']);
				$fromPageName = ''; if (!empty($pageID)) $fromPageName = get_the_title($pageID);

								$formStyle = ''; 
				if ($zbs_form_style == 'zbs_simple') $formStyle = 'Simple';
				if ($zbs_form_style == 'zbs_naked') $formStyle = 'Naked';
				if ($zbs_form_style == 'zbs_cgrab') $formStyle = 'Content Grab';
				$formStyleStr = ''; if (!empty($formStyle)) $formStyleStr = ' ('.$formStyle.')';

												
								if (!empty($pageID)){
					
					
												$existingUserFormSourceShort = 'User completed form <i class="fa fa-wpforms"></i>';
						$existingUserFormSourceLong = 'Form <span class="zbsEmphasis">'.$formTitle.'</span>'.$formStyleStr.', which was filled out from the page: <span class="zbsEmphasis">'.$fromPageName.'</span> (#'.$pageID.')';

												$newUserFormSourceShort = 'Created from Form Capture <i class="fa fa-wpforms"></i>';
						$newUserFormSourceLong = 'User created from the form <span class="zbsEmphasis">'.$formTitle.'</span>'.$formStyleStr.', which was filled out from the page: <span class="zbsEmphasis">'.$fromPageName.'</span> (#'.$pageID.')';


				} else {
				
					
												$existingUserFormSourceShort = 'User completed form <i class="fa fa-wpforms"></i>';
						$existingUserFormSourceLong = 'Form <span class="zbsEmphasis">'.$formTitle.'</span>'.$formStyleStr.', which was filled out from an externally embedded form.';

												$newUserFormSourceShort = 'Created from Form Capture <i class="fa fa-wpforms"></i>';
						$newUserFormSourceLong = 'User created from the form <span class="zbsEmphasis">'.$formTitle.'</span>'.$formStyleStr.', which was filled out from an externally embedded form.';

				}

								$fallBackLog = array(
							'type' => 'Form Filled',							'shortdesc' => $existingUserFormSourceShort,
							'longdesc' => $existingUserFormSourceLong
						);

								$internalAutomatorOverride = array(

							'note_override' => array(
						
										'type' => 'Form Filled',										'shortdesc' => $newUserFormSourceShort,
										'longdesc' => $newUserFormSourceLong				

							)

						);

						
						
	    	switch($zbs_form_style){

	    		case "zbs_simple":

		    				    		$zbs_email = sanitize_text_field($_POST['zbs_email']); 		    							zeroBS_integrations_addOrUpdateCustomer('form',$zbs_email,
						array(

					    													    	'zbsc_status' => 'Lead',


					    	'zbsc_email' => $zbs_email,
					    ),
					    
					    '', 						
												$fallBackLog,

						false, 
												$internalAutomatorOverride

					); 

					
		    				    		$zbs_form_conversions = get_post_meta($zbs_form_id, 'zbs_form_conversions', true);
		    		if($zbs_form_conversions == ''){
		    			$zbs_form_conversions = 1;
		    		}else{
		    			$zbs_form_conversions++;
		    		}
		    		update_post_meta($zbs_form_id,'zbs_form_conversions', $zbs_form_conversions);  
	    		break;

	    		case "zbs_naked":

	    			
		    				    		$zbs_email = sanitize_text_field($_POST['zbs_email']); 		    		$zbs_fname = sanitize_text_field($_POST['zbs_fname']);
		    				    		
		    							zeroBS_integrations_addOrUpdateCustomer('form',$zbs_email,
						array(
							
					    													    	'zbsc_status' => 'Lead',

				    	'zbsc_email' => $zbs_email,
				    	'zbsc_fname' => $zbs_fname
				    					    					    	),
					    
					    '', 						
												$fallBackLog,

						false, 
												$internalAutomatorOverride
				    ); 

					
		    				    		$zbs_form_conversions = get_post_meta($zbs_form_id, 'zbs_form_conversions', true);
		    		if($zbs_form_conversions == ''){
		    			$zbs_form_conversions = 1;
		    		}else{
		    			$zbs_form_conversions++;
		    		}
		    		update_post_meta($zbs_form_id,'zbs_form_conversions', $zbs_form_conversions);  

	    		break;
	    		case "zbs_cgrab":

		    				    		$zbs_email = sanitize_text_field($_POST['zbs_email']); 		    		$zbs_fname = sanitize_text_field($_POST['zbs_fname']);
		    		$zbs_lname = sanitize_text_field($_POST['zbs_lname']);
		    				    				    			$zbs_notes = "<blockquote>Customer Form Submit Message:<br />===========<br />".zeroBSCRM_textProcess($_POST['zbs_notes'])."<br />===========</blockquote>";


		    			
		    						    				$fallBackLog['longdesc'] .= $zbs_notes;

		    						    				$internalAutomatorOverride['note_override']['longdesc'] .= $zbs_notes;


		    							zeroBS_integrations_addOrUpdateCustomer('form',$zbs_email,
						array(
							
					    													    	'zbsc_status' => 'Lead',

				    	'zbsc_email' => $zbs_email,
				    	'zbsc_fname' => $zbs_fname,
				    	'zbsc_lname' => $zbs_lname,
				    					    	),
					    
					    '', 						
												$fallBackLog,

						false, 
												$internalAutomatorOverride
				    ); 

					
		    				    		$zbs_form_conversions = get_post_meta($zbs_form_id, 'zbs_form_conversions', true);
		    		if($zbs_form_conversions == ''){
		    			$zbs_form_conversions = 1;
		    		}else{
		    			$zbs_form_conversions++;
		    		}
		    		update_post_meta($zbs_form_id,'zbs_form_conversions', $zbs_form_conversions);  
	    		break;
	    		default:
	    		exit();  	    	}

	    		    		    		    	$r['message'] = 'Contact received.';
	    	$r['code'] = 'success';
	    	echo json_encode($r);
	    	die(); 

	    }

	}
	add_action('wp_ajax_nopriv_zbs_lead_form_capture','zbs_lead_form_capture');
	add_action( 'wp_ajax_zbs_lead_form_capture', 'zbs_lead_form_capture' );

	






		define('ZBSCRM_INC_AJAX',true);