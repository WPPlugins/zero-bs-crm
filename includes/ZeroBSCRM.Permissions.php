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








	#} Build User Roles
	function zeroBSCRM_addUserRoles(){

			#} ZBS Admin
									$result = add_role( 'zerobs_admin', __w(

				'ZeroBSCRM Admin (Full ZBS Permissions)', 'zerobscrm' ),

				array(

				'read' => true, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);

		    		    $role = get_role( 'zerobs_admin' );

		    		    
		    		    $role->add_cap( 'read' );
		    $role->remove_cap( 'edit_posts' );
		    $role->add_cap( 'admin_zerobs_usr' ); 		    $role->add_cap( 'admin_zerobs_customers' );
		    $role->add_cap( 'admin_zerobs_customers_tags' );
		    $role->add_cap( 'admin_zerobs_quotes' );
		    $role->add_cap( 'admin_zerobs_invoices' );
		    $role->add_cap( 'admin_zerobs_transactions' );
		    $role->add_cap( 'manage_categories' );
		    $role->add_cap( 'manage_sales_dash' ); 		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);




		    		    
		    
		    		    $role = get_role( 'administrator' );

		    		    $role->add_cap( 'admin_zerobs_customers' );
		    $role->add_cap( 'admin_zerobs_customers_tags' );
		    $role->add_cap( 'admin_zerobs_quotes' );
		    $role->add_cap( 'admin_zerobs_invoices' );
		   	$role->add_cap( 'admin_zerobs_transactions' );
		    $role->add_cap( 'manage_sales_dash' );
		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);



		    		    
		    #} ZBS Customer Manager
			$result = add_role( 'zerobs_customermgr', __w(

				'ZeroBSCRM Customer Manager', 'zerobscrm' ),

				array(

				'read' => true, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);

		    		    $role = get_role( 'zerobs_customermgr' );

		    		    $role->add_cap( 'read' );
		    $role->remove_cap( 'edit_posts' );
		    $role->add_cap( 'admin_zerobs_usr' ); 		    $role->add_cap( 'admin_zerobs_customers' );
		    $role->add_cap( 'admin_zerobs_customers_tags' );
		    $role->add_cap( 'manage_categories' );
		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);




		    		    


		    #} ZBS Quote Manager
			$result = add_role( 'zerobs_quotemgr', __w(

				'ZeroBSCRM Quote Manager', 'zerobscrm' ),

				array(

				'read' => true, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);

		    		    $role = get_role( 'zerobs_quotemgr' );

		    		    $role->add_cap( 'read' );
		    $role->remove_cap( 'edit_posts' );
		    $role->add_cap( 'admin_zerobs_usr' ); 		    $role->add_cap( 'admin_zerobs_quotes' );
		    $role->add_cap( 'manage_categories' );
		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);




		    		    
		    #} ZBS Invoice Manager
			$result = add_role( 'zerobs_invoicemgr', __w(

				'ZeroBSCRM Invoice Manager', 'zerobscrm' ),

				array(

				'read' => true, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);

		    		    $role = get_role( 'zerobs_invoicemgr' );

		    		    $role->add_cap( 'read' );
		    $role->remove_cap( 'edit_posts' );
		    $role->add_cap( 'admin_zerobs_usr' ); 		    $role->add_cap( 'admin_zerobs_invoices' );
		    $role->add_cap( 'manage_categories' );
		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);




		    		    
		    #} ZBS Transaction Manager
			$result = add_role( 'zerobs_transactionmgr', __w(

				'ZeroBSCRM Transaction Manager', 'zerobscrm' ),

				array(

				'read' => false, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);

		    		    $role = get_role( 'zerobs_transactionmgr' );

		    		    $role->add_cap( 'read' );
		    $role->remove_cap( 'edit_posts' );
		    $role->add_cap( 'admin_zerobs_usr' ); 		    $role->add_cap( 'admin_zerobs_transactions' );
		    $role->add_cap( 'manage_categories' );
		    $role->add_cap( 'zbs_dash' ); 
		    unset($role);




		    		    

		    #} ZBS Customer
			$result = add_role( 'zerobs_customer', __w(

				'ZeroBSCRM Customer', 'zerobscrm' ),

				array(

				'read' => true, 				'edit_posts' => false, 				'edit_pages' => false, 				'edit_others_posts' => false, 				'create_posts' => false, 				'manage_categories' => false, 				'publish_posts' => false, 
				)

			);


		    		    


	}

	
	










	function zeroBSCRM_permsIsZBSUser(){

				global $zeroBSCRM_isZBSUser;

		if (isset($zeroBSCRM_isZBSUser)) return $zeroBSCRM_isZBSUser;

				$zeroBSCRM_isZBSUser = false;
	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_usr')) $zeroBSCRM_isZBSUser = true;
	    if ($cu->has_cap('zerobs_customer')) $zeroBSCRM_isZBSUser = true;
	    
	    return $zeroBSCRM_isZBSUser;
	}
	function zeroBSCRM_permsIsZBSUserOrAdmin(){

				global $zeroBSCRM_isZBSUser;

		if (isset($zeroBSCRM_isZBSUser)) return $zeroBSCRM_isZBSUser;

				$zeroBSCRM_isZBSUser = false;
	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_usr')) $zeroBSCRM_isZBSUser = true;

	    	    if ($cu->has_cap('manage_options')) $zeroBSCRM_isZBSUser = true;
	    
	    return $zeroBSCRM_isZBSUser;
	}
	function zeroBSCRM_permsCustomers(){

	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_customers')) return true;
	    return false;
	}
	function zeroBSCRM_permsCustomersTags(){

	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_customers_tags')) return true;
	    return false;
	}
	function zeroBSCRM_permsQuotes(){

	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_quotes')) return true;
	    return false;
	}
	function zeroBSCRM_permsInvoices(){

	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_invoices')) return true;
	    return false;
	}
	function zeroBSCRM_permsTransactions(){

	    $cu = wp_get_current_user();
	    if ($cu->has_cap('admin_zerobs_transactions')) return true;
	    return false;
	}

	function zeroBSCRM_permsClient(){   
	    $cu = wp_get_current_user();
	    if ($cu->has_cap('zerobs_customer')) return true;
	    return false;
	}







		define('ZBSCRM_INC_PERMS',true);