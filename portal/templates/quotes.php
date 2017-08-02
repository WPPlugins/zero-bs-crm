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
if ( ! defined( 'ABSPATH' ) ) exit; 
$ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
if($ZBSuseQuotes < 0){
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
}

add_action( 'wp_enqueue_scripts', 'zeroBS_portal_enqueue_stuff' );
get_header();

?>

<div class="zbs-client-portal-wrap">
	<?php
				zeroBS_portalnav('quotes');
	?>
<div class='zbs-portal-wrapper zbs-portal-invoices-list'>

<?php
	global $wpdb;
	$uid = get_current_user_id();
	$cID = zeroBS_getCustomerIDFromWPID($uid);
	$currencyChar = zeroBSCRM_getCurrencyChr();


	$customer_quotes = zeroBS_getQuotesForCustomer($cID,true,100,0,false);


	if(count($customer_quotes) > 0){
		echo '<table class="table">';

		echo '<th>' . __w('Quote #','zerobscrm') . '</th>';
		echo '<th>' . __w('Quote Date','zerobscrm') . '</th>';
		echo '<th>' . __w('Title','zerobscrm') . '</th>';
		echo '<th>' . __w('Total','zerobscrm') . '</th>';
		echo '<th>' . __w('Status','zerobscrm') . '</th>';
		
		foreach($customer_quotes as $cinv){

			$inv_date = date_create($cinv['meta']['date']);

			$quote_stat = zeroBS_getQuoteStatus($cinv);

			echo '<tr>';
				echo '<td><a href="'. home_url('?p='.$cinv['id']) .'">#'. $cinv['zbsid'] . __w(' (view)') . '</a></td>';
				echo '<td>' . date_format($inv_date, 'd M Y') . '</td>';
				echo '<td><span class="name">'.$cinv['meta']['name'].'</span></td>';
				echo '<td>' . $currencyChar . number_format($cinv['meta']['val'],2) . '</td>';
				echo '<td><span class="status">'.$quote_stat.'</span></td>';
						echo '</tr>';
		}
		echo '</table>';
	}else{
		echo _we('Sorry. You do not have any quotes yet.','zerobscrm'); 
	}
	?>
	</div>

</div>

<?php get_footer(); ?>