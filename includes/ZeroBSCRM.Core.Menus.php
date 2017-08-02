<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V2.0.6
 *
 * Copyright 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 24/05/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;





#} Add le admin menu
function zeroBSCRM_admin_menu() {

	global $zeroBSCRM_slugs, $zeroBSCRM_Settings; 
		$settings = $zeroBSCRM_Settings->getAll();

		$b2bMode = zeroBSCRM_getSetting('companylevelcustomers');

        $zbsMenuMode = $zeroBSCRM_Settings->get('menulayout');
    if (!isset($zbsMenuMode) || !in_array($zbsMenuMode,array(1,2,3))) $zbsMenuMode = 2; 

    	do_action('zerobs_importers');  
		$adminMenuTitle = 'Zero BS CRM';  	if ($zbsMenuMode == ZBS_MENU_SLIM) $adminMenuTitle = 'ZBS CRM Admin';

	$adminPageHome = add_menu_page( 'Zero BS CRM Plugin', $adminMenuTitle, 'manage_options', $zeroBSCRM_slugs['home'], 'zeroBSCRM_pages_home'); 	add_action( "admin_print_styles-{$adminPageHome}", 'zeroBSCRM_global_admin_styles' );

			    $adminMenuWelcome = add_submenu_page( $zeroBSCRM_slugs['home'], 'Welcome to ZBS', 'Welcome to ZBS', 'manage_options', $zeroBSCRM_slugs['home'], 'zeroBSCRM_pages_home' );
    add_action( "admin_print_styles-{$adminMenuWelcome}", 'zeroBSCRM_global_admin_styles' );

              
        $adminMenuStatus = add_submenu_page( $zeroBSCRM_slugs['home'], 'System Status', 'System Status', 'manage_options', $zeroBSCRM_slugs['systemstatus'], 'zeroBSCRM_pages_systemstatus' );
	add_action( "admin_print_styles-{$adminMenuStatus}", 'zeroBSCRM_global_admin_styles' );   
    
        $adminMenuFeedback = add_submenu_page( $zeroBSCRM_slugs['home'], 'Feedback', 'Feedback', 'manage_options', $zeroBSCRM_slugs['feedback'], 'zeroBSCRM_pages_feedback' );
	add_action( "admin_print_styles-{$adminMenuFeedback}", 'zeroBSCRM_global_admin_styles' ); 
 

        $adminMenuSettings = add_submenu_page( $zeroBSCRM_slugs['home'], 'Settings', 'Settings', 'manage_options', $zeroBSCRM_slugs['settings'], 'zeroBSCRM_pages_settings' );
	add_action( "admin_print_styles-{$adminMenuSettings}", 'zeroBSCRM_global_admin_styles' );  
    
        $adminMenuExtend = add_submenu_page( $zeroBSCRM_slugs['home'], 'Extensions', '<span style="color: #FCB214 !important;">Extensions</span>', 'manage_options', $zeroBSCRM_slugs['extensions'], 'zeroBSCRM_pages_extensions' );
	add_action( "admin_print_styles-{$adminMenuExtend}", 'zeroBSCRM_global_admin_styles' ); 


	        		    		$adminMenuTrash = add_submenu_page( 'admin.php?page=zerobscrm-plugin-settings', __w('Trash Msg','zerobscrm'), __w('Trash Msg','zerobscrm'), 'admin_zerobs_customers', 'zbs-deletion', 'zeroBSCRM_pages_postdelete', 1);
		add_action( "admin_print_styles-{$adminMenuTrash}", 'zeroBSCRM_global_admin_styles' ); 
				$adminMenuNoAccess = add_submenu_page( 'admin.php?page=zerobscrm-plugin-settings', __w('No Rights Msg','zerobscrm'), __w('No Rights Msg','zerobscrm'), 'admin_zerobs_customers', 'zbs-noaccess', 'zeroBSCRM_pages_norights', 1);
		add_action( "admin_print_styles-{$adminMenuNoAccess}", 'zeroBSCRM_global_admin_styles' ); 


        	$adminMenuData = add_menu_page( 'Data Tools', 'Data Tools', 'manage_options', $zeroBSCRM_slugs['datatools'], 'zeroBSCRM_pages_datatools', 'dashicons-admin-tools',90); 	add_action( "admin_print_styles-{$adminMenuData}", 'zeroBSCRM_global_admin_styles' ); 


	$adminMenuBulk = add_submenu_page( $zeroBSCRM_slugs['datatools'], 'Bulk Tools', 'Bulk Tools', 'manage_options', $zeroBSCRM_slugs['bulktools'], 'zeroBSCRM_pages_bulktools' );
	add_action( "admin_print_styles-{$adminMenuBulk}", 'zeroBSCRM_global_admin_styles' ); 


	 	
	  	

    	
        do_action('zerobs_extrasubmenu');                           	

    
				$takeoverModeAll = $zeroBSCRM_Settings->get('wptakeovermodeforall');
		$takeoverModeZBS = $zeroBSCRM_Settings->get('wptakeovermode');  
		$takeoverMode = false; if ($takeoverModeAll || (zeroBSCRM_permsIsZBSUser() && $takeoverModeZBS)) $takeoverMode = true;

	    if ($zbsMenuMode == ZBS_MENU_CRMONLY){
    	$takeoverModeAll = true;
    	$takeoverModeZBS = true;
    	$takeoverMode  = true;
    }


	if ($takeoverMode){
					remove_menu_page( 'index.php' );                  		remove_menu_page( 'edit-tags.php?taxonomy=category' );                   		

		if ($takeoverModeAll){

		    remove_menu_page( 'edit-tags.php?taxonomy=category' ); 		    remove_menu_page( 'index.php' );                  		    remove_menu_page( 'edit.php' );                   		    remove_menu_page( 'upload.php' );                 		    remove_menu_page( 'edit.php?post_type=page' );    		    remove_menu_page( 'edit-comments.php' );          		    remove_menu_page( 'themes.php' );                 		    remove_menu_page( 'plugins.php' );                		    remove_menu_page( 'users.php' );                  		    remove_menu_page( 'tools.php' );                  		    remove_menu_page( 'options-general.php' );        
		}

						remove_menu_page('profile.php');

			    	    	    		$adminMenuLogout = add_menu_page( 'Log Out', 'Log Out', 'read', $zeroBSCRM_slugs['logout'], 'zeroBSCRM_pages_logout', 'dashicons-unlock',100);
		add_action( "admin_print_styles-{$adminMenuLogout}", 'zeroBSCRM_global_admin_styles' ); 



	}
   


		$allCustomersPage = remove_submenu_page( 'edit.php?post_type=zerobs_customer', 'edit.php?post_type=zerobs_customer' );

		if (zeroBSCRM_permsCustomers()){

	    	    $adminMenuCompany = add_submenu_page( 'edit.php?post_type=zerobs_customer', __w('Manage '.zeroBSCRM_getContactOrCustomer().'s','zerobscrm'), __w('Manage '.zeroBSCRM_getContactOrCustomer().'s','zerobscrm'), 'admin_zerobs_customers', 'manage-customers', 'zeroBSCRM_render_customerslist_page', 1);
		add_action( "admin_print_styles-{$adminMenuCompany}", 'zeroBSCRM_global_admin_styles' ); 

	   
	    	    	    if (isset($settings['showneedsquote']) && $settings['showneedsquote'] == 1){
	    	$adminNeedsAQ = add_submenu_page( 'edit.php?post_type=zerobs_customer', __w('Needs a Quote','zerobscrm'), __w('Needs a Quote','zerobscrm'), 'manage_options', 'manage-customers-noqj', 'zeroBSCRM_render_customersNoQJlist_page' );
			add_action( "admin_print_styles-{$adminNeedsAQ}", 'zeroBSCRM_global_admin_styles' ); 

	    }
	    
			    if ($b2bMode){
	    	$adminB2B = add_submenu_page( 'edit.php?post_type=zerobs_company', __w('Manage '.zeroBSCRM_getCompanyOrOrgPlural(),'zerobscrm'), __w('Manage '.zeroBSCRM_getCompanyOrOrgPlural(),'zerobscrm'), 'admin_zerobs_customers', 'manage-companies', 'zeroBSCRM_render_companyslist_page', 1);
	    	add_action( "admin_print_styles-{$adminB2B}", 'zeroBSCRM_global_admin_styles' ); 
	    }

				$zbscsearch = add_submenu_page('edit.php?post_type=zerobs_customer', __w('Search','zerobscrm'), __w('Search','zerobscrm'), 'admin_zerobs_customers', 'customer-searching', 'zeroBSCRM_customersearch', 2 );
		add_action( 'admin_print_styles-' . $zbscsearch, 'zbscrm_customer_search_custom_css' );

	    	    	    
	    		    	global $submenu;

	    		    	if (isset($submenu['edit.php?post_type=zerobs_customer'])){

	    			    		$menuItems = array();
	    		foreach ($submenu['edit.php?post_type=zerobs_customer'] as $ind => $menuItem){

	    			if ($menuItem[2] == 'post-new.php?post_type=zerobs_customer') $menuItems['addnew'] = $menuItem;
	    			if ($menuItem[2] == 'edit-tags.php?taxonomy=zerobscrm_customertag&amp;post_type=zerobs_customer') $menuItems['tags'] = $menuItem;
	    			if ($menuItem[2] == 'manage-customers') $menuItems['manage'] = $menuItem;
	    			if ($menuItem[2] == 'manage-customers-noqj') $menuItems['noqj'] = $menuItem;
	    			if ($menuItem[2] == 'customer-searching') $menuItems['custsearch'] = $menuItem;


	    		}

	    			    		$finalArr = array();
	    		if (isset($menuItems['manage'])) $finalArr[] = $menuItems['manage'];
	    		if (isset($menuItems['custsearch'])) $finalArr[] = $menuItems['custsearch'];
	    		if (isset($menuItems['noqj'])) $finalArr[] = $menuItems['noqj'];
	    		if (zeroBSCRM_permsCustomersTags()){ if (isset($menuItems['tags'])) $finalArr[] = $menuItems['tags']; }
	    		if (isset($menuItems['addnew'])) $finalArr[] = $menuItems['addnew'];

	    		
			    $submenu['edit.php?post_type=zerobs_customer'] = $finalArr;

			}

	    		    	if ($b2bMode) if (isset($submenu['edit.php?post_type=zerobs_company'])){

	    			    		$menuItems = array();
	    		foreach ($submenu['edit.php?post_type=zerobs_company'] as $ind => $menuItem){

	    			if ($menuItem[2] == 'post-new.php?post_type=zerobs_company') $menuItems['addnew'] = $menuItem;
	    			if ($menuItem[2] == 'edit-tags.php?taxonomy=zerobscrm_companytag&amp;post_type=zerobs_company') $menuItems['tags'] = $menuItem;
	    			if ($menuItem[2] == 'manage-companies') $menuItems['manage'] = $menuItem;


	    		}

	    			    		$finalArr = array();
	    		if (isset($menuItems['manage'])) $finalArr[] = $menuItems['manage'];
	    		if (zeroBSCRM_permsCustomersTags()){ if (isset($menuItems['tags'])) $finalArr[] = $menuItems['tags']; }
	    		if (isset($menuItems['addnew'])) $finalArr[] = $menuItems['addnew'];

			    $submenu['edit.php?post_type=zerobs_company'] = $finalArr;

			}

	}



    	$allQuotesPage = remove_submenu_page( 'edit.php?post_type=zerobs_quote', 'edit.php?post_type=zerobs_quote' );

		if (zeroBSCRM_permsQuotes()){

	    	    $adminManageQ = add_submenu_page( 'edit.php?post_type=zerobs_quote', __w('Manage Quotes','zerobscrm'), __w('Manage Quotes','zerobscrm'), 'admin_zerobs_quotes', 'manage-quotes', 'zeroBSCRM_render_quoteslist_page' );
	    
	    add_action( "admin_print_styles-{$adminManageQ}", 'zeroBSCRM_global_admin_styles' ); 

	    	    	    
	    		    	global $submenu;

	    	

	    	if (isset($submenu['edit.php?post_type=zerobs_quote'])){
		    	
		    				    $arr = array();
			    if (isset($submenu['edit.php?post_type=zerobs_quote'][11])) $arr[] = $submenu['edit.php?post_type=zerobs_quote'][11]; 			    if (isset($submenu['edit.php?post_type=zerobs_quote'][10])) $arr[] = $submenu['edit.php?post_type=zerobs_quote'][10]; 			    			    $submenu['edit.php?post_type=zerobs_quote'] = $arr;		


												$useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

				if ($useQuoteBuilder == "1"){

				    				    $adminMenuQuoteTemplates = add_submenu_page( 'edit.php?post_type=zerobs_quote', __w('Quote Templates','zerobscrm'), __w('Quote Templates','zerobscrm'), 'admin_zerobs_quotes', 'manage-quote-templates', 'zeroBSCRM_render_quotetemplateslist_page', 1);
					add_action( "admin_print_styles-{$adminMenuQuoteTemplates}", 'zeroBSCRM_global_admin_styles' );

				}

			} 
	}


    	$allInvoicesPage = remove_submenu_page( 'edit.php?post_type=zerobs_invoice', 'edit.php?post_type=zerobs_invoice' );

		if (zeroBSCRM_permsInvoices()){

	    	    $adminManageI = add_submenu_page( 'edit.php?post_type=zerobs_invoice', __w('Manage Invoices','zerobscrm'), __w('Manage Invoices','zerobscrm'), 'admin_zerobs_invoices', 'manage-invoices', 'zeroBSCRM_render_invoiceslist_page' );
		add_action( "admin_print_styles-{$adminManageI}", 'zeroBSCRM_global_admin_styles' );

	    
	    	    	    
	    		    	global $submenu;

	    		    	if (isset($submenu['edit.php?post_type=zerobs_invoice'])){

		    				    $arr = array();
			    if (isset($submenu['edit.php?post_type=zerobs_invoice'][11])) $arr[] = $submenu['edit.php?post_type=zerobs_invoice'][11]; 			    if (isset($submenu['edit.php?post_type=zerobs_invoice'][10])) $arr[] = $submenu['edit.php?post_type=zerobs_invoice'][10]; 			    			    $submenu['edit.php?post_type=zerobs_invoice'] = $arr;

			}

	}


    	$allTransactionsPage = remove_submenu_page( 'edit.php?post_type=zerobs_transaction', 'edit.php?post_type=zerobs_transaction' );

		if (zeroBSCRM_permsTransactions()){

	    	    $adminManageT = add_submenu_page( 'edit.php?post_type=zerobs_transaction', __w('Manage Transactions','zerobscrm'), __w('Manage Transactions','zerobscrm'), 'admin_zerobs_transactions', 'manage-transactions', 'zeroBSCRM_render_transactionslist_page' );
	    add_action( "admin_print_styles-{$adminManageT}", 'zeroBSCRM_global_admin_styles' );
	    	    	    
	    		    	global $submenu;

	    		    	if (isset($submenu['edit.php?post_type=zerobs_transaction'])){

		    				    $arr = array();
			    			    			    $zbsFirstMenuItem = false;$zbsSecondMenuItem = false;$zbsThirdMenuItem = false;
			    foreach ($submenu['edit.php?post_type=zerobs_transaction'] as $key => $menu){

			    	switch ($menu[0]){

			    		case 'Manage Transactions':
			    			$zbsFirstMenuItem = $menu;
			    			break;
			    		case 'Transaction Tags':
			    			$zbsSecondMenuItem = $menu;
			    			break;
			    		case 'Add New':
			    			$zbsThirdMenuItem = $menu;
			    			break;

			    	}
			    }
			    $arr = array($zbsFirstMenuItem,$zbsSecondMenuItem,$zbsThirdMenuItem);

			    			    $submenu['edit.php?post_type=zerobs_transaction'] = $arr;

			}

	}

    if ($zbsMenuMode == ZBS_MENU_SLIM){
    	    	remove_menu_page('edit.php?post_type=zerobs_customer');        	remove_menu_page('edit.php?post_type=zerobs_transaction'); 	    	remove_menu_page('edit.php?post_type=zerobs_quote');     	remove_menu_page('edit.php?post_type=zerobs_invoice');     	$removedFormsMenu = remove_menu_page('edit.php?post_type=zerobs_form');     	remove_menu_page('edit.php?post_type=zerobs_company'); 
    	    	    	$removedDataTools = remove_menu_page('zerobscrm-datatools');  


    	


    		    		global $submenu;
    		if (isset($removedDataTools) && isset($submenu['zerobscrm-plugin'])){

    			    			unset($removedDataTools[4],$removedDataTools[5],$removedDataTools[6]);


    			    			$menuArr = $submenu['zerobscrm-plugin'];

    			    			$replacementMenu = array();
    			foreach ($menuArr as $menuItem){

    				    				if ($menuItem[2] == 'zerobscrm-systemstatus') $replacementMenu[] = $removedDataTools;

    				    				$replacementMenu[] = $menuItem;
    			}

    			    			$submenu['zerobscrm-plugin'] = $replacementMenu;

    		}
	    	

    	    	remove_menu_page('edit.php?post_type=zerobs_mailcampaign');     	remove_menu_page('sales-dash'); 

    			$adminUserD = add_menu_page( 'Zero BS CRM User Dash', 'Zero BS CRM', 'zbs_dash', $zeroBSCRM_slugs['dash'], 'zeroBSCRM_pages_dash','dashicons-groups',2);
		add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );

	    	    if ($b2bMode){
	    	$adminUserD = add_submenu_page( $zeroBSCRM_slugs['dash'], __w(''.zeroBSCRM_getCompanyOrOrgPlural(),'zerobscrm'), __w(''.zeroBSCRM_getCompanyOrOrgPlural(),'zerobscrm'), 'admin_zerobs_customers', 'manage-companies-crm', 'zeroBSCRM_render_companyslist_page');
	    	add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );

	    }
	    $adminUserD = add_submenu_page( $zeroBSCRM_slugs['dash'], __w(''.zeroBSCRM_getContactOrCustomer().'s','zerobscrm'), __w(''.zeroBSCRM_getContactOrCustomer().'s','zerobscrm'), 'admin_zerobs_customers', 'manage-customers-crm', 'zeroBSCRM_render_customerslist_page', 1);
	    add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );


		$ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
		if($ZBSuseQuotes == "1"){
		    $adminUserD = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Quotes','zerobscrm'), __w('Quotes','zerobscrm'), 'admin_zerobs_quotes', 'manage-quotes-crm', 'zeroBSCRM_render_quoteslist_page' );
		    add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );
		}

		$ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');
		if($ZBSuseInvoices == "1"){
		    $adminUserD = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Invoices','zerobscrm'), __w('Invoices','zerobscrm'), 'admin_zerobs_invoices', 'manage-invoices-crm', 'zeroBSCRM_render_invoiceslist_page' );
			add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );
		}

			    $adminUserD = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Transactions','zerobscrm'), __w('Transactions','zerobscrm'), 'admin_zerobs_transactions', 'manage-transactions-crm', 'zeroBSCRM_render_transactionslist_page' );
	    add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );


	    	    
	    $adminAdvancedSearch = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Advanced Search','zerobscrm'), __w('Advanced Search','zerobscrm'), 'admin_zerobs_customers', 'advancedy-search-crm', 'zeroBSCRM_advancedSearch' );
	    add_action( "admin_print_styles-{$adminAdvancedSearch}", 'zeroBSCRM_global_admin_styles' );

		

	    
	    		    	global $submenu;


	    		    	if (zeroBSCRM_isExtensionInstalled('forms') && isset($submenu[$zeroBSCRM_slugs['dash']])){

	    			    			    			    		$submenu[$zeroBSCRM_slugs['dash']][] = array(__w('Forms','zerobscrm'),'edit_pages','edit.php?post_type=zerobs_form',__w('Forms','zerobscrm'));

	    	}

    }

    if ($zbsMenuMode == ZBS_MENU_FULL){
    	
    			$adminUserD = add_menu_page( 'Zero BS CRM User Dash', 'Zero BS CRM', 'zbs_dash', $zeroBSCRM_slugs['dash'], 'zeroBSCRM_pages_dash','dashicons-groups',2);
	    add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );

	    $adminAdvancedSearch = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Advanced Search','zerobscrm'), __w('Advanced Search','zerobscrm'), 'admin_zerobs_customers', 'advancedy-search-crm', 'zeroBSCRM_advancedSearch' );
	    add_action( "admin_print_styles-{$adminAdvancedSearch}", 'zeroBSCRM_global_admin_styles' );

    }

    if ($zbsMenuMode == ZBS_MENU_CRMONLY){
    	
    			$adminUserD = add_menu_page( 'Zero BS CRM User Dash', 'Zero BS CRM', 'zbs_dash', $zeroBSCRM_slugs['dash'], 'zeroBSCRM_pages_dash','dashicons-groups',2);
    	add_action( "admin_print_styles-{$adminUserD}", 'zeroBSCRM_global_admin_styles' );

	    $adminAdvancedSearch = add_submenu_page( $zeroBSCRM_slugs['dash'], __w('Advanced Search','zerobscrm'), __w('Advanced Search','zerobscrm'), 'admin_zerobs_customers', 'advancedy-search-crm', 'zeroBSCRM_advancedSearch' );
	    add_action( "admin_print_styles-{$adminAdvancedSearch}", 'zeroBSCRM_global_admin_styles' );
	    
    }

	

	#} Add daterangepicker to "add quote" and "add invoice" pages:
    add_action( 'load-post.php', 'zeroBSCRM_load_libs_js_momentdatepicker' );
        add_action( 'load-post-new.php', 'zeroBSCRM_load_libs_js_momentdatepicker' );
        add_action( 'load-post-new.php', 'zeroBSCRM_load_libs_js_momentdatepicker' );

		
		


}







define('ZBSCRMMENUINC',true);