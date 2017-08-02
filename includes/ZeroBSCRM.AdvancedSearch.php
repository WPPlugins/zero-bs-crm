<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V2+
 *
 * Copyright 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 12/07/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;



function zeroBSCRM_advancedSearch(){

	global $wpdb;

	$querystring = ''; $s1 =''; $s2 = '';

	if(isset($_GET['adv-s']) && $_GET['zbs-which'] == 0){
		$querystring = sanitize_text_field($_GET['adv-s']);
		$results = zeroBS_searchCustomers($querystring);
		$s1 = 'selected';
		$s2 = '';
	}
	if(isset($_GET['adv-s']) && $_GET['zbs-which'] == 1){
		$querystring = sanitize_text_field($_GET['adv-s']);
		$results = zeroBS_searchLogs($querystring);
		$s1 = '';
		$s2 = 'selected';
	}

	echo '<h4>' . __w('Advanced Search','zerobscrm') . '</h4>';
		if (current_user_can('manage_options')) echo '<div class="notice is-dismissible"><p>Note: This is a BETA feature of ZBSCRM, please do <a href="'.admin_url('admin.php?page=zerobscrm-feedback').'" target="_blank">Give Feedback</a> if you have time!</p></div>';
	echo '<div id="zbs-search-form"><form method="GET"><input type="hidden" name="page" value="advancedy-search-crm" />';
	echo '<input type="text" class="form-control act-ser" id="adv-s" name="adv-s" value="'.$querystring.'" placeholder="Search.."/>';
	?>
		<select id="zbs-which" name="zbs-which" class="form-control">
			<option value="0" <?php echo $s1;?>><?php _we("Customers","zerobscrm"); ?></option>
			<option value="1" <?php echo $s2;?>><?php _we("Activity","zerobscrm"); ?></option>
		</select>
	<?php
	echo '<input type="submit" value="Search" class="button-primary las"/>';
	echo '</form></div>';

	?>

	<style>
		#zbs-search-form {
			margin:10px;
		}
		#adv-s{
			width:300px;
			float:left;
			margin-left:5px;
			margin-right:10px;
		}
		#zbs-which{
			width:200px;
			float:left;
			margin-right:10px;
		}
		.las{
			margin-top:20px;
		}
		.activity-log{
			margin:20px;
		}
		.activity-log .log{
			background:white;
			margin-bottom:20px;
			border: 1px solid #ddd;
			padding:20px;
		}
		.log .log-title{
			font-size:14px;
		}
		.log .log-content{
			font-size:12px;
		}
		.log-footer{
			border-top: 1px solid #999;
		}
		.log-footer .type{
			font-size: 11px;
			font-style: italic;
		}
		.img-rounded{
			border-radius:50%;
		}
		.avatar{
			width:50px;
			float:left;
		}
		.wrapper{
			margin-left:50px;
		}
		.log-status{
			padding:5px;
			font-size:13px;
			color:white;
			background:#999;
			display:inline-block;
		}
	</style>
	<div class="activity-log">
	<?php

	$default = 'http://1.gravatar.com/avatar/4f1e528f9735dd9d0cbc322ca321db52?s=32&d=mm&f=y&r=g;';
	$size = 40;
	$searchType = -1; if (isset($_GET['zbs-which'])) $searchType = (int)$_GET['zbs-which'];
	switch($searchType){

				case 1:

						foreach($results as $result){

												$customer = zeroBS_getCustomer($result['owner']);

								if (is_array($customer['meta'])){

															$customerName = zeroBS_customerName($result['owner'],$customer['meta'],false,true);
					$customerEmail = zeroBS_customerEmail($result['owner'],$customer['meta']);
					
				} else {

					$customerName = ''; $customerEmail = ''; 
				}

				$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $customerEmail ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;


				echo "<div class='log'>";
					echo "<div class='avatar'>";
						echo '<img class="img-rounded" src="' .$grav_url. '" alt="" />';
					echo "</div><div class='wrapper'>";
					echo "<div class='log-title'>";
						echo $result['meta']['shortdesc'];
					echo "</div>";
					echo "<div class='log-content'>";
						echo $result['meta']['longdesc'];
					echo "</div>";
					echo "<div class='log-footer'>";
						echo "<div class='type'>" . $result['meta']['type'] . " (" . $result['created'] . ")</div>";
						if (!empty($customerName)){
							echo "<div class='when'><span class='name'><a href=\"".admin_url('post.php?post='.$result['owner'].'&action=edit')."\">" . $customerName . '</a>';
							if (!empty($customerEmail)) echo ' ('.$customerEmail.')'; 							echo "</span></div>"; 
						} else {
							echo "<div class='when'><span class='name'>Customer Deleted</span></div>"; 

						}
					echo "</div></div>";
				echo '</div>';


			}

			break;


				case 0: 

						foreach($results as $result){
				
												$customerName = zeroBS_customerName($result['id'],$result,false,true);
				$customerEmail = zeroBS_customerEmail($result['id'],$result);
				$customerAddr = zerobs_customerAddr($result['id'],array(),'full');


				$grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $customerEmail ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
				echo "<div class='log'>";
					echo "<div class='avatar'>";
						echo '<img class="img-rounded" src="' .$grav_url. '" alt="" />';
					echo "</div><div class='wrapper'>";
					echo "<div class='log-status'>";
						echo $result['status'];
					echo "</div>";
					echo "<div class='log-content'>";
						echo "<div class='name'>";
							echo "<h4>" . $customerName . "</h4>";
						echo "</div>";
						echo "<div class='address'>";
						echo $customerAddr;
						
						echo "</div>";
					echo "</div>";
					echo "<div class='log-footer'>";
						echo "<div class='when'><span class='name'><a href=\"".admin_url('post.php?post='.$result['id'].'&action=edit')."\">" . $customerName . '</a>';
						if (!empty($customerEmail)) echo ' ('.$customerEmail.')'; 						echo "</span></div>";
					echo "</div></div>";
				echo '</div>';
			}

			break;

			default:

								
				break;

		}


	
	?>
	</div>


	<?php
}




define('ZBSCRM_INC_ACTIVITY_SEARCH',true);