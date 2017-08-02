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








function zeroBS_integrations_getCustomer($externalSource='',$externalID=''){

		$potentialCustomerID = zeroBS_getCustomerIDWithExternalSource($externalSource,$externalID);

	if ($potentialCustomerID !== false){

				return zeroBS_getCustomer($potentialCustomerID);

	}

		return false;

}


function zeroBS_integrations_addOrUpdateCustomer($externalSource='',$externalID='',$customerFields=array(), $customerDate = '', $fallbackLog='auto', $extraMeta = false, $automatorPassthroughArray = false, $emailAlreadyExistsAction = 'update'){

		$usualUpdate = true;

	if (!empty($externalSource) && !empty($externalID) && is_array($customerFields) && count($customerFields) > 0){

		if (isset($customerFields['zbsc_email']) && !empty($customerFields['zbsc_email'])){

						$potentialCustomerIDfromEmail = zeroBS_getCustomerIDWithEmail($customerFields['zbsc_email']);

						if (!empty($potentialCustomerIDfromEmail)){

								switch ($emailAlreadyExistsAction){

					
					case 'update':

												$usualUpdate = true;

						break;
					case 'skip':

												$usualUpdate = false;


						break;
					case 'notifyexit':

												echo 'Customer Add/Update Issue: A customer already exists with the email "'.$customerFields['zbsc_email'].'" (ID: '.$potentialCustomerIDfromEmail.'), user could not be processed!';
						exit();

						break;



				}

			}

		}

								if ($usualUpdate){

						$potentialCustomerID = zeroBS_getCustomerIDWithExternalSource($externalSource,$externalID);

						if ($potentialCustomerID === false && $potentialCustomerIDfromEmail !== false) $potentialCustomerID = $potentialCustomerIDfromEmail;

						$fallbackLogToPass = false;
			if ( 
				!isset($fallbackLog) ||
				!is_array($fallbackLog)
				) {

								if ($fallbackLog !== 'none'){

																				
				}

			} elseif (is_array($fallbackLog)){

								$fallbackLogToPass = $fallbackLog;

			}

									$automatorPassthrough = false; if (isset($automatorPassthroughArray) && is_array($automatorPassthroughArray)) $automatorPassthrough = $automatorPassthroughArray;

						$extraMeta = false;

						$customerID = zeroBS_addUpdateCustomer($potentialCustomerID,$customerFields,$externalSource,$externalID, $customerDate, $fallbackLogToPass, $extraMeta, $automatorPassthrough);

						if ($customerID !== false) zbsCustomer_updateCustomerNameInPostTitle($customerID,false);

			return $customerID;


		} 
	} else{
		return false;
	}

}




function zeroBS_integrations_addOrUpdateCompany(
	$externalSource='',
	$externalID='',
	$companyFields=array(), 
	$companyDate = '', 
	$fallbackLog='auto', 
	$extraMeta = false, 
	$automatorPassthroughArray = false, 
	$conameAlreadyExistsAction = 'update'){

		$usualUpdate = true;

	if (!empty($externalSource) && !empty($externalID) && is_array($companyFields) && count($companyFields) > 0){

		if (isset($companyFields['zbsc_coname']) && !empty($companyFields['zbsc_coname'])){

						$potentialCompanyIDfromName = zeroBS_getCompanyIDWithName($companyFields['zbsc_coname']);

						if (!empty($potentialCompanyIDfromName)){

								switch ($conameAlreadyExistsAction){

					
					case 'update':

												$usualUpdate = true;

						break;
					case 'skip':

												$usualUpdate = false;


						break;
					case 'notifyexit':

												echo 'Company Add/Update Issue: A customer already exists with the name "'.$companyFields['zbsc_coname'].'" (ID: '.$potentialCompanyIDfromName.'), user could not be processed!';
						exit();

						break;



				}

			}

		}

								if ($usualUpdate){

						$potentialCompanyID = zeroBS_getCompanyIDWithExternalSource($externalSource,$externalID);

						if ($potentialCompanyID === false && $potentialCompanyIDfromName !== false) $potentialCompanyID = $potentialCompanyIDfromName;

						$fallbackLogToPass = false;
			if ( 
				!isset($fallbackLog) ||
				!is_array($fallbackLog)
				) {

								if ($fallbackLog !== 'none'){

																				
				}

			} elseif (is_array($fallbackLog)){

								$fallbackLogToPass = $fallbackLog;

			}

									$automatorPassthrough = false; if (isset($automatorPassthroughArray) && is_array($automatorPassthroughArray)) $automatorPassthrough = $automatorPassthroughArray;

						$extraMeta = false;

						$companyID = zeroBS_addUpdateCompany($potentialCompanyID,$companyFields,$externalSource,$externalID, $companyDate, $fallbackLogToPass, $extraMeta, $automatorPassthrough);

						if ($companyID !== false) zbsCustomer_updateCompanyNameInPostTitle($companyID,false);

			return $companyID;


		} 
	} else{
		return false;
	}

}



function zeroBS_integrations_getCompany($externalSource='',$externalID=''){

		$potentialCompanyID = zeroBS_getCompanyIDWithExternalSource($externalSource,$externalID);

	if ($potentialCompanyID !== false){

				return zeroBS_getCompany($potentialCompanyID);

	}

		return false;

}










function zeroBS_integrations_addOrUpdateTransaction(
	$externalSource='', 
	$externalID='',  
	$transactionFields=array(),  
	$transactionTags=array(), 
	$transactionDate = '', 
	$fallbackLog='auto', 
	$extraMeta = false, 
	$automatorPassthroughArray = false){

		if (
		!empty($externalSource) && !empty($externalID) && is_array($transactionFields) && count($transactionFields) > 0 &&
		isset($transactionFields['orderid']) && !empty($transactionFields['orderid']) &&
		isset($transactionFields['customer']) && !empty($transactionFields['customer']) &&
		isset($transactionFields['status']) && !empty($transactionFields['status']) &&
		isset($transactionFields['total']) && !empty($transactionFields['total'])
		){



						$potentialTransactionID = zeroBS_getTransactionIDWithExternalSource($externalSource,$externalID);

						$fallbackLogToPass = false;
			if ( 
				!isset($fallbackLog) ||
				!is_array($fallbackLog)
				) {

								if ($fallbackLog !== 'none'){

																				
				}

			} elseif (is_array($fallbackLog)){

								$fallbackLogToPass = $fallbackLog;

			}

									$automatorPassthrough = false; if (isset($automatorPassthroughArray) && is_array($automatorPassthroughArray)) $automatorPassthrough = $automatorPassthroughArray;

						$transactionWPID = zeroBS_addUpdateTransaction($potentialTransactionID, $transactionFields, $externalSource, $externalID, $transactionDate, $transactionTags, $fallbackLogToPass, $extraMeta, $automatorPassthrough);

						
			return $transactionWPID;

	} else { 
		return false;

	}

}


function zeroBS_integrations_getTransaction($externalSource='',$externalID=''){

		$potentialTransactionID = zeroBS_getTransactionIDWithExternalSource($externalSource,$externalID);

	if ($potentialTransactionID !== false){

				return zeroBS_getTransaction($potentialTransactionID);

	}

		return false;

}









function zeroBS_integrations_getAllCategories($incEmpty=false){

	if (!$incEmpty){

		$cats = array(
									'zerobscrm_customertag' => get_categories(array('taxonomy'=>'zerobscrm_customertag','orderby' => 'name','order'=> 'ASC'))
		);

	} else {

		$cats = array(
									'zerobscrm_customertag' => get_terms(array('taxonomy'=>'zerobscrm_customertag','orderby' => 'name','order'=> 'ASC','hide_empty' => false))
		);

	}

	return $cats;

}


function zeroBS_integrations_searchCustomers($s=''){
	
	if (!empty($s)) return zeroBS_searchCustomers($s);

	return array();

}

function zeroBS_integrations_addLog(

		$cID = -1,
		
		$logDate = -1,

		

		$noteFields = array()

		){

	if (!empty($cID)){

				zeroBS_addUpdateLog($cID,-1,$logDate,$noteFields);

		return true;

	}

	return false;


}







		define('ZBSCRM_INC_INTEGRATE',true);