<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.15
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 30/08/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;







 



global $zeroBSCRM_IA_ActiveAutomations, $zeroBSCRM_IA_Dupeblocks; $zeroBSCRM_IA_Dupeblocks = array('quote.new','invoice.new','transaction.new');
function zeroBSCRM_FireInternalAutomator($actionStr='',$obj=array()){

	$goodToGo = true;

	global $zeroBSCRM_IA_ActiveAutomations, $zeroBSCRM_IA_Dupeblocks;
		if (in_array($actionStr,$zeroBSCRM_IA_Dupeblocks)){

		if (gettype($obj) != "string" && gettype($obj) != "String"){
						$objStr = json_encode($obj);
			$objStr = md5($objStr);
			$actionHash = $actionStr.$objStr;
		} else 
			$actionHash = $actionStr.md5($obj);
		
		if (isset($zeroBSCRM_IA_ActiveAutomations[$actionHash])) 
			$goodToGo = false; 		else
			$zeroBSCRM_IA_ActiveAutomations[$actionHash] = time();

	}


	if ($goodToGo && !empty($actionStr)){

		
				$actionHolderName = 'zeroBSCRM_IA_Action_'.str_replace('.','_',$actionStr);

				global $$actionHolderName;

				if (isset($$actionHolderName) && is_array($$actionHolderName)){

						foreach ($$actionHolderName as $action){

				if (isset($action['act']) && !empty($action['act']) && isset($action['params'])){

					
										if (function_exists($action['act'])){

												call_user_func($action['act'],$obj);

					}


				}

			}

		}

	}

	return;	

}


function zeroBSCRM_AddInternalAutomatorRecipe($actionStr='',$functionName='',$paramsObj=array()){

	if (!empty($actionStr) && !empty($functionName)){

		
				$actionHolderName = 'zeroBSCRM_IA_Action_'.str_replace('.','_',$actionStr);

				global $$actionHolderName;

				if (!isset($$actionHolderName)) $$actionHolderName = array();

						array_push($$actionHolderName,array('act'=>$functionName,'params'=>$paramsObj));

		return true;

	}

	return false;
}

 


   

define('ZBSCRM_INC_IA',true);