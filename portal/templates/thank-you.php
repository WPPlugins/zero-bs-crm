<?php /* *
 * Payment Thank You Page
 *
 * This is used as a 'Thank You' page following a successful payment.
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
			<h1><?php _we("Thank You", "zerobscrm"); ?></h1>
			<p>
			<?php _we("Thank you for your payment.", "zerobscrm"); ?>
			</p>
	</div>

</div>

<?php get_footer(); ?>