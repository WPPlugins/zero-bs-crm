<?php /* *
 * Payment Cancelled Page
 *
 * This is used as a 'Payment Cancelled' page following a cancelled payment
 *
 * @author 		Mike Stott
 * @package 	Customer Portal
 * @version     1.2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; add_action( 'wp_enqueue_scripts', 'zeroBS_portal_enqueue_stuff' );
get_header();

?>

<div class="zbs-client-portal-wrap">

	<div class='zbs-portal-wrapper zbs-portal-invoices-list'>
			<h1><?php _we("Payment Cancelled", "zerobscrm"); ?></h1>
			<p>
			<?php _we("Your payment was cancelled.", "zerobscrm"); ?>
			</p>
	</div>

</div>


<?php get_footer(); ?>