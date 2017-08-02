<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.0
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 26/05/2016
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;








	function zeroBS__addTransactionMetaBoxes()
	{
	    add_meta_box("zeroBSCRM-transaction-meta-box", "Transaction Info", "zbs_transactions_meta_box_markup", "zerobs_transaction", "normal", "high", null);
	}

	add_action("add_meta_boxes", "zeroBS__addTransactionMetaBoxes");









		function zbs_transactions_meta_box_markup(){
		global $post;
	    $meta = get_post_meta($post->ID,'zbs_transaction_meta', true);
	    if($meta == ''){ ?>
	    	<input type="hidden" name="zbs_hidden_flag" value="true" />
	    	<input type="hidden" name="zbscrm_newtransaction" value="1" />
	    	<table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">

				<tr class="wh-large">
					<th><label for="orderid">Transaction unique ID:</label></th>
		            <td><input type = "text" id="orderid" name="orderid" class="form-control" value="<?php echo zeroBSCRM_uniqueID(); ?>" autocomplete="off"></td>
		        </tr>

				<tr class="wh-large">
					<th><label>Transaction Date:</label></th>
	                <td>
	                	<input type="text" name="transactionDate" id="transactionDate" class="form-control zbs-date" placeholder="" value="" autocomplete="off" />
	                	<input type="hidden" name="trans_time" id="trans_time" value="" /><!-- for now -->
	                </td>
	            </tr>

				<tr class="wh-large">
					<th><label for="total"><?php _we("Transaction Value ","zerobscrm"); ?><?php echo "(". zeroBSCRM_getCurrencyChr() . "):"; ?></label></th>
		            <td><input id="total" name="total" value="" class="form-control numbersOnly" style="width: 130px;display: inline-block;" autocomplete="off" /></td>
		        </tr>

				<tr class="wh-large">
					<th><label for="item"><?php _we("Transaction Name:","zerobscrm"); ?></label>
					<span class="zbs-infobox" style="margin-top:3px">If possible, keep these the same for the same item (they are used in the transaction index)</span> 
					</th>
		            <td><input id="item" name="item" value="" class="form-control widetext" autocomplete="off" /></td>
		        </tr>

		        <tr><td colspan="2"><hr /></td></tr>
		        <tr><td colspan="2" style="margin-top:0;padding-top:0;"><h2 style="margin-top:0;padding-top:0;">Assign To: (Optional)</td></tr>

		        <tr class="wh-large">
		        	<th><label for"customer-ta"><?php _we("Customer","zerobscrm"); ?></label></th>
		        	<td>
		        		<?php echo zeroBSCRM_CustomerTypeList('zbscrmjs_transaction_setCustomer'); ?>
		        	</td>
		        </tr>

				<tr class="wh-large hide">
					<th><label for="customer">Customer ID</label></th>
		            <td><input id="customer" name="customer" value="" class="form-control widetext" type="hidden"></td>
		        </tr>

				<tr class="wh-large hide">
					<th><label for="customer">Customer Name</label></th>
		            <td><input id="customer_name" name="customer_name" value="" class="form-control widetext" type="hidden"></td>
		        </tr>

				<tr class="wh-large">
					<th><label for="total"><?php _we("Assign to invoice:","zerobscrm"); ?></label>
					<span class="zbs-infobox" style="margin-top:3px">Is this transaction a payment for an invoice? If so enter the Invoice ID. Otherwise leave blank</span> 
					</th>
		            <td id="invoiceFieldWrap" style="position:relative"><input id="invoice_id" name="invoice_id" value="" class="form-control" autocomplete="off" /></td>
		        </tr>
		    </table>
	    	<?php
	    }
	    else{ 
	    	echo '<input type="hidden" name="zbs_hidden_flag" value="1" />';
	    	echo '<table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">';
	    	?>

				<tr class="wh-large">
					<th><label for="orderid">Transaction unique ID:</label></th>
		            <td><input type="text" id="orderid" name="orderid" class="form-control" value="<?php if(isset($meta['orderid'])){ echo $meta['orderid']; }else{ echo zeroBSCRM_uniqueID(); } ?>" autocomplete="off" /></td>
		        </tr>

				<tr class="wh-large">
					<th><label>Transaction Date:</label></th>
	                <td>
	                	<input type="text" name="transactionDate" id="transactionDate" class="form-control zbs-date" placeholder="" value="<?php if(isset($post->post_date)){

		                			                	$dt = strtotime($post->post_date);
		                	echo date('d.m.Y',$dt);

	                	}?>" autocomplete="off" />
	                	<input type="hidden" name="trans_time" id="trans_time" value="<?php if(isset($meta['trans_time'])){ echo $meta['trans_time']; }?>" /><!-- passes any saved times for now -->
	                </td>
	            </tr>

				<tr class="wh-large">
					<th><label for="total"><?php _we("Transaction Value ","zerobscrm"); ?><?php echo "(". zeroBSCRM_getCurrencyChr() . "):"; ?></label></th>
		            <td><input id="total" name="total" value="<?php if(isset($meta['total'])){ echo $meta['total']; } ?>" class="form-control numbersOnly" style="width: 130px;display: inline-block;" autocomplete="off" /></td>
		        </tr>

				<tr class="wh-large">
					<th><label for="item"><?php _we("Transaction Name:","zerobscrm"); ?></label>
					<span class="zbs-infobox" style="margin-top:3px">If possible, keep these the same for the same item (they are used in the transaction index)</span> 
					</th>
		            <td><input id="item" name="item" value="<?php if(isset($meta['item'])){ echo $meta['item']; }?>" class="form-control widetext" autocomplete="off" /></td>
		        </tr>

		        <tr><td colspan="2"><hr /></td></tr>
		        <tr><td colspan="2"><h2>Assign To: (Optional)</td></tr>

		        <tr class="wh-large">
		        	<th><label for"customer-ta"><?php _we("Customer","zerobscrm"); ?></label></th>
		        	<td>
		        		<?php
		        		$cname = ''; 
		        		if (isset($meta['customer_name'])) 
		        			$cname = $meta['customer_name'];
		        		else {

							if (!empty($meta['customer'])){

								$cname = zeroBS_getCustomerNameShort($meta['customer']);
							}
		        		}
		        		echo zeroBSCRM_CustomerTypeList('zbscrmjs_transaction_setCustomer', $cname); ?>
		        	</td>
		        </tr>


				<tr class="wh-large hide">
					<th><label for="customer">Customer ID</label></th>
		            <td><input id="customer" name="customer" value="<?php if(isset($meta['customer'])){ echo $meta['customer']; } ?>" class="form-control widetext" type="hidden"></td>
		        </tr>

				<tr class="wh-large hide">
					<th><label for="customer">Customer Name</label></th>
		            <td><input id="customer_name" name="customer_name" value="<?php if(isset($meta['customer_name']) && !empty($meta['customer_name'])){ echo $meta['customer_name']; } else {

						if (!empty($cname)) echo $cname;

		            } ?>" class="form-control widetext" type="hidden"></td>
		        </tr>
				<tr class="wh-large">
					<th><label for="total"><?php _we("Assign to invoice:","zerobscrm"); ?></label>
					<span class="zbs-infobox" style="margin-top:3px">Is this transaction a payment for an invoice? If so enter the Invoice ID. Otherwise leave blank</span> 
					</th>
		            <td id="invoiceFieldWrap" style="position:relative"><input id="invoice_id" name="invoice_id" value="<?php if(isset($meta['invoice_id'])){ echo $meta['invoice_id']; } ?>" class="form-control" autocomplete="off" /></td>
		        </tr>
		    </table>
	    	<?php } ?>
		<script type="text/javascript">

		
		jQuery(document).ready(function(){

            // turn off auto-complete on records via form attr... should be global for all ZBS record pages
            jQuery('#post').attr('autocomplete','off');


			// on init, if customer has been selected, prefil inv list
			if (jQuery('#customer').val()){
			
				// any inv selected?
				var existingInvID = false;
				if (jQuery('#invoice_id').val()) existingInvID = jQuery('#invoice_id').val();

				zbscrmjs_build_custInv_dropdown(jQuery('#customer').val(),existingInvID);

			}


		});


		// custom fuction to copy customer details from typeahead customer deets
		function zbscrmjs_transaction_setCustomer(obj){

			//console.log("Customer Chosen!",obj);

			if (typeof obj.id != "undefined"){

				// set vals
				jQuery("#customer").val(obj.id);
				jQuery("#customer_name").val(obj.name);

				// build inv dropdown
				zbscrmjs_build_custInv_dropdown(obj.id);

			}
		}

		// this builds a dropdown of invoices against a customer
		function zbscrmjs_build_custInv_dropdown(custID,preSelectedInvID){

			var previousInvVal = jQuery('#invoice_id').val();

			// if cust id, retrieve inv list from ajax/cache
			if (custID != ""){

				// show loading
				jQuery('#invoiceFieldWrap').append(zbscrm_js_uiSpinnerBlocker());
				
				zbscrm_js_getCustInvs(custID, function(r){

					// successfully got list!
					// console.log("got list",[r,r.length]);

					// wrap
					var retHTML = '<select id="invoice_id" name="invoice_id" class="form-control">';

						// if has invoices:
						if (r.length > 0){

							// def
							retHTML += '<option value="" disabled="disabled"';
								
								// if an inv id is passed, don't select this
								if (typeof preSelectedInvID == "undefined" || preSelectedInvID <= 0) retHTML += ' selected="selected"';
							
							retHTML += '>Select Invoice</option>';
							retHTML += '<option value="">None</option>';

							// cycle through + create
							jQuery.each(r,function(ind,ele){

								var invID = ele.id; //  POST id


								// build a user-friendly str
								var invStr = ""; 

								// #TRANSITIONTOMETANO 
								if (typeof ele.zbsid != "undefined") {
								
									invStr += '#' + ele.zbsid;	
								
								} else {
									
									// forced to show post id as some kind of identifier..
									invStr += '#PID:' + ele.id;

								}
								if (typeof ele.meta != "undefined"){
									// val
									if (typeof ele.meta.val != "undefined") invStr += ' (' + window.zbJS_curr + ele.meta.val + ')';
									// date
									if (typeof ele.meta.date != "undefined") invStr += ' - ' + ele.meta.date;

								}

								retHTML += '<option value="' + invID + '"';

									// if prefilled... select
									if (typeof preSelectedInvID != "undefined" && invID == preSelectedInvID) retHTML += ' selected="selected"';

								retHTML += '>' + invStr + '</option>';

							});

						} else {

							// no invs
							retHTML += '<option value="" disabled="disabled" selected="selected">No Invoices Found!</option>';

						}

					// / wrap
					retHTML += '</select>';
					
					// output
					jQuery('#invoiceFieldWrap').html(retHTML);


				},function(r){

					// failed to get... leave as manual

					// localise
					var previousInvValL = previousInvVal;

					// NOTE THIS IS DUPE BELOW... REFACTOR
					jQuery('#invoiceFieldWrap').html('<input id="invoice_id" name="invoice_id" value="' + previousInvValL + '" class="form-control">');

				});

			} else {

				// leave as manual entry (but maybe later do not allow?)
				// NOTE THIS IS DUPE ABOVE... REFACTOR
				jQuery('#invoiceFieldWrap').html('<input id="invoice_id" name="invoice_id" value="' + previousInvVal + '" class="form-control">');

			}


		}

		</script>
		<?php
	}


	function wpt_save_zbs_transaction_meta($post_id, $post) {

	     global $wp, $wpdb;  
	    	    if ( !current_user_can( 'edit_post', $post_id ))
	        return $post_id;
	    	    	    if (isset($_POST['zbs_hidden_flag'])) {

	    					$zbo['orderid'] = ''; 	if(isset($_POST['orderid'])) 	$zbo['orderid'] = sanitize_text_field($_POST["orderid"]);
				$zbo['currency'] = ''; 	if(isset($_POST['currency'])) 	$zbo['currency'] = $_POST["currency"];
				$zbo['item'] = ''; 		if (isset($_POST["item"])) 		$zbo['item'] = sanitize_text_field($_POST["item"]);
				$zbo['customer'] = ''; 	if(isset($_POST['customer'])) 	$zbo['customer'] = (int)sanitize_text_field($_POST["customer"]);


				if(isset($_POST['status'])){
			    	$zbo['status'] 		= 		$_POST["status"];
				}
				$zbo['total'] 		= 		$_POST["total"];

				if(isset($_POST['net'])){			
					$zbo['net'] 		= 		$_POST["net"];
				}

				if(isset($_POST['tax'])){
					$zbo['tax'] 		= 		$_POST["tax"];
				}

				if(isset($_POST['fee'])){
					$zbo['fee'] 		= 		$_POST["fee"];
				}

				if(isset($_POST['discount'])){
					$zbo['discount'] 	= 		$_POST['discount'];
				}

								$zbo['customer_name'] = $_POST['customer_name'];

			  				  				  				  	 

			  				  	$zbo['trans_time'] = (int)$_POST['trans_time'];   

			  	
				  				  							$zbo['invoice_id'] = ''; 	if(isset($_POST['invoice_id'])) 	$zbo['invoice_id'] = (int)sanitize_text_field($_POST["invoice_id"]);

									  	if($zbo['invoice_id'] != ''){

				  						  		update_post_meta($post_id, 'zbs_invoice_partials', $zbo['invoice_id']); 

				  						  		
			  		}


			  			  		update_post_meta($post_id,'zbs_transaction_meta',$zbo);

		  				  		update_post_meta($post_id,'zbs_parent_cust', $zbo['customer']);  

	            

	  	}

	}
	add_action('save_post', 'wpt_save_zbs_transaction_meta', 1, 2); 




        define('ZBSCRM_INC_TRANSMB',true);