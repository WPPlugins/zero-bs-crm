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







	#} A list of applicable Mimetypes for file uploads
	global 	$zeroBSCRM_Mimes;
			$zeroBSCRM_Mimes = array(
										'pdf' => array('application/pdf'),
										'doc' => array('application/msword'),
										'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
										'ppt' => array('application/vnd.ms-powerpointtd>'),
										'pptx' => array('application/vnd.openxmlformats-officedocument.presentationml.presentation'),
										'xls' => array('application/vnd.ms-excel'),
										'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
										'csv' => array('text/csv'),
										'png' => array('image/png'),
										'jpg' => array('image/jpeg'),
										'mp3' => array('audio/mpeg'),
										'txt' => array('text/plain'),
										'zip' => array('application/zip, application/x-compressed-zip')
															);









		function zeroBS_acceptableFileTypeListStr(){

		$ret = '';
	  
		global $zeroBSCRM_Mimes, $zeroBSCRM_Settings; 
				$settings = $zeroBSCRM_Settings->getAll();
		
		if (isset($settings['filetypesupload'])) {

			if (isset($settings['filetypesupload']['all']) && $settings['filetypesupload']['all'] == 1){

				$ret = 'All File Types';

			} else {

				foreach ($settings['filetypesupload'] as $filetype => $enabled){

					if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1) {

						if (!empty($ret)) $ret .= ', ';

						$ret .= '.'.$filetype;

					}

				} 

			}

		}

		if (empty($ret)) $ret = 'No Uploads Allowed';

		return $ret;
	}

	function zeroBS_acceptableFileTypeListArr(){

		$ret = array();
	  
		global $zeroBSCRM_Mimes, $zeroBSCRM_Settings; 
				$settings = $zeroBSCRM_Settings->getAll();
		
		if (isset($settings['filetypesupload'])) 
			foreach ($settings['filetypesupload'] as $filetype => $enabled){

				if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1) $ret[] = '.'.$filetype;

			} 

		return $ret;
	}

	function zeroBS_acceptableFileTypeMIMEArr(){

		$ret = array();
	  
		global $zeroBSCRM_Mimes, $zeroBSCRM_Settings; 
				$settings = $zeroBSCRM_Settings->getAll();
		
		if (isset($settings['filetypesupload'])) 
			foreach ($settings['filetypesupload'] as $filetype => $enabled){

				if (isset($settings['filetypesupload'][$filetype]) && $enabled == 1) $ret[] = $zeroBSCRM_Mimes[$filetype][0];

			} 

		return $ret;
	}



		function zeroBS_removeFile($customerID=-1,$fileType,$fileURL=''){

	  	if ( current_user_can( 'manage_options' ) ) {   
			if ($customerID !== -1 && !empty($fileURL)){
				
				switch ($fileType){

					case 'customer':

						$filesArrayKey = 'zbs_customer_files';

						break;
					case 'quotes':

						$filesArrayKey = 'zbs_customer_quotes';

						break;
					case 'invoices':

						$filesArrayKey = 'zbs_customer_invoices';

						break;



				}

								if (isset($filesArrayKey)){

					
												$changeFlag = false; $fileObjToDelete = false;

												$filesList = get_post_meta($customerID, $filesArrayKey, true);
						if (is_array($filesList) && count($filesList) > 0){

														$ret = array();
							
														foreach ($filesList as $fileObj){

								if ($fileObj['url'] != $fileURL) 
									$ret[] = $fileObj;
								else {
									$fileObjToDelete = $fileObj;
									$changeFlag = true;
								}

							}

							if ($changeFlag) update_post_meta($customerID,$filesArrayKey,$ret);

						} 
										if ($changeFlag && isset($fileObjToDelete) && isset($fileObjToDelete['file'])){

																		unlink($fileObjToDelete['file']);

					}

					return true;

				}


			}

		} 

		return false;
	}


   


		define('ZBSCRM_INC_FILEUPL',true);