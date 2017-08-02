<?php /* *
 * Redeem gift card template.
 *
 * This template can be overriden by copying this file to your-theme/zerobscrm-plugin-templates/dashboard.php
 * It uses the DEFAULT styles of the Theme. Extra style packs are available from 
 * https://zerobscrm.com/extensions/
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
				zeroBS_portalnav('dashboard');
	?>

	<div class='zbs-portal-wrapper'>

		<h1>Welcome to your Dashboard</h1>

		<div class='content'>
			<p>
				<?php
				_we("Welcome to your Client Dashboard. Here you can view your information.", "zerobscrm");
				?>
		</div>


	</div>

</div>

<?php 

get_footer();

 ?>