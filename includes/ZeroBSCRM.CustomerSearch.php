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


   	function zbscrm_customer_search_custom_css(){
		wp_enqueue_style( 'customer-search-bs', ZEROBSCRM_URL.'css/bootstrap.min.css' );
		wp_enqueue_style( 'customer-search', ZEROBSCRM_URL.'css/ZeroBSCRM.admin.customersearch.min.css', 'customer-search-bs' );
	}

   function zeroBSCRM_customersearch() {


   	   	   	$page = 0;
   	$perPage = 25;
   	$withInvoices = false;
   	$withQuotes = false;
   	$searchPhrase='';
   	$withTransactions = false;
   	$argsOverride = false;
   	$companyID = false;

   	   	
   	$zbs_customer_total = zeroBS_getCustomerCount();
   	$zbs_pages = ceil($zbs_customer_total / $perPage);

   	$zbs_customer_total_global = $zbs_customer_total;


   	$tagID ='';
   	
   	if(isset($_GET['zbs_tag']) && !empty($_GET['zbs_tag'])) $tagID = (int)$_GET['zbs_tag'];
   	if(isset($_GET['zbs_page']) && !empty($_GET['zbs_page'])) $page = (int)$_GET['zbs_page'];

   	if(isset($_POST['cust_search']) && !empty($_POST['cust_search'])) $searchPhrase = zeroBSCRM_textProcess($_POST['cust_search']);

   	$search_customers = zeroBS_getCustomers(true,$perPage,$page,$withInvoices,$withQuotes,$searchPhrase,$withTransactions,$argsOverride,$companyID,$tagID);
   	if(count($search_customers) > 0){
   		if(!empty($search_customers[0]['filterTot'])) $zbs_customer_total = $search_customers[0]['filterTot'];
		if($search_customers[0]['filterPages'] >= 0) $zbs_pages = $search_customers[0]['filterPages'];
	}else{
		$zbs_customer_total = 0;
	}
   	?>
   	<script type="text/javascript">
   	jQuery(document).ready(function(){
	   	jQuery('#check_all').click(function(e){
	    	var table= jQuery('#customer_results');
	    	jQuery('td input:checkbox',table).prop('checked',this.checked);
		});

		jQuery('#export-idsxx').click(function(e){
			e.preventDefault();
			zbscrm_JS_check_checkboxes();
		});
	});

	 function zbscrm_JS_check_checkboxes() {         
	     var allVals = [];
	     jQuery('#checks :checked').each(function() {
	       allVals.push(jQuery(this).val());
	     });
	     if(allVals != ''){
	     	console.log('all OK');
	     	jQuery('#export-customers-form').submit();
	     }else{
	     	alert('Please select some customers');
	     	return false;
	     }

	}

   	</script>
   	<?php if(wp_is_mobile()){ ?>
   		<style>
   			.col-md-8{
   				width: 100%;
   			}
   			.zbs-s-sidebar{
			    top: initial !important;
			    bottom: initial !important;
			    background-color: #f9f9f9;
			    border: 1px solid #c6c6c6;
			    border-right: 1px solid #c6c6c6;
			    margin-top: 50px;
			    margin-bottom: 50px;
			    border-radius: 5px;
			    box-shadow: 0px 1px 4px rgba(0,0,0,0.3);
			    width:100%;
   			}
   		</style>
   	<?php } ?>
   		<div class='container'>
   				<div class='row customer-list'>
   					<div class='col-md-8 header-pad'>
   						<h4 class='customer-header'><?php _we(zeroBSCRM_getContactOrCustomer().'s','zerbscrm'); ?></h4>
   						<div class='actions'>
   							<?php $val = __w(" Export","zerobscrm"); ?>
   							<form method="post" id="export-customers-form" action="">
							<input type="submit" id="export-ids" name="download_cust_csv_filter" class="button-primary" value="<?php echo $val;?>" />
   						</div>
   						<table id="customer_results" class='table'>
   							<thead>
	   							<th class='cb'><input type="checkbox" id="check_all" name="check_all" value="all"/></th>
	   							<th><?php echo zeroBSCRM_getContactOrCustomer(); ?></th>
	   							<th><?php _we('Date Added','zerobscrm'); ?></th>
	   							<th><?php _we('Status','zerobscrm'); ?></th>
   							</thead>
   							<tbody id='checks'>
	   							<?php
	   									   								foreach($search_customers as $cust){
	   									$d = new DateTime($cust['created']);
	   									$formatted_date = $d->format(zeroBSCRM_getDateFormat());
	   									echo '<tr>';
	   									echo '<td class="cb"><input type="checkbox" name="cid[]" value="'.$cust['id'].'"/></td>';
	   									echo '<td><a href="post.php?post='.$cust['id'].'&action=edit">' . $cust['name'] . '</a></td>';
	   									echo '<td>' . $formatted_date . '</td>';
	   									echo '<td>' . $cust['meta']['status'] . '</td>';
	   									echo '</tr>';
	   								}
	   							?>
   							</tbody>
   							</form>
   						</table>

   						<?php
   						

						if($zbs_customer_total > 0){
					    $zbs_pagination = array(
					        'range'           => 4,
					        'count' 		  => $zbs_pages,
					        'page'			  => $page
					    );

						zeroBSCRM_pagination($zbs_pagination);
						}

						?>
		

						<div class='zbs_total pull-right'><h4><?php _we('Total: ','zerobscrm');?><?php echo number_format($zbs_customer_total,0); ?></h4></div>


   					</div>
   					<div class='col-md-4 zbs-s-sidebar'>
   						<div class='section'>
	   						<div class='search-by'>
	   							<form action="#" method="POST">
	   								<?php
	   								if(!empty($searchPhrase)){
	   								$zbs_placeholder = $searchPhrase;
	   								}else{
	   									$zbs_placeholder = 'Search ' . strtolower(zeroBSCRM_getContactOrCustomer()) . 's';
	   								}	
	   								?>
	   								<input class='form-control' id='cust_search' name='cust_search' type='text' placeholder='<?php echo $zbs_placeholder;?>'/>
	   								<input type="submit" class='button-primary form-control zbs-submit' value="Search <?php echo zeroBSCRM_getContactOrCustomer(); ?>s" />
	   							</form>
	   						</div>
   						</div>
   						<div class='section'>

   								<div class='import-buttons'>
   									<a href="<?php echo get_admin_url('','admin.php?page=zerobscrm-datatools');?>" target="_blank"><div class='button import-customers'><?php _we("Import " .zeroBSCRM_getContactOrCustomer() ."s", "zerobscrm"); ?></div></a>
   									
   									<a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_customer');?>" target="_blank"><div class='button add-new-customer'>+</div></a>
   								</div>

   						</div>
   						<div class='mid-banner'>
   							<?php
							$zbstermc = sprintf( _n( '%s '.zeroBSCRM_getContactOrCustomer(), '%s '.zeroBSCRM_getContactOrCustomer().'s', $zbs_customer_total_global, 'zerobscrm' ), $zbs_customer_total_global);

   							?>
   							<a href='<?php echo get_admin_url('','edit.php?post_type=zerobs_customer&page=customer-searching');?>'><span class='lead'><?php _we('All ' . zeroBSCRM_getContactOrCustomer() . 's');?></span><span class='sub-count'><?php echo $zbstermc; ?></span></a>
   						</div>
   						<div class='section'>
   							<h5><i class='fa fa-tags'></i> <?php _we("Tags","zerobscrm");?></h5>
   							<ul id='customer-search-tags'>
   							<?php
   								$terms = get_terms( 'zerobscrm_customertag', array(
								    'hide_empty' => false,
								) );
																foreach($terms as $term){
									$zbsurl = get_admin_url('','edit.php?post_type=zerobs_customer&page=customer-searching') ."&zbs_tag=".$term->term_id;
									$zbstermc = sprintf( _n( '%s '.zeroBSCRM_getContactOrCustomer(), '%s '.zeroBSCRM_getContactOrCustomer().'s', $term->count, 'zerobscrm' ), $term->count);

									echo "<li><a href='".$zbsurl."'>". $term->name . "<span class='sub-count'>" .$zbstermc. "</span></a></li>";
								}
   							?>
   							<li><a class='under' href='<?php echo get_admin_url('','edit-tags.php?taxonomy=zerobscrm_customertag&post_type=zerobs_customer');?>'><?php _we("+ Create a Tag","zerobscrm"); ?></a></li>
   							</ul>
   						</div>
   					</div>
   					<div class='clear'></div>
   				</div>
   		</div>
   		<?php
   }

		define('ZBSCRM_INC_CUSTOMER_SEARCH',true);