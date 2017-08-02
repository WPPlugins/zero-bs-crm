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







      function zeroBSCRM_update_edit_form() {

      global $post;
      
            if(!$post)
          return;
      
            $post_type = get_post_type($post->ID);
      
                  if (!in_array($post_type,array('zerobs_customer','zerobs_quote','zerobs_invoice','zerobs_transaction','zerobs_company')))
          return;

            printf(' enctype="multipart/form-data" encoding="multipart/form-data" ');

  }
  add_action('post_edit_form_tag', 'zeroBSCRM_update_edit_form');








        function zeroBSCRM_pages_header($subpage=''){

    	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version,$zeroBSCRM_Settings, $zeroBSCRM_slugs;	    	
    	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
        
        
      ?>
      <div id="sgpBody">
          <div class="wrap"> 
      	    <div id="icon-sb" class="icon32"><br /></div><h2>Zero BS CRM<?php if (!empty($subpage)) echo ': '.$subpage; ?></h2> 
          </div>
          <div id="sgpHeader">
      		<a href="<?php echo $zeroBSCRM_urls['home']; ?>?utm_source=inplugin&utm_medium=adminhead" title="Zero BS CRM Plugin Homepage" target="_blank">ZBSCRM</a> | 
      		<?php if (!empty($zeroBSCRM_urls['support'])) { ?><a href="<?php echo $zeroBSCRM_urls['support']; ?>" title="Zero BS CRM Plugin <?php _we('Support Page','zerobscrm'); ?>" target="_blank"><?php _we('Support','zerobscrm'); ?></a> | <?php } ?>
      		<?php if (!empty($zeroBSCRM_urls['docs'])) { ?><a href="<?php echo $zeroBSCRM_urls['docs']; ?>" title="View Documentation" target="_blank">Documentation</a> | <?php } ?>
          <a href="<?php echo admin_url('admin.php?page='.$zeroBSCRM_slugs['extensions']); ?>" title="Extensions Manager"><?php _e('Extensions Manager','zerobscrm'); ?></a> | 
          <?php if (!empty($zeroBSCRM_urls['products'])) { ?><a href="<?php echo $zeroBSCRM_urls['products']; ?>?utm_source=inplugin&utm_medium=adminhead" title="<?php _we('CRM Power-ups','zerobscrm'); ?>" target="_blank"><?php _we('Extension  Store','zerobscrm'); ?></a> | <?php } ?>
      		<?php $ulj = $zeroBSCRM_Settings->get('updatelistjoin'); if (!empty($zeroBSCRM_urls['updatelist']) && empty($ulj)) { ?><a href="<?php echo $zeroBSCRM_urls['updatelist']; ?>" title="<?php _we('Get Notified About Updates','zerobscrm'); ?>" target="_blank"><?php _we('Get Notified About Updates','zerobscrm'); ?></a> | <?php } ?>
      		<?php if(function_exists('zerobscrm_tidyadmin')){ ?>
      		<a href="plugins.php" title="<?php _we('View Active Plugins','zerobscrm'); ?>" target="_blank"><?php _we('Manage Extensions','zerobscrm'); ?></a> |
      		<?php } ?>
      	
              Version <?php echo $zeroBSCRM_version; ?>   
              <?php  ?>
              <a href="<?php echo $zeroBSCRM_urls['limitedlaunch']; ?>?utm_source=inplugin&utm_medium=adminhead" target="_blank" class="button" style="height: 18px;line-height: 16px;color: #000;background: orange;border-color: red;margin-left: 14px;"><?php _e('Limited Launch Offer','zerobscrm'); ?></a>
          </div>
          <div id="ZeroBSCRMAdminPage">
          <?php 	
      	
      	      	    }


        function zeroBSCRM_pages_footer(){
        
    	?></div><?php 	
    	
    }


        function zeroBSCRM_pages_logout() {

    	?><script type="text/javascript">window.location='<?php echo wp_logout_url(); ?>';</script><h1 style="text-align:center">Logging you out!</h1><?php

    }







  function zeroBSCRM_pagelink($page){
    if($page > 0){
      $pagin = $page;
    }else{
      $pagin = 0;
    }
    $zbsurl = get_admin_url('','admin.php?page=customer-searching') ."&zbs_page=".$pagin;
    return $zbsurl;
  }

  function zeroBSCRM_pagination( $args = array() ) {
      $defaults = array(
          'range'           => 4,
          'previous_string' => __w( 'Previous', 'zerobscrm' ),
          'next_string'     => __w( 'Next', 'zerobscrm' ),
          'before_output'   => '<ul class="pagination">',
          'after_output'    => '</ul>',
          'count'       => 0,
          'page'        => 0
      );
      
      $args = wp_parse_args( 
          $args, 
          apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
      );
      
      $args['range'] = (int) $args['range'] - 1;
      $count = (int)$args['count'];
      $page  = (int)$args['page'];
      $ceil  = ceil( $args['range'] / 2 );
      
      if ( $count <= 1 )
          return FALSE;
      
      if ( !$page )
          $page = 1;
      
      if ( $count > $args['range'] ) {
          if ( $page <= $args['range'] ) {
              $min = 1;
              $max = $args['range'] + 1;
          } elseif ( $page >= ($count - $ceil) ) {
              $min = $count - $args['range'];
              $max = $count;
          } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
              $min = $page - $ceil;
              $max = $page + $ceil;
          }
      } else {
          $min = 1;
          $max = $count;
      }
      
      $echo = '';
      $previous = intval($page) - 1;
      $previous = esc_attr( zeroBSCRM_pagelink($previous) );
      
      $firstpage = esc_attr( zeroBSCRM_pagelink(0) );

      if ( $firstpage && (1 != $page) )
          $echo .= '<li class="previous"><a href="' . $firstpage . '">' . __w( 'First', 'text-domain' ) . '</a></li>';
      if ( $previous && (1 != $page) )
          $echo .= '<li><a href="' . $previous . '" title="' . __w( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
      
      if ( !empty($min) && !empty($max) ) {
          for( $i = $min; $i <= $max; $i++ ) {
              if ($page == $i) {
                  $echo .= '<li class="active"><span class="active">' . str_pad( (int)$i, 2, '0', STR_PAD_LEFT ) . '</span></li>';
              } else {
                  $echo .= sprintf( '<li><a href="%s">%002d</a></li>', esc_attr( zeroBSCRM_pagelink($i) ), $i );
              }
          }
      }
      
      $next = intval($page) + 1;
      $next = esc_attr( zeroBSCRM_pagelink($next) );
      if ($next && ($count != $page) )
          $echo .= '<li><a href="' . $next . '" title="' . __w( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
      
      $lastpage = esc_attr( zeroBSCRM_pagelink($count) );
      if ( $lastpage ) {
          $echo .= '<li class="next"><a href="' . $lastpage . '">' . __w( 'Last', 'text-domain' ) . '</a></li>';
      }
      if ( isset($echo) )
          echo $args['before_output'] . $echo . $args['after_output'];
  }




function zeroBSCRM_pages_dash(){
  global  $zeroBSCRM_slugs;
  global  $zeroBSCRM_paypal_slugs;  ?>
<style>
.zbs-box{
  height: 300px;
  background: white;
  box-shadow: 1px;
  border: 1px solid #ddd;
}
.col-md-4{
  width: 30%;
  margin-right: 3%;
  float:left;
  margin-bottom: 10px;
  margin-top:10px;
}
.title{
  text-align: center;
}
.title .fa{
  margin-right:5px;
}
.zbs-box p{
  text-align: center;
  position:relative;
}
.zbs-button{
  display: inline-block;
  margin: 10px !important;
}
.video{
  text-decoration: none;
}
.center{
  text-align:center;
}
.zbs-pad{
  margin: 5%;
  text-align:center;
}
.intro{
  font-size:15px;
  text-align: center;
}
.video{
  display:none;
}
</style>
<h1 class='center'>Welcome to Zero BS CRM</h1>
<p class='intro'>Need help with any of the features of Zero BS CRM. You can <a href='<?php echo get_admin_url('','admin.php?page='.$zeroBSCRM_slugs['feedback']);?>'>contact us here</a></p>
<div class='row'>

  <!-- I don't think we need this, at least at top? 
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-dashboard'></i><?php _we('Dashboard', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('View your WordPress Dashboard', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url(); ?>'><?php _we('View Dashboard','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a><br/>
    </p>
  </div> -->

<?php

      $ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
    $ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');

?>

<?php if (zeroBSCRM_permsCustomers()){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-user'></i><?php _we('Customers', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Manage your customers here', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href="<?php echo get_admin_url('','edit.php?post_type=zerobs_customer&page=manage-customers');?>"><?php _we('Manage Customers','zerobscrm');?></a><br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','edit-tags.php?taxonomy=zerobscrm_customertag&post_type=zerobs_customer');?>'><?php _we('Customer Tags','zerobscrm');?></a><br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=customer-searching');?>'><?php _we('Customer Search','zerobscrm');?></a><br/>      
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=zerobscrm-plugin-settings&tab=customfields');?>'><?php _we('Add Extra Customer Fields','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>
<?php } ?>

<?php if ($ZBSuseQuotes == "1" && zeroBSCRM_permsQuotes()){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-clipboard'></i><?php _we('Quotes', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Manage your quotes here', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=manage-quotes');?>'><?php _we('Manage Quotes','zerobscrm');?></a><br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','edit.php?post_type=zerobs_quote&page=manage-quote-templates');?>'><?php _we('Manage Templates','zerobscrm');?></a><br/>

    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a><br/>
    </p>
  </div>
<?php } ?>


<?php if ($ZBSuseInvoices == "1" && zeroBSCRM_permsInvoices()){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-file-text-o'></i><?php _we('Invoices', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Manage your invoices here', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','edit.php?post_type=zerobs_invoice&page=manage-invoices');?>'><?php _we('Manage Invoices','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a><br/>
    </p>
  </div>
<?php } ?>

<?php if (zeroBSCRM_permsTransactions()){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-shopping-cart'></i><?php _we('Transactions', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Add transactions to your CRM', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=manage-transactions');?>'><?php _we('Manage Transactions','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a><br/>
    </p>
  </div>
<?php } ?>

<?php if (zeroBSCRM_isExtensionInstalled('forms')){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-wpforms'></i><?php _we('Forms', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Setup your front end forms here', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','edit.php?post_type=zerobs_form');?>'><?php _we('Manage Forms','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a><br/>
    </p>
  </div>
<?php } ?>

<?php if (current_user_can( 'manage_options' )){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-cog'></i><?php _we('Data Tools', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Import and Export your CRM data', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=zerobscrm-datatools');?>'><?php _we('Data Tools','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>

  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-refresh'></i><?php _we('Sync Tools', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Sync your CRM with our Sync tools', 'zerobscrm'); ?>
      <br/>
      <?php $anySyncToolsInstalled = false; 
      if (zeroBSCRM_isExtensionInstalled('pay')){ $anySyncToolsInstalled = true; ?><a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=paypal-importer');?>'>PayPal Sync</a><br/><?php } 
      if (zeroBSCRM_isExtensionInstalled('woo')){ $anySyncToolsInstalled = true; ?><a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=woo-importer');?>'>Woo Sync</a><br/><br/><?php } 
      

      if (!$anySyncToolsInstalled){
        echo '<br /><br /><strong>No Sync Tools Installed!:</strong><br />';
        ?><a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page='.$zeroBSCRM_slugs['extensions']);?>'>Purchase Sync Tools</a><?php
      }
      ?>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>
<?php } ?>

<?php if (zeroBSCRM_isExtensionInstalled('mailcampaigns')){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-envelope'></i><?php _we('Mail Campaigns', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Send your customers targetted emails', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','edit.php?post_type=zerobs_mailcampaign&page=manage-campaigns');?>'><?php _we('Mail Campaings','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>  
<?php } ?>


<?php if (zeroBSCRM_isExtensionInstalled('salesdash')){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-bar-chart'></i><?php _we('Sales Dashboard', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('View your transaction history in an awesome sales dashboard', 'zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=sales-dash');?>'><?php _we('Sales Dashboard','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>
<?php } ?>  

<?php if (current_user_can( 'manage_options' )){ ?>
  <div class="zbs-box col-md-4">
    <h2 class='title'><i class='fa fa-cog'></i><?php _we('Settings', 'zerobscrm'); ?></h2>
    <p>
      <?php _we('Setup your CRM','zerobscrm'); ?>
      <br/>
      <a class='zbs-button button-primary' href='<?php echo get_admin_url('','admin.php?page=zerobscrm-plugin-settings');?>'><?php _we('Setup your CRM','zerobscrm');?></a><br/>
    </p>
    <br/>
    <p>
      <a href='' class='video'><i class='fa fa-play'></i> Learn more here</a>
    </p>
  </div>
</div>
<?php } ?>  


<?php
}



function zerobscrm_show_love($url='', $text='Zero BS - The WordPress CRM'){
		?>
	<style>
	 ul.share-buttons{
	  list-style: none;
	  padding: 0;
	  text-align: center;
	}
	ul.share-buttons li{
		display: inline-block;
		margin-left:4px;
	}
	.logo-wrapper{
		padding:20px;
	}
	.logo-wrapper img{
		width:200px;
	}
	</style>

	<?php $text = htmlentities($text); ?>

	<p style="font-size:16px;text-align:center"><?php _we('Zero BS CRM is the ultimate CRM tool for WordPress.<br/ >Help us get the word out and show some love... You know what to do...','zerobscrm'); ?></p>
	<ul class="share-buttons">
  <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fzerobscrm.com&t=<?php echo $text;?>" target="_blank"
  ><img src="<?php echo ZEROBSCRM_URL.'i/Facebook.png'; ?>"></a></li>
  <li><a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fzerobscrm.com&text=<?php echo $text;?>%20http%3A%2F%2Fzerobscrm.com&via=epicplugins" target="_blank" title="Tweet"><img src="<?php echo ZEROBSCRM_URL.'i/Twitter.png'; ?>"></a></li>
  <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fzerobscrm.com" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(<?php echo $url; ?>)); return false;"><img src="<?php echo ZEROBSCRM_URL.'i/Google+.png'; ?>"></a></li>
  <li><a href="http://www.tumblr.com/share?v=3&u=http%3A%2F%2Fzerobscrm.com&t=<?php echo $text;?>&s=" target="_blank" title="Post to Tumblr"><img src="<?php echo ZEROBSCRM_URL.'i/Tumblr.png'; ?>"></a></li>
  <li><a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fzerobscrm.com&description=<?php echo $text;?>" target="_blank" title="Pin it"><img src="<?php echo ZEROBSCRM_URL.'i/Pinterest.png'; ?>"></a></li>
  <li><a href="https://getpocket.com/save?url=http%3A%2F%2Fzerobscrm.com&title=<?php echo $text;?>" target="_blank" title="Add to Pocket"><img src="<?php echo ZEROBSCRM_URL.'i/Pocket.png'; ?>"></a></li>
  <li><a href="http://www.reddit.com/submit?url=http%3A%2F%2Fzerobscrm.com&title=<?php echo $text;?>" target="_blank" title="Submit to Reddit"><img src="<?php echo ZEROBSCRM_URL.'i/Reddit.png'; ?>"></a></li>
  <li><a href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fzerobscrm.com&title=<?php echo $text;?>&summary=&source=http%3A%2F%2Fzerobscrm.com" target="_blank" title="Share on LinkedIn"><img src="<?php echo ZEROBSCRM_URL.'i/LinkedIn.png'; ?>"></a></li>
  <li><a href="http://wordpress.com/press-this.php?u=http%3A%2F%2Fzerobscrm.com&t=<?php echo $text;?>&s=" target="_blank" title="Publish on WordPress"><img src="<?php echo ZEROBSCRM_URL.'i/Wordpress.png'; ?>"></a></li>
  <li><a href="https://pinboard.in/popup_login/?url=http%3A%2F%2Fzerobscrm.com&title=<?php echo $text;?>&description=" target="_blank" title="Save to Pinboard" <img src="<?php echo ZEROBSCRM_URL.'i/Pinboard.png'; ?>"></a></li>
  <li><a href="mailto:?subject=&body=<?php echo $text;?>:%20http%3A%2F%2Fzerobscrm.com" target="_blank" title="Email"><img src="<?php echo ZEROBSCRM_URL.'i/Email.png'; ?>"></a></li>
</ul>

	<?php
}


function zeroBSCRM_pages_home() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header();






    



		zeroBSCRM_html_home2(); 

		zeroBSCRM_pages_footer();

?>
</div>
<?php 
}
 

function zeroBSCRM_pages_feedback() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Send Us Feedback');

		zeroBSCRM_html_feedback(); 

		zeroBSCRM_pages_footer();

?>
</div>
<?php 
}

function zeroBSCRM_pages_extensions() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Extensions');

		zeroBSCRM_html_extensions(); 

		zeroBSCRM_pages_footer();

?>
</div>
<?php 
}

function zeroBSCRM_pages_bulktools() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Bulk Tools');

		zeroBSCRM_html_bulktools(); 

		zeroBSCRM_pages_footer();

?>
</div>
<?php 
}




function zeroBSCRM_admin_tabs( $current = 'homepage' ) { 

	global $zeroBSCRM_slugs, $zeroBSCRM_Settings;
	        $tabs = array( 'settings' => 'Settings'); 	
	$settings = $zeroBSCRM_Settings->getAll();
				$tabs['customfields'] = "Custom Fields";
	
			$tabs['fieldsorts'] = "Field Sorts";

	if($settings['feat_forms'] == 1){
		$tabs['forms'] = "Forms";
	} 

    $tabs['whlang'] = 'Language';


  if($settings['feat_api'] == 1){
    $tabs['api'] = "API";
  }

    
        global $zeroBSCRM_extensionsInstalledList;

        if (isset($zeroBSCRM_extensionsInstalledList) && is_array($zeroBSCRM_extensionsInstalledList)) foreach ($zeroBSCRM_extensionsInstalledList as $installedExt){

    	    	global $zbsExtensionsExcludeFromSettings;     	if (!in_array($installedExt, $zbsExtensionsExcludeFromSettings)){

	    		    	if (function_exists('zeroBSCRM_extension_name_'.$installedExt)){

								$extNameFunc = 'zeroBSCRM_extension_name_'.$installedExt;
	    		$tabs[$installedExt] = call_user_func($extNameFunc);

	    	} else {

	    			    		$tabs[$installedExt] = ucwords($installedExt);

	    	}

	    }

    }

    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=".$zeroBSCRM_slugs['settings']."&tab=$tab'>$name</a>";
        
    }
    echo '</h2>';
}







function zeroBSCRM_pages_settings() {
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version, $pagenow, $zeroBSCRM_slugs;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Settings');

		global $zeroBSCRM_extensionsInstalledList;

    	$currentTab = 'settings'; $getTab = ''; if (isset($_GET['tab'])) $getTab = sanitize_text_field($_GET['tab']);
  if ( !empty ( $getTab  )  && in_array($getTab,$zeroBSCRM_extensionsInstalledList)) $currentTab = $getTab;
   if ($getTab == 'customfields') $currentTab = 'customfields';
	if ($getTab == 'customers') $currentTab = 'customers';
	if ($getTab == 'quotes') $currentTab = 'quotes';
	if ($getTab == 'invoices') $currentTab = 'invoices';
	if ($getTab == 'forms') $currentTab = 'forms';

	  if ($getTab == 'fieldsorts') $currentTab = 'fieldsorts';

    if ($getTab == 'whlang') $currentTab = 'whlang';

    if($getTab == 'api') $currentTab = 'api';


	
    ?>

<div class="wrap">		
		<?php
			if ( isset($_GET['updated']) && 'true' == esc_attr( $_GET['updated'] ) ) echo '<div class="updated" ><p>Settings updated.</p></div>';
			
						zeroBSCRM_admin_tabs($currentTab);
		?>

		<div id="poststuff">
				<?php
				wp_nonce_field( "ilc-settings-page" ); 
				
				if ( $pagenow == 'admin.php' && $_GET['page'] == $zeroBSCRM_slugs['settings'] ){ 
				
										
					if ($currentTab == 'settings'){

												zeroBSCRM_html_settings();

					} else if ($currentTab == 'customfields') {

												zeroBSCRM_html_customfields();

                    																} else if($currentTab == 'forms'){ 
						zeroBSCRM_html_settings_forms();
					} else if ($currentTab == 'fieldsorts') {

												zeroBSCRM_html_fieldsorts();

          } else if ($currentTab == 'api') {

                        zeroBSCRM_html_api_page();

          } 

          else if ($currentTab == 'whlang') {

                        global $zeroBSCRM_helpEmail;
            $langPageHTML = zeroBSCRM_whLangLibLangEditPage('zerobscrm','zeroBSCRM_Settings','Zero BS CRM',$zeroBSCRM_helpEmail);
            echo $langPageHTML;


          } else {

												if (function_exists('zeroBSCRM_extensionhtml_settings_'.$currentTab)){

														$settingsPageFunc = 'zeroBSCRM_extensionhtml_settings_'.$currentTab;
							call_user_func($settingsPageFunc);

						} else {

							zeroBSCRM_html_msg(-1,'There was an error loading this settings page.');

						}

					}

									}
				?>
	
			<?php ?>

		</div>


<?php
	
		zeroBSCRM_pages_footer();

?>
</div>
<?php 
}



function zeroBSCRM_html_api_page(){

  global $zeroBSCRM_slugs;

    $confirmAct = false;

        if (isset($_GET['regeneratekeys'])) if ($_GET['regeneratekeys']==1){


      if (!isset($_GET['imsure'])){

                    $confirmAct = true;
          $actionStr        = 'regeneratekeys';
          $actionButtonStr    = __w('Regenerate API Key & Secret?','zerobscrm');
          $confirmActStr      = __w('Regenerate API Credentials','zerobscrm');
          $confirmActStrShort   = __w('Are you sure you want to regenerate your API Credentials','zerobscrm');
          $confirmActStrLong    = __w('Regenerating your API Credentials will mean that your existing details will no longer work.','zerobscrm');

        } else {


          if (wp_verify_nonce( $_GET['_wpnonce'], 'regeneratekeys' ) ){

              $newKey = zeroBSCRM_regenerateAPIKey();
              $newSecret = zeroBSCRM_regenerateAPISecret();
              $generatedNewKey = 1;

          }

        }

    } 

        if (isset($_POST['generate-key']) && $_POST['generate-key'] == 1) {

      $newKey = zeroBSCRM_regenerateAPIKey();
      $newSecret = zeroBSCRM_regenerateAPISecret();
      $generatedNewKey = 1;

    }
    
            $api_key = zeroBSCRM_getAPIKey();
    $api_secret = zeroBSCRM_getAPISecret();

    $endpoint_url = zeroBSCRM_getAPIEndpoint(); 
    

    if ($api_key == ''){
       ?>
       <style>
          .zbs-api-key-generate{
            padding:20px;
            text-align:center;
            font-size:30px;
            background:white;
          }
          .zbs-api-key-generate .button-primary{
              font-size:20px;
          }
       </style>
      <div class='zbs-api-key-generate'>
        <form action="#" method="POST">
            <p>
            <?php _we("You do not have an API key. Generate one?"); ?>
            </p>
            <input type='submit' class='generate-api button-primary' value='<?php _we("Generate API key"); ?>'/>
            <input type='hidden' name='generate-key' id='generate-key' value='1'/>
        </form>
      </div>
       <?php
    } else {

                if (isset($generatedNewKey)) zeroBSCRM_html_msg(0,__w('Successfully generated API Credentials','zerobscrm'));

        $perms = array('revoked','read and write');


        if (!$confirmAct){

            ?><p style="width:740px;margin:10px;padding: 14px;background: #FFF;"><strong>Your CRM API Endpoint:</strong> <?php echo $endpoint_url;  ?></p><?php

              echo '<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">';
              echo '<thead>';
                echo '<th>' . __w('API Key') . '</th>';
                echo '<th>' . __w('API Secret') . '</th>';
              echo '</thead>';
              echo '<tbody>';
                  echo '<tr>';
                    echo '<td class="bold">' . $api_key . '</td>';
                    echo '<td class="bold">' . $api_secret . '</td>';
                  echo '</tr>';
              echo '</tbody>';
              echo '</table>';

               ?>
               <style>
                  .zbs-api-key-generate{
                    padding:20px;
                    text-align:center;
                    font-size:30px;
                    background:white;
                    width:740px;margin:10px;
                  }
                  .zbs-api-key-generate .button-primary{
                      font-size:20px;
                  }
               </style>
              <div class='zbs-api-key-generate'>
                <form action="" method="POST">
                    <p style="    padding: 14px;background: #FFF;"><button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=api&regeneratekeys=1';"><?php _we('Regenerate API Credentials','zerobscrm'); ?></button> </p>
                    <input type='hidden' name='generate-key' id='generate-key' value='1'/>
                </form>
              </div>
               <?php


        } else {

            ?><div id="clpSubPage" class="whclpActionMsg six">
              <p><strong><?php echo $confirmActStr; ?></strong></p>
                <h3><?php echo $confirmActStrShort; ?></h3>
                <?php echo $confirmActStrLong; ?><br /><br />
                <button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&tab=api&'.$actionStr.'=1&imsure=1','regeneratekeys'); ?>';"><?php echo $actionButtonStr; ?></button>
                <button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=api';"><?php _we("Cancel",'zerobscrm'); ?></button>
                <br />
          </div><?php 
        } 


    }

}


function zeroBSCRM_pages_datatools() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Import Tools');
	
		zeroBSCRM_html_datatools();
	
		zeroBSCRM_pages_footer();

?>
</div>
<?php 
} 



function zeroBSCRM_pages_sync_home() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('Sync Tools');
	
		?>
	<h2><?php _we('Keep your Transactions in Sync','zerobscrm');?></h2>

	<p><?php _we('Welcome to the Zero BS Sync Core. Here you can sync your transactions easily'); ?></p> 

	<?php
	
		zeroBSCRM_pages_footer();

?>
</div>
<?php 
} 




function zeroBSCRM_pages_postdelete() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (
		!zeroBSCRM_permsCustomers()
		&& !zeroBSCRM_permsQuotes()
		&& !zeroBSCRM_permsInvoices()
		&& !zeroBSCRM_permsTransactions()
		)  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    
		zeroBSCRM_html_deletion();
	
		
?>
</div>
<?php 
} 



function zeroBSCRM_pages_norights() {
  
  global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;    
  if (
    !zeroBSCRM_permsCustomers()
    && !zeroBSCRM_permsQuotes()
    && !zeroBSCRM_permsInvoices()
    && !zeroBSCRM_permsTransactions()
    )  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
      
    zeroBSCRM_html_norights();
  
    
?>
</div>
<?php 
} 


function zeroBSCRM_pages_systemstatus() {
	
	global $wpdb, $zeroBSCRM_urls, $zeroBSCRM_version;		
	if (!current_user_can('manage_options'))  { wp_die( __w('You do not have sufficient permissions to access this page.','zerobscrm') ); }
    
	    zeroBSCRM_pages_header('System Status');
	
		zeroBSCRM_html_systemstatus();
	
		zeroBSCRM_pages_footer();

?>
</div>
<?php 
} 








function zeroBSCRM_html_home2(){

  
  global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;  
  $showFull = true; 

  global $zeroBSCRM_urls;



  if ($showFull) { 
  ?>
  <div class="wrap">
  <h1 style="font-size: 34px;margin-left: 50px;color: #e06d17;margin-top: 1em;">Welcome to <b>Zero BS CRM</b></h1>
  <p style="font-size: 16px;margin-left: 50px;padding: 12px 20px 10px 20px;">We are happy to have you here. You're now well on your way to getting in control of your business.<br />Please take a couple of minutes to check out 
  some of our best selling extensions.</p>

  <div class="wrap">

  
      <div class='extensions' style='margin-left:2%;width:48%;float:left;'>

            <div id="sbSubPage" style="max-width:600px">

              <h2 class="sbhomep">5 Steps to setting up ZBS</h2>
                <p class="sbhomep sbhomepl">1) Sign up for updates on the right of this page (Don't miss out on freebies + critical updates!)</p>
                <p class="sbhomep sbhomepl">2) Check over <a href="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>">the settings</a></p>
                <p class="sbhomep sbhomepl">3) Set Up your Customer Tags <a href="edit-tags.php?taxonomy=zerobscrm_customertag&post_type=zerobs_customer">here</a>
                  <br />Tag Examples: "Corporate, Small Business, Local"
                  <!--<br />(Two types of tag allow you to set up Work &amp; Customer specific tags (Work Tag Examples: "development, php, js", Customer Tag Examples: "Corporate, Small Business, Local")--></p>
                <!-- <p class="sbhomep sbhomepl">3) Make <a href="users.php">Users</a>!<br />You can create users specific to ZBS, so they will only have access to Customers, Quotes, and/or Invoices.</p>-->
                <p class="sbhomep sbhomepl">4) Add your first customer <a href="<?php echo admin_url('post-new.php?post_type=zerobs_customer'); ?>">here</a>! (<a href="https://www.youtube.com/watch?v=WepIml_wWEM" target="_blank">See 2 min video guide</a>)</p>
                <p class="sbhomep sbhomepl">5) Check out the <a href="<?php echo admin_url('admin.php?page='.$zeroBSCRM_slugs['extensions']); ?>">Extensions Manager</a>!</p>
            
                <p class="sbhomep">
                    <strong>Get Started:</strong><br /><br />
                    
                    <a href="admin.php?page=<?php echo $zeroBSCRM_slugs['settings']; ?>" class="button button-primary button-large"><?php _we('View Settings','zerobscrm'); ?></a> 
                    <a href="<?php echo $zeroBSCRM_urls['docs']; ?>" class="button button-primary button-large"><?php _we('Read Documentation','zerobscrm'); ?></a> 
                    <a href="<?php echo admin_url('admin.php?page='.$zeroBSCRM_slugs['extensions']); ?>" class="button button-primary button-large"><?php _we('Get Extensions','zerobscrm'); ?></a>
                
                    <hr />
                    &nbsp;
                </p>
          </div>

      </div>


      <div class='sign-up' style='width:48%;float:right'>

        <h4 style="color: green;text-align: center;">Leverage your new CRM (Bonuses!) <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></h4>  
       
        <script src="https://assets.convertkit.com/assets/CKJS4.js?v=21"></script>
        <div class="ck_form_container ck_inline" data-ck-version="5">
          
            <div class="ck_form ck_vertical_subscription_form">
              <div class="ck_form_content">
                <div class="ck_description">
                  <span class="ck_image">
                    <img alt="Zerobscrm_get_most" src="<?php echo $zeroBSCRM_urls['extimgrepo'].'zbs-get.png'; ?>" width='230px'/>
                  </span>
              </div>
              </div>

            <div class="ck_form_fields">
              <div id="ck_success_msg" style="display:none;">
                <p>Awesome. Please confirm your sign up by checking your email.</p>
              </div>

              <!--  Form starts here  -->
              <form id="ck_subscribe_form" class="ck_subscribe_form" action="https://app.convertkit.com/landing_pages/64179/subscribe" data-remote="true">
                <input type="hidden" value="{&quot;form_style&quot;:&quot;full&quot;,&quot;embed_style&quot;:&quot;inline&quot;,&quot;embed_trigger&quot;:&quot;scroll_percentage&quot;,&quot;scroll_percentage&quot;:&quot;70&quot;,&quot;delay_seconds&quot;:&quot;10&quot;,&quot;display_position&quot;:&quot;br&quot;,&quot;display_devices&quot;:&quot;all&quot;,&quot;days_no_show&quot;:&quot;15&quot;,&quot;converted_behavior&quot;:&quot;show&quot;}" id="ck_form_options">
                <input type="hidden" name="id" value="64179" id="landing_page_id">
                <div class="ck_errorArea">
                  <div id="ck_error_msg" style="display:none">
                    <p>There was an error submitting your subscription. Please try again.</p>
                  </div>
                </div>
                <div class='new'>
                    <h3>No <b>Bullsh*t</b> here <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></h3>
                      <p style="font-size:14px;">Learn how to make the most of your CRM with our 5-step guide. <em>Includes extra special discounts off our extensions!</em></p>
                </div>
                <div class="ck_control_group ck_first_name_field_group">
                  <label class="ck_label" for="ck_firstNameField">Enter Your Name:</label>
                  <input type="text" name="first_name" class="ck_first_name" id="ck_firstNameField">
                </div>
                <div class="ck_control_group ck_email_field_group">
                  <label class="ck_label" for="ck_emailField">Email Address</label>
                  <input type="email" name="email" class="ck_email_address" id="ck_emailField" required>
                </div>

                  <label class="ck_checkbox" style="display:none;">
                    <input class="optIn ck_course_opted" name="course_opted" type="checkbox" id="optIn" checked />
                    <span class="ck_opt_in_prompt">I'd like to receive the free email course.</span>
                  </label>

                <button class="subscribe_button ck_subscribe_button btn fields" id="ck_subscribe_button">
                  Subscribe
                </button>
                <span class="ck_guarantee">
                  We won&#x27;t send you SPAM. Ever.
                </span>
              </form>
            </div>

          </div>

      </div>

    <!-- following is styles for sign up box -->
    <style type="text/css">#ck_subscribe_form,.ck_form{clear:both}.ck_form{background:url(data:image/gif;base64,R0lGODlhAQADAIABAMzMzP///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS41LWMwMTQgNzkuMTUxNDgxLCAyMDEzLzAzLzEzLTEyOjA5OjE1ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MUQ5NjM5RjgxQUVEMTFFNEJBQTdGNTQwMjc5MTZDOTciIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MUQ5NjM5RjkxQUVEMTFFNEJBQTdGNTQwMjc5MTZDOTciPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxRDk2MzlGNjFBRUQxMUU0QkFBN0Y1NDAyNzkxNkM5NyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxRDk2MzlGNzFBRUQxMUU0QkFBN0Y1NDAyNzkxNkM5NyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACH5BAEAAAEALAAAAAABAAMAAAICRFIAOw==) center top repeat-y #fff;font-family:"Helvetica Neue",Helvetica,Arial,Verdana,sans-serif;line-height:1.5em;overflow:hidden;color:#666;font-size:16px;border-top:solid 20px #000;border-top-color:#000;border-bottom:solid 10px #000;border-bottom-color:#000;-webkit-box-shadow:0 0 5px rgba(0,0,0,.3);-moz-box-shadow:0 0 5px rgba(0,0,0,.3);box-shadow:0 0 5px rgba(0,0,0,.3);margin:20px 0}.extensions img{border:0;width:195px;margin-right:5px;margin-bottom:10px}.ck_form,.ck_form *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}.ck_form_content,.ck_form_fields{width:50%;float:left;padding:5%}.ck_form_content{border-bottom:none}.ck_form.ck_vertical{background:#fff}.ck_vertical .ck_form_content,.ck_vertical .ck_form_fields{padding:10%;width:100%;float:none}.ck_vertical .ck_form_content{border-bottom:1px dotted #aaa;overflow:hidden}@media all and (max-width:499px){.ck_form{background:#fff}.ck_form_content,.ck_form_fields{padding:10%;width:100%;float:none}.ck_form_content{border-bottom:1px dotted #aaa}}.ck_form_content h3{margin:0 0 15px;font-size:24px;padding:0}.new{color:#000}.new h3{padding-top:0;margin-top:3px}.ck_form_content p{font-size:14px;color:#000}.ck_image{float:left;margin-right:5px}.ck_errorArea{display:none}#ck_success_msg{padding:10px 10px 0;border:1px solid #ddd;background:#eee}.ck_label{font-size:14px;font-weight:700}.ck_form input[type=text],.ck_form input[type=email]{font-size:14px;padding:10px 8px;width:100%;border:1px solid #d6d6d6;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;background-color:#f8f7f7;margin-bottom:5px;height:auto}.ck_form input[type=text]:focus,.ck_form input[type=email]:focus{outline:0;border-color:#aaa}.ck_checkbox{padding:10px 0 10px 20px;display:block;clear:both}.ck_checkbox input.optIn{margin-left:-20px;margin-top:0}.ck_form .ck_opt_in_prompt{margin-left:4px}.ck_form .ck_opt_in_prompt p{display:inline}.ck_form .ck_subscribe_button{width:100%;color:#fff;margin:10px 0 0;padding:10px 0;font-size:18px;background:#000;-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;cursor:pointer;border:none;text-shadow:none}.ck_form .ck_guarantee{color:#626262;font-size:12px;text-align:center;padding:5px 0;display:block}.ck_form .ck_powered_by{display:block;color:#aaa}.ck_form .ck_powered_by:hover{display:block;color:#444}.ck_converted_content{display:none;padding:5%;background:#fff}</style>

    <!-- following is wh tweaks 2.0.2 responsive -->
    <style type="text/css">

        @media only screen and (max-width: 1300px)  {
          .ck_form_content { display: none !important;}
        }

    </style>

    </div> <!-- / right div signup --> 

  </div> <!-- / .wrap --> 

  <hr />

  <div style="text-align:center;font-size:14px; clear:both;margin-top:40px;margin-bottom:80px">
    
    <?php zerobscrm_show_love(); ?>

  </div>




  <?php 

    } 


}

function zeroBSCRM_html_datatools(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	?>
            
        <div id="sbSubPage" style="width:600px"><h2 class="sbhomep">Welcome to Zero BS CRM Import Tools</h2>
        	<p class="sbhomep">This is the home for all of the different admin tools for ZBS which import data (Excluding the <strong>Sync</strong> Extensions).</p>
        	<p class="sbhomep">
        		<strong>Free Data Tools:</strong><br />
				<button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['bulktools']; ?>';" class="button button-primary button-large" style="padding: 7px 16px;font-size: 16px;height: 46px;margin-bottom:8px;"><?php _we('Bulk Tools','zerobscrm'); ?></button><br />
			</p>
        	<p class="sbhomep">
        		<strong>Data Tool Extensions Installed:</strong><br /><br />
            	<?php 

            		            		$zbsDataToolsInstalled = 0; global $zeroBSCRM_CSVImporterslugs;
            		if (zeroBSCRM_isExtensionInstalled('csvimporter') && isset($zeroBSCRM_CSVImporterslugs)){

            			?><button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_CSVImporterslugs['app']; ?>';" class="button button-primary button-large" style="padding: 7px 16px;font-size: 16px;height: 46px;margin-bottom:8px;"><?php _we('CSV Importer','zerobscrm'); ?></button><br /><?php
            			$zbsDataToolsInstalled++;

            		}

            		if ($zbsDataToolsInstalled == 0){

            			?>You do not have any Pro ZBS Data Tools installed as of yet! <a href="<?php echo $zeroBSCRM_urls['productsdatatools']; ?>" target="_blank">Get some now</a><?php

            		}

            	?>            	
            </p><p class="sbhomep">
            	<!-- #datatoolsales -->
        		<strong>Import Tools:</strong><br /><br />            	
            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo $zeroBSCRM_urls['productsdatatools']; ?>';"><?php _we('View Available ZBS Import Tools','zerobscrm'); ?></button>            	
            </p>
            <div class="sbhomep">
              <strong>Export Tools:</strong><br/>
              <p><?php _we('Alternative you can run a customer search and then export all data related to your customer search results','zerobscrm'); ?>
              <?php echo zeroBSCRM_pages_export(); ?>
            </div>
		</div><?php 

}

function zeroBSCRM_html_feedback(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	?>
            
        <div id="sbSubPage" style="width:600px"><h2 class="sbhomep">Feedback Makes ZBS Better</h2>
        	<p class="sbhomep">We love to hear what you think of Zero BS CRM, it helps us make the CRM even better, even if you're hitting a wall with something, (it's useful so long as it's constructive critisism!)</p>
        	<p class="sbhomep">If you have a feature you'd like to see, a bug you may have found, or you'd like to suggest an idea for an extension, let us know below:</p>
        	<p class="sbhomep">
        		<a href="<?php echo $zeroBSCRM_urls['feedback']; ?>" class="button button-primary button-large"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Feedback</a>   	
            </p>
            <p class="sbhomep">What not to send through here:
	        	<ul class="sbhomep">
	        		<li>Documentation requests (<a href="<?php echo $zeroBSCRM_urls['docs']; ?>">Click here</a> for that)</li>
	        		<li>Support requests (<a href="<?php echo $zeroBSCRM_urls['support']; ?>">Click here</a> for that)</li>
	        	</ul>
	       	</p>
		</div><?php 

}

function zeroBSCRM_html_extensions_forWelcomeWizard(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	


	?>
            
        <div id="sbSubPage" style="width:100%;max-width:1000px"><h2 class="sbhomep">Power Up your CRM</h2>
        	<p class="sbhomep">We hope that you love using ZBS and that you agree with our mentality of stripping out useless features and keeping things simple. Cool.</p>
        	<p class="sbhomep">We offer a few extensions which supercharge your ZBS. As is our principle, though, you wont find any bloated products here. These are simple, effective power ups for your ZBS. And compared to pay-monthly costs, they're affordable! Win!</p>
        	<div style="width:100%"><a href="<?php echo $zeroBSCRM_urls['products']; ?>" target="_blank"><img style="width:100%;max-width:100%;margin-left:auto;margin-right:auto;" src="<?php echo $zeroBSCRM_urls['extimgrepo'].'extensions.png'; ?>" alt="" /></a></div>
            <p class="sbhomep">
	        	<a href="<?php echo $zeroBSCRM_urls['products']; ?>" class="button button-primary button-large" style="padding: 7px 16px;font-size: 22px;height: 46px;" target="_blank">View More</a>   	
	       	</p>
		</div><?php 
	

}

function zeroBSCRM_extensions_init_install(){

        if (isset($_GET['zbsinstall']) && !empty($_GET['zbsinstall'])){

            global $zeroBSExtensionMsgs;


            global $zeroBSCRM_extensionsCompleteList;
      if (
                wp_verify_nonce( $_GET['_wpnonce'], 'zbscrminstallnonce' )
        &&
                array_key_exists($_GET['zbsinstall'], $zeroBSCRM_extensionsCompleteList)){

                $toActOn = $_GET['zbsinstall'];
        $installingExt = zeroBSCRM_returnExtensionDetails($toActOn);
        $installName = 'Unknown'; if (isset($installingExt['name'])) $installName = $installingExt['name'];

                $act = 'install'; if (zeroBSCRM_isExtensionInstalled($toActOn)) $act = 'uninstall';
        $successfullyInstalled = false;

                try {

          if ($act == 'install'){

            
                        if (function_exists('zeroBSCRM_extension_install_'.$toActOn)){

                            $successfullyInstalled = call_user_func('zeroBSCRM_extension_install_'.$toActOn);

            }

          } else {

            
                        if (function_exists('zeroBSCRM_extension_uninstall_'.$toActOn)){

                            $successfullyInstalled = call_user_func('zeroBSCRM_extension_uninstall_'.$toActOn);

            }

          }

        } catch (Exception $ex){

          
        }

                $zeroBSExtensionMsgs = array($successfullyInstalled,$installName,$act);


      }
    }
}

function zeroBSCRM_html_extensions(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	

	


    global $zeroBSExtensionMsgs;
    

        zeroBSCRM_extension_checkinstall_pdfinv();

				zeroBSCRM_freeExtensionsInit();

				$extensionList = zeroBSCRM_extensionsListSegmented();



	?>
            
        <div id="zbsExtensionsPage">

        	<h2 class="sbhomep" style="font-size:28px;padding: 18px 0;"><i class="fa fa-plus-circle" aria-hidden="true"></i> Power Up your CRM</h2>
        	<div class="sbhomep">
            <p style="font-size:15px;">Welcome to the <strong>ZBS CRM Extension Hub</strong>. From this page you can quickly and easily install extra extensions that will supercharge your CRM.</p>
            <p style="text-align:Center;margin-top:2em;margin-bottom:2em">
              <a href="<?php echo $zeroBSCRM_urls['products']; ?>?utm_source=inplugin&utm_medium=exthub&utm_content=top" class="button button-large" style="font-size: 26px;padding: 12px;line-height: 1em;height: auto;background:orange;color:#000"><i class="fa fa-shopping-cart" aria-hidden="true" style="color:#FFF"></i> Shop Extensions</a>
            </p>
          </div>


        	<?php

        	        	if (isset($zeroBSExtensionMsgs)){

        		if ($zeroBSExtensionMsgs[0]){

        			$msgHTML = '<i class="fa fa-check" aria-hidden="true"></i> Successfully '.$zeroBSExtensionMsgs[2].'ed extension: '.$zeroBSExtensionMsgs[1];

        			        			if ($zeroBSExtensionMsgs[2] == 'install' && isset($installingExt) && isset($installingExt['meta']) && isset($installingExt['meta']['helpurl']) && !empty($installingExt['meta']['helpurl'])){

        				$msgHTML .= '<br /><i class="fa fa-info-circle" aria-hidden="true"></i> <a href="'.$installingExt['meta']['helpurl'].'" target="_blank">View '.$zeroBSExtensionMsgs[1].' Help Documentation</a>';

        			}
        				
        			echo zeroBSCRM_html_msg(0,$msgHTML);

        		} else {

        			global $zbsExtensionInstallError, $zeroBSCRM_urls;

        			$errmsg = 'Unable to install extension: '.$zeroBSExtensionMsgs[1].', please contact <a href="'.$zeroBSCRM_urls['support'].'" target="_blank">Support</a> if this persists.';

        			if (isset($zbsExtensionInstallError)) $errmsg .= '<br />Installer Error: '.$zbsExtensionInstallError;


        			echo zeroBSCRM_html_msg(-1,$errmsg);

        		}

        	}

        	?>

        	<div class="zbsExtensionGroup">
        		<h2>Free extensions</h2>
        		<p>We've made a few extensions that are totally free, and can be installed with one click below. These are created as extras to the main core because not everyone wants the specific functionality.</p>
        		<div class="zbsExtensionList">
        			<?php if (count($extensionList['free']) > 0) foreach ($extensionList['free'] as $extKey => $ext){ 

                                $shortName = $ext['name']; if (isset($ext['meta']) && isset($ext['meta']['shortname'])) $shortName = $ext['meta']['shortname'];

                                $proVerInstalled = false; if (isset($ext['meta']) && isset($ext['meta']['prover']) && zeroBSCRM_isExtensionInstalled($ext['meta']['prover'])) $proVerInstalled = true;

              ?>
        			<div class="zbsExtension zbsFreeExtension">
        				<div class="zbsExtensionImg"<?php if (isset($ext['meta']['colour'])) echo ' style="background:'.$ext['meta']['colour'].' !important;"'; ?>>
        					<?php echo $ext['meta']['imgstr']; ?>
        				</div>
        				<h3><?php echo $ext['name']; ?></h3>
        				<p><?php echo $ext['meta']['desc']; ?></p>
        				<div class="zbsExtensionButton"><?php
                if ($proVerInstalled){
                  ?><label style="color:green"><?php _e('PRO version installed.','zerobscrm'); ?></label><?php 
                } else { ?>
                <button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('admin.php?page=zerobscrm-extensions&zbsinstall='.$extKey,'zbscrminstallnonce'); ?>';"><?php if ($ext['installed']) echo 'Deactivate'; else echo 'Install'; ?> <?php echo $shortName; ?></button></div>
                <?php } ?>
        			</div>
        			<?php } ?>
        		</div>
        		<div style="clear:both"></div>
        	</div>
        	<div class="zbsExtensionGroup">
        		<h2>Paid Extensions</h2>
				<p>Want to get the absolute most out of your ZBS CRM? These paid extensions are <em>Next Level</em>:</p>
        		<div class="zbsExtensionList">
        			<?php if (count($extensionList['paid']) > 0) foreach ($extensionList['paid'] as $extKey => $ext){ 

                                $shortName = $ext['name']; if (isset($ext['meta']) && isset($ext['meta']['shortname'])) $shortName = $ext['meta']['shortname'];

                ?>
        			<div class="zbsExtension">
        				<div class="zbsExtensionImg"<?php if (isset($ext['meta']['colour'])) echo ' style="background:'.$ext['meta']['colour'].' !important;"'; ?>>
        					<?php echo $ext['meta']['imgstr']; ?>
        				</div>
        				<h3><?php echo $ext['name']; ?></h3>
        				<p><?php echo $ext['meta']['desc']; ?></p>
        				<div class="zbsExtensionButton"><?php
                                if ($ext['installed']){ if (isset($ext['meta']['helpurl']) && !empty($ext['meta']['helpurl'])) { ?>
                  <strong>Installed</strong> <a target="_blank" href="<?php echo $ext['meta']['helpurl']; ?>" class="button button-primary button-large">View Docs</a>
                <?php } } else { ?>
                  <a target="_blank" href="<?php echo $ext['meta']['url']; ?>?utm_source=inplugin&utm_medium=exthub&utm_content=specificext" class="button button-primary button-large">Purchase <?php echo $shortName; ?></a>
                <?php } ?>
                </div>
        			</div>
        			<?php } ?>
        		</div>
        		<div style="clear:both"></div>
        	</div>
        	
        	
		</div>

	<?php 


}

function zeroBSCRM_html_bulktools(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
		global $zbscrmApprovedExternalSources;

		$showNormalpage = true;

		if (isset($_GET['zbsact'])){

				if (in_array($_GET['zbsact'],array('removeext'))){

			
						if ($_GET['zbsact'] == 'removeext'){

								$source = '';
				if (isset($_GET['removeext']) && !empty($_GET['removeext']) && isset($zbscrmApprovedExternalSources[$_GET['removeext']])){

										if (!isset($_GET['imsure'])){

												$showNormalpage = false;


												$countRemove = zeroBS_getExternalSourceCustomerCount($_GET['removeext']);

						if ($countRemove > 0){

			   				?><div id="clpSubPage" class="whclpActionMsg six">
			        		<p><strong>Delete all <?php echo $zbscrmApprovedExternalSources[$_GET['removeext']][0]; ?> Customers?</strong></p>
			            	<h3>Are you sure you want to remove all <strong><?php echo zeroBSCRM_prettifyLongInts($countRemove); ?></strong> of these customers?</h3>
			            	Once you do so, you cannot retrieve these customers, they're gone for good!<br /><br />
			            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['bulktools'].'&zbsact=removeext&removeext='.sanitize_text_field($_GET['removeext']).'&imsure=1','megadeletezerobscrm'); ?>';">Proceed, Delete the Customers</button>
			            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['bulktools']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
			            	<br />
							</div><?php 

						} else {

							
			   				?><div id="clpSubPage" class="whclpActionMsg six">
			        		<p><strong>Delete all <?php echo $zbscrmApprovedExternalSources[$_GET['removeext']][0]; ?> Customers?</strong></p>
			            	<h3>There are 0 customers here to delete!</h3><br /><br />
			            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['bulktools']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
			            	<br />
							</div><?php 

						}


					} else {
						
											if (wp_verify_nonce( $_GET['_wpnonce'], 'megadeletezerobscrm' ) ){

												$showNormalpage = false;

						
			   				?><div id="clpSubPage" class="whclpActionMsg six">
			        		<p><strong>Deleting all <?php echo $zbscrmApprovedExternalSources[$_GET['removeext']][0]; ?> Customers...</strong></p>
			            	<h3>Please do not close this tab until this process has finished!</h3>
							<?php 

																$toRemove = zeroBS_getExternalSourceCustomers($_GET['removeext']);
								$removedCount = 0;

						        foreach ($toRemove as $deleteCustomer){

						            						            wp_delete_post($deleteCustomer['id'],true);

						            echo 'Removed #'.$deleteCustomer['id'].' ('.$deleteCustomer['name'].')<br />';

						            $removedCount++;

						        }

						        echo '<hr /><h3 style="margin-bottom:30px;"><i class="fa fa-check" aria-hidden="true" style="color:green"></i> Successfully deleted '.zeroBSCRM_prettifyLongInts($removedCount).' customers.</h3>';



							?>
			            	<hr />
			            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['bulktools']; ?>';"><?php _we("Back to Bulk Tools",'zerobscrm'); ?></button>
			            	<br />
							</div><?php 

						} 

					}


				}



			}


		}

	} 

	if ($showNormalpage) {

		
		?>
            
        <div id="sbSubPage" style="width:100%;max-width:1000px"><h2 class="sbhomep">Admin-only bulk customer removal and other bulk-action tools.</h2>
        	<p class="sbhomep">Please only use the tools listed on this page if you are 100% aware of what you're doing. Some of the tools here can remove all of your customers, and we can't get them back for you!</p>
        	<hr />
        	<p class="sbhomep"><strong style="font-size:16px;margin-bottom:10px;">Bulk Customer Deletion:</strong><br /><?php       	

										if (count($zbscrmApprovedExternalSources)) foreach ($zbscrmApprovedExternalSources as $srcKey => $srcDeet){

					?><a href="?page=<?php echo $zeroBSCRM_slugs['bulktools']; ?>&zbsact=removeext&removeext=<?php echo $srcKey; ?>" class="button button-primary button-large" style="padding: 7px 16px;font-size: 16px;height: 46px;margin-bottom:8px;">Remove all <?php echo $srcDeet[0]; ?> Customers <i class="fa <?php echo $srcDeet['ico']; ?>"></i></a><br /><?php				}

				?></p>
			<hr />
        	<p class="sbhomep">
        		Want a tool that's not listed here?  <a href="?page=<?php echo $zeroBSCRM_slugs['feedback']; ?>" target="_blank">Let us know</a>.
        	</p>  
		</div><?php 

	}

}


function zeroBSCRM_html_deletion(){
	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	

		$deltype = '?'; 	$delstr = '?'; 	$delID = -1;
	$isRestore = false;
	$backToPage = 'edit.php?post_type=zerobs_customer&page=manage-customers';

		if (isset($_GET['restoreplz']) && $_GET['restoreplz'] == 'kthx') $isRestore = true;

				if (isset($_GET['cid']) && !empty($_GET['cid'])){

			$delID = (int)$_GET['cid'];
			$delIDVar = 'cid';
			$backToPage = 'edit.php?post_type=zerobs_customer&page=manage-customers';

						$delType = zeroBSCRM_getContactOrCustomer(); 			$delStr = zeroBS_getCustomerName($delID);

		} else if (isset($_GET['qid']) && !empty($_GET['qid'])){

						$delID = (int)$_GET['qid'];
			$delIDVar = 'qid';
			$backToPage = 'edit.php?post_type=zerobs_quote&page=manage-quotes';

						$delType = 'Quote';
			$delStr = 'Quote ID: '.$delID; 
		} else if (isset($_GET['iid']) && !empty($_GET['iid'])){

						$delID = (int)$_GET['iid'];
			$delIDVar = 'iid';
			$backToPage = 'admin.php?page=manage-invoices';

						$delType = 'Invoice';
			$delStr = 'Invoice ID: '.$delID; 
		} else if (isset($_GET['tid']) && !empty($_GET['tid'])){

						$delID = (int)$_GET['tid'];
			$delIDVar = 'tid';
			$backToPage = 'edit.php?post_type=zerobs_transaction&page=manage-transactions';

						$delType = 'Transaction';
			$delStr = 'Transaction ID: '.$delID;

		}

				if ($isRestore && !empty($delID)){

			wp_untrash_post($delID);

		}


	?>
    <div id="zbsDeletionPage">
    	<div id="zbsDeletionMsgWrap">
    		<div id="zbsDeletionIco"><i class="fa <?php if ($isRestore){ ?>fa-undo<?php } else { ?>fa-trash<?php } ?>" aria-hidden="true"></i></div>
    		<div class="zbsDeletionMsg"><?php echo $delStr; ?> successfully 
    			<?php if ($isRestore){ ?>
    			retrieved from Trash
    			<?php } else { ?>
    			moved to Trash
    			<?php } ?>
    		</div>
    		<div class="zbsDeletionAction">
    			<?php if ($isRestore){ ?>
    			<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo esc_url( $backToPage); ?>'">Back to <?php echo $delType; ?>s</button>
    			<?php } else { ?>
    			<button type="button" class="button button-large" onclick="javascript:window.location='edit.php?post_type=zerobs_customer&page=zbs-deletion&<?php echo $delIDVar; ?>=<?php echo $delID; ?>&restoreplz=kthx'">Undo (Restore <?php echo $delType; ?>)</button>
    			&nbsp;&nbsp;
    			<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo esc_url( $backToPage ); ?>'">Back to <?php echo $delType; ?>s</button>
    			<?php } ?>
    		</div>
    	</div>
    </div>        
    <?php 

}


function zeroBSCRM_html_norights(){
  
  global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;  

    $noaccessType = '?';   $noaccessstr = '?';   $noaccessID = -1;
  $isRestore = false;
  $backToPage = 'edit.php?post_type=zerobs_customer&page=manage-customers';

    if (isset($_GET['post_type']) && !empty($_GET['post_type']))
    $noAccessType = $_GET['post_type'];
  else {

    if (isset( $_GET['id'] )) $noAccessType = get_post_type( $_GET['id'] );

  }

  switch ($noAccessType){

      case 'zerobs_customer':
        
          $backToPage = 'edit.php?post_type=zerobs_customer&page=manage-customers';
          $noAccessTypeStr = 'Customer';

          break;

      case 'zerobs_company':
        
          $backToPage = 'edit.php?post_type=zerobs_company&page=manage-companies';
          $noAccessTypeStr = 'Company';

          break;

      default:
        
                    $backToPage = 'admin.php?page=zerobscrm-dash';
          $noAccessTypeStr = 'Resource';

          break;

  }




  ?>
    <div id="zbsNoAccessPage">
      <div id="zbsNoAccessMsgWrap">
        <div id="zbsNoAccessIco"><i class="fa fa-archive" aria-hidden="true"></i></div>
        <div class="zbsNoAccessMsg">
          <h2><?php echo _we('Access Restricted'); ?></h2>
          <p><?php echo _we('You do not have access to this '.$noAccessTypeStr.'.'); ?></p>
        </div>
        <div class="zbsNoAccessAction">
          <button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo esc_url( $backToPage ); ?>'">Back</button>

        </div>
      </div>
    </div>        
    <?php 
 

}



function zeroBSCRM_html_settings(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	$confirmAct = false;
	$settings = $zeroBSCRM_Settings->getAll();		

	global $zeroBSCRM_Mimes;

	

		$autoLoggers = array(
	            			array('fieldname' => 'autolog_customer_new', 'title'=> 'Customer Creation'),
	            			array('fieldname' => 'autolog_company_new', 'title'=> 'Company Creation'),
	            			array('fieldname' => 'autolog_quote_new', 'title'=> 'Quote Creation'),
	            			array('fieldname' => 'autolog_invoice_new', 'title'=> 'Invoice Creation'),
                    array('fieldname' => 'autolog_transaction_new', 'title'=> 'Transaction Creation')
	            		);

		

				global $whwpCurrencyList;
		if(!isset($whwpCurrencyList)) require_once(ZEROBSCRM_PATH . 'includes/wh.currency.lib.php');
		

		if (isset($_POST['editwplf'])){

				$updatedSettings = array();
		$updatedSettings['wptakeovermode'] = 0; if (isset($_POST['wpzbscrm_wptakeovermode']) && !empty($_POST['wpzbscrm_wptakeovermode'])) $updatedSettings['wptakeovermode'] = 1;
		$updatedSettings['wptakeovermodeforall'] = 0; if (isset($_POST['wpzbscrm_wptakeovermodeforall']) && !empty($_POST['wpzbscrm_wptakeovermodeforall'])) $updatedSettings['wptakeovermodeforall'] = 1;
		$updatedSettings['customheadertext'] = ''; if (isset($_POST['wpzbscrm_customheadertext']) && !empty($_POST['wpzbscrm_customheadertext'])) $updatedSettings['customheadertext'] = sanitize_text_field($_POST['wpzbscrm_customheadertext']);
		$updatedSettings['loginlogourl'] = ''; if (isset($_POST['wpzbscrm_loginlogourl']) && !empty($_POST['wpzbscrm_loginlogourl'])) $updatedSettings['loginlogourl'] = sanitize_text_field($_POST['wpzbscrm_loginlogourl']);
		$updatedSettings['showneedsquote'] = 0; if (isset($_POST['wpzbscrm_showneedsquote']) && !empty($_POST['wpzbscrm_showneedsquote'])) $updatedSettings['showneedsquote'] = 1;
		$updatedSettings['killfrontend'] = 0; if (isset($_POST['wpzbscrm_killfrontend']) && !empty($_POST['wpzbscrm_killfrontend'])) $updatedSettings['killfrontend'] = 1;

				$updatedSettings['quoteoffset'] = 0; if (isset($_POST['wpzbscrm_quoteoffset']) && !empty($_POST['wpzbscrm_quoteoffset'])) $updatedSettings['quoteoffset'] = (int)sanitize_text_field($_POST['wpzbscrm_quoteoffset']);
		$updatedSettings['invoffset'] = 0; if (isset($_POST['wpzbscrm_invoffset']) && !empty($_POST['wpzbscrm_invoffset'])) $updatedSettings['invoffset'] = (int)sanitize_text_field($_POST['wpzbscrm_invoffset']);
		$updatedSettings['invallowoverride'] = 0; if (isset($_POST['wpzbscrm_invallowoverride']) && !empty($_POST['wpzbscrm_invallowoverride'])) $updatedSettings['invallowoverride'] = 1;
		$updatedSettings['showaddress'] = 0; if (isset($_POST['wpzbscrm_showaddress']) && !empty($_POST['wpzbscrm_showaddress'])) $updatedSettings['showaddress'] = 1;
		$updatedSettings['secondaddress'] = 0; if (isset($_POST['wpzbscrm_secondaddress']) && !empty($_POST['wpzbscrm_secondaddress'])) $updatedSettings['secondaddress'] = 1;
		
				$updatedSettings['companylevelcustomers'] = 0; if (isset($_POST['wpzbscrm_companylevelcustomers']) && !empty($_POST['wpzbscrm_companylevelcustomers'])) $updatedSettings['companylevelcustomers'] = 1;
		$updatedSettings['coororg'] = 'co'; if (isset($_POST['wpzbscrm_coororg']) && !empty($_POST['wpzbscrm_coororg']) && $_POST['wpzbscrm_coororg'] == 'org') $updatedSettings['coororg'] = 'org';

				$updatedSettings['showthanksfooter'] = 0; if (isset($_POST['wpzbscrm_showthanksfooter']) && !empty($_POST['wpzbscrm_showthanksfooter'])) $updatedSettings['showthanksfooter'] = 1;

				foreach ($autoLoggers as $autoLog) {
			$updatedSettings[$autoLog['fieldname']] = 0; if (isset($_POST['wpzbscrm_'.$autoLog['fieldname']]) && !empty($_POST['wpzbscrm_'.$autoLog['fieldname']])) $updatedSettings[$autoLog['fieldname']] = 1;
		}

				$updatedSettings['usegcaptcha'] = 0; if (isset($_POST['wpzbscrm_usegcaptcha']) && !empty($_POST['wpzbscrm_usegcaptcha'])) $updatedSettings['usegcaptcha'] = 1;
		$updatedSettings['gcaptchasitekey'] = 0; if (isset($_POST['wpzbscrm_gcaptchasitekey']) && !empty($_POST['wpzbscrm_gcaptchasitekey'])) $updatedSettings['gcaptchasitekey'] = sanitize_text_field($_POST['wpzbscrm_gcaptchasitekey']);
		$updatedSettings['gcaptchasitesecret'] = 0; if (isset($_POST['wpzbscrm_gcaptchasitesecret']) && !empty($_POST['wpzbscrm_gcaptchasitesecret'])) $updatedSettings['gcaptchasitesecret'] = sanitize_text_field($_POST['wpzbscrm_gcaptchasitesecret']);

						
				$updatedSettings['countries'] = 0; if (isset($_POST['wpzbscrm_countries']) && !empty($_POST['wpzbscrm_countries'])) $updatedSettings['countries'] = 1;


        $updatedSettings['menulayout'] = 1; if(isset($_POST['wpzbscrm_menulayout']) && !empty($_POST['wpzbscrm_menulayout'])) $updatedSettings['menulayout'] = (int)$_POST['wpzbscrm_menulayout'];

		$fileTypesUpload = $settings['filetypesupload'];
				
			foreach ($zeroBSCRM_Mimes as $filetype => $mimedeet){
				$fileTypesUpload[$filetype] = 0; if (isset($_POST['wpzbscrm_ft_'.$filetype]) && !empty($_POST['wpzbscrm_ft_'.$filetype])) $fileTypesUpload[$filetype] = 1;
			}

			$fileTypesUpload['all'] = 0; if (isset($_POST['wpzbscrm_ft_all']) && !empty($_POST['wpzbscrm_ft_all'])) $fileTypesUpload['all'] = 1;

		$updatedSettings['filetypesupload'] = $fileTypesUpload;
		
        $updatedSettings['perusercustomers'] = 0; if (isset($_POST['wpzbscrm_perusercustomers']) && !empty($_POST['wpzbscrm_perusercustomers'])) $updatedSettings['perusercustomers'] = 1;
    $updatedSettings['usercangiveownership'] = 0; if (isset($_POST['wpzbscrm_usercangiveownership']) && !empty($_POST['wpzbscrm_usercangiveownership'])) $updatedSettings['usercangiveownership'] = 1;


				$updatedSettings['currency'] = array('chr'	=> '$','strval' => 'USD'); 
		if (isset($_POST['wpzbscrm_currency'])) 
			foreach ($whwpCurrencyList as $currencyObj) 
				if ($currencyObj[1] == $_POST['wpzbscrm_currency']) {
					$updatedSettings['currency']['chr'] = $currencyObj[0];
					$updatedSettings['currency']['strval'] = $currencyObj[1];
					break;
				}

				
				$updatedSettings['css_override'] = ''; if (isset($_POST['wpzbscrm_css_override'])) $updatedSettings['css_override'] = zeroBSCRM_textProcess($_POST['wpzbscrm_css_override']);

				foreach ($updatedSettings as $k => $v) $zeroBSCRM_Settings->update($k,$v);

				$sbupdated = true;

				$settings = $zeroBSCRM_Settings->getAll();
			
	}

		if (isset($_GET['resetsettings'])) if ($_GET['resetsettings']==1){


		if (!isset($_GET['imsure'])){

								$confirmAct = true;
				$actionStr 				= 'resetsettings';
				$actionButtonStr 		= __w('Reset Settings to Defaults?','zerobscrm');
				$confirmActStr 			= __w('Reset All Zero BS CRM Settings?','zerobscrm');
				$confirmActStrShort 	= __w('Are you sure you want to reset these settings to the defaults?','zerobscrm');
				$confirmActStrLong 		= __w('Once you reset these settings you cannot retrieve your previous settings.','zerobscrm');

			} else {


				if (wp_verify_nonce( $_GET['_wpnonce'], 'resetclearzerobscrm' ) ){

												$zeroBSCRM_Settings->resetToDefaults();

												$settings = $zeroBSCRM_Settings->getAll();

												$sbreset = true;

				}

			}

	} 

		if (isset($_GET['rebuildcustomertitles'])) if ($_GET['rebuildcustomertitles']==1){


		if (!isset($_GET['imsure'])){

								$confirmAct = true;
				$actionStr 				= 'rebuildcustomertitles';
				$actionButtonStr 		= __w('Rebuild Customer Titles?','zerobscrm');
				$confirmActStr 			= __w('Rebuild All Zero BS CRM Customer Titles?','zerobscrm');
				$confirmActStrShort 	= __w('Are you sure you want to rebuild these titles?','zerobscrm');
				$confirmActStrLong 		= __w('This routine will try and rebuild all titles at once, if you have many thousands this might be a problem.','zerobscrm');

			} else {


				if (wp_verify_nonce( $_GET['_wpnonce'], 'resetclearzerobscrm' ) ){

										$rebuildCustomerNames = true;

				}

			}

	} 




	if (!$confirmAct && !isset($rebuildCustomerNames)){

	?>
    
        <p id="sbDesc"><?php _we('From this page you can choose global settings for Zero BS CRM, and using the tabs above you can setup different','zerobscrm'); ?> <a href="<?php echo $zeroBSCRM_urls['products']; ?>" target="_blank">Extensions</a></p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Updated','zerobscrm')); echo '</div>'; } ?>
        <?php if (isset($sbreset)) if ($sbreset) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Reset','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
        	<pre><?php ?></pre>

        		<form method="post" action="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>">
        			<input type="hidden" name="editwplf" id="editwplf" value="1" />


        			<?php  ?>



        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('General Settings','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<?php 

	                    		                    							            $tbpCurrency = $settings['currency'];
					            $tbpCurrencyChar = '&pound;'; $tbpCurrencyStr = 'GBP';
					            if (isset($tbpCurrency) && isset($tbpCurrency['chr'])) {
					                
					                $tbpCurrencyChar = $tbpCurrency['chr']; 
					                $tbpCurrencyStr = $tbpCurrency['strval'];

					            }

				            ?>
	                    	<tr>
	                    		<td class="wfieldname"><?php _we('Currency','zerobscrm'); ?>:</td>
	                    		<td><select class="winput short" name="wpzbscrm_currency" id="wpzbscrm_currency">
	                    			<!-- common currencies first -->
	                    			<option value="USD">$ (USD)</option>
	                    			<option value="GBP">&pound; (GBP)</option>
	                    			<option disabled="disabled">----</option>
	                    			<?php foreach ($whwpCurrencyList as $currencyObj){
	                    				?><option value="<?php echo $currencyObj[1]; ?>"<?php if (isset($settings['currency']) && isset($settings['currency']['strval']) && $settings['currency']['strval'] == $currencyObj[1]) echo ' selected="selected"'; ?>><?php echo $currencyObj[0].' ('.$currencyObj[1].')'; ?></option>
	                    			<?php } ?>
	                    		</select></td>
	                    	</tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_showaddress"><?php _we('Show Customer Address Fields','zerobscrm'); ?>:</label><br /><?php _we('Untick to hide the address fields (useful for online business)','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_showaddress" id="wpzbscrm_showaddress" value="1"<?php if (isset($settings['showaddress']) && $settings['showaddress'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_secondaddress"><?php _we('Second Address Fields','zerobscrm'); ?>:</label><br /><?php _we('Allow editing of a "second address" against a customer','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_secondaddress" id="wpzbscrm_secondaddress" value="1"<?php if (isset($settings['secondaddress']) && $settings['secondaddress'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_countries"><?php _we('Use "Countries" in Address Fields','zerobscrm'); ?>:</label><br /><?php _we('Untick to hide country from address fields (useful for local business)','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_countries" id="wpzbscrm_countries" value="1"<?php if (isset($settings['countries']) && $settings['countries'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_companylevelcustomers"><?php _we('B2B Mode','zerobscrm'); ?>:</label><br /><?php _we('Adds a "company or organisation" level to customers (allowing you to store contacts at a company)','zerobscrm'); ?></td>
                          <td><input type="checkbox" class="winput form-control" name="wpzbscrm_companylevelcustomers" id="wpzbscrm_companylevelcustomers" value="1"<?php if (isset($settings['companylevelcustomers']) && $settings['companylevelcustomers'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>



	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_coororg"><?php _we('Company Label','zerobscrm'); ?>:</label><br /><?php _we('Should ZBS use the label "Company" or "Organisation" for your B2B setup?','zerobscrm'); ?></td>
	                    		<td><select class="winput short" name="wpzbscrm_coororg" id="wpzbscrm_coororg">
	                    			<option value="co"<?php if (isset($settings['coororg']) && $settings['coororg'] == 'co') echo ' selected="selected"'; ?>>Company</option>
	                    			<option value="org"<?php if (isset($settings['coororg']) && $settings['coororg'] == 'org') echo ' selected="selected"'; ?>>Organisation</option>
	                    		</select>

	                    		</td>
	                    	</tr>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_perusercustomers"><?php _we('Customer Ownership','zerobscrm'); ?>:</label><br /><?php _we('*NEW for v2.2* If ticked, each user "owns" the customer record, and only sees customers which they are thus assigned. (Note: also applies to companies if using B2B mode.)','zerobscrm'); ?></td>
                          <td><input type="checkbox" class="winput form-control" name="wpzbscrm_perusercustomers" id="wpzbscrm_perusercustomers" value="1"<?php if (isset($settings['perusercustomers']) && $settings['perusercustomers'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>
                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_usercangiveownership"><?php _we('Assign Ownership','zerobscrm'); ?>:</label><br /><?php _we('*NEW for v2.2* Allow users to assign customers they "own" to another user','zerobscrm'); ?></td>
                          <td><input type="checkbox" class="winput form-control" name="wpzbscrm_usercangiveownership" id="wpzbscrm_usercangiveownership" value="1"<?php if (isset($settings['usercangiveownership']) && $settings['usercangiveownership'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_showneedsquote"><?php _we('Show \'Needs a Quote\' section','zerobscrm'); ?>:</label><br /><?php _we('Adds a page to Customers menu to show customers added which do not have quotes attached, (and are not marked \'Refused\' or \'Blacklisted\')','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_showneedsquote" id="wpzbscrm_showneedsquote" value="1"<?php if (isset($settings['showneedsquote']) && $settings['showneedsquote'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_quoteoffset"><?php _we('Quote Number Offset','zerobscrm'); ?>:</label><br /><?php _we('Setting this value will offset your quote numbers. E.g. adding 1000 here will make a quote number 1000+it\'s actual number','zerobscrm'); ?></td>
	                    		<td><input type="text" class="winput form-control" name="wpzbscrm_quoteoffset" id="wpzbscrm_quoteoffset" value="<?php if (isset($settings['quoteoffset']) && !empty($settings['quoteoffset'])) echo (int)$settings['quoteoffset']; ?>" placeholder="e.g. 1000" /><br />(Currently, Next Quote Number to be issued: <?php echo zeroBSCRM_getNextQuoteID(); ?>)</td>
	                    	</tr>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_invoffset"><?php _we('Invoice Number Offset','zerobscrm'); ?>:</label><br /><?php _we('Setting this value will offset your invoice numbers. E.g. adding 1000 here will make a invoice number 1000+it\'s actual number','zerobscrm'); ?></td>
	                    		<td><input type="text" class="winput form-control" name="wpzbscrm_invoffset" id="wpzbscrm_invoffset" value="<?php if (isset($settings['invoffset']) && !empty($settings['invoffset'])) echo (int)$settings['invoffset']; ?>" placeholder="e.g. 1000" /><br />(Currently, Next Invoice Number to be issued: <?php echo zeroBSCRM_getNextInvoiceID(); ?>)</td>
	                    	</tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_invallowoverride"><?php _we('Allow Override of Invoice Numbers','zerobscrm'); ?>:</label><br /><?php _we('Allows the editing of Invoice Numbers','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_invallowoverride" id="wpzbscrm_invallowoverride" value="1"<?php if (isset($settings['invallowoverride']) && $settings['invallowoverride'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>



	                    </tbody>

	                </table>
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('WordPress Menu Layout','zerobscrm'); ?>:</th>
                          </tr>

                      </thead>
                      
                      <tbody>
                      
                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_menulayout"><?php _we('Menu Layout','zerobscrm'); ?>:</label><br /><?php _we('How do you want your WordPress Admin Menu to Display?','zerobscrm'); ?></td>
                          <td style="width:540px">
                          <select class="winput" name="wpzbscrm_menulayout" id="wpzbscrm_menylayout">
                            <!-- common currencies first -->
                            <option value="1" <?php if (isset($settings['menulayout']) && $settings['menulayout'] == '1') echo ' selected="selected"'; ?>><?php _we('Full','zerobscrm');?></option>
                            <option value="2" <?php if (isset($settings['menulayout']) && $settings['menulayout'] == '2') echo ' selected="selected"'; ?>><?php _we('Slimline','zerobscrm');?></option>
                            <option value="3" <?php if (isset($settings['menulayout']) && $settings['menulayout'] == '3') echo ' selected="selected"'; ?>><?php _we('CRM Only','zerobscrm');?></option>
                          </select>
                          </td>
                        </tr>

                      </tbody>
               </table>

               <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('WordPress Override Mode','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_wptakeovermode"><?php _we('Override WordPress','zerobscrm'); ?>:</label><br /><?php _we('Enabling this setting hides the WordPress header, menu items, and Dashboard for users assigned ZeroBS CRM roles','zerobscrm'); ?></td>
                          <td><input type="checkbox" class="winput form-control" name="wpzbscrm_wptakeovermode" id="wpzbscrm_wptakeovermode" value="1"<?php if (isset($settings['wptakeovermode']) && $settings['wptakeovermode'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>
                      
                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_wptakeovermodeforall"><?php _we('Override WordPress (For All WP Users)','zerobscrm'); ?>:</label></td>
                          <td>
                            <input type="checkbox" class="winput form-control" name="wpzbscrm_wptakeovermodeforall" id="wpzbscrm_wptakeovermodeforall" value="1"<?php if (isset($settings['wptakeovermodeforall']) && $settings['wptakeovermodeforall'] == "1") echo ' checked="checked"'; ?> />
                            <br /><small><?php _we('Enabling this setting hides the WordPress header, menu items, and Dashboard for all WordPress Users','zerobscrm'); ?></small>
                            <br /><small><?php _we('It does not affect access to your Client Portal, API, or Proposals.','zerobscrm'); ?></small>
                          </td>
                        </tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_loginlogourl"><?php _we('Login Logo Override','zerobscrm'); ?>:</label><br /><?php _we('Enter an URL here, or upload a logo to override the WordPress login logo!','zerobscrm'); ?></td>
	                    		<td style="width:540px">
	                    			<input style="width:90%;padding:10px;" name="wpzbscrm_loginlogourl" id="wpzbscrm_loginlogourl" class="form-control link" type="text" value="<?php if (isset($settings['loginlogourl']) && !empty($settings['loginlogourl'])) echo $settings['loginlogourl']; ?>" />
	                    			<button style="margin:10px;" id="wpzbscrm_loginlogourlAdd" class="button" type="button">Upload Image</button>
	                    		</td>
	                    	</tr>
	                    
	   
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_customheadertext"><?php _we('Custom CRM Header','zerobscrm'); ?>:</label><br /><?php _we('Naming your CRM with the above \'Override WordPress\' option selected will show a custom header with that name','zerobscrm'); ?></td>
	                    		<td><input type="text" class="winput form-control" name="wpzbscrm_customheadertext" id="wpzbscrm_customheadertext" value="<?php if (isset($settings['customheadertext']) && !empty($settings['customheadertext'])) echo $settings['customheadertext']; ?>" placeholder="e.g. Epic CRM" /></td>
	                    	</tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_killfrontend"><?php _we('Disable Front-End','zerobscrm'); ?>:</label></td>
	                    		<td>
                            <input type="checkbox" class="winput form-control" name="wpzbscrm_killfrontend" id="wpzbscrm_killfrontend" value="1"<?php if (isset($settings['killfrontend']) && $settings['killfrontend'] == "1") echo ' checked="checked"'; ?> />
                            <br /><small><?php _we('Enabling this setting will disable the front-end of this WordPress install, (redirecting it to your login url!)','zerobscrm'); ?></small>
                            <br /><small><?php _we('This will effectively disable your Client Portal (if installed), but will not affect your API.','zerobscrm'); ?></small>
                          </td>
	                    	</tr>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_showthanksfooter"><?php _we('Show Footer Message','zerobscrm'); ?>:</label><br /><?php _we('Show or Hide "Thanks for using Zero BS CRM"','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_showthanksfooter" id="wpzbscrm_showthanksfooter" value="1"<?php if (isset($settings['showthanksfooter']) && $settings['showthanksfooter'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>
			
	                    </tbody>

	                </table>

        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('File Attachment Settings','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    
	                    	<tr>
	                    		<td class="wfieldname"><label><?php _we('Accepted Upload File Types','zerobscrm'); ?>:</label><br /><?php _we('This setting specifies which file types are acceptable for uploading against customers, quotes, or invoices.','zerobscrm'); ?></td>
	                    		<td style="width:540px">
	                    			<?php foreach ($zeroBSCRM_Mimes as $filetype => $mimedeet){ ?>
	                    			<input type="checkbox" class="winput form-control" name="<?php echo 'wpzbscrm_ft_'.$filetype; ?>" id="<?php echo 'wpzbscrm_ft_'.$filetype; ?>" value="1"<?php if (isset($settings['filetypesupload']) && isset($settings['filetypesupload'][$filetype]) && $settings['filetypesupload'][$filetype] == "1") echo ' checked="checked"'; ?> /> <?php echo '.'.$filetype; ?><br />
	                    			<?php } ?>
	                    			<input type="checkbox" class="winput form-control" name="<?php echo 'wpzbscrm_ft_all'; ?>" id="<?php echo 'wpzbscrm_ft_all'; ?>" value="1"<?php if (isset($settings['filetypesupload']) && isset($settings['filetypesupload']['all']) && $settings['filetypesupload']['all'] == "1") echo ' checked="checked"'; ?> /> * (All)<br />
	                    		</td>
	                    	</tr>
			
	                    </tbody>

	                </table>

        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Auto-logging Settings','zerobscrm'); ?>:<br />(Automatically create log on action)</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<?php 
	                    		foreach ($autoLoggers as $autoLog){ ?>
	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_<?php echo $autoLog['fieldname']; ?>"><?php _we('Auto-log: '.$autoLog['title'],'zerobscrm'); ?>:</label></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_<?php echo $autoLog['fieldname']; ?>" id="wpzbscrm_<?php echo $autoLog['fieldname']; ?>" value="1"<?php if (isset($settings[$autoLog['fieldname']]) && $settings[$autoLog['fieldname']] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>

	                    	<?php } ?>
			
	                    </tbody>

	                </table>
					

	                <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               		<tbody>

	                    	<tr>
	                    		<td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _we('Save Settings','zerobscrm'); ?></button></td>
	                    	</tr>

	                    </tbody>
	                </table>

	            </form>


	            <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;margin-top:40px;">
	               
	                   <thead>
	                        <tr>
	                            <th class="wmid">Zero BS CRM Plugin: <?php _we('Extra Tools','zerobscrm'); ?></th>
	                        </tr>
	                    </thead>
	                    
	                    <tbody>
	                    	<tr>
	                    		<td>
	                    			<p style="padding: 10px;text-align:center;">
		                    			<button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&resetsettings=1';"><?php _we('Restore default settings','zerobscrm'); ?></button> 
		                    			<button type="button" class="button button-primary button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&rebuildcustomertitles=1';"><?php _we('Rebuild Customer Titles','zerobscrm'); ?></button>
		                    		</p>
		                    	</td>
	                    	</tr>
	                    </tbody>
	            </table>

	            <script type="text/javascript">

	            	jQuery(document).ready(function(){


			            // Uploader
			            // http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin (3rd answer)                    
			            jQuery('#wpzbscrm_loginlogourlAdd').click(function(e) {
			                e.preventDefault();
			                var image = wp.media({ 
			                    title: 'Upload Image',
			                    // mutiple: true if you want to upload multiple files at once
			                    multiple: false
			                }).open()
			                .on('select', function(e){
			                    
			                    // This will return the selected image from the Media Uploader, the result is an object
			                    var uploaded_image = image.state().get('selection').first();
			                    // We convert uploaded_image to a JSON object to make accessing it easier
			                    // Output to the console uploaded_image
			                    //console.log(uploaded_image);
			                    var image_url = uploaded_image.toJSON().url;
			                    // Let's assign the url value to the input field
			                    jQuery('#wpzbscrm_loginlogourl').val(image_url);

			                });
			            });




	            	});


	            </script>
	            
   		</div><?php 
   		
   		} else if ($rebuildCustomerNames){

   			   			$fullCustomerList = zeroBS_getCustomers(true,10000,0);

   			if (count($fullCustomerList) > 0){


   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong>Found <?php echo zeroBSCRM_prettifyLongInts(count($fullCustomerList)); ?> Customers...</strong></p>
            	<h3>Starting Title Rebuild:</h3>
            	<?php echo $confirmActStrLong; ?><br /><br />
            	<br />
	            	<div stlye="padding:20px">
	            		<?php

	            			foreach ($fullCustomerList as $cust){

	            					            				echo 'Updating "'.$cust['name'].'" ('.$cust['id'].')... new name: "'.zbsCustomer_updateCustomerNameInPostTitle($cust['id'],$cust['meta']).'"<br />';

	            			}

	            		?>
	            	</div>
				</div><?php 

   			} else {

   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong>No Customers Found</strong></p>
				</div><?php 
   			}


   		} else {

   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong><?php echo $confirmActStr; ?></strong></p>
            	<h3><?php echo $confirmActStrShort; ?></h3>
            	<?php echo $confirmActStrLong; ?><br /><br />
            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&'.$actionStr.'=1&imsure=1','resetclearzerobscrm'); ?>';"><?php echo $actionButtonStr; ?></button>
            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
            	<br />
				</div><?php 
   		} 

}

function zeroBSCRM_html_settings_forms(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	$confirmAct = false;
	$settings = $zeroBSCRM_Settings->getAll();		

	global $zeroBSCRM_Mimes;




		if (isset($_POST['editwplf'])){


		

		$updatedSettings['showpoweredby'] = 0; if (isset($_POST['wpzbscrm_showpoweredby']) && !empty($_POST['wpzbscrm_showpoweredby'])) $updatedSettings['showpoweredby'] = 1;		
		$updatedSettings['usegcaptcha'] = 0; if (isset($_POST['wpzbscrm_usegcaptcha']) && !empty($_POST['wpzbscrm_usegcaptcha'])) $updatedSettings['usegcaptcha'] = 1;
		$updatedSettings['gcaptchasitekey'] = 0; if (isset($_POST['wpzbscrm_gcaptchasitekey']) && !empty($_POST['wpzbscrm_gcaptchasitekey'])) $updatedSettings['gcaptchasitekey'] = sanitize_text_field($_POST['wpzbscrm_gcaptchasitekey']);
		$updatedSettings['gcaptchasitesecret'] = 0; if (isset($_POST['wpzbscrm_gcaptchasitesecret']) && !empty($_POST['wpzbscrm_gcaptchasitesecret'])) $updatedSettings['gcaptchasitesecret'] = sanitize_text_field($_POST['wpzbscrm_gcaptchasitesecret']);

				foreach ($updatedSettings as $k => $v) $zeroBSCRM_Settings->update($k,$v);

				$sbupdated = true;

				$settings = $zeroBSCRM_Settings->getAll();
			
	}

		if (isset($_GET['resetsettings'])) if ($_GET['resetsettings']==1){


		if (!isset($_GET['imsure'])){

								$confirmAct = true;
				$actionStr 				= 'resetsettings';
				$actionButtonStr 		= __w('Reset Settings to Defaults?','zerobscrm');
				$confirmActStr 			= __w('Reset All Zero BS CRM Settings?','zerobscrm');
				$confirmActStrShort 	= __w('Are you sure you want to reset these settings to the defaults?','zerobscrm');
				$confirmActStrLong 		= __w('Once you reset these settings you cannot retrieve your previous settings.','zerobscrm');

			} else {


				if (wp_verify_nonce( $_GET['_wpnonce'], 'resetclearzerobscrm' ) ){

												$zeroBSCRM_Settings->resetToDefaults();

												$settings = $zeroBSCRM_Settings->getAll();

												$sbreset = true;

				}

			}

	} 


	if (!$confirmAct && !isset($rebuildCustomerNames)){

	?>
    
        <p id="sbDesc"><?php _we('From this page you can modify the settings for ZBS CRM Front-end Forms.','zerobscrm'); ?> <a href="<?php echo $zeroBSCRM_urls['products']; ?>" target="_blank">Extensions</a></p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Updated','zerobscrm')); echo '</div>'; } ?>
        <?php if (isset($sbreset)) if ($sbreset) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Reset','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
        	<pre><?php ?></pre>

        		<form method="post" action="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=forms">
        			<input type="hidden" name="editwplf" id="editwplf" value="1" />


        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Forms Settings','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_showpoweredby"><?php _we('Show powered by ZBS','zerobscrm'); ?>:</label><br /><?php _we('Help show us some love by displaying the powered by on your forms','zerobscrm'); ?>.</td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_showpoweredby" id="wpzbscrm_showpoweredby" value="1"<?php if (isset($settings['showpoweredby']) && $settings['showpoweredby'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_usegcaptcha"><?php _we('Enable reCaptcha','zerobscrm'); ?>:</label><br /><?php _we('This setting enabled reCaptcha for your front end forms. If you\'d like to use this to avoid spam, please sign up for a site key and secret','zerobscrm'); ?> <a href="https://www.google.com/recaptcha/admin#list" target="_blank">here</a>.</td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_usegcaptcha" id="wpzbscrm_usegcaptcha" value="1"<?php if (isset($settings['usegcaptcha']) && $settings['usegcaptcha'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_gcaptchasitekey"><?php _we('reCaptcha Site Key','zerobscrm'); ?>:</label><br /><?php _we('','zerobscrm'); ?></td>
	                    		<td><input type="text" class="winput form-control" name="wpzbscrm_gcaptchasitekey" id="wpzbscrm_gcaptchasitekey" value="<?php if (isset($settings['gcaptchasitekey']) && !empty($settings['gcaptchasitekey'])) echo $settings['gcaptchasitekey']; ?>" placeholder="e.g. 6LekCyoTAPPPALWpHONFsRO5RQPOqoHfehdb4iqG" /></td>
	                    	</tr>
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_gcaptchasitesecret"><?php _we('reCaptcha Site Secret','zerobscrm'); ?>:</label><br /><?php _we('','zerobscrm'); ?></td>
	                    		<td><input type="text" class="winput form-control" name="wpzbscrm_gcaptchasitesecret" id="wpzbscrm_gcaptchasitesecret" value="<?php if (isset($settings['gcaptchasitesecret']) && !empty($settings['gcaptchasitesecret'])) echo $settings['gcaptchasitesecret']; ?>" placeholder="e.g. 6LekCyoTAAPPAJbQ1rq81117nMoo9y45fB3OLJVx" /></td>
	                    	</tr>
			
	                    </tbody>

	                </table>

					

	                <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               		<tbody>

	                    	<tr>
	                    		<td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _we('Save Settings','zerobscrm'); ?></button></td>
	                    	</tr>

	                    </tbody>
	                </table>

	            </form>


	            <script type="text/javascript">

	            	jQuery(document).ready(function(){


			            // Uploader
			            // http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin (3rd answer)                    
			            jQuery('#wpzbscrm_loginlogourlAdd').click(function(e) {
			                e.preventDefault();
			                var image = wp.media({ 
			                    title: 'Upload Image',
			                    // mutiple: true if you want to upload multiple files at once
			                    multiple: false
			                }).open()
			                .on('select', function(e){
			                    
			                    // This will return the selected image from the Media Uploader, the result is an object
			                    var uploaded_image = image.state().get('selection').first();
			                    // We convert uploaded_image to a JSON object to make accessing it easier
			                    // Output to the console uploaded_image
			                    //console.log(uploaded_image);
			                    var image_url = uploaded_image.toJSON().url;
			                    // Let's assign the url value to the input field
			                    jQuery('#wpzbscrm_loginlogourl').val(image_url);

			                });
			            });




	            	});


	            </script>
	            
   		</div><?php 
   		
   		}else {

   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong><?php echo $confirmActStr; ?></strong></p>
            	<h3><?php echo $confirmActStrShort; ?></h3>
            	<?php echo $confirmActStrLong; ?><br /><br />
            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&'.$actionStr.'=1&imsure=1','resetclearzerobscrm'); ?>';"><?php echo $actionButtonStr; ?></button>
            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
            	<br />
				</div><?php 
   		} 

}


function zeroBSCRM_extensionhtml_settings_invbuilder(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	$confirmAct = false;
	$settings = $zeroBSCRM_Settings->getAll();		

	global $zeroBSCRM_Mimes;

	
		

				global $whwpCurrencyList;
		if(!isset($whwpCurrencyList)) require_once(ZEROBSCRM_PATH . 'includes/wh.currency.lib.php');
		

		if (isset($_POST['editwplf'])){

				$updatedSettings['businessname'] = ''; if (isset($_POST['businessname'])) $updatedSettings['businessname'] = zeroBSCRM_textProcess($_POST['businessname']);
		$updatedSettings['businessyourname'] = ''; if (isset($_POST['businessyourname'])) $updatedSettings['businessyourname'] = zeroBSCRM_textProcess($_POST['businessyourname']);
		$updatedSettings['businessyouremail'] = ''; if (isset($_POST['businessyouremail'])) $updatedSettings['businessyouremail'] = zeroBSCRM_textProcess($_POST['businessyouremail']);
		$updatedSettings['businessyoururl'] = ''; if (isset($_POST['businessyoururl'])) $updatedSettings['businessyoururl'] = zeroBSCRM_textProcess($_POST['businessyoururl']);
		$updatedSettings['businessextra'] = ''; if (isset($_POST['businessextra'])) $updatedSettings['businessextra'] = zeroBSCRM_textProcess($_POST['businessextra']);


    $updatedSettings['paymentinfo'] = ''; if (isset($_POST['paymentinfo'])) $updatedSettings['paymentinfo'] = zeroBSCRM_textProcess($_POST['paymentinfo']);
    $updatedSettings['paythanks'] = ''; if (isset($_POST['paythanks'])) $updatedSettings['paythanks'] = zeroBSCRM_textProcess($_POST['paythanks']);




        $updatedSettings['ppbusinessemail'] = ''; if (isset($_POST['ppbusinessemail'])) $updatedSettings['ppbusinessemail'] = zeroBSCRM_textProcess($_POST['ppbusinessemail']);
    $updatedSettings['stripe_sk'] = ''; if (isset($_POST['stripe_sk'])) $updatedSettings['stripe_sk'] = zeroBSCRM_textProcess($_POST['stripe_sk']);
    $updatedSettings['stripe_pk'] = ''; if (isset($_POST['stripe_pk'])) $updatedSettings['stripe_pk'] = zeroBSCRM_textProcess($_POST['stripe_pk']);
    $updatedSettings['invpro_pay'] = 1; if (isset($_POST['wpzbscrm_invpro_pay']) && !empty($_POST['wpzbscrm_invpro_pay'])) $updatedSettings['invpro_pay'] = (int)$_POST['wpzbscrm_invpro_pay'];

				$updatedSettings['invfromemail'] = ''; if (isset($_POST['invfromemail'])) $updatedSettings['invfromemail'] = zeroBSCRM_textProcess($_POST['invfromemail']);
		$updatedSettings['invfromname'] = ''; if (isset($_POST['invfromname'])) $updatedSettings['invfromname'] = zeroBSCRM_textProcess($_POST['invfromname']);

				$updatedSettings['invtax'] = 0; if (isset($_POST['wpzbscrm_invtax']) && !empty($_POST['wpzbscrm_invtax'])) $updatedSettings['invtax'] = 1;
		$updatedSettings['invdis'] = 0; if (isset($_POST['wpzbscrm_invdis']) && !empty($_POST['wpzbscrm_invdis'])) $updatedSettings['invdis'] = 1;
		$updatedSettings['invpandp'] = 0; if (isset($_POST['wpzbscrm_invpandp']) && !empty($_POST['wpzbscrm_invpandp'])) $updatedSettings['invpandp'] = 1;

            $updatedSettings['invoicelogourl'] = ''; if (isset($_POST['wpzbscrm_invoicelogourl']) && !empty($_POST['wpzbscrm_invoicelogourl'])) $updatedSettings['invoicelogourl'] = sanitize_text_field($_POST['wpzbscrm_invoicelogourl']);


				foreach ($updatedSettings as $k => $v) $zeroBSCRM_Settings->update($k,$v);

				$sbupdated = true;

				$settings = $zeroBSCRM_Settings->getAll();
			
	}

		if (isset($_GET['resetsettings'])) if ($_GET['resetsettings']==1){


		if (!isset($_GET['imsure'])){

								$confirmAct = true;
				$actionStr 				= 'resetsettings';
				$actionButtonStr 		= __w('Reset Settings to Defaults?','zerobscrm');
				$confirmActStr 			= __w('Reset All Zero BS CRM Settings?','zerobscrm');
				$confirmActStrShort 	= __w('Are you sure you want to reset these settings to the defaults?','zerobscrm');
				$confirmActStrLong 		= __w('Once you reset these settings you cannot retrieve your previous settings.','zerobscrm');

			} else {


				if (wp_verify_nonce( $_GET['_wpnonce'], 'resetclearzerobscrm' ) ){

												$zeroBSCRM_Settings->resetToDefaults();

												$settings = $zeroBSCRM_Settings->getAll();

												$sbreset = true;

				}

			}

	} 






	if (!$confirmAct && !isset($rebuildCustomerNames)){

	?>
    
        <p id="sbDesc"><?php _we('Setup and control how the invoicing functionality works in your ZBS CRM. If you have any feedback on our invoicing functionality please do let us know.','zerobscrm'); ?></p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Updated','zerobscrm')); echo '</div>'; } ?>
        <?php if (isset($sbreset)) if ($sbreset) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Reset','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
        	<pre><?php ?></pre>

        		<form method="post">
        			<input type="hidden" name="editwplf" id="editwplf" value="1" />

        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">

                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('Your Business Logo','zerobscrm'); ?>:</th>
                          </tr>


                      <tr>
                          <td class="wfieldname"><label for="wpzbscrm_invoicelogourl"><?php _we('Default Invoice Logo','zerobscrm'); ?>:</label><br /><?php _we('Enter an URL here, or upload a default logo to use on your invoices!','zerobscrm'); ?></td>
                          <td style="width:540px">
                            <input style="width:90%;padding:10px;" name="wpzbscrm_invoicelogourl" id="wpzbscrm_invoicelogourl" class="form-control link" type="text" value="<?php if (isset($settings['invoicelogourl']) && !empty($settings['invoicelogourl'])) echo $settings['invoicelogourl']; ?>" />
                            <button style="margin:10px;" id="wpzbscrm_invoicelogourlAdd" class="button" type="button">Upload Image</button>
                          </td>
                        </tr>


                      </thead>
                      
                      <tbody>

               

                        </tbody>

                        </table>


       <?php if(defined('ZBSCRM_INC_EXT_INVOICINGPRO')){  ?>

                    <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                 
                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('Invoicing Pro','zerobscrm'); ?>:</th>
                          </tr>

                      </thead>
                      
                      <tbody>


                      <tr>
                          <td class="wfieldname"><label for="wpzbscrm_invpro_pay"><?php _we('Accept Payment via','zerobscrm'); ?>:</label><br /><?php _we('How do you want to accept payments?','zerobscrm'); ?></td>
                          <td style="width:540px">
                          <select class="winput" name="wpzbscrm_invpro_pay" id="wpzbscrm_invpro_pay">
                            <!-- common currencies first -->
                            <option value="1" <?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] == '1') echo ' selected="selected"'; ?>><?php _we('Stripe','zerobscrm');?></option>
                            <option value="2" <?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] == '2') echo ' selected="selected"'; ?>><?php _we('PayPal','zerobscrm');?></option>
                            <option value="3" <?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] == '3') echo ' selected="selected"'; ?>><?php _we('WorldPay','zerobscrm');?></option>
                          </select>
                          <br/>
                          <p class="zbscrmInvProStripeReq"<?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] != '1') echo ' style="display:none"'; ?>><?php _we('To use Stripe for payments your website will need to use SSL (that is your client portal should be delivered @','zerobscrm'); echo ' '.str_replace('http:/','https:/',site_url('/clients')); ?> </p>
                          </td>
                        </tr>

                        <tr class="zbscrmInvProStripeReq"<?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] != '1') echo ' style="display:none"'; ?>>
                            <td class="wfieldname"><label for="stripe_sk"><?php _we('Stripe Secret Key','zerobscrm'); ?>:</label><br /><?php _we('Your stripe secret key','zerobscrm'); ?></td>
                          <td style="width:540px"><input type="text" class="winput form-control" name="stripe_sk" id="stripe_sk" value="<?php if (isset($settings['stripe_sk']) && !empty($settings['stripe_sk'])) echo $settings['stripe_sk']; ?>" placeholder="sk_test_????????????????????????" /></td>
                        </tr>

                        <tr class="zbscrmInvProStripeReq"<?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] != '1') echo ' style="display:none"'; ?>>
                            <td class="wfieldname"><label for="stripe_pk"><?php _we('Stripe Public Key','zerobscrm'); ?>:</label><br /><?php _we('Your stripe public key','zerobscrm'); ?></td>
                          <td style="width:540px"><input type="text" class="winput form-control" name="stripe_pk" id="stripe_pk" value="<?php if (isset($settings['stripe_pk']) && !empty($settings['stripe_pk'])) echo $settings['stripe_pk']; ?>" placeholder="pk_test_????????????????????????" /></td>
                        </tr>



                        <tr class="zbscrmInvProPayPalReq"<?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] != '2') echo ' style="display:none"'; ?>>
                            <td class="wfieldname"><label for="ppbusinessemail"><?php _we('Your PayPal Business Email','zerobscrm'); ?>:</label><br /><?php _we('Your email for paypal','zerobscrm'); ?><br /><?php _we('(must be a business account)','zerobscrm'); ?></td>
                          <td style="width:540px"><input type="text" class="winput form-control" name="ppbusinessemail" id="ppbusinessemail" value="<?php if (isset($settings['ppbusinessemail']) && !empty($settings['ppbusinessemail'])) echo $settings['ppbusinessemail']; ?>" placeholder="mike@epicplugins.com" /></td>
                        </tr>

                        <tr class="zbscrmInvProPWorldPayReq"<?php if (isset($settings['invpro_pay']) && $settings['invpro_pay'] != '3') echo ' style="display:none"'; ?>>
                            <td class="wfieldname"><label for="wpinstallationid"><?php _we('Your Installation ID','zerobscrm'); ?>:</label><br /><?php _we('Your installation ID for WorldPay','zerobscrm'); ?></td>
                          <td style="width:540px"><input type="text" class="winput form-control" name="wpinstallationid" id="wpinstallationid" value="<?php if (isset($settings['wpinstallationid']) && !empty($settings['wpinstallationid'])) echo $settings['wpinstallationid']; ?>" placeholder="" /></td>
                        </tr>

                        </tbody>

                        </table>


              <?php } ?>               



                    <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Your Business Information','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<tr>
	                      		<td class="wfieldname"><label for="businessname"><?php _we('Your Business Name','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="businessname" id="businessname" value="<?php if (isset($settings['businessname']) && !empty($settings['businessname'])) echo $settings['businessname']; ?>" placeholder="e.g. Epic Plugins" /></td>
	                    	</tr>

	                    	<tr>
	                      		<td class="wfieldname"><label for="businessyourname"><?php _we('Your Name','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="businessyourname" id="businessyourname" value="<?php if (isset($settings['businessyourname']) && !empty($settings['businessyourname'])) echo $settings['businessyourname']; ?>" placeholder="e.g. John Doe" /></td>
	                    	</tr>

	                    	<tr>
	                      		<td class="wfieldname"><label for="businessyouremail"><?php _we('Your Contact Email','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="businessyouremail" id="businessyouremail" value="<?php if (isset($settings['businessyouremail']) && !empty($settings['businessyouremail'])) echo $settings['businessyouremail']; ?>" placeholder="e.g. email@domain.com" /></td>
	                    	</tr>

	                    	<tr>
	                      		<td class="wfieldname"><label for="businessyoururl"><?php _we('Your website URL','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="businessyoururl" id="businessyoururl" value="<?php if (isset($settings['businessyoururl']) && !empty($settings['businessyoururl'])) echo $settings['businessyoururl']; ?>" placeholder="e.g. http://epicplugins.com" /></td>
	                    	</tr>

	                    	<tr>
	                      		<td class="wfieldname"><label for="businessextra"><?php _we('Extra Info','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
	                    		<td style="width:540px"><textarea class="winput form-control" name="businessextra" id="businessextra"  placeholder="e.g. your Address" ><?php if (isset($settings['businessextra']) && !empty($settings['businessextra'])) echo $settings['businessextra']; ?></textarea></td>
	                    	</tr>


                        <tr>
                            <td class="wfieldname"><label for="paymentinfo"><?php _we('Payment Info','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
                          <td style="width:540px"><textarea class="winput form-control" name="paymentinfo" id="paymentinfo"  placeholder="e.g. BACS details" ><?php if (isset($settings['paymentinfo']) && !empty($settings['paymentinfo'])) echo $settings['paymentinfo']; ?></textarea></td>
                        </tr>


                        <tr>
                            <td class="wfieldname"><label for="paythanks"><?php _we('Thank You','zerobscrm'); ?>:</label><br /><?php _we('This information is (optionally) added to your invoice','zerobscrm'); ?></td>
                          <td style="width:540px"><textarea class="winput form-control" name="paythanks" id="paythanks"  placeholder="e.g. If you have any questions let us know" ><?php if (isset($settings['paythanks']) && !empty($settings['paythanks'])) echo $settings['paythanks']; ?></textarea></td>
                        </tr>


	                    </tbody>

	                </table>

        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Invoice Email Settings','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<tr>
	                      		<td class="wfieldname"><label for="invfromemail"><?php _we('Invoice from email','zerobscrm'); ?>:</label><br /><?php _we('The from email when you send out your email (e.g. invoicing@yourdomain.com)','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="invfromemail" id="invfromemail" value="<?php if (isset($settings['invfromemail']) && !empty($settings['invfromemail'])) echo $settings['invfromemail']; ?>" placeholder="e.g. invoicing@yourdomain.com" /></td>
	                    	</tr>

	                    	<tr>
	                      		<td class="wfieldname"><label for="invfromname"><?php _we('Invoice from name','zerobscrm'); ?>:</label><br /><?php _we('The name people will see on their email from (e.g. Notification via YourSite','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="text" class="winput form-control" name="invfromname" id="invfromname" value="<?php if (isset($settings['invfromname']) && !empty($settings['invfromname'])) echo $settings['invfromname']; ?>" placeholder="e.g. Notification via Epic Plugins" /></td>
	                    	</tr>

	                    </tbody>

	                </table>
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Invoice Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_tax"><?php _we('Show tax on invoices','zerobscrm'); ?>:</label><br /><?php _we('Tick if you need to charge tax','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_invtax" id="wpzbscrm_invtax" value="1"<?php if (isset($settings['invtax']) && $settings['invtax'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>	                    
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_discount"><?php _we('Show discount on invoices','zerobscrm'); ?>:</label><br /><?php _we('Tick if you want to add discounts','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_invdis" id="wpzbscrm_invdis" value="1"<?php if (isset($settings['invdis']) && $settings['invdis'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>	
	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_pandp"><?php _we('Show P&P on invoices','zerobscrm'); ?>:</label><br /><?php _we('Tick if you want to add postage and packaging','zerobscrm'); ?></td>
	                    		<td style="width:540px"><input type="checkbox" class="winput form-control" name="wpzbscrm_invpandp" id="wpzbscrm_invpandp" value="1"<?php if (isset($settings['invpandp']) && $settings['invpandp'] == "1") echo ' checked="checked"'; ?> /></td>
	                    	</tr>	
	                    </tbody>

	                </table>

					

	                <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               		<tbody>

	                    	<tr>
	                    		<td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _we('Save Settings','zerobscrm'); ?></button></td>
	                    	</tr>

	                    </tbody>
	                </table>

	            </form>


	            <script type="text/javascript">

	            	jQuery(document).ready(function(){

                  jQuery('#wpzbscrm_invpro_pay').change(function(){

                      if (jQuery(this).val() == "1"){
                        jQuery('.zbscrmInvProPayPalReq').hide();
                        jQuery('.zbscrmInvProStripeReq').show();
                      } else {
                        jQuery('.zbscrmInvProPayPalReq').show();
                        jQuery('.zbscrmInvProStripeReq').hide();
                      }


                  });


			            // Uploader
			            // http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin (3rd answer)                    
			            jQuery('#wpzbscrm_invoicelogourlAdd').click(function(e) {
			                e.preventDefault();
			                var image = wp.media({ 
			                    title: 'Upload Image',
			                    // mutiple: true if you want to upload multiple files at once
			                    multiple: false
			                }).open()
			                .on('select', function(e){
			                    
			                    // This will return the selected image from the Media Uploader, the result is an object
			                    var uploaded_image = image.state().get('selection').first();
			                    // We convert uploaded_image to a JSON object to make accessing it easier
			                    // Output to the console uploaded_image
			                    //console.log(uploaded_image);
			                    var image_url = uploaded_image.toJSON().url;
			                    // Let's assign the url value to the input field
			                    jQuery('#wpzbscrm_invoicelogourl').val(image_url);

			                });
			            });




	            	});


	            </script>
	            
   		</div><?php 
   		
   		} else {

   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong><?php echo $confirmActStr; ?></strong></p>
            	<h3><?php echo $confirmActStrShort; ?></h3>
            	<?php echo $confirmActStrLong; ?><br /><br />
            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&'.$actionStr.'=1&imsure=1','resetclearzerobscrm'); ?>';"><?php echo $actionButtonStr; ?></button>
            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
            	<br />
				</div><?php 
   		} 
}


function zeroBSCRM_extensionhtml_settings_quotebuilder(){
      
  global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;  
  $confirmAct = false;
  $settings = $zeroBSCRM_Settings->getAll();    

  global $zeroBSCRM_Mimes;




    if (isset($_POST['editwplf'])){


    

    $updatedSettings['showpoweredbyquotes'] = 0; if (isset($_POST['wpzbscrm_showpoweredbyquotes']) && !empty($_POST['wpzbscrm_showpoweredbyquotes'])) $updatedSettings['showpoweredbyquotes'] = 1;    
    $updatedSettings['usequotebuilder'] = 0; if (isset($_POST['wpzbscrm_usequotebuilder']) && !empty($_POST['wpzbscrm_usequotebuilder'])) $updatedSettings['usequotebuilder'] = 1;

        foreach ($updatedSettings as $k => $v) $zeroBSCRM_Settings->update($k,$v);

        $sbupdated = true;

        $settings = $zeroBSCRM_Settings->getAll();
      
  }

    if (isset($_GET['resetsettings'])) if ($_GET['resetsettings']==1){


    if (!isset($_GET['imsure'])){

                $confirmAct = true;
        $actionStr        = 'resetsettings';
        $actionButtonStr    = __w('Reset Settings to Defaults?','zerobscrm');
        $confirmActStr      = __w('Reset All Zero BS CRM Settings?','zerobscrm');
        $confirmActStrShort   = __w('Are you sure you want to reset these settings to the defaults?','zerobscrm');
        $confirmActStrLong    = __w('Once you reset these settings you cannot retrieve your previous settings.','zerobscrm');

      } else {


        if (wp_verify_nonce( $_GET['_wpnonce'], 'resetclearzerobscrm' ) ){

                        $zeroBSCRM_Settings->resetToDefaults();

                        $settings = $zeroBSCRM_Settings->getAll();

                        $sbreset = true;

        }

      }

  } 


  if (!$confirmAct && !isset($rebuildCustomerNames)){

  ?>
    
        <p id="sbDesc"><?php _we('Choose your default settings for the ZBS Quote Builder!','zerobscrm'); ?> (Quotes can be enabled/disabled globally from the <a href="?page=<?php echo $zeroBSCRM_slugs['extensions']; ?>" target="_blank">Extensions</a> page. You can also buy Quotes PRO in the <a href="<?php echo $zeroBSCRM_urls['products']; ?>" target="_blank">Extension Store</a> to supercharge your proposal writing!)</p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Updated','zerobscrm')); echo '</div>'; } ?>
        <?php if (isset($sbreset)) if ($sbreset) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Settings Reset','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
          <pre><?php ?></pre>

            <form method="post" action="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=quotebuilder">
              <input type="hidden" name="editwplf" id="editwplf" value="1" />


              <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                 
                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('Quotes Settings','zerobscrm'); ?>:</th>
                          </tr>

                      </thead>
                      
                      <tbody>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_usequotebuilder"><?php _we('Enable Quote Builder','zerobscrm'); ?>:</label><br /><?php _we('Disabling this will remove the quote-writing element of Quotes. This is useful if you\'re only using ZBS to log quotes, not write them.','zerobscrm'); ?>.</td>
                          <td style=""><input type="checkbox" class="winput form-control" name="wpzbscrm_usequotebuilder" id="wpzbscrm_usequotebuilder" value="1"<?php if (isset($settings['usequotebuilder']) && $settings['usequotebuilder'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_showpoweredbyquotes"><?php _we('Show powered by ZBS','zerobscrm'); ?>:</label><br /><?php _we('Show us some love by displaying (tiny at the bottom), \'Powered by ZBSCRM\'','zerobscrm'); ?>.</td>
                          <td style=""><input type="checkbox" class="winput form-control" name="wpzbscrm_showpoweredbyquotes" id="wpzbscrm_showpoweredbyquotes" value="1"<?php if (isset($settings['showpoweredbyquotes']) && $settings['showpoweredbyquotes'] == "1") echo ' checked="checked"'; ?> /></td>
                        </tr>
      
                      </tbody>

                  </table>

          

                  <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                    <tbody>

                        <tr>
                          <td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _we('Save Settings','zerobscrm'); ?></button></td>
                        </tr>

                      </tbody>
                  </table>

              </form>


              <script type="text/javascript">

                jQuery(document).ready(function(){



                });


              </script>
              
      </div><?php 
      
      }else {

          ?><div id="clpSubPage" class="whclpActionMsg six">
            <p><strong><?php echo $confirmActStr; ?></strong></p>
              <h3><?php echo $confirmActStrShort; ?></h3>
              <?php echo $confirmActStrLong; ?><br /><br />
              <button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&'.$actionStr.'=1&imsure=1','resetclearzerobscrm'); ?>';"><?php echo $actionButtonStr; ?></button>
              <button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
              <br />
        </div><?php 
      } 

}


function zeroBSCRM_html_systemstatus(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs, $zeroBSCRM_Mimes;	
	$settings = $zeroBSCRM_Settings->getAll();		

    

	?>
    
        <p id="sbDesc"><?php _we('This page allows easy access for the various system status variables related to your WordPress install and Zero BS CRM.','zerobscrm'); ?></p>

        <div id="sbA">


                	<?php 

                  
                	                	                	
                			$zbsEnvList = array(

                                                    'autodraftgarbagecollect' => 'Auto-draft Garbage Collection',
                          'locale' => 'Locale'

                        ); 

                			if (count($zbsEnvList)){ 
                	?>
        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Zero BS Environment','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<?php foreach ($zbsEnvList as $envCheckKey => $envCheckName){ 

	                    			                    		$result = zeroBSCRM_checkSystemFeat($envCheckKey,true);

	                    		?>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_env_<?php echo $envCheckKey; ?>"><?php _we($envCheckName,'zerobscrm'); ?>:</label></td>
	                    		<td style="width:540px"><?php echo $result[1]; ?></td>
	                    	</tr>

	                    	<?php } ?>
			
	                    </tbody>

	                </table>


	                <?php } ?>


                	<?php 

                	                	                	
                			$servEnvList = array(
                				'curl'		=> 'CURL',
                				'zlib'		=>'zlib (Zip Library)',
                				'pdfdom'	=>'PDF Engine',
                        'phpver'  => 'PHP Version'
                				);

                			if (count($servEnvList)){ 
                	?>
        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Server Environment','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<?php foreach ($servEnvList as $envCheckKey => $envCheckName){ 

	                    			                    		$result = zeroBSCRM_checkSystemFeat($envCheckKey,true);

	                    		?>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_env_<?php echo $envCheckKey; ?>"><?php _we($envCheckName,'zerobscrm'); ?>:</label></td>
	                    		<td style="width:540px"><?php echo $result[1]; ?></td>
	                    	</tr>

	                    	<?php } ?>
			
	                    </tbody>

	                </table>

	                <?php } ?>


                	<?php 

                	                	                	
                			$wpEnvList = array(); 
                			if (count($wpEnvList)){ 
                	?>
        			<table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('WordPress Environment','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody>

	                    	<?php foreach ($wpEnvList as $envCheckKey => $envCheckName){ 

	                    			                    		$result = zeroBSCRM_checkSystemFeat($envCheckKey,true);

	                    		?>

	                    	<tr>
	                    		<td class="wfieldname"><label for="wpzbscrm_env_<?php echo $envCheckKey; ?>"><?php _we($envCheckName,'zerobscrm'); ?>:</label></td>
	                    		<td style="width:540px"><?php echo $result[1]; ?></td>
	                    	</tr>

	                    	<?php } ?>
			
	                    </tbody>

	                </table>

	                <?php } ?>


                  <?php 

                                                                        
                      global $zeroBSCRM_Settings;                       $migratedAlreadyArr = $zeroBSCRM_Settings->get('migrations');

                                            $migrationVers = array('123'=>'1.2.3','1119' => '1.1.19','127'=>'1.2.7');

                      if (count($migratedAlreadyArr) > 0){ 
                  ?>
              <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                 
                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('Zero BS Migrations Completed','zerobscrm'); ?>:</th>
                          </tr>

                      </thead>
                      
                      <tbody>

                        <?php foreach ($migratedAlreadyArr as $migrationkey){ 

                          $migrationDetail = get_option('zbsmigration'.$migrationkey);
                          
                          $migrationName = $migrationkey; if (isset($migrationVers[$migrationkey])) $migrationName = $migrationVers[$migrationkey];

                        ?>

                        <tr>
                          <td class="wfieldname"><label for="wpzbscrm_mig_<?php echo $migrationkey; ?>"><?php _we('Migration: '.$migrationName,'zerobscrm'); ?>:</label></td>
                          <td style="width:540px"><?php 

                              if (isset($migrationDetail['completed'])) {
                                
                                echo 'Completed '.date('F j, Y, g:i a',$migrationDetail['completed']); 
                                if (isset($migrationDetail['meta']) && isset($migrationDetail['meta']['updated'])) echo ' ('.$migrationDetail['meta']['updated'].')';

                              } else echo '?';

                              ?></td>
                        </tr>

                        <?php } ?>
      
                      </tbody>

                  </table>


                  <?php } ?>


	            <script type="text/javascript">

	            	jQuery(document).ready(function(){



	            	});


	            </script>
	            
   		</div><?php 

}


function zeroBSCRM_html_customfields(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	$confirmAct = false;
	$settings = $zeroBSCRM_Settings->getAll();

	$acceptableCFTypes = array('text','textarea','date','select','tel','price','email');
		
		if (isset($_POST['editwplf'])){


				$customFields = array(

			'customers'=>array(),
			'companies'=>array(),
			'quotes'=>array(),
						'addresses'=>array()

		);


				for ($i = 1; $i <= 31; $i++){

    			    			    			if (isset($_POST['wpzbscrm_cf_addresses'.$i.'_t']) && !empty($_POST['wpzbscrm_cf_addresses'.$i.'_t'])){

    				$possType = sanitize_text_field($_POST['wpzbscrm_cf_addresses'.$i.'_t']);
    				$possName = sanitize_text_field($_POST['wpzbscrm_cf_addresses'.$i.'_n']);
    				$possPlaceholder = sanitize_text_field($_POST['wpzbscrm_cf_addresses'.$i.'_p']); 
    				if (in_array($possType,$acceptableCFTypes)){

    					    					if (empty($possName)) $possName = 'Custom Field '. (count($customFields['addresses']) + 1);

    					    					$customFields['addresses'][] = array($possType,$possName,$possPlaceholder);

    				}

    			}

    			    			    			if (isset($_POST['wpzbscrm_cf_customers'.$i.'_t']) && !empty($_POST['wpzbscrm_cf_customers'.$i.'_t'])){

    				$possType = sanitize_text_field($_POST['wpzbscrm_cf_customers'.$i.'_t']);
    				$possName = sanitize_text_field($_POST['wpzbscrm_cf_customers'.$i.'_n']);
    				$possPlaceholder = sanitize_text_field($_POST['wpzbscrm_cf_customers'.$i.'_p']); 
    				if (in_array($possType,$acceptableCFTypes)){

    					    					if (empty($possName)) $possName = 'Custom Field '. (count($customFields['customers']) + 1);

    					    					$customFields['customers'][] = array($possType,$possName,$possPlaceholder);

    				}

    			}

    			    			    			if (isset($_POST['wpzbscrm_cf_companies'.$i.'_t']) && !empty($_POST['wpzbscrm_cf_companies'.$i.'_t'])){

    				$possType = sanitize_text_field($_POST['wpzbscrm_cf_companies'.$i.'_t']);
    				$possName = sanitize_text_field($_POST['wpzbscrm_cf_companies'.$i.'_n']);
    				$possPlaceholder = sanitize_text_field($_POST['wpzbscrm_cf_companies'.$i.'_p']); 
    				if (in_array($possType,$acceptableCFTypes)){

    					    					if (empty($possName)) $possName = 'Custom Field '. (count($customFields['companies']) + 1);

    					    					$customFields['companies'][] = array($possType,$possName,$possPlaceholder);

    				}

    			}

    			    			    			if (isset($_POST['wpzbscrm_cf_quotes'.$i.'_t']) && !empty($_POST['wpzbscrm_cf_quotes'.$i.'_t'])){

    				$possType = sanitize_text_field($_POST['wpzbscrm_cf_quotes'.$i.'_t']);
    				$possName = sanitize_text_field($_POST['wpzbscrm_cf_quotes'.$i.'_n']);
    				$possPlaceholder = sanitize_text_field($_POST['wpzbscrm_cf_quotes'.$i.'_p']); 
    				if (in_array($possType,$acceptableCFTypes)){

    					    					if (empty($possName)) $possName = 'Custom Field '. (count($customFields['quotes']) + 1);

    					    					$customFields['quotes'][] = array($possType,$possName,$possPlaceholder);

    				}

    			}

    } 
									


      
                $customisedFields = array(

                        'customers' => array(
                                                                                                                                  'status'=> array(
                            1,'Lead,Customer,Refused,Blacklisted,Cancelled by Customer,Cancelled by Us (Pre-Quote),Cancelled by Us (Post-Quote)'
                          ),
                          'prefix'=> array(
                            1,'Mr,Mrs,Ms,Miss,Dr,Prof,Mr & Mrs'
                          )
                        ),
                        'quotes' => array(),
                        'invoices' => array(),
                        'transactions' => array()

                      );

                $zbsStatusStr = ''; if (isset($_POST['zbs-status']) && !empty($_POST['zbs-status'])) $zbsStatusStr = sanitize_text_field($_POST['zbs-status']);
        $zbsDefaultStatusStr = ''; if (isset($_POST['zbs-default-status']) && !empty($_POST['zbs-default-status'])) $zbsDefaultStatusStr = sanitize_text_field($_POST['zbs-default-status']);
        $zbsPrefixStr = ''; if (isset($_POST['zbs-prefix']) && !empty($_POST['zbs-prefix'])) $zbsPrefixStr = sanitize_text_field($_POST['zbs-prefix']);

                $zbsShowID = -1; if (isset($_POST['zbs-show-id']) && !empty($_POST['zbs-show-id'])) $zbsShowID = 1;

        
                    if (strpos($zbsStatusStr, ',') > -1) {

                        $zbsStatusArr = array(); $zbsStatusUncleanArr = explode(',',$zbsStatusStr);
            foreach ($zbsStatusUncleanArr as $x) {
              $z = trim($x);
              if (!empty($z)) $zbsStatusArr[] = $z;
            }

            $customisedFields['customers']['status'][1] = implode(',',$zbsStatusArr); 
          } else {

                            if (!empty($zbsStatusStr)) $customisedFields['customers']['status'][1] = $zbsStatusStr;

          }

                    if (strpos($zbsPrefixStr, ',') > -1) {

                        $zbsPrefixArr = array(); $zbsPrefixUncleanArr = explode(',',$zbsPrefixStr);
            foreach ($zbsPrefixUncleanArr as $x) {
              $z = trim($x);
              if (!empty($z)) $zbsPrefixArr[] = $z;
            }

            $customisedFields['customers']['prefix'][1] =  implode(',',$zbsPrefixArr); 
          } else {

                            if (!empty($zbsPrefixStr)) $customisedFields['customers']['prefix'][1] = $zbsPrefixStr;

          }


                    $zeroBSCRM_Settings->update('customisedfields',$customisedFields);
          $zeroBSCRM_Settings->update('defaultstatus',$zbsDefaultStatusStr);
          $zeroBSCRM_Settings->update('showid',$zbsShowID);


                              

				$zeroBSCRM_Settings->update('customfields',$customFields);

				$sbupdated = true;

				$settings = $zeroBSCRM_Settings->getAll();
			
	}

	if (!$confirmAct){

	?>
    
        <p id="sbDesc"><?php _we('Using this page you can add or edit custom fields associated with Customers, Companies or Quotes','zerobscrm'); ?></p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Custom Fields Updated','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
        	<!--<pre><?php ?></pre>-->

        		<form method="post" action="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=customfields">
        			<input type="hidden" name="editwplf" id="editwplf" value="1" />
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Addresses: Custom Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody id="zbscrm-addresses-custom-fields">

	                    	<tr>
	                    		<td colspan="2" style="text-align:right"><button type="button" id="zbscrm-addcustomfield-address" class="button button-success">+ Add Custom Field</button></td>
	                    	</tr>
			
	                    </tbody>

	                </table>
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Customers: Custom Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody id="zbscrm-customers-custom-fields">

	                    	<tr>
	                    		<td colspan="2" style="text-align:right"><button type="button" id="zbscrm-addcustomfield-customer" class="button button-success">+ Add Custom Field</button></td>
	                    	</tr>
			
	                    </tbody>

	                </table>
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we(zeroBSCRM_getCompanyOrOrg().': Custom Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody id="zbscrm-companies-custom-fields">

	                    	<tr>
	                    		<td colspan="2" style="text-align:right"><button type="button" id="zbscrm-addcustomfield-company" class="button button-success">+ Add Custom Field</button></td>
	                    	</tr>
			
	                    </tbody>

	                </table>
        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Quotes: Custom Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody id="zbscrm-quotes-custom-fields">

	                    	<tr>
	                    		<td colspan="2" style="text-align:right"><button type="button" id="zbscrm-addcustomfield-quotes" class="button button-success">+ Add Custom Field</button></td>
	                    	</tr>
			
	                    </tbody>

	                </table>
        			 <?php if (isset($usingInvoiceCustomFieldsBro)) { ?><table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               
	                   <thead>
	                    
	                        <tr>
	                            <th colspan="2" class="wmid"><?php _we('Invoices: Custom Fields','zerobscrm'); ?>:</th>
	                        </tr>

	                    </thead>
	                    
	                    <tbody id="zbscrm-invoices-custom-fields">

	                    	<tr>
	                    		<td colspan="2" style="text-align:right"><button type="button" id="zbscrm-addcustomfield-invoices" class="button button-success">+ Add Custom Field</button></td>
	                    	</tr>
			
	                    </tbody>

	                </table>
                  <?php } ?>
                 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
                 
                     <thead>
                      
                          <tr>
                              <th colspan="2" class="wmid"><?php _we('ID, Status & Prefix','zerobscrm'); ?>:</th>
                          </tr>

                      </thead>
                      
                      <tbody id="zbscrm-statusprefix-custom-fields">

                        <tr>
                          <td colspan="2" style="padding:2%;">

                              <table class="table table-bordered table-striped wtab">
                                <tbody id="zbscrm-statusprefix-custom-fields">

                                  <tr>
                                    <td width="94">                                      
                                      <label for="zbs-status"><?php _we('Show ID\'s','zerobscrm'); ?></label>
                                    </td>
                                    <td>
                                      <input type="checkbox" name="zbs-show-id" id="zbs-show-id" value="1" <?php if (isset($settings['showid']) && $settings['showid'] == "1") echo ' checked="checked"'; ?> class="form-control" />
                                      <small style="margin-top:4px">Choose whether to show or hide "Customer/Company ID" on customer record &amp; manager pages</small>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td width="94">                                      
                                      <label for="zbs-status"><?php _we('Status Options','zerobscrm'); ?></label>
                                    </td>
                                    <td>
                                      <?php 

                                                                                $zbsStatusStr = ''; 
                                                                                if (isset($settings['customisedfields']['customers']['status']) && is_array($settings['customisedfields']['customers']['status'])) $zbsStatusStr = $settings['customisedfields']['customers']['status'][1];                                        
                                        if (empty($zbsStatusStr)) {
                                                                                    global $zbsCustomerFields; if (is_array($zbsCustomerFields)) $zbsStatusStr = implode(',',$zbsCustomerFields['status'][3]);
                                        }                                        

                                      ?>
                                      <input type="text" name="zbs-status" id="zbs-status" value="<?php echo $zbsStatusStr; ?>" class="form-control" />
                                      <small style="margin-top:4px">Default is:<br /><span style="background:#ceeaea;padding:0 4px">Lead,Customer,Refused,Blacklisted,Cancelled by Customer,Cancelled by Us (Pre-Quote),Cancelled by Us (Post-Quote)</span></small>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td width="94">                                      
                                      <label for="zbs-default-status"><?php _we('Status: Default','zerobscrm'); ?></label>
                                    </td>
                                    <td>
                                      <?php 

                                                                                if (isset($settings['defaultstatus'])) $defaultStatusStr = $settings['defaultstatus'];
                                        if (!empty($zbsStatusStr)) {

                                          ?><select name="zbs-default-status" id="zbs-default-status" class="form-control"> 
                                          <?php

                                            $zbsStatuses = explode(',', $zbsStatusStr);
                                            if (is_array($zbsStatuses)) { foreach ($zbsStatuses as $statusStr){

                                              ?><option value="<?php echo $statusStr; ?>"<?php
                                              if ($defaultStatusStr == $statusStr) echo ' selected="selected"';
                                              ?>><?php echo $statusStr; ?></option><?php

                                            }}else{

                                                ?><option value="">None (Set values above &amp; save to enable this)</option><?php 

                                            }

                                            ?></select><?php

                                        }                                        

                                      ?>
                                      <small style="margin-top:4px">This setting determines which <strong>Status</strong> will automatically be assigned to new customer records where a status is not specified (e.g. via web form)</small>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td>                                      
                                      <label for="zbs-prefix"><?php _we('Prefix Options','zerobscrm'); ?></label>
                                    </td>
                                    <td>
                                      <?php 

                                                                                                                        $zbsPrefixStr = ''; 
                                        if (isset($settings['customisedfields']['customers']['prefix']) && is_array($settings['customisedfields']['customers']['prefix'])) $zbsPrefixStr = $settings['customisedfields']['customers']['prefix'][1];                                        
                                        if (empty($zbsPrefixStr)) {
                                                                                    global $zbsCustomerFields; if (is_array($zbsCustomerFields)) $zbsPrefixStr = implode(',',$zbsCustomerFields['prefix'][3]);
                                        }       
                                        

                                      ?>
                                      <input type="text" name="zbs-prefix" id="zbs-prefix" value="<?php echo $zbsPrefixStr; ?>" class="form-control" />
                                      <small style="margin-top:4px">Default is: <span style="background:#ceeaea;padding:0 4px">Mr,Mrs,Ms,Miss,Dr,Prof,Mr &amp; Mrs</span></small>
                                    </td>
                                  </tr>
                
                                </tbody>
                              </table>


                          </td>
                        </tr>
      
                      </tbody>

                  </table>
	                <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
	               		<tbody>

	                    	<tr>
	                    		<td colspan="2" class="wmid"><button type="submit" class="button button-primary button-large"><?php _we('Save Custom Fields','zerobscrm'); ?></button></td>
	                    	</tr>

	                    </tbody>
	                </table>

	            </form>

	            <script type="text/javascript">

	            	var wpzbscrmCustomFields = <?php echo json_encode($settings['customfields']); ?>;
	            	var wpzbscrmAcceptableTypes = <?php echo json_encode($acceptableCFTypes); ?>;

	            	jQuery(document).ready(function(){

	            		// build init
	            		zbscrmBuildCustomLines();


	            		// add field 
	            		jQuery('#zbscrm-addcustomfield-address').click(function(){

	            			zbscrmBuildLine('addresses','text','','');

	            		});
	            		jQuery('#zbscrm-addcustomfield-customer').click(function(){

	            			zbscrmBuildLine('customers','text','','');

	            		});
	            		jQuery('#zbscrm-addcustomfield-company').click(function(){

	            			zbscrmBuildLine('companies','text','','');

	            		});
	            		jQuery('#zbscrm-addcustomfield-quotes').click(function(){

	            			zbscrmBuildLine('quotes','text','','');

	            		});
	            		jQuery('#zbscrm-addcustomfield-invoices').click(function(){

	            			zbscrmBuildLine('invoices','text','','');

	            		});

	            	});

	            	function zbscrmBuildCustomLines(){

	            		// addresses
	            		if (typeof window.wpzbscrmCustomFields.addresses != "undefined"){
	            			
	            			// cycle
	            			jQuery.each(window.wpzbscrmCustomFields.addresses,function(ind,ele){

	            				zbscrmBuildLine('addresses',ele[0],ele[1],ele[2]);

	            			});

	            		}

	            		// customers
	            		if (typeof window.wpzbscrmCustomFields.customers != "undefined"){
	            			
	            			// cycle
	            			jQuery.each(window.wpzbscrmCustomFields.customers,function(ind,ele){

	            				zbscrmBuildLine('customers',ele[0],ele[1],ele[2]);

	            			});

	            		}

	            		// customers
	            		if (typeof window.wpzbscrmCustomFields.companies != "undefined"){
	            			
	            			// cycle
	            			jQuery.each(window.wpzbscrmCustomFields.companies,function(ind,ele){

	            				zbscrmBuildLine('companies',ele[0],ele[1],ele[2]);

	            			});

	            		}

	            		// quotes
	            		if (typeof window.wpzbscrmCustomFields.quotes != "undefined"){
	            			
	            			// cycle
	            			jQuery.each(window.wpzbscrmCustomFields.quotes,function(ind,ele){

	            				zbscrmBuildLine('quotes',ele[0],ele[1],ele[2]);

	            			});

	            		}

	            		// invoices
	            		/* If using! 
                  if (typeof window.wpzbscrmCustomFields.invoices != "undefined"){
	            			
	            			// cycle
	            			jQuery.each(window.wpzbscrmCustomFields.invoices,function(ind,ele){

	            				zbscrmBuildLine('invoices',ele[0],ele[1],ele[2]);

	            			});

	            		}*/


	            	}

	            	// area = customers, quotes, invoices
	            	function zbscrmBuildLine(area,typestr,namestr,placeholder){

	            		//if (typeof area == "undefined") area = 'customer';
	            		if (typeof typestr == "undefined") typestr = 'text';
	            		if (typeof namestr == "undefined") namestr = 'Custom Field';
	            		if (typeof placeholder == "undefined") placeholder = '';

	            		// count existing - no need to add one as "Add new" line counts
	            		var i = jQuery('#zbscrm-' + area + '-custom-fields tr').length;

	            		if (typestr == 'select')
	            			placeholderstr = 'CSV of Options (e.g. \'a,b,c\')';
	            		else
	            			placeholderstr = 'Placeholder';

	            		var html = '<tr class="zbscrm-cf"><td class="zbscrm-cf-n">';
	            			html += '<input type="text" class="form-control" name="wpzbscrm_cf_' + area + i + '_n" value="' + namestr + '" placeholder="Field Name..." /><br />';
	            			html += '<button type="button" class="zbscrm-remove button" style="margin:5px">Remove</button></td><td>';
	            			html += zbscrmBuildSelect(area,typestr,i) + '<br /><span>' + placeholderstr + '</span>:';
	            			html += '<input type="text" class="form-control" name="wpzbscrm_cf_' + area + i + '_p" value="' + placeholder + '" placeholder="Field Placeholder..." />';
	            			html += '</td></tr>';

	            		// add it
	            		jQuery('#zbscrm-' + area + '-custom-fields tr').last().before(html);

	            		// rebind
	            		setTimeout(function(){ zbscrmBindRemove(); },0);

	            	}

	            	function zbscrmBuildSelect(area,typestr,i){

	            		var selectHTML = '<select class="form-control zbscrm-customtype" name="wpzbscrm_cf_'  + area + i + '_t">';
	            			jQuery.each(window.wpzbscrmAcceptableTypes,function(ind,ele){

	            				var eleStr = ucwords(ele); 
	            				if (eleStr == 'Tel') eleStr = 'Telephone';

	            				selectHTML += '<option value="' + ele + '"';
	            				if (ele == typestr) selectHTML += ' selected="selected"';
	            				selectHTML += '>' + eleStr + '</option>';	            				

	            			});

	            			selectHTML += '</select>';

	            		return selectHTML;
	            	}

	            	function zbscrmBindRemove(){

	            		jQuery('.zbscrm-remove').unbind('click').click(function(){

	            			jQuery(this).closest('tr').remove();

	            		});

	            		jQuery('.zbscrm-customtype').unbind('change').change(function(){

	            			if (jQuery(this).val() == 'select') 
	            				jQuery('span',jQuery(this).parent()).html('CSV of Options (e.g. \'a,b,c\')');
	            			else
	            				jQuery('span',jQuery(this).parent()).html('Placeholder');

	            		});

	            	}

	            </script>
	            
   		</div><?php 
   		
   		} else {

   				?><div id="clpSubPage" class="whclpActionMsg six">
        		<p><strong><?php echo $confirmActStr; ?></strong></p>
            	<h3><?php echo $confirmActStrShort; ?></h3>
            	<?php echo $confirmActStrLong; ?><br /><br />
            	<button type="button" class="button button-primary button-large" onclick="javascript:window.location='<?php echo wp_nonce_url('?page='.$zeroBSCRM_slugs['settings'].'&'.$actionStr.'=1&imsure=1','resetclearzerobscrm'); ?>';"><?php echo $actionButtonStr; ?></button>
            	<button type="button" class="button button-large" onclick="javascript:window.location='?page=<?php echo $zeroBSCRM_slugs['settings']; ?>';"><?php _we("Cancel",'zerobscrm'); ?></button>
            	<br />
				</div><?php 
   		} 
}


function zeroBSCRM_html_fieldsorts(){
    	
	global $wpdb, $zeroBSCRM_Settings, $zeroBSCRM_db_version, $zeroBSCRM_version, $zeroBSCRM_urls, $zeroBSCRM_slugs;	
	
				$fieldTypes = array(

					'address' => array('name'=>'Address Fields','obj'=>'zbsAddressFields'),
					'customer' => array('name'=>'Customer Fields','obj'=>'zbsCustomerFields'),
					'company' => array('name'=>zeroBSCRM_getCompanyOrOrg().' Fields','obj'=>'zbsCompanyFields'),
					'quote' => array('name'=>'Quote Fields','obj'=>'zbsCustomerQuoteFields'),
					
				);

		if (isset($_POST['editwplfsort'])){

				global $zbsFieldSorts;

				$newFieldOrderList = array();    $newFieldHideList = array();

				foreach ($fieldTypes as $key => $fieldType){ 

						$potentialCSV = ''; if (isset($_POST['zbscrm-'.$key.'-sortorder']) && !empty($_POST['zbscrm-'.$key.'-sortorder'])) $potentialCSV = sanitize_text_field($_POST['zbscrm-'.$key.'-sortorder']);

						
						if (!empty($potentialCSV)){

												$newArr = explode(',', $potentialCSV);

																				$newFieldOrderList[$key] = $newArr;

			}


      
                        $potentialCSV = ''; if (isset($_POST['zbscrm-'.$key.'-hidelist']) && !empty($_POST['zbscrm-'.$key.'-hidelist'])) $potentialCSV = sanitize_text_field($_POST['zbscrm-'.$key.'-hidelist']);

                        
                        if (!empty($potentialCSV)){

                                          $newArr = explode(',', $potentialCSV);

                                                                      $newFieldHideList[$key] = $newArr;

            }

            

		}

		
		    $zeroBSCRM_Settings->update('fieldsorts',$newFieldOrderList);
    $zeroBSCRM_Settings->update('fieldhides',$newFieldHideList);
		$sbupdated = true;

				
				zeroBSCRM_applyFieldSorts();

	}

    $fieldHideOverrides = $zeroBSCRM_Settings->get('fieldhides');

	?>
    
        <p id="sbDesc"><?php _we('Using this page you can modify the order of the fields associated with Customers, Companies, Quotes','zerobscrm'); ?></p>

        <?php if (isset($sbupdated)) if ($sbupdated) { echo '<div style="width:500px; margin-left:20px;" class="wmsgfullwidth">'; zeroBSCRM_html_msg(0,__w('Field Orders Updated','zerobscrm')); echo '</div>'; } ?>
        
        <div id="sbA">
        		<form method="post" action="?page=<?php echo $zeroBSCRM_slugs['settings']; ?>&tab=fieldsorts" id="zbsfieldsortform">
        			<input type="hidden" name="editwplfsort" id="editwplfsort" value="1" />
        			 
        			<?php foreach ($fieldTypes as $key => $fieldType){ ?>



		        			 <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
			               
			                   <thead>
			                    
			                        <tr>
			                            <th colspan="2" class="wmid"><?php _we($fieldType['name'],'zerobscrm'); ?>:</th>
			                        </tr>

			                    </thead>
			                    
			                    <tbody id="zbscrm-<?php echo $key; ?>-fieldsorts">

			                    	<tr>
			                    		<td colspan="2" style="text-align:right">

			                    			<div class="zbsSortableFieldList">

			                    				<ul id="zbscrm-<?php echo $key; ?>-sort">

			                    					<?php 
			                    									                    									                    						$fieldTypeObjVarName = $fieldType['obj'];
			                    						global $$fieldTypeObjVarName;
			                    						$fieldTypesArray = $$fieldTypeObjVarName;

			                    									                    						$migratedFieldsOut = array();

			                    						                                      $csvSortOrder = '';
                                      $csvHideList = '';

			                    						if (count($fieldTypesArray) > 0) foreach ($fieldTypesArray as $subkey => $field){

			                    										                    							
			                    							if (isset($field['migrate']) && !empty($field['migrate'])){

			                    								if (!in_array($field['migrate'],$migratedFieldsOut)){

			                    									switch ($field['migrate']){

			                    													                    										case "addresses":

						                    														                    								?><li data-key="addresses">Addresses</li><?php			                    											

			                    											break;

			                    									}


				                    												                    								if (!empty($csvSortOrder)) $csvSortOrder .= ',';
				                    								$csvSortOrder .= $field['migrate'];

				                    											                    									$migratedFieldsOut[] = $field['migrate'];

			                    								} 
			                    							} else {

			                    											                    								?><li data-key="<?php echo $subkey; ?>"><?php echo $field[1]; 

			                    								if (substr($subkey,0,2) == "cf") echo ' ('.__w('Custom Field','zerobscrm').')';


                                                                                    if (in_array($key,array('customer','company'))){

                                                                                                if (isset($field['essential']) && !empty($field['essential'])){

                                                    

                                                } else {
                                                  
                                                  
                                                                                                        $hidden = false; 
                                                    if (isset($fieldHideOverrides[$key]) && is_array($fieldHideOverrides[$key])){
                                                      if (in_array($subkey, $fieldHideOverrides[$key])){
                                                        $hidden = true;

                                                                                                                if (!empty($csvHideList)) $csvHideList .= ',';
                                                        $csvHideList .= $subkey;
                                                      }
                                                    }
                                                  ?><div class="zbs-showhide-field"><label for="zbsshowhide<?php echo $key.'-'.$subkey; ?>"><?php _we('Hide','zerobscrm'); ?>:</label><input id="zbsshowhide<?php echo $key.'-'.$subkey; ?>" type="checkbox" value="1"<?php if ($hidden) echo ' checked="checked"'; ?> /><?php



                                                }

                                          } 
			                    								?></li><?php

			                    											                    								if (!empty($csvSortOrder)) $csvSortOrder .= ',';
			                    								$csvSortOrder .= $subkey;

			                    							}

			                    						}

			                    					?>

			                    				</ul>

			                    			</div>

			                    		</td>
			                    	</tr>
					
			                    </tbody>

			                </table>
			                <input type="hidden" name="zbscrm-<?php echo $key; ?>-sortorder" id="zbscrm-<?php echo $key; ?>-sortorder" value="<?php echo $csvSortOrder; ?>" />
                      <input type="hidden" name="zbscrm-<?php echo $key; ?>-hidelist" id="zbscrm-<?php echo $key; ?>-hidelist" value="<?php echo $csvHideList; ?>" />

			            <?php } ?>

		                <table class="table table-bordered table-striped wtab" style="width:780px;margin:10px;">
		               		<tbody>

		                    	<tr>
		                    		<td colspan="2" class="wmid">
		                    			<button type="button" class="button button-primary button-large" id="zbsSaveFieldSorts"><?php _we('Save Field Sorts','zerobscrm'); ?></button>
		                    		</td>
		                    	</tr>

		                    </tbody>
		                </table>

	            </form>

	            <script type="text/javascript">
	            	var zbsSortableFieldTypes = [<?php $x = 1; foreach ($fieldTypes as $key => $fieldType){ if ($x > 1){ echo ","; } echo "'".$key."'"; $x++; } ?>];

	            	jQuery(document).ready(function(){


						    jQuery( ".zbsSortableFieldList ul" ).sortable();
						    jQuery( ".zbsSortableFieldList ul" ).disableSelection();

						    // bind go button
						    jQuery('#zbsSaveFieldSorts').click(function(){

						    	// compile csv's
						    	jQuery.each(window.zbsSortableFieldTypes,function(ind,ele){

						    		var csvList = '';
                    var csvHideList = '';

						    		// list into csv
						    		jQuery('#zbscrm-' + ele + '-sort li').each(function(ind,ele){

						    			if (csvList.length > 0) csvList += ',';

						    			csvList += jQuery(ele).attr('data-key');

						    			//DEBUG  console.log(ind + " " + jQuery(ele).attr('data-key'));


                      // show hides:

                        // if is present
                        if (jQuery('.zbs-showhide-field input[type=checkbox]',jQuery(ele))){

                            // if is checked
                            if (jQuery('.zbs-showhide-field input[type=checkbox]',jQuery(ele)).prop('checked')){

                                  // log hide
                                  if (csvHideList.length > 0) csvHideList += ',';

                                  csvHideList += jQuery(ele).attr('data-key');

                            }
                        }

						    		});

						    		// add to hidden input
						    		jQuery('#zbscrm-' + ele + '-sortorder').val(csvList);
                    jQuery('#zbscrm-' + ele + '-hidelist').val(csvHideList);
						    		//DEBUG  console.log("set " + '#zbscrm-' + ele + '-sortorder',csvList);


						    	});


						    	setTimeout(function(){
							    	
							    	// submit form
							    	jQuery('#zbsfieldsortform').submit();

							    },0);

						    })

	            	});

	            </script>
	            
   		</div><?php 
}






     function whStyles_html_msg($flag,$msg,$includeExclaim=false){

    zeroBSCRM_html_msg($flag,$msg,$includeExclaim);

  }

    function zeroBSCRM_html_msg($flag,$msg,$includeExclaim=false){
    
      if ($includeExclaim){ $msg = '<div id="sgExclaim">!</div>'.$msg.''; }
      if ($flag == -1){
        echo '<div class="fail wrap whAlert-box">'.$msg.'</div>';
      } 
      if ($flag == 0){
        echo '<div class="success wrap whAlert-box">'.$msg.'</div>';  
      }
      if ($flag == 1){
        echo '<div class="warn wrap whAlert-box">'.$msg.'</div>'; 
      }
        if ($flag == 2){
            echo '<div class="info wrap whAlert-box">'.$msg.'</div>';
        }

      
  }







		define('ZBSCRM_INC_ADMPGS',true);