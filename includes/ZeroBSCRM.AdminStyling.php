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






			function zeroBSCRM_getCloseState($key=''){

		if (!empty($key)) return get_option('zbs_closers_'.$key,false);

		return false;

	}
		function zeroBSCRM_clearCloseState($key=''){

		if (!empty($key)) return delete_option('zbs_closers_'.$key);

		return false;

	}







		add_filter( 'gettext', 'zbs_change_publish_button', 10, 2 );
	function zbs_change_publish_button( $translation, $text ) {
		if ( $text == 'Publish' ) return 'Save';
		return $translation;
	}

			function zeroBSCRM_improvedPostMsgsCustomers( $messages ) {

		
		$messages['post'] = array(
		0 => '', 		1 => sprintf( __w('Customer updated. <a href="%s">Back to Customers</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_customer&page=manage-customers') ), 		2 => __w('Customer updated.','zerobscrm'),
		3 => __w('Customer field deleted.','zerobscrm'),
		4 => __w('Customer updated.','zerobscrm'),
		
		5 => isset($_GET['revision']) ? sprintf( __w('Customer restored to revision from %s','zerobscrm'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __w('Customer added. <a href="%s">Back to Customers</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_customer&page=manage-customers' ) ), 		7 => __w('Customer saved.','zerobscrm'),
		8 => sprintf( __w('Customer submitted. <a target="_blank" href="%s">Back to Customers</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_customer&page=manage-customers' ) ), 		9 => '', 		10 => sprintf( __w('Customer draft updated. <a target="_blank" href="%s">Back to Customers</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_customer&page=manage-customers' ) ) , 		);

		return $messages;
	}
	function zeroBSCRM_improvedPostMsgsCompanies( $messages ) {

		
		$messages['post'] = array(
		0 => '', 		1 => sprintf( __w(zeroBSCRM_getCompanyOrOrg().' updated. <a href="%s">Back to '.zeroBSCRM_getCompanyOrOrgPlural().'</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_company&page=manage-companies') ), 		2 => __w(zeroBSCRM_getCompanyOrOrg().' updated.','zerobscrm'),
		3 => __w(zeroBSCRM_getCompanyOrOrg().' field deleted.','zerobscrm'),
		4 => __w(zeroBSCRM_getCompanyOrOrg().' updated.','zerobscrm'),
		
		5 => isset($_GET['revision']) ? sprintf( __w(zeroBSCRM_getCompanyOrOrg().' restored to revision from %s','zerobscrm'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __w(zeroBSCRM_getCompanyOrOrg().' added. <a href="%s">Back to '.zeroBSCRM_getCompanyOrOrgPlural().'</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_company&page=manage-companies' ) ), 		7 => __w(zeroBSCRM_getCompanyOrOrg().' saved.','zerobscrm'),
		8 => sprintf( __w(zeroBSCRM_getCompanyOrOrg().' submitted. <a target="_blank" href="%s">Back to '.zeroBSCRM_getCompanyOrOrgPlural().'</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_company&page=manage-companies' ) ), 		9 => '', 		10 => sprintf( __w(zeroBSCRM_getCompanyOrOrg().' draft updated. <a target="_blank" href="%s">Back to '.zeroBSCRM_getCompanyOrOrgPlural().'</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_customer&page=manage-companies' ) ) , 		);

		return $messages;
	}
	function zeroBSCRM_improvedPostMsgsInvoices( $messages ) {

		
		$messages['post'] = array(
		0 => '', 		1 => sprintf( __w('Invoice updated. <a href="%s">Back to Invoices</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_invoice&page=manage-invoices') ), 		2 => __w('Invoice updated.','zerobscrm'),
		3 => __w('Invoice field deleted.','zerobscrm'),
		4 => __w('Invoice updated.','zerobscrm'),
		
		5 => isset($_GET['revision']) ? sprintf( __w('Invoice restored to revision from %s','zerobscrm'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __w('Invoice added. <a href="%s">Back to Invoices</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_invoice&page=manage-invoices' ) ), 		7 => __w('Invoice saved.','zerobscrm'),
		8 => sprintf( __w('Invoice submitted. <a target="_blank" href="%s">Back to Invoices</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_invoice&page=manage-invoices' ) ), 		9 => '', 		10 => sprintf( __w('Invoice draft updated. <a target="_blank" href="%s">Back to Invoices</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_invoice&page=manage-invoices' ) ) , 		);

		return $messages;
	}
	function zeroBSCRM_improvedPostMsgsQuotes( $messages ) {

		
		$messages['post'] = array(
		0 => '', 		1 => sprintf( __w('Quote updated. <a href="%s">Back to Quotes</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_quote&page=manage-quotes') ), 		2 => __w('Quote updated.','zerobscrm'),
		3 => __w('Quote field deleted.','zerobscrm'),
		4 => __w('Quote updated.','zerobscrm'),
		
		5 => isset($_GET['revision']) ? sprintf( __w('Quote restored to revision from %s','zerobscrm'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __w('Quote added. <a href="%s">Back to Quotes</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_quote&page=manage-quotes' ) ), 		7 => __w('Quote saved.','zerobscrm'),
		8 => sprintf( __w('Quote submitted. <a target="_blank" href="%s">Back to Quotes</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_quote&page=manage-quotes' ) ), 		9 => '', 		10 => sprintf( __w('Quote draft updated. <a target="_blank" href="%s">Back to Quotes</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_quote&page=manage-quotes' ) ) , 		);

		return $messages;
	}
	function zeroBSCRM_improvedPostMsgsTransactions( $messages ) {

		
		$messages['post'] = array(
		0 => '', 		1 => sprintf( __w('Transaction updated. <a href="%s">Back to Transactions</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_transaction') ), 		2 => __w('Transaction updated.','zerobscrm'),
		3 => __w('Transaction field deleted.','zerobscrm'),
		4 => __w('Transaction updated.','zerobscrm'),
		
		5 => isset($_GET['revision']) ? sprintf( __w('Transaction restored to revision from %s','zerobscrm'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __w('Transaction added. <a href="%s">Back to Transactions</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_transaction' ) ), 		7 => __w('Transaction saved.','zerobscrm'),
		8 => sprintf( __w('Transaction submitted. <a target="_blank" href="%s">Back to Transactions</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_transaction' ) ), 		9 => '', 		10 => sprintf( __w('Transaction draft updated. <a target="_blank" href="%s">Back to Transactions</a>','zerobscrm'), esc_url( 'edit.php?post_type=zerobs_transaction' ) ) , 		);

		return $messages;
	}








	#} Footer Text
	function zeroBSCRM_footer_admin () 
	{
		global $zeroBSCRM_version, $zeroBSCRM_urls;

				if (zeroBSCRM_isAdminPage()){
			echo 'If you like <strong>Zero BS CRM</strong> please leave us a <a href="'.$zeroBSCRM_urls['rateuswporg'].'" target="_blank" class="zbs-rating-link" data-rated="Thanks :)">★★★★★</a> rating. A huge thanks in advance!';
			return '';
		}

		$showThanks = zeroBSCRM_getSetting('showthanksfooter');
	    if ($showThanks) {
	    	echo '<span id="footer-thankyou">Thank you for  using <a href="'.$zeroBSCRM_urls['home'] .'" target="_blank">Zero BS CRM</a></span>';
	    }
	    return '';
	}
	add_filter('admin_footer_text', 'zeroBSCRM_footer_admin');
	function zeroBSCRM_footer_ver() 
	{
		global $zeroBSCRM_version, $zeroBSCRM_urls;
		$showThanks = zeroBSCRM_getSetting('showthanksfooter');
	    if ($showThanks) return '<p id="footer-upgrade" class="alignright">Version '.$zeroBSCRM_version.'</p>';
	    return '';
	}
	add_filter('update_footer', 'zeroBSCRM_footer_ver', 11);








#} Admin Colour Schemes
add_action('admin_head','zbs_color_grabber');
function zbs_color_grabber(){
        global $_wp_admin_css_colors, $zbsadmincolors;
    $current_color = get_user_option( 'admin_color' );
    echo '<script type="text/javascript">var zbsJS_admcolours = '.json_encode($_wp_admin_css_colors[$current_color]).';</script>';
    echo '<script type="text/javascript">var zbsJS_unpaid = "'. __w('unpaid','zerobscrm') .'";</script>';
    echo '<script type="text/javascript">var zbJS_curr = "'. zeroBSCRM_getCurrencyChr() .'";</script>';
    $zbsadmincolors = $_wp_admin_css_colors[$current_color]->colors;
    ?>
    <style>
    	.ranges li{
    		color: <?php echo $zbsadmincolors[0]; ?>;
    	}
    	.max_this{
			color: <?php echo $zbsadmincolors[0]; ?> !important;
    	}
    	.ranges li:hover, .ranges li.active {
		    background: <?php echo $zbsadmincolors[0]; ?> !important;
		    border: 1px solid <?php echo $zbsadmincolors[0]; ?> !important;
		}
		.daterangepicker td.active{
			background-color: <?php echo $zbsadmincolors[0]; ?> !important;
		}
		.zerobs_customer{
			background-color: <?php echo $zbsadmincolors[0]; ?> !important;
		}
		.users-php .zerobs_customer {
  			background: none !important; 
  		}
		.zerobs_transaction{
			background-color: <?php echo $zbsadmincolors[2]; ?> !important;
		}
		.zerobs_invoice{
			background-color: <?php echo $zbsadmincolors[1]; ?> !important;
		}
		.zerobs_quote{
			background-color: <?php echo $zbsadmincolors[3]; ?> !important;
		}
		.graph-box .view-me, .rev{
			color: <?php echo $zbsadmincolors[0]; ?> !important;
		}
		.toplevel_page_sales-dash .sales-graph-wrappers .area, .sales-dashboard_page_gross-revenue .sales-graph-wrappers .area, .sales-dashboard_page_net-revenue .sales-graph-wrappers .area, .sales-dashboard_page_discounts .sales-graph-wrappers .area, .sales-dashboard_page_fees .sales-graph-wrappers .area, .sales-dashboard_page_average-rev .sales-graph-wrappers .area, .sales-dashboard_page_new-customers .sales-graph-wrappers .area, .sales-dashboard_page_total-customers .sales-graph-wrappers .area{
			fill: <?php echo $zbsadmincolors[0]; ?> !important;
		}
		.bar{
			fill: <?php echo $zbsadmincolors[0]; ?> !important;	
		}
    </style>
    <?php
}








	function zeroBSCRM_stopFrontEnd(){

		
		if (is_user_logged_in()){
						header('Location: '.admin_url( 'admin.php?page=manage-customers' ));
			exit();

		} else {
						header('Location: '.wp_login_url());
			exit();
		}

	}

	function zeroBSCRM_catchDashboard(){

		
						if (!zeroBSCRM_isAPIRequest() && !zeroBSCRM_isClientPortalPage()) { 

				
				
				if( is_admin() && zeroBSCRM_permsIsZBSUser()) {

															
										global $pagenow;

						if ($pagenow == 'profile.php' || $pagenow == 'index.php' ){
														$sent = false;
							if (zeroBSCRM_permsCustomers())	{
								wp_redirect( admin_url( 'admin.php?page=manage-customers' ) );
								$sent = 1;
							}
							if (!$sent && zeroBSCRM_permsQuotes()) {
								wp_redirect( admin_url( 'admin.php?page=manage-quotes' ) );
								$sent = 1;
							}
							if (!$sent && zeroBSCRM_permsInvoices()) {
								wp_redirect( admin_url( 'admin.php?page=manage-invoices' ) );
								$sent = 1;
							}
							if (!$sent){

																wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') );
							}
							
						}
				}

			} 
	}












add_action( 'admin_bar_menu', 'remove_wp_items', 100 );
function remove_wp_items( $wp_admin_bar ) {

	global $zeroBSCRM_Settings; 
		$customheadertext = $zeroBSCRM_Settings->get('customheadertext');	

		$takeoverModeAll = $zeroBSCRM_Settings->get('wptakeovermodeforall');
	$takeoverModeZBS = $zeroBSCRM_Settings->get('wptakeovermode');  

	$takeoverMode = false; 

	if ($takeoverModeAll || (zeroBSCRM_permsIsZBSUser() && $takeoverModeZBS)) $takeoverMode = true;


	if ($takeoverMode){

		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('site-name');
		$wp_admin_bar->remove_menu('comments');
		$wp_admin_bar->remove_menu('new-content');
		$wp_admin_bar->remove_menu('my-account');
		
		if (!empty($customheadertext)){

									 $wp_admin_bar->add_node( array(

			 	'id' => 'zbshead',
			 	'title' => '<div class="wp-menu-image dashicons-before dashicons-groups" style="display: inline-block;margin-right: 6px;"><br></div>'.$customheadertext,
			 	'href' => 'admin.php?page=zerobscrm-dash',
			 	'meta' => array(
			 					 	)


			 ) );

		}

	}
}








function zeroBSCRM_addThemeThumbnails(){

	if ( function_exists( 'add_theme_support' ) ) {
		add_theme_support( 'post-thumbnails' );
	}
}





		define('ZBSCRM_INC_ADMSTY',true);