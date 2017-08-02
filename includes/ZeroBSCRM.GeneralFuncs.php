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




    function zbs_write_log ( $log )  {
   
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
      
    }





         function zeroBSCRM_getDateFormat(){

   		   		global $zeroBSCRM_dateFormat; if (isset($zeroBSCRM_dateFormat)) return $zeroBSCRM_dateFormat;
   		$zeroBSCRM_dateFormat = get_option('date_format');

   		return $zeroBSCRM_dateFormat;

   }
   function zeroBSCRM_getTimeFormat(){

   		   		global $zeroBSCRM_timeFormat; if (isset($zeroBSCRM_timeFormat)) return $zeroBSCRM_timeFormat;
   		$zeroBSCRM_timeFormat = get_option('time_format');

   		return $zeroBSCRM_timeFormat;

   }




   	function zeroBSCRM_force_404() {
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
	}


   	function zeroBSCRM_GenerateHashForPost($postID=-1,$length=20){

   		   		if (!empty($postID)){

   			   			
   			$newMD5 = wp_generate_password(20, false);

   			return substr($newMD5,0,$length-1);

   		}

   		return '';

   	}

	function zeroBSCRM_loadCountryList(){
	    	    global $zeroBSCRM_countries;
	    if(!isset($zeroBSCRM_countries)) require_once(ZEROBSCRM_PATH . 'includes/wh.countries.lib.php');

	    return $zeroBSCRM_countries;
	}

	function zeroBSCRM_uniqueID(){
		
		

				return uniqid('ZBS_');

	}

	function zeroBSCRM_ifV($v){
		if (isset($v)) echo $v; 
	}

	function zbs_prettyprint($array){
		echo '<pre>';
	    var_dump($array);
	    echo '</pre>';
	}


	function zeroBS_delimiterIf($delimiter,$ifStr=''){

		if (!empty($ifStr)) return $delimiter;

		return '';
	}



	function zeroBSCRM_isLoginPage(){
				if ( in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) ) ) return true;
		return false;
	}

	function zeroBSCRM_isWelcomeWizPage(){
		
		global $zeroBSCRM_killDenied;
		if (isset($zeroBSCRM_killDenied) && $zeroBSCRM_killDenied === true) return true;
		return false;

	}

	function zeroBSCRM_is_existingcustomer_edit_page(){
	    
	    global $pagenow;

	    	    if (!is_admin()) return false;

	    $pageIs = in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

	    if ($pageIs){

	    		    	if (
	    		(isset($_GET['post']) && 'zerobs_customer' === get_post_type( $_GET['post'] ))
	    			    			    			    			    		){
		        
		        				return true;

		    }

	    }

	    return false;
	}

	function zeroBSCRM_is_existingcompany_edit_page(){
	    
	    global $pagenow;

	    	    if (!is_admin()) return false;

	    $pageIs = in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

	    if ($pageIs){

	    		    	if (
	    		(isset($_GET['post']) && 'zerobs_company' === get_post_type( $_GET['post'] ))
	    			    			    			    			    		){
		        
		        				return true;

		    }

	    }

	    return false;
	}

	function zeroBSCRM_is_customer_edit_page(){
	    
	    global $pagenow;

	    	    if (!is_admin()) return false;

	    $pageIs = in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

	    if ($pageIs){

	    		    	if (
	    		(isset($_GET['post']) && 'zerobs_customer' === get_post_type( $_GET['post'] ))
	    			    			    			    		){
		        
		        				return true;

		    }

	    }

	    return false;
	}

	function zeroBSCRM_is_company_edit_page(){
	    
	    global $pagenow;

	    	    if (!is_admin()) return false;

	    $pageIs = in_array( $pagenow, array( 'post.php', 'post-new.php' ) );

	    if ($pageIs){

	    		    	if (
	    		(isset($_GET['post']) && 'zerobs_company' === get_post_type( $_GET['post'] ))
	    		||
	    		(isset($_GET['post_type']) && 'zerobs_company' === $_GET['post_type'] )
	    		){
		        
		        				return true;

		    }

	    }

	    return false;
	}

	function zeroBSCRM_is_edit_page($new_edit = null){
	    global $pagenow;
	    	    if (!is_admin()) return false;


	    if($new_edit == "edit")
	        return in_array( $pagenow, array( 'post.php',  ) );
	    elseif($new_edit == "new") 	        return in_array( $pagenow, array( 'post-new.php' ) );
	    else 	        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}

		function zeroBSCRM_isAdminPage(){
		
		global $zeroBSCRM_slugs;
		
		$isOurPage = false;	
		if (isset($_GET['page'])) if (in_array($_GET['page'],$zeroBSCRM_slugs)) $isOurPage = true; 
		
		return $isOurPage;
		
	}

	function zeroBSCRM_isAPIRequest(){
		
						
				if (isset($_SERVER['SCRIPT_URL']) && strpos('#'.$_SERVER['SCRIPT_URL'], '/zbs_api/') > 0) return true;

		return false;
	}

	function zeroBSCRM_isClientPortalPage(){
		
						
				if (isset($_SERVER['SCRIPT_URL']) && strpos('#'.$_SERVER['SCRIPT_URL'], '/clients/') > 0) return true;

				if (isset($_SERVER['SCRIPT_URL']) && strpos('#'.$_SERVER['SCRIPT_URL'], '/proposal/') > 0) return true;

		return false;
	}

		function zeroBSCRM_prettifyLongInts($i){
		
		if ((int)$i > 999){
			return number_format($i);	
		} else {
			if (zeroBSCRM_numberOfDecimals($i) > 2) return round($i,2); else return $i;	
		}
		
	}

		function zeroBSCRM_prettyAbbr($size) {
	    $size = preg_replace('/[^0-9]/','',$size);
	    $sizes = array("", "k", "m");
	    if ($size == 0) { return('n/a'); } else {
	    return (round($size/pow(1000, ($i = floor(log($size, 1000)))), 0) . $sizes[$i]); }
	}


		function zeroBSCRM_numberOfDecimals($value)
	{
		if ((int)$value == $value)
		{
			return 0;
		}
		else if (! is_numeric($value))
		{
			return false;
		}

		return strlen($value) - strrpos($value, '.') - 1;
	}


	function zeroBSCRM_mtime_float(){
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}     

		function zeroBSCRM_getRealIpAddr()
	{
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
				elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}






	function zeroBSCRM_findAB($html,$first,$nextStr,$fallbackCloser='</'){

		$f1 = strpos($html,$first);
		$f1end = $f1 + strlen($first);
		if ($f1){
			$f2 = strpos(substr($html,$f1end),$nextStr);
			if (!$f2){
								$f2 = strpos(substr($html,$f1end),$fallbackCloser);
			}
			if (!$f2) $f2 = strlen(substr($html,$f1end));
			return substr($html,$f1end,$f2);
		}

				return '';
	}

		function zeroBSCRM_retrieve($u,$withType=false,$return404s=false,$passPost=array()){
		
		try {
			if( function_exists('curl_init') ) { 

					$ch = curl_init($u);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
																				$ua='Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';
					 if ($ua!="") {
							curl_setopt($ch, CURLOPT_USERAGENT, $ua);
						} else {
							curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
						}

										if (is_array($passPost) && count($passPost) > 0){

												foreach($passPost as $key=>$value) { $passPost_string .= $key.'='.$value.'&'; }
						rtrim($passPost_string, '&');

						curl_setopt($ch,CURLOPT_POST, count($passPost));
						curl_setopt($ch,CURLOPT_POSTFIELDS, $passPost_string);

					}

					$ret = curl_exec($ch);
					$headerhttpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					if ($withType) $ctype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
					curl_close($ch);

					if ($headerhttpcode == 404 || $headerhttpcode == 500) $ret = '';

					
			} else $ret = file_get_contents($u);
		} catch (Exception $e){
			$ret = false;	
		}

		if ($withType)
			return array($ret,$ctype);
		else
			return $ret;
		
	}
				function zeroBSCRM_retrieveFile($u,$filepath){

	 	 $fp = fopen($filepath, 'w+');
	     $ch = curl_init($u);

	     curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	     curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	     	     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	     curl_setopt($ch, CURLOPT_FILE, $fp);
	     curl_exec($ch);

	     curl_close($ch);
	     fclose($fp);

	     return (filesize($filepath) > 0)? true : false;

	}
		function zeroBSCRM_expandArchive($filepath,$expandTo){

						if (zeroBSCRM_checkSystemFeat('zlib')){

						try {

				if (file_exists($filepath) && file_exists($expandTo)){

					$zip = new ZipArchive;
					$res = $zip->open($filepath);
					if ($res === TRUE) {
					  $zip->extractTo($expandTo);
					  $zip->close();
					  return true;
					}

				}

			} catch (exception $ex){


			}

		} else {

						if (!class_exists('PclZip')) require_once(ZEROBSCRM_PATH . 'includes/lib/pclzip-2-8-2/pclzip.lib.php');

						try {

				if (file_exists($filepath) && file_exists($expandTo)){

						$archive = new PclZip($filepath);

						if ($archive->extract(PCLZIP_OPT_PATH, $expandTo) == 0) {
						    
						    return false;

						} else {
						    
						    return true;

						}


				}

			} catch (exception $ex){


			}




		}

		return false;

	}






		define('ZBSCRM_INC_GENFUNC',true);