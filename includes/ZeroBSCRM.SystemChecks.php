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








			function zeroBSCRM_checkSystemFeat($key='',$withInfo=false){

		$featList = array('zlib','pdfdom','curl','autodraftgarbagecollect','phpver','locale');

		if (in_array($key,$featList) && function_exists("zeroBSCRM_checkSystemFeat_".$key)) return call_user_func_array("zeroBSCRM_checkSystemFeat_".$key,array($withInfo));

		if (!$withInfo)
			return false;
		else
			return array(false,'No Check!');

	}




function zeroBSCRM_checkSystemFeat_phpver(){

	$enabled = true;
	$enabledStr = 'PHP Version ' . phpversion();

	return array($enabled, $enabledStr);
}




	
	function zeroBSCRM_checkSystemFeat_autodraftgarbagecollect($withInfo=false){

				$lastCleared = get_option('zbscptautodraftclear','');

		if (!$withInfo){

			$enabledStr = 'Not yet cleared'; if (!empty($lastCleared)) $enabledStr = 'Cleared '.date(zeroBSCRM_getTimeFormat().' '.zeroBSCRM_getDateFormat(),$lastCleared); 
			return $enabledStr;

		} else {

			$enabled = false; $enabledStr = 'Not yet cleared'; 
			if (!empty($lastCleared)){
				$enabledStr = 'Cleared '.date(zeroBSCRM_getTimeFormat().' '.zeroBSCRM_getDateFormat(),$lastCleared); 
				$enabled = true;
			}
			return array($enabled,$enabledStr);

		}

	}






	function zeroBSCRM_checkSystemFeat_zlib($withInfo=false){


		if (!$withInfo)
			return class_exists('ZipArchive');
								else {

			$enabled = class_exists('ZipArchive');
			$str = 'zlib is properly enabled on your server.';
			if (!$enabled) $str = 'zlib is disabled on your server.';

			return array($enabled,$str);

		}


	}
	function zeroBSCRM_checkSystemFeat_pdfdom($withInfo=false){


		if (!$withInfo)
			return file_exists(ZEROBSCRM_PATH.'includes/dompdf/autoload.inc.php');
		else {

			$enabled = file_exists(ZEROBSCRM_PATH.'includes/dompdf/autoload.inc.php');
			$str = 'PDF Engine is properly installed on your server.';
			if (!$enabled) $str = 'PDF Engine is not installed on your server.';

			return array($enabled,$str);

		}


	}
	function zeroBSCRM_checkSystemFeat_curl($withInfo=false){


		if (!$withInfo)
			return function_exists('curl_init');
		else {

			$enabled = function_exists('curl_init');
			$str = 'CURL is enabled on your server.';
			if (!$enabled) $str = 'CURL is not enabled on your server.';

			return array($enabled,$str);

		}


	}
	function zeroBSCRM_checkSystemFeat_locale($withInfo=false){


		if (!$withInfo)
			return true;
		else {

			$locale = zeroBSCRM_getLocale();
			$str = 'WordPress Locale is set to '.$locale;

			return array(true,$str);

		}


	}







		define('ZBSCRM_INC_SYSCHECK',true);