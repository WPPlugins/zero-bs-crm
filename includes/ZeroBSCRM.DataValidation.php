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







		function zeroBSCRM_textProcess($title){
		return htmlentities(stripslashes($title),ENT_QUOTES,'UTF-8');
	} 
	function zeroBSCRM_textExpose($title){
		return html_entity_decode($title,ENT_QUOTES,'UTF-8');
	} 








		function zeroBSCRM_validateEmail($emailAddr){

		if (filter_var($emailAddr, FILTER_VALIDATE_EMAIL)) return true;

		return false;

	}


			function zeroBSCRM_validateUSTel($string=''){

		$isPhoneNum = false;

				$justNums = preg_replace("/[^0-9]/", '', $string);

				if (strlen($justNums) == 11) $justNums = preg_replace("/^1/", '',$justNums);

				if (strlen($justNums) == 10) $isPhoneNum = true;

		return $isPhoneNum;
	}

			function zeroBSCRM_validateUKMob($aNumber=''){
		$origNo = $aNumber;
										
				$aNumber = preg_replace("/[^0-9]/", '', $origNo);
		if (substr($aNumber,0,1) == '0') $aNumber = substr($aNumber,1);
						
	    return preg_match('/(^\d{12}$)|(^\d{10}$)/', $aNumber) && preg_match('/(^7)|(^447)/', $aNumber);

	}

		function zeroBSCRM_ValidateMob($number){

		$nation = zeroBSCRM_getSetting('googcountrycode');

		switch ($nation){

			case 'GB':
				return zeroBSCRM_validateUKMob($number);
				break;
			case 'US':
				return zeroBSCRM_validateUSTel($number);
				break;
			default:
				return true;
				break;


		}

		return false;

	}





		define('ZBSCRM_INC_DATAVAL',true);