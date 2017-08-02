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
	$queryStr = get_query_var( 'clients' );
		if (strpos($queryStr,'/'))
		$zbsPortalRequest = explode('/',$queryStr);
	else
				$zbsPortalRequest = array($queryStr);
?>

<div class="zbs-client-portal-wrap">

	<?php
				$uid = get_current_user_id();
		$cID = zeroBS_getCustomerIDFromWPID($uid);
		if(!$cID){
			$cID = -999;
		}
		$aID = zeroBSCRM_invoice_canView($cID, (int)$zbsPortalRequest[1]);

		if ( current_user_can('manage_options') ){ ?>
			<div class='wrapper' style="padding:20px;">
			<div class='alert alert-info'>
				Hi, you are viewing this invoice in the Client Portal (this message is only shown to admins). 
				Learn more about the client portal <a style="color:orange;font-size:18px;" href="https://zerobscrm.com/kb/knowledge-base/how-does-the-client-portal-work/" target="_blank">here</a>
			</div>
			</div>
			<div class='zbs-portal-wrapper-sin zbs-single-invoice-portal'>
				<?php
				echo zeroBSCRM_invoice_generatePortalInvoiceHTML($zbsPortalRequest[1]);
				?>
				<div class='zbs-invoice-pro'>
				    <?php do_action('invoicing_pro_paypal_button'); ?>
				</div>
			</div>
		<?php	}else  if($aID != $cID){
			echo '<div class="zbs-alert-danger">' . __w("<b>Error:</b> You are not allowed to view this Invoice","zerobscrm") . '</div>';
		}else{ ?>
			<div class='zbs-portal-wrapper-sin zbs-single-invoice-portal'>
				<?php
				echo zeroBSCRM_invoice_generatePortalInvoiceHTML($zbsPortalRequest[1]);
				?>
				<div class='zbs-invoice-pro'>
				    <?php do_action('invoicing_pro_paypal_button'); ?>
				</div>
				<div class='zbs-back-to-invoices' style="margin-top:20px;margin-bottom:20px;">
					<a href='<?php echo home_url('/clients/invoices/');?>'><?php _we("Back to Invoices","zerobscrm"); ?></a><br/>
					<a href="<?php echo wp_logout_url(); ?>">Logout</a>
				</div>
			</div>
		<?php	}  ?>


</div>

<?php get_footer();  ?>