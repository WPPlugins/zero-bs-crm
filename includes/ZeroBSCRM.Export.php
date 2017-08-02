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







function zeroBSCRM_pages_export(){
	$zbscontent = null;
    ob_start();

	$b2bMode = zeroBSCRM_getSetting('companylevelcustomers');
	if($b2bMode){
		$zbs_cust = 'Export Contacts';
	}else{
		$zbs_cust = 'Export Customers';
	}
	?>

	<?php if($b2bMode){ ?>
	<h5><?php _we("Companies", "zerobscrm"); ?></h5>
	<form method="post" id="download_form" action="">
            <input type="submit" name="download_co_csv" class="button-primary" value="<?php _we('Export Companies'); ?>" />
    </form>
    <?php } ?>


	<h5><?php echo $zbs_cust; ?></h5>
	<form method="post" id="download_form" action="">
            <input type="submit" name="download_cust_csv" class="button-primary" value="<?php echo $zbs_cust; ?>" />
    </form>


	<h5><?php _we("Invoices", "zerobscrm"); ?></h5>
	<form method="post" id="download_form" action="">
            <input type="submit" name="download_inv_csv" class="button-primary" value="<?php _we('Export Invoices'); ?>" />
    </form>


	<h5><?php _we("Quotes", "zerobscrm"); ?></h5>
	<form method="post" id="download_form" action="">
            <input type="submit" name="download_quo_csv" class="button-primary" value="<?php _we('Export Quotes'); ?>" />
    </form>

    <!--
	<h3>Transactions</h3>
	<form method="post" id="download_form" action="">
            <input type="submit" name="download_tran_csv" class="button-primary" value="<?php _we('Export Transactions'); ?>" />
    </form>
	-->

	<?php
    $zbscontent = ob_get_contents();
    ob_end_clean();
    return $zbscontent;

}


function zbs_export_companies(){
	global $plugin_page;
	if ( isset($_POST['download_co_csv']) && $plugin_page == 'zerobscrm-datatools' ) {
		

		if(zeroBSCRM_permsCustomers()){
		header('Content-Type: text/csv; charset=utf-8');		
		$zbs_cust = 'Export Companies';
		$filename = 'ZBS.csv';		
		header('Content-Disposition: attachment; filename= ' . $filename);
		$output = fopen('php://output', 'w');

		fputcsv($output, array('Status','Prefix', 'First Name', 'Last Name', 'Address Line 1', 'Address Line 2','City','County','Postcode','Home Tel','Work Tel','Mobile', 'Email','Notes','Quotes', 'Invoices','Transactions','Total Value'));
		$customers = zeroBS_getCompanies(true,100000,0,true,true,'',true,false,false);

		foreach($customers as $customer){
			$zbsMeta = get_post_meta($customer['id'],'zbs_customer_meta',true);
			$totalVal = zeroBS_customerTotalValue($customer['id'],$customer['invoices'],$customer['transactions']);
			$out = array($zbsMeta['status'], $zbsMeta['prefix'] ,$zbsMeta['fname'], $zbsMeta['lname'],$zbsMeta['addr1'],$zbsMeta['addr2'],$zbsMeta['city'],$zbsMeta['county'],$zbsMeta['postcode'],$zbsMeta['hometel'],$zbsMeta['worktel'],$zbsMeta['mobtel'], $zbsMeta['email'],$zbsMeta['notes'], count($customer['quotes']), count($customer['invoices']),count($customer['transactions']),$totalVal );
			fputcsv($output, $out);
			}

		}
		die();
	}
}
add_action('admin_init','zbs_export_companies');


function zbs_export_customers_filter(){
		global $plugin_page;

		if ( isset($_POST['download_cust_csv_filter']) && $plugin_page == 'customer-searching' ) {

		if(zeroBSCRM_permsCustomers()){

			header('Content-Type: text/csv; charset=utf-8');		
			$b2bMode = zeroBSCRM_getSetting('companylevelcustomers');
		if($b2bMode){
			$zbs_cust = 'Export Contacts';
		}else{
			$zbs_cust = 'Export Customers';
		}


		if(!isset($_POST['cid']) && empty($_POST['cid'])){
			$inArr = '';
		}else{
			$inArr = $_POST['cid'];	
		}


		$filename = 'ZBS.csv';		
		header('Content-Disposition: attachment; filename= ' . $filename);
		$output = fopen('php://output', 'w');

		fputcsv($output, array('Status','Prefix', 'First Name', 'Last Name', 'Address Line 1', 'Address Line 2','City','County','Postcode','Home Tel','Work Tel','Mobile', 'Email','Notes','Quotes', 'Invoices','Transactions','Total Value'));
		$customers = zeroBS_getCustomers(true,100000,0,true,true,'',true,false,false,'', $inArr);

		foreach($customers as $customer){
			$zbsMeta = get_post_meta($customer['id'],'zbs_customer_meta',true);
			$totalVal = zeroBS_customerTotalValue($customer['id'],$customer['invoices'],$customer['transactions']);
			$out = array($zbsMeta['status'], $zbsMeta['prefix'] ,$zbsMeta['fname'], $zbsMeta['lname'],$zbsMeta['addr1'],$zbsMeta['addr2'],$zbsMeta['city'],$zbsMeta['county'],$zbsMeta['postcode'],$zbsMeta['hometel'],$zbsMeta['worktel'],$zbsMeta['mobtel'], $zbsMeta['email'],$zbsMeta['notes'], count($customer['quotes']), count($customer['invoices']),count($customer['transactions']),$totalVal );
			fputcsv($output, $out);
			}

		}
		die();
	}
}
add_action('admin_init','zbs_export_customers_filter');



function zbs_export_customers(){
	global $plugin_page;
	if ( isset($_POST['download_cust_csv']) && $plugin_page == 'zerobscrm-datatools' ) {

		if(zeroBSCRM_permsCustomers()){

		header('Content-Type: text/csv; charset=utf-8');		
		$b2bMode = zeroBSCRM_getSetting('companylevelcustomers');
		if($b2bMode){
			$zbs_cust = 'Export Contacts';
		}else{
			$zbs_cust = 'Export Customers';
		}

		$filename = 'ZBS.csv';		
		header('Content-Disposition: attachment; filename= ' . $filename);
		$output = fopen('php://output', 'w');

		fputcsv($output, array('Status','Prefix', 'First Name', 'Last Name', 'Address Line 1', 'Address Line 2','City','County','Postcode','Home Tel','Work Tel','Mobile', 'Email','Notes','Quotes', 'Invoices','Transactions','Total Value'));
		$customers = zeroBS_getCustomers(true,100000,0,true,true,'',true,false,false);

		foreach($customers as $customer){
			$zbsMeta = get_post_meta($customer['id'],'zbs_customer_meta',true);
			$totalVal = zeroBS_customerTotalValue($customer['id'],$customer['invoices'],$customer['transactions']);
			$out = array($zbsMeta['status'], $zbsMeta['prefix'] ,$zbsMeta['fname'], $zbsMeta['lname'],$zbsMeta['addr1'],$zbsMeta['addr2'],$zbsMeta['city'],$zbsMeta['county'],$zbsMeta['postcode'],$zbsMeta['hometel'],$zbsMeta['worktel'],$zbsMeta['mobtel'], $zbsMeta['email'],$zbsMeta['notes'], count($customer['quotes']), count($customer['invoices']),count($customer['transactions']),$totalVal );
			fputcsv($output, $out);
			}
		}
		die();
	}
}
add_action('admin_init','zbs_export_customers');

function zbs_export_invoices(){
	global $plugin_page;
	if ( isset($_POST['download_inv_csv']) && $plugin_page == 'zerobscrm-datatools' ) {

		if(zeroBSCRM_permsInvoices()){
			header('Content-Type: text/csv; charset=utf-8');		
			$filename = 'ZBS-Invoices-Export-'. date('Ymd') .'.csv';		
			header('Content-Disposition: attachment; filename= ' . $filename);
			$output = fopen('php://output', 'w');
			fputcsv($output, array('Number','Status', 'Customer Name', 'Value', 'Created'));		
			$invoices = zeroBS_getInvoices(true,1000000,0,true);
			foreach($invoices as $invoice){
				if(empty($invoice['meta']['status'])){
					$stat = 'Draft';
				}else{
					$stat = $invoice['meta']['status'];
				}
				$who = $invoice['customer']['meta']['fname'] . ' ' . $invoice['customer']['meta']['lname'];
				if(empty($invoice['meta']['val'])){
					$val = 0;
				}else{
					$val = $invoice['meta']['val'];
				}

										$invoiceID = $invoice['id']; 					if (isset($invoice['zbsid']) && !empty($invoice['zbsid'])) $invoiceID = $invoice['zbsid']; 
				$out = array($invoiceID, $stat , $who, $val, $invoice['created']);
				fputcsv($output, $out);
			}
		}
		die();
	}
}
add_action('admin_init','zbs_export_invoices');

function zbs_export_quotes(){
	global $plugin_page;
	if ( isset($_POST['download_quo_csv']) && $plugin_page == 'zerobscrm-datatools') {
		if(zeroBSCRM_permsQuotes()){
			header('Content-Type: text/csv; charset=utf-8');	
			$filename = 'ZBS-Quotes-Export-'. date('Ymd') .'.csv';		
			header('Content-Disposition: attachment; filename= ' . $filename);
			$output = fopen('php://output', 'w');

			fputcsv($output, array('Created','Number', 'Customer Name', 'Value','Notes'));
			
			$quotes = zeroBS_getQuotes(true,1000000,0,true);

			foreach($quotes as $quote){
				if(empty($quote['meta']['notes'])){
					$notes = '';
				}else{
					$notes = $quote['meta']['notes'];
				}
				$who = $quote['customer']['meta']['fname'] . ' ' . $quote['customer']['meta']['lname'];
				if(empty($quote['meta']['val'])){
					$val = 0;
				}else{
					$val = $quote['meta']['val'];
				}

										$quoteID = $quote['id']; 					if (isset($quote['zbsid']) && !empty($quote['zbsid'])) $quoteID = $quote['zbsid']; 
				$out = array($quote['created'], $quoteID , $who, $val, $notes);
				fputcsv($output, $out);
			}
		}
		die();
	}
}
add_action('admin_init','zbs_export_quotes');







		define('ZBSCRM_INC_EXPORT',true);