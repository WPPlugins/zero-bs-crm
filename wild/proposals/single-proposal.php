<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.2+
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 29/12/2016
 */



		global $post,$zbsQuoteData;

	
	
	$uid = get_current_user_id();
	$cID = zeroBS_getCustomerIDFromWPID($uid);

	if((int)$zbsQuoteData['customerid'] == (int)$cID){

?>

	<div id="zerobs-proposal-<?php echo $post->ID; ?>" class="zerobs-proposal">
		
		<div class="zerobs-proposal-body"><?php if (isset($zbsQuoteData) && isset($zbsQuoteData['quotebuilder']) && isset($zbsQuoteData['quotebuilder']['content'])) echo $zbsQuoteData['quotebuilder']['content']; ?></div>
	
		<?php 		if (isset($zbsQuoteData) && isset($zbsQuoteData['meta']) && (!isset($zbsQuoteData['meta']['accepted']) || (isset($zbsQuoteData['meta']['accepted']) && empty($zbsQuoteData['meta']['accepted'])))){ ?>
			<div class="zerobs-proposal-actions" id="zerobs-proposal-actions-<?php echo $post->ID; ?>">
				<h3><?php _we('Accept Proposal?','zerobscrm'); ?></h3>
				<label for="zerobs-proposal-signer-<?php echo $post->ID; ?>"><?php _we('Please enter your email to confirm you accept this proposal:','zerobscrm'); ?></label>
				<input type="text" class="zerobs-proposal-signer" id="zerobs-proposal-signer-<?php echo $post->ID; ?>" placeholder="Your Email..." value="" />
				<div class="zerobs-proposal-fielderr zerobs-proposal-signererr" id="zerobs-proposal-signererr-<?php echo $post->ID; ?>"><?php _we('Your email is required in order to accept a proposal','zerobscrm'); ?></div>
				<br />
				<button class="button btn btn-large btn-success button-success zerobs-proposal-accept" data-zbsquoid="<?php echo $post->ID; ?>" data-zbsquohash="<?php echo get_post_meta($post->ID,'zbshash',true); ?>"><?php _we('Accept','zerobscrm'); ?></button>
			</div>
			<div class="zerobs-proposal-fini" id="zerobs-proposal-fini-<?php echo $post->ID; ?>"><?php _we('Thank you for accepting this proposal, we\'ll be in touch shortly.','zerobscrm'); ?></div>
			<div class="zerobs-proposal-err" id="zerobs-proposal-err-<?php echo $post->ID; ?>"><?php _we('There was an error accepting this proposal, please email us to let us know.','zerobscrm'); ?></div>
		<?php } ?>

		<?php             $showPoweredBy = zeroBSCRM_getSetting('showpoweredbyquotes');
            if ($showPoweredBy == "1"){ global $zeroBSCRM_urls; ?><div class="zerobs-proposal-poweredby"><?php _we('Proposals Powered by','zerobscrm'); ?> <a href="<?php echo $zeroBSCRM_urls['home']; ?>" target="_blank">ZeroBS CRM</a> <img src="<?php echo ZEROBSCRM_URL.'i/WYSIWYG_icon.png'; ?>" alt="ZeroBS CRM" /></div>
        <?php } ?>

	</div>
	<div style="clear:both"></div>
    <?php echo '<script type="text/javascript">var zbsCRM_JS_proposalNonce = \''.wp_create_nonce( "zbscrmquo-nonce" ).'\';var zbsCRM_JS_AJAXURL = \''.esc_url( admin_url('admin-ajax.php') ).'\';</script>'; ?>
	<?php ?>
	<script type="text/javascript" src="<?php echo ZEROBSCRM_URL.'js/ZeroBSCRM.public.proposals.min.js'; ?>"></script>

<?php

}else{

?>
<div class="zbs-client-portal-wrap">

<?php

$args = array(
	'echo'           => true,
	'remember'       => true,
	'redirect'       => site_url( '/clients/dashboard/' ),
	'form_id'        => 'loginform',
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'label_username' => __( 'Email Address' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'value_username' => '',
	'value_remember' => false
);

echo '<div class="container zbs-portal-login" style="margin-top:20px;text-align:center;">';
?>
<h2><?php _we('Login to your Client Portal','zerobscrm');?></h2>
<p><?php _we('Please login to your Client Portal to be able to view your Quote.','zerobscrm');?></p>
<p><?php _we('This is your usual login details. If you have any difficulties in using this page please contact us.','zerobscrm');?></p>
<div class="login-form">
<?php
wp_login_form( $args );
?>
</div>
<?php
echo '</div>';


?>
</div>

<?php
}



