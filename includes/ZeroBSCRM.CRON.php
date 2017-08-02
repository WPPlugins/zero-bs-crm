<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.2.3
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 15/12/16
 */






   global 	$zbscrm_CRONList; 
   			$zbscrm_CRONList = array(

   				   				'clearAutoDrafts' => 'hourly'
   				
   			);







function zeroBSCRM_activateCrons(){


	global $zbscrm_CRONList; 
	foreach ($zbscrm_CRONList as $cronName => $timingStr)	{
		
		$hook = 'zbs'.strtolower($cronName);
		$funcName = 'zeroBSCRM_cron_'.$cronName;
		
	    if (! wp_next_scheduled ( $hook )) {
			wp_schedule_event(time(), $timingStr, $hook);
	    }

	}

}
register_activation_hook(ZBS_ROOTFILE, 'zeroBSCRM_activateCrons');
function zeroBSCRM_deactivateCrons(){

	global $zbscrm_CRONList; 
	foreach ($zbscrm_CRONList as $cronName)	{
		
		$hook = 'zbs'.strtolower($cronName);
		$funcName = 'zeroBSCRM_cron_'.$cronName;

		wp_clear_scheduled_hook($hook);

	}

}
register_deactivation_hook(ZBS_ROOTFILE, 'zeroBSCRM_deactivateCrons');








   	function zeroBSCRM_cron_clearAutoDrafts() {

				zeroBSCRM_clearCPTAutoDrafts();

	}

	add_action('zbsclearautodrafts', 'zeroBSCRM_cron_clearAutoDrafts');







define('ZBSCRM_INC_CRON',1);