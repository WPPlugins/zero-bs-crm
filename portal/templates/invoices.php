<?php /* *
 * Invoices Template.
 *
 * This template can be overriden by copying this file to your-theme/zerobscrm-plugin-templates/invoices.php
 * It uses the DEFAULT styles of the Theme. Extra style packs are available from 
 * https://zerobscrm.com/extensions/
 *
 * @author 		Mike Stott
 * @package 	Customer Portal
 * @version     1.2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; add_action( 'wp_enqueue_scripts', 'zeroBS_portal_enqueue_stuff' );
get_header();

$ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');
if($ZBSuseInvoices < 0){
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
}

?>

<div class="zbs-client-portal-wrap">

	<?php
		zeroBS_portalnav('invoices');
	?>

	<div class='zbs-portal-wrapper zbs-portal-invoices-list'>
<?php
	global $wpdb;
	$uid = get_current_user_id();
	$cID = zeroBS_getCustomerIDFromWPID($uid);
	$currencyChar = zeroBSCRM_getCurrencyChr();


	$customer_invoices = zeroBS_getInvoicesForCustomer($cID,true,100,0,false);


	if(count($customer_invoices) > 0){
		echo '<table class="table">';

		echo '<th>' . __w('Invoice #','zerobscrm') . '</th>';
		echo '<th>' . __w('Paid #','zerobscrm') . '</th>';
		echo '<th>' . __w('Invoice Date','zerobscrm') . '</th>';
		echo '<th>' . __w('Due Date','zerobscrm') . '</th>';
		echo '<th>' . __w('Total','zerobscrm') . '</th>';
		echo '<th>' . __w('Status','zerobscrm') . '</th>';
		
		foreach($customer_invoices as $cinv){

					$trans_number = zeroBS_getTransactionsForInvoice($cinv['id']);

			if($trans_number){
				$paid_no =  '#' . $trans_number;
			}else{
				$paid_no = '';
			}


			$inv_date = date_create($cinv['meta']['date']);

			if($cinv['meta']['due'] == -1){
								$zbs_when_due = __("No due date", "zerobscrm");
			}else{
				$due_date = date_create($cinv['meta']['date']);
				$str = $cinv['meta']['due'] . ' days';
				date_add($due_date, date_interval_create_from_date_string($str));
				$zbs_when_due = date_format($due_date, 'd M Y');
			}
			echo '<tr>';
				echo '<td><a href="'. home_url('/clients/invoices/'.$cinv['id']) .'">#'. $cinv['zbsid'] . __w(' (view)') . '</a></td>';
				echo '<td>'. $paid_no . '</td>';
				echo '<td>' . date_format($inv_date, 'd M Y') . '</td>';
				echo '<td>' . $zbs_when_due . '</td>';
				echo '<td>' . $currencyChar . number_format($cinv['meta']['val'],2) . '</td>';
				echo '<td><span class="status '. strtolower($cinv['meta']['status']).'">'.$cinv['meta']['status'].'</span></td>';
						echo '</tr>';
		}
		echo '</table>';
	}else{
		echo _we('Sorry. You do not have any invoices yet.','zerobscrm'); 
	}
	?>
	</div>
</div>


<?php get_footer(); ?>