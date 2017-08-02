<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.20
 *
 * Copyright 2016 - 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 01/11/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;



function zeroBS_portal_enqueue_stuff() {
    wp_enqueue_style('zbs-portal', ZEROBSCRM_URL . 'portal/css/portal.min.css' );
    wp_enqueue_style('zbs-bs', ZEROBSCRM_URL . 'css/bootstrap.min.css' );
    wp_enqueue_style('zbs-fa', ZEROBSCRM_URL . 'css/font-awesome.min.css' );


}

add_rewrite_endpoint( 'clients', EP_ROOT );






function zeroBS_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) :
		$template_path = 'zerobscrm-plugin-templates/';
	endif;
		if ( ! $default_path ) :
		$default_path = ZEROBSCRM_PATH . 'portal/templates/'; 	endif;
		$template = locate_template( array(
		$template_path . $template_name,
		$template_name
	) );
		if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'zeroBS_locate_template', $template, $template_name, $template_path, $default_path );
}


function zeroBS_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = zeroBS_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
		return;
	endif;
	include $template_file;
}


add_filter( 'template_include', 'zeroBS_portal_template', 99 );

function zeroBS_portal_template( $template ) {

	$zbsClientQuery = get_query_var( 'clients' );

	
			if (isset($zbsClientQuery) && !empty($zbsClientQuery)){

				if (strpos($zbsClientQuery,'/'))
			$zbsPortalRequest = explode('/',$zbsClientQuery);
		else
						$zbsPortalRequest = array($zbsClientQuery);

				if (!empty($zbsPortalRequest[0]) && $zbsPortalRequest[0] == 'pn'){

						do_action('zerobscrm_portal_pn');

		} else {

			
						if ( !empty( $zbsPortalRequest[0] ) ) {
								if ( $zbsPortalRequest[0] == 'login' ){
					if(!is_user_logged_in()){
						return zeroBS_get_template( 'login.php' );
					}else{
						return zeroBS_get_template('dashboard.php');
					}
				}
								if ( $zbsPortalRequest[0] == 'invoices' ) {
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						if(!empty($zbsPortalRequest[1])){
							return zeroBS_get_template('single-invoice.php');
						}else{
							return zeroBS_get_template('invoices.php');
						}
					}
				}


				if ( $zbsPortalRequest[0] == 'quotes' ) {
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						if(!empty($zbsPortalRequest[1])){
							return zeroBS_get_template('single-quote.php');
						}else{
							return zeroBS_get_template('quotes.php');
						}
					}
				}

				if ( $zbsPortalRequest[0] == 'transactions' ) {
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						if(!empty($zbsPortalRequest[1])){
							return zeroBS_get_template('single-transaction.php');
						}else{
							return zeroBS_get_template('transactions.php');
						}
					}
				}

				if($zbsPortalRequest[0]  == 'dashboard'){
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						return zeroBS_get_template('dashboard.php');
					}
				}

				if($zbsPortalRequest[0]  == 'details'){
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						return zeroBS_get_template('details.php');
					}
				}


				if($zbsPortalRequest[0]  == 'thanks'){
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						return zeroBS_get_template('thank-you.php');
					}
				}

				if($zbsPortalRequest[0]  == 'cancel'){
					if(!is_user_logged_in()){
						return zeroBS_get_template('login.php');
					}else{
						return zeroBS_get_template('cancelled.php');
					}
				}


			}

		} 
	}

	return $template;
}


function zeroBS_portalnav($menu = 'dashboard'){
    $ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
    $ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');

    $dash = 'na'; 
    $inv = 'na';
    $tran = 'na';
    $quo = 'na';
    $det = 'na';

    switch($menu){
    	case 'dashboard':
    		$dash = 'active';
    	break;
    	case 'invoices':
    		$inv = 'active';
    	break;
    	case 'transactions':
    		$tran = 'active';
    	break;
    	case 'details':
    		$det = 'active';
    	break;
    	case 'quotes':
    		$quo = 'active';
    	break;
    }

    ?>
	<ul id="zbs-nav-tabs">
		<li class='<?php echo $dash; ?>'><a href="<?php echo site_url('/clients/dashboard/');?>"><i class='fa fa-dashboard'></i> <?php _we('Dashboard','zerobscrm');?></a></li>
		<?php if($ZBSuseQuotes > 0){  ?>
		<li class='<?php echo $quo; ?>'><a href="<?php echo site_url('/clients/quotes/');?>"><i class='fa fa-clipboard'></i> <?php _we('Quotes','zerobscrm');?></a></li>
		<?php } ?>
		<?php if($ZBSuseInvoices > 0){  ?>
		<li class='<?php echo $inv; ?>'><a href="<?php echo site_url('/clients/invoices/');?>"><i class='fa fa-file-text-o'></i> <?php _we('Invoices','zerobscrm');?></a></li>
		<?php } ?>
		<li class='<?php echo $tran; ?>'><a href="<?php echo site_url('/clients/transactions/');?>"><i class='fa fa-shopping-cart'></i> <?php _we('Transactions','zerobscrm');?></a></li>
		<li class='<?php echo $det; ?>'><a href="<?php echo site_url('/clients/details/');?>"><i class='fa fa-user'></i> <?php _we('Your Details','zerobscrm');?></a></li>
	</ul>
	<?php
}


define('ZBSCRM_INC_PORTAL',true);