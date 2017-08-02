<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.18
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 30/08/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;











global $zeroBSCRM_migrations; $zeroBSCRM_migrations = array('1119','123','127');

function zeroBSCRM_migrations_run($settingsArr=false){

	global $zeroBSCRM_migrations;

		if (gettype($settingsArr) != "array") {
	
		global $zeroBSCRM_Settings; 		$migratedAlreadyArr = $zeroBSCRM_Settings->get('migrations');

	} else $migratedAlreadyArr = $settingsArr;

		if (count($zeroBSCRM_migrations) > 0) foreach ($zeroBSCRM_migrations as $migration){

		if (!in_array($migration,$migratedAlreadyArr) && function_exists('zeroBSCRM_migration_'.$migration)) call_user_func('zeroBSCRM_migration_'.$migration);

	}

}







	function zeroBSCRM_migration_1119(){

				global $zeroBSCRM_version,$zeroBSCRM_Settings; 
						
						
						if ($zeroBSCRM_version == "1.1.19"){

						
						$allCompanies = zeroBS_getCompanies(true,10000); $cosUpdated = 0;
			if (count($allCompanies) > 0) foreach ($allCompanies as $co){

		        		        		        $simpleCName = zeroBS_companyName('',$co['meta'],false,false);
		        update_post_meta($co['id'],'zbs_company_nameperm',$simpleCName);

		        
		        		        $cosUpdated++;


		    }


		    		    $migrationsExisting = $zeroBSCRM_Settings->get('migrations'); $migrationsExisting[] = '1119';
		    $zeroBSCRM_Settings->update('migrations',$migrationsExisting);
		    		    update_option('zbsmigration1119',array('completed'=>time(),'meta'=>array('updated'=>$cosUpdated)));

		    		    add_action( 'admin_notices', 'zeroBSCRM_migration_notice_1119' );
			

		}


	}
	function zeroBSCRM_migration_notice_1119() {
	    ?>
	    <div class="updated notice is-dismissable">
	        <p><?php _we( 'ZeroBS CRM has completed a necessary migration to 1.1.19, Great!', 'zerobscrm' ); ?></p>
	    </div>
	    <?php
	} 


	function zeroBSCRM_migration_123(){

				global $zeroBSCRM_version,$zeroBSCRM_Settings; 
						
						
						
				
			
						$allQuotes = zeroBS_getQuotes(false,50000); 
			$quoteOffset = zeroBSCRM_getQuoteOffset();
			$quotesUpdated = 0; $maxQuoteNo = 0;
			if (count($allQuotes) > 0) foreach ($allQuotes as $quote){

												if (!isset($quote['zbsid']) || empty($quote['zbsid']) || $quote['zbsid'] == 0){

										$newQuoteID = $quoteOffset+(int)$quote['id'];        			

										update_post_meta($quote['id'],'zbsid',$newQuoteID);

					
					$quotesUpdated++;

					if ($newQuoteID > $maxQuoteNo) $maxQuoteNo = $newQuoteID;

				}

			}

						if ($maxQuoteNo > 0) zeroBSCRM_setMaxQuoteID($maxQuoteNo);

			
						$allInvoices = zeroBS_getInvoices(true,50000); 
			$invoiceOffset = zeroBSCRM_getInvoiceOffset();
			$invsUpdated = 0; $maxInvoiceNo = 0;
			if (count($allInvoices) > 0) foreach ($allInvoices as $invoice){

												if (!isset($invoice['zbsid']) || empty($invoice['zbsid']) || $invoice['zbsid'] == 0){

										$newInvoiceID = $invoiceOffset+(int)$invoice['id'];

										if (isset($invoice['meta']) && isset($invoice['meta']['no']) && !empty($invoice['meta']['no'])) $newInvoiceID = (int)$invoice['meta']['no'];

										update_post_meta($invoice['id'],'zbsid',$newInvoiceID);

					
					$invsUpdated++;

					if ($newInvoiceID > $maxInvoiceNo) $maxInvoiceNo = $newInvoiceID;

				}

			}

						if ($maxInvoiceNo > 0) zeroBSCRM_setMaxInvoiceID($maxInvoiceNo);

			

		    		    $migrationsExisting = $zeroBSCRM_Settings->get('migrations'); $migrationsExisting[] = '123';
		    $zeroBSCRM_Settings->update('migrations',$migrationsExisting);
		    		    update_option('zbsmigration123',array('completed'=>time(),'meta'=>array('updated'=>'['.$quotesUpdated.','.$invsUpdated.','.$maxQuoteNo.','.$maxInvoiceNo.']')));

		    		    add_action( 'admin_notices', 'zeroBSCRM_migration_notice_123' );
			

		

	}
	function zeroBSCRM_migration_notice_123() {
	    ?>
	    <div class="updated notice is-dismissable">
	        <p><?php _we( 'ZeroBS CRM has completed a necessary migration to 1.2.3, Great!', 'zerobscrm' ); ?></p>
	    </div>
	    <?php
	} 


	
	function zeroBSCRM_migration_127(){

				global $zeroBSCRM_version,$zeroBSCRM_Settings,$zeroBSCRM_Conf_Setup; 
		
						$freshLanguageArr = array(); if (isset($zeroBSCRM_Conf_Setup['conf_defaults']['whlang']) && is_array($zeroBSCRM_Conf_Setup['conf_defaults']['whlang'])) $freshLanguageArr = $zeroBSCRM_Conf_Setup['conf_defaults']['whlang'];
			$zeroBSCRM_Settings->update('whlang',$freshLanguageArr);

		    		    $migrationsExisting = $zeroBSCRM_Settings->get('migrations'); $migrationsExisting[] = '127';
		    $zeroBSCRM_Settings->update('migrations',$migrationsExisting);
		    		    update_option('zbsmigration127',array('completed'=>time(),'meta'=>array('updated'=>'1')));

		    		    add_action( 'admin_notices', 'zeroBSCRM_migration_notice_127' );
			

		

	}
	function zeroBSCRM_migration_notice_127() {
	    ?>
	    <div class="updated notice is-dismissable">
	        <p><?php _we( 'ZeroBS CRM has completed a necessary migration to 1.2.7, Great!', 'zerobscrm' ); ?></p>
	    </div>
	    <?php
	} 




define('ZBSCRM_INC_MIGRATIONS',true);
