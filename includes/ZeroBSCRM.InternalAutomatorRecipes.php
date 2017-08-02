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








		zeroBSCRM_AddInternalAutomatorRecipe('customer.new','zeroBSCRM_IA_NewCustomerLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('company.new','zeroBSCRM_IA_NewCompanyLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('quote.new','zeroBSCRM_IA_NewQuoteLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('invoice.new','zeroBSCRM_IA_NewInvoiceLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('transaction.new','zeroBSCRM_IA_NewTransactionLog',array());

		zeroBSCRM_AddInternalAutomatorRecipe('status.change','zeroBSCRM_IA_StatusChange',array());









	



		function zeroBSCRM_IA_NewCustomerLog($obj=array()){

				$autoLogThis = zeroBSCRM_getSetting('autolog_customer_new');

		if ($autoLogThis > 0){

						$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['id'])) $zbsNoteAgainstPostID = (int)$obj['id'];
			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

								if (isset($obj['automatorpassthrough']) && is_array($obj['automatorpassthrough']) && isset($obj['automatorpassthrough']['note_override']) && is_array($obj['automatorpassthrough']['note_override']) && isset($obj['automatorpassthrough']['note_override']['type'])){

					
												$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,$obj['automatorpassthrough']['note_override']);

				} else {

					


										$newCustomerName = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['customerMeta']) && is_array($obj['customerMeta'])) $newCustomerName = zeroBS_customerName($obj['id'],$obj['customerMeta'],false,true);
					$noteShortDesc = 'Customer Created'; if (!empty($newCustomerName)) $noteShortDesc = $newCustomerName;
					$noteLongDesc = '';

																	            if (isset($obj['extsource']) && !empty($obj['extsource'])){

		                switch ($obj['extsource']){

		                    case 'pay': 
		                        $noteLongDesc = 'Created from PayPal <i class="fa fa-paypal"></i>';

		                        break;

		                    
		                    case 'env':

		                        $noteLongDesc = 'Created from Envato <i class="fa fa-envira"></i>';

		                        break;

		                    case 'form':

		                        $noteLongDesc = 'Created from Form Capture <i class="fa fa-wpforms"></i>';

		                        break;

		                    case 'csv':

		                        $noteLongDesc = 'Created from CSV Import <i class="fa fa-file-text"></i>';

		                        break;

		                    case 'gra':

		                        $noteLongDesc = 'Created from Gravity Forms <i class="fa fa-wpforms"></i>';

		                        break;

		                    default:

		                        		                        $noteLongDesc = 'Created from External Source <i class="fa fa-users"></i>';

		                        break;

		                }


		            }


										$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
						'type' => 'Created',
						'shortdesc' => $noteShortDesc,
						'longdesc' => $noteLongDesc
					));

				} 
			}

		}


	}
		function zeroBSCRM_IA_NewCompanyLog($obj=array()){

				$autoLogThis = zeroBSCRM_getSetting('autolog_company_new');

		if ($autoLogThis > 0){

						$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['id'])) $zbsNoteAgainstPostID = (int)$obj['id'];
			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

								if (isset($obj['automatorpassthrough']) && is_array($obj['automatorpassthrough']) && isset($obj['automatorpassthrough']['note_override']) && is_array($obj['automatorpassthrough']['note_override']) && isset($obj['automatorpassthrough']['note_override']['type'])){

					
												$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,$obj['automatorpassthrough']['note_override']);

				} else {

					


										$newCompanyName = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['companyMeta']) && is_array($obj['companyMeta'])) $newCompanyName = zeroBS_companyName($obj['id'],$obj['companyMeta'],false,true);
					$noteShortDesc = 'Company Created'; if (!empty($newCompanyName)) $noteShortDesc = $newCompanyName;
					$noteLongDesc = '';

																	            if (isset($obj['extsource']) && !empty($obj['extsource'])){

		                switch ($obj['extsource']){

		                    case 'pay': 
		                        $noteLongDesc = 'Created from PayPal <i class="fa fa-paypal"></i>';

		                        break;

		                    
		                    case 'env':

		                        $noteLongDesc = 'Created from Envato <i class="fa fa-envira"></i>';

		                        break;

		                    case 'form':

		                        $noteLongDesc = 'Created from Form Capture <i class="fa fa-wpforms"></i>';

		                        break;

		                    case 'csv':

		                        $noteLongDesc = 'Created from CSV Import <i class="fa fa-file-text"></i>';

		                        break;

		                    default:

		                        		                        $noteLongDesc = 'Created from External Source <i class="fa fa-users"></i>';

		                        break;

		                }


		            }


										$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
						'type' => 'Created',
						'shortdesc' => $noteShortDesc,
						'longdesc' => $noteLongDesc
					));

				} 
			}

		}


	}

		function zeroBSCRM_IA_NewQuoteLog($obj=array()){


				$autoLogThis = zeroBSCRM_getSetting('autolog_quote_new');

		if ($autoLogThis > 0){

						$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];
									$quoteID = ''; if (is_array($obj) && isset($obj['zbsid'])) $quoteID = $obj['zbsid'];
			$quoteName = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['quoteMeta']) && is_array($obj['quoteMeta']) && isset($obj['quoteMeta']['name'])) $quoteName = $obj['quoteMeta']['name'];
			$quoteValue = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['quoteMeta']) && is_array($obj['quoteMeta']) && isset($obj['quoteMeta']['val'])) $quoteValue = zeroBSCRM_prettifyLongInts($obj['quoteMeta']['val']);
			$noteShortDesc = ''; 
			if (!empty($quoteID)) $noteShortDesc .= ' #'.$quoteID;
			if (!empty($quoteName)) $noteShortDesc .= ' '.$quoteName;
			if (!empty($quoteValue)) $noteShortDesc .= ' ('.zeroBSCRM_getCurrencyStr().' '.$quoteValue.')';

			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

								$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
					'type' => 'Quote Created',
					'shortdesc' => $noteShortDesc,
					'longdesc' => ''
				));

			}

		}


	}

		function zeroBSCRM_IA_NewInvoiceLog($obj=array()){


				$autoLogThis = zeroBSCRM_getSetting('autolog_invoice_new');

		if ($autoLogThis > 0){

						$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];
									$invoiceNo = ''; if (is_array($obj) && isset($obj['zbsid'])) $invoiceNo = $obj['zbsid'];
			$invoiceValue = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['invoiceMeta']) && is_array($obj['invoiceMeta']) && isset($obj['invoiceMeta']['val'])) $invoiceValue = zeroBSCRM_prettifyLongInts($obj['invoiceMeta']['val']);
			$noteShortDesc = ''; 
			if (!empty($invoiceNo)) $noteShortDesc .= ' #'.$invoiceNo;
			if (!empty($invoiceValue)) $noteShortDesc .= ' ('.zeroBSCRM_getCurrencyStr().' '.$invoiceValue.')';

			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

								$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
					'type' => 'Invoice Created',
					'shortdesc' => $noteShortDesc,
					'longdesc' => ''
				));

			}

		}

	}

		function zeroBSCRM_IA_NewTransactionLog($obj=array()){

		$newLogID = false;

				$autoLogThis = zeroBSCRM_getSetting('autolog_transaction_new');

				$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];

		if ($autoLogThis > 0 && isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

						if (isset($obj['automatorpassthrough']) && is_array($obj['automatorpassthrough']) && isset($obj['automatorpassthrough']['note_override']) && is_array($obj['automatorpassthrough']['note_override']) && isset($obj['automatorpassthrough']['note_override']['type'])){

				
										$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,$obj['automatorpassthrough']['note_override']);

			} else {

				
								$transID = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['transactionMeta']) && is_array($obj['transactionMeta']) && isset($obj['transactionMeta']['orderid'])) $transID = $obj['transactionMeta']['orderid'];
				$transValue = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['transactionMeta']) && is_array($obj['transactionMeta']) && isset($obj['transactionMeta']['total'])) $transValue = zeroBSCRM_prettifyLongInts($obj['transactionMeta']['total']);
				$noteShortDesc = ''; 
				if (!empty($transID)) $noteShortDesc .= ' #'.$transID;
				if (!empty($transValue)) $noteShortDesc .= ' ('.zeroBSCRM_getCurrencyStr().' '.$transValue.')';


										$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
						'type' => 'Transaction Created',
						'shortdesc' => $noteShortDesc,
						'longdesc' => ''
					));
				

			}

		}


		return $newLogID;

	} 




   

define('ZBSCRM_INC_IARECIPES',true);