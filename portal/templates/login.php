<?php /* *
 * Client Area Login Template.
 *
 * This template can be overriden by copying this file to your-theme/zerobscrm-plugin-templates/login.php
 *
 * @author 		Mike Stott
 * @package 	Customer Portal
 * @version     1.2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; add_action( 'wp_enqueue_scripts', 'zeroBS_portal_enqueue_stuff' );
get_header();
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
<h2><?php _we('Welcome to your Client Portal','zerobscrm');?></h2>
<p>Please login to your Client Portal to be able to view your documents</p>
<div class="login-form">
<?php
wp_login_form( $args );
?>
</div>
<?php
echo '</div>';


?>
</div>

<?php get_footer(); ?>