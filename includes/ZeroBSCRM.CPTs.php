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


global $zbsCustomPostTypesToHide; $zbsCustomPostTypesToHide = array('zerobs_customer','zerobs_company','zerobs_invoice','zerobs_transaction','zerobs_form','zerobs_mailcampaign');

global $zbsCustomPostTypes; $zbsCustomPostTypes = array('zerobs_customer','zerobs_company','zerobs_invoice','zerobs_quote','zerobs_transaction','zerobs_form','zerobs_mailcampaign'); 




#} Setup Custom Post Types
function zeroBSCRM_setupPostTypes() {

		$b2bMode = zeroBSCRM_getSetting('companylevelcustomers');

	$ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
	$ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');
	$ZBSuseForms = zeroBSCRM_getSetting('feat_forms');

	if (!$b2bMode){

		

		$labels = array(
			'name'                       => _wx( 'Customer Tags', 'Customer Tags', 'zerobscrm' ),
			'singular_name'              => _wx( 'Customer Tag', 'Customer Tag', 'zerobscrm' ),
			'menu_name'                  => __w( 'Customer Tags', 'zerobscrm' ),
			'all_items'                  => __w( 'All Tags', 'zerobscrm' ),
			'parent_item'                => __w( 'Parent Tag', 'zerobscrm' ),
			'parent_item_colon'          => __w( 'Parent Tag:', 'zerobscrm' ),
			'new_item_name'              => __w( 'New Tag Name', 'zerobscrm' ),
			'add_new_item'               => __w( 'Add Tag Item', 'zerobscrm' ),
			'edit_item'                  => __w( 'Edit Tag', 'zerobscrm' ),
			'update_item'                => __w( 'Tag Item', 'zerobscrm' ),
			'view_item'                  => __w( 'View Tag', 'zerobscrm' ),
			'separate_items_with_commas' => __w( 'Separate Tags with commas', 'zerobscrm' ),
			'add_or_remove_items'        => __w( 'Add or remove Tags', 'zerobscrm' ),
			'choose_from_most_used'      => __w( 'Choose from the most used', 'zerobscrm' ),
			'popular_items'              => __w( 'Popular Tags', 'zerobscrm' ),
			'search_items'               => __w( 'Search Tags', 'zerobscrm' ),
			'not_found'                  => __w( 'Not Found', 'zerobscrm' ),
			'no_terms'                   => __w( 'No Tags', 'zerobscrm' ),
			'items_list'                 => __w( 'Tags list', 'zerobscrm' ),
			'items_list_navigation'      => __w( 'Tags list navigation', 'zerobscrm' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		    'capabilities' => array(
		      'manage_terms'=> 'manage_categories',
		      'edit_terms'=> 'manage_categories',
		      'delete_terms'=> 'manage_categories',
		      'assign_terms' => 'read'
		    )
		);
		register_taxonomy( 'zerobscrm_customertag', array( 'zerobscrm_customertag' ), $args );


		$labels = array(
			'name'                  => _wx( 'Customers', 'Customers', 'zerobscrm' ),
			'singular_name'         => _wx( 'Customer', 'Customer', 'zerobscrm' ),
			'menu_name'             => __w( 'Customers', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Customer', 'zerobscrm' ),
			'archives'              => __w( 'Customer Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Company:', 'zerobscrm' ),
			'parent'    			 => __w( 'Company', 'zerobscrm' ),
			'all_items'             => __w( 'All Customers', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Customer', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Customer', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Customer', 'zerobscrm' ),
			'update_item'           => __w( 'Update Customer', 'zerobscrm' ),
			'view_item'             => __w( 'View Customer', 'zerobscrm' ),
			'search_items'          => __w( 'Search Customer', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Customer Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Customer image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Customer image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Customer image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Customer', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Customer', 'zerobscrm' ),
			'items_list'            => __w( 'Customers list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Customers list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Customers list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( 'Customer', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Customer', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array(  'thumbnail', 'taxonomies'), 						'hierarchical'          => false, 			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.2",
			'menu_icon'             => 'dashicons-admin-users',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,		
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
			'taxonomies' 			=> array('zerobscrm_customertag'),
		    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_customers',
		        'edit_posts' => 'admin_zerobs_customers',
		        'edit_others_posts' => 'admin_zerobs_customers',
		        'publish_posts' => 'admin_zerobs_customers',
		        'read_post' => 'admin_zerobs_customers',
		        'read_private_posts' => 'admin_zerobs_customers',
		        'delete_post' => 'admin_zerobs_customers'
		    )
		);
		register_post_type( 'zerobs_customer', $args );


	} else {

						


		$labels = array(
			'name'                       => _wx( 'Contact Tags', 'Contact Tags', 'zerobscrm' ),
			'singular_name'              => _wx( 'Contact Tag', 'Contact Tag', 'zerobscrm' ),
			'menu_name'                  => __w( ' Contact Tags', 'zerobscrm' ),
			'all_items'                  => __w( 'All Tags', 'zerobscrm' ),
			'parent_item'                => __w( 'Parent Tag', 'zerobscrm' ),
			'parent_item_colon'          => __w( 'Parent Tag:', 'zerobscrm' ),
			'new_item_name'              => __w( 'New Tag Name', 'zerobscrm' ),
			'add_new_item'               => __w( 'Add Tag Item', 'zerobscrm' ),
			'edit_item'                  => __w( 'Edit Tag', 'zerobscrm' ),
			'update_item'                => __w( 'Tag Item', 'zerobscrm' ),
			'view_item'                  => __w( 'View Tag', 'zerobscrm' ),
			'separate_items_with_commas' => __w( 'Separate Tags with commas', 'zerobscrm' ),
			'add_or_remove_items'        => __w( 'Add or remove Tags', 'zerobscrm' ),
			'choose_from_most_used'      => __w( 'Choose from the most used', 'zerobscrm' ),
			'popular_items'              => __w( 'Popular Tags', 'zerobscrm' ),
			'search_items'               => __w( 'Search Tags', 'zerobscrm' ),
			'not_found'                  => __w( 'Not Found', 'zerobscrm' ),
			'no_terms'                   => __w( 'No Tags', 'zerobscrm' ),
			'items_list'                 => __w( 'Tags list', 'zerobscrm' ),
			'items_list_navigation'      => __w( 'Tags list navigation', 'zerobscrm' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		    'capabilities' => array(
		      'manage_terms'=> 'manage_categories',
		      'edit_terms'=> 'manage_categories',
		      'delete_terms'=> 'manage_categories',
		      'assign_terms' => 'read'
		    )
		);
		register_taxonomy( 'zerobscrm_customertag', array( 'zerobscrm_customertag' ), $args );


		$labels = array(
			'name'                  => _wx( 'Contacts', 'Contacts', 'zerobscrm' ),
			'singular_name'         => _wx( 'Contact', 'Contact', 'zerobscrm' ),
			'menu_name'             => __w( 'Contacts', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Contact', 'zerobscrm' ),
			'archives'              => __w( 'Contact Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Company:', 'zerobscrm' ),
			'parent'    			 => __w( 'Company', 'zerobscrm' ),
			'all_items'             => __w( 'All Contacts', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Contact', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Contact', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Contact', 'zerobscrm' ),
			'update_item'           => __w( 'Update Contact', 'zerobscrm' ),
			'view_item'             => __w( 'View Contact', 'zerobscrm' ),
			'search_items'          => __w( 'Search Contact', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Contact Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Contact image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Contact image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Contact image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Contact', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Contact', 'zerobscrm' ),
			'items_list'            => __w( 'Contacts list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Contacts list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Contacts list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( 'Contact', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Contact', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array(  'thumbnail', 'taxonomies'), 			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false, 			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.2",
			'menu_icon'             => 'dashicons-admin-users',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,	
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
			'taxonomies' 			=> array('zerobscrm_customertag'),
		    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_customers',
		        'edit_posts' => 'admin_zerobs_customers',
		        'edit_others_posts' => 'admin_zerobs_customers',
		        'publish_posts' => 'admin_zerobs_customers',
		        'read_post' => 'admin_zerobs_customers',
		        'read_private_posts' => 'admin_zerobs_customers',
		        'delete_post' => 'admin_zerobs_customers'
		    )
		);
		register_post_type( 'zerobs_customer', $args );



						$companyOrOrg = zeroBSCRM_getSetting('coororg');
		$body = zeroBSCRM_getCompanyOrOrg();
		$bodyPlural = zeroBSCRM_getCompanyOrOrgPlural();

		$labels = array(
			'name'                       => _wx( $body.' Tags', $body.' Tags', 'zerobscrm' ),
			'singular_name'              => _wx( $body.' Tag', $body.' Tag', 'zerobscrm' ),
			'menu_name'                  => __w( $body.' Tags', 'zerobscrm' ),
			'all_items'                  => __w( 'All Tags', 'zerobscrm' ),
			'parent_item'                => __w( 'Parent Tag', 'zerobscrm' ),
			'parent_item_colon'          => __w( 'Parent Tag:', 'zerobscrm' ),
			'new_item_name'              => __w( 'New Tag Name', 'zerobscrm' ),
			'add_new_item'               => __w( 'Add Tag Item', 'zerobscrm' ),
			'edit_item'                  => __w( 'Edit Tag', 'zerobscrm' ),
			'update_item'                => __w( 'Tag Item', 'zerobscrm' ),
			'view_item'                  => __w( 'View Tag', 'zerobscrm' ),
			'separate_items_with_commas' => __w( 'Separate Tags with commas', 'zerobscrm' ),
			'add_or_remove_items'        => __w( 'Add or remove Tags', 'zerobscrm' ),
			'choose_from_most_used'      => __w( 'Choose from the most used', 'zerobscrm' ),
			'popular_items'              => __w( 'Popular Tags', 'zerobscrm' ),
			'search_items'               => __w( 'Search Tags', 'zerobscrm' ),
			'not_found'                  => __w( 'Not Found', 'zerobscrm' ),
			'no_terms'                   => __w( 'No Tags', 'zerobscrm' ),
			'items_list'                 => __w( 'Tags list', 'zerobscrm' ),
			'items_list_navigation'      => __w( 'Tags list navigation', 'zerobscrm' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		    'capabilities' => array(
		      'manage_terms'=> 'manage_categories',
		      'edit_terms'=> 'manage_categories',
		      'delete_terms'=> 'manage_categories',
		      'assign_terms' => 'read'
		    )
		);
		register_taxonomy( 'zerobscrm_companytag', array( 'zerobscrm_companytag' ), $args );


		$labels = array(
			'name'                  => _wx( $bodyPlural, $bodyPlural, 'zerobscrm' ),
			'singular_name'         => _wx( $body.'', $body.'', 'zerobscrm' ),
			'menu_name'             => __w( $bodyPlural, 'zerobscrm' ),
			'name_admin_bar'        => __w( $body, 'zerobscrm' ),
			'archives'              => __w( $body.' Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( $body.':', 'zerobscrm' ),
			'parent'    			 => __w( $body.'', 'zerobscrm' ),
			'all_items'             => __w( 'All '.$bodyPlural, 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New '.$body.'', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New '.$body.'', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit '.$body.'', 'zerobscrm' ),
			'update_item'           => __w( 'Update '.$body.'', 'zerobscrm' ),
			'view_item'             => __w( 'View '.$body.'', 'zerobscrm' ),
			'search_items'          => __w( 'Search '.$body.'', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( $body.' Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set '.$body.' image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove '.$body.' image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as '.$body.' image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into '.$body.'', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this '.$body.'', 'zerobscrm' ),
			'items_list'            => __w( $bodyPlural.' list', 'zerobscrm' ),
			'items_list_navigation' => __w( $bodyPlural.' list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter '.$bodyPlural.' list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( $body.'', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS '.$body.'', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array(  'thumbnail', 'taxonomies'), 			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false, 			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.1",
			'menu_icon'             => 'dashicons-store',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,	
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
			'taxonomies' 			=> array('zerobscrm_companytag'),
		    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_customers',
		        'edit_posts' => 'admin_zerobs_customers',
		        'edit_others_posts' => 'admin_zerobs_customers',
		        'publish_posts' => 'admin_zerobs_customers',
		        'read_post' => 'admin_zerobs_customers',
		        'read_private_posts' => 'admin_zerobs_customers',
		        'delete_post' => 'admin_zerobs_customers'
		    )
		);
		register_post_type( 'zerobs_company', $args );





	}

	

	if($ZBSuseQuotes == "1"){

	    	    $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

		$labels = array(
			'name'                  => _wx( 'Quotes', 'Quotes', 'zerobscrm' ),
			'singular_name'         => _wx( 'Quote', 'Quote', 'zerobscrm' ),
			'menu_name'             => __w( 'Quotes', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Quote', 'zerobscrm' ),
			'archives'              => __w( 'Quote Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Parent Quote:', 'zerobscrm' ),
			'all_items'             => __w( 'All Quotes', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Quote', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Quote', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Quote', 'zerobscrm' ),
			'update_item'           => __w( 'Update Quote', 'zerobscrm' ),
			'view_item'             => __w( 'View Quote', 'zerobscrm' ),
			'search_items'          => __w( 'Search Quote', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Quote Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Quote image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Quote image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Quote image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Quote', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Quote', 'zerobscrm' ),
			'items_list'            => __w( 'Quotes list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Quotes list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Quotes list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( 'Quote', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Quote', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array( 'taxonomies' ), 			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.3",
			'menu_icon'             => 'dashicons-clipboard',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,		
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
					    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_quotes',
		        'edit_posts' => 'admin_zerobs_quotes',
		        'edit_others_posts' => 'admin_zerobs_quotes',
		        'publish_posts' => 'admin_zerobs_quotes',
		        'read_post' => 'admin_zerobs_quotes',
		        'read_private_posts' => 'admin_zerobs_quotes',
		        'delete_post' => 'admin_zerobs_quotes'
		    ),

		);

				
		 
		
		if ($useQuoteBuilder == "1") {


						$args['publicly_queryable'] = true;

						$args['rewrite'] = array(
					'slug' => 'proposal',
					'with_front' => true
					);


		}
				

		register_post_type( 'zerobs_quote', $args );


				if ($useQuoteBuilder == "1") {

			$labels = array(
				'name'                  => _wx( 'Quote Templates', 'Quote Templates', 'zerobscrm' ),
				'singular_name'         => _wx( 'Quote Template', 'Quote Template', 'zerobscrm' ),
				'menu_name'             => __w( 'Quote Templates', 'zerobscrm' ),
				'name_admin_bar'        => __w( 'Quote Template', 'zerobscrm' ),
				'archives'              => __w( 'Quote Template Archives', 'zerobscrm' ),
				'parent_item_colon'     => __w( 'Parent Quote Template:', 'zerobscrm' ),
				'all_items'             => __w( 'All Quote Templates', 'zerobscrm' ),
				'add_new_item'          => __w( 'Add New Quote Template', 'zerobscrm' ),
				'add_new'               => __w( 'Add New', 'zerobscrm' ),
				'new_item'              => __w( 'New Quote Template', 'zerobscrm' ),
				'edit_item'             => __w( 'Edit Quote Template', 'zerobscrm' ),
				'update_item'           => __w( 'Update Quote Template', 'zerobscrm' ),
				'view_item'             => __w( 'View Quote Template', 'zerobscrm' ),
				'search_items'          => __w( 'Search Quote Template', 'zerobscrm' ),
				'not_found'             => __w( 'Not found', 'zerobscrm' ),
				'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
				'featured_image'        => __w( 'Quote Template Image', 'zerobscrm' ),
				'set_featured_image'    => __w( 'Set Quote Template image', 'zerobscrm' ),
				'remove_featured_image' => __w( 'Remove Quote Template image', 'zerobscrm' ),
				'use_featured_image'    => __w( 'Use as Quote Template image', 'zerobscrm' ),
				'insert_into_item'      => __w( 'Insert into Quote Template', 'zerobscrm' ),
				'uploaded_to_this_item' => __w( 'Uploaded to this Quote Template', 'zerobscrm' ),
				'items_list'            => __w( 'Quote Templates list', 'zerobscrm' ),
				'items_list_navigation' => __w( 'Quote Templates list navigation', 'zerobscrm' ),
				'filter_items_list'     => __w( 'Filter Quote Templates list', 'zerobscrm' ),
			);
			$args = array(
				'label'                 => __w( 'Quote Template', 'zerobscrm' ),
				'description'           => __w( 'Zero-BS Quote Template', 'zerobscrm' ),
				'labels'                => $labels,
				'supports'              => array('taxonomies','editor','title'),
				'taxonomies'            => array( ),
				'hierarchical'          => false,
								'public'                => false,
				'show_ui'               => true,
				'show_in_menu'          => false,
				'menu_position'         => "25.3",
				'menu_icon'             => 'dashicons-clipboard',
				'show_in_admin_bar'     => false,
				'show_in_nav_menus'     => false,
				'can_export'            => false,
				'has_archive'           => false,		
				'exclude_from_search'   => true, 				'publicly_queryable'    => false, 				'capability_type'       => 'page',
							    'capabilities' => array(
			        'edit_post' => 'admin_zerobs_quotes',
			        'edit_posts' => 'admin_zerobs_quotes',
			        'edit_others_posts' => 'admin_zerobs_quotes',
			        'publish_posts' => 'admin_zerobs_quotes',
			        'read_post' => 'admin_zerobs_quotes',
			        'read_private_posts' => 'admin_zerobs_quotes',
			        'delete_post' => 'admin_zerobs_quotes'
			    )
			);

			register_post_type( 'zerobs_quo_template', $args );

			
		}

	}
	
	if($ZBSuseInvoices == "1"){

		$labels = array(
			'name'                  => _wx( 'Invoices', 'Invoices', 'zerobscrm' ),
			'singular_name'         => _wx( 'Invoice', 'Invoice', 'zerobscrm' ),
			'menu_name'             => __w( 'Invoices', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Invoice', 'zerobscrm' ),
			'archives'              => __w( 'Invoice Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Parent Invoice:', 'zerobscrm' ),
			'all_items'             => __w( 'All Invoices', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Invoice', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Invoice', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Invoice', 'zerobscrm' ),
			'update_item'           => __w( 'Update Invoice', 'zerobscrm' ),
			'view_item'             => __w( 'View Invoice', 'zerobscrm' ),
			'search_items'          => __w( 'Search Invoice', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Invoice Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Invoice image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Invoice image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Invoice image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Invoice', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Invoice', 'zerobscrm' ),
			'items_list'            => __w( 'Invoices list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Invoices list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Invoices list', 'zerobscrm' ),
		);





		$args = array(


			'label'                 => __w( 'Invoice', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Invoice', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array(   'taxonomies'),
			'taxonomies'            => array( ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.4",
			'menu_icon'             => 'dashicons-media-text',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,		
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
					    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_invoices',
		        'edit_posts' => 'admin_zerobs_invoices',
		        'edit_others_posts' => 'admin_zerobs_invoices',
		        'publish_posts' => 'admin_zerobs_invoices',
		        'read_post' => 'admin_zerobs_invoices',
		        'read_private_posts' => 'admin_zerobs_invoices',
		        'delete_post' => 'admin_zerobs_invoices',
		    )
		);
		register_post_type( 'zerobs_invoice', $args );

	}

	

			$labels = array(
			'name'                       => _wx( 'Transaction Tags', 'Transaction Tags', 'zerobscrm' ),
			'singular_name'              => _wx( 'Transaction Tag', 'Transaction Tag', 'zerobscrm' ),
			'menu_name'                  => __w( 'Transaction Tags', 'zerobscrm' ),
			'all_items'                  => __w( 'All Tags', 'zerobscrm' ),
			'parent_item'                => __w( 'Parent Tag', 'zerobscrm' ),
			'parent_item_colon'          => __w( 'Parent Tag:', 'zerobscrm' ),
			'new_item_name'              => __w( 'New Tag Name', 'zerobscrm' ),
			'add_new_item'               => __w( 'Add Tag Item', 'zerobscrm' ),
			'edit_item'                  => __w( 'Edit Tag', 'zerobscrm' ),
			'update_item'                => __w( 'Tag Item', 'zerobscrm' ),
			'view_item'                  => __w( 'View Tag', 'zerobscrm' ),
			'separate_items_with_commas' => __w( 'Separate Tags with commas', 'zerobscrm' ),
			'add_or_remove_items'        => __w( 'Add or remove Tags', 'zerobscrm' ),
			'choose_from_most_used'      => __w( 'Choose from the most used', 'zerobscrm' ),
			'popular_items'              => __w( 'Popular Tags', 'zerobscrm' ),
			'search_items'               => __w( 'Search Tags', 'zerobscrm' ),
			'not_found'                  => __w( 'Not Found', 'zerobscrm' ),
			'no_terms'                   => __w( 'No Tags', 'zerobscrm' ),
			'items_list'                 => __w( 'Tags list', 'zerobscrm' ),
			'items_list_navigation'      => __w( 'Tags list navigation', 'zerobscrm' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		    'capabilities' => array(
		      'manage_terms'=> 'manage_categories',
		      'edit_terms'=> 'manage_categories',
		      'delete_terms'=> 'manage_categories',
		      'assign_terms' => 'read'
		    )
		);
		register_taxonomy( 'zerobscrm_transactiontag', array( 'zerobscrm_transactiontag' ), $args );

		$labels = array(
		'name'                  => _wx( 'Transactions', 'Transactions', 'zerobscrm' ),
		'singular_name'         => _wx( 'Transactions', 'Transaction', 'zerobscrm' ),
		'menu_name'             => __w( 'Transactions', 'zerobscrm' ),
		'name_admin_bar'        => __w( 'Transaction', 'zerobscrm' ),
		'archives'              => __w( 'Transactions Archives', 'zerobscrm' ),
		'parent_item_colon'     => __w( 'Parent Transaction:', 'zerobscrm' ),
		'all_items'             => __w( 'All Transactions', 'zerobscrm' ),
		'add_new_item'          => __w( 'Add New Transaction', 'zerobscrm' ),
		'add_new'               => __w( 'Add New', 'zerobscrm' ),
		'new_item'              => __w( 'New Transaaction', 'zerobscrm' ),
		'edit_item'             => __w( 'Edit Transaction', 'zerobscrm' ),
		'update_item'           => __w( 'Update Transaction', 'zerobscrm' ),
		'view_item'             => __w( 'View Transaction', 'zerobscrm' ),
		'search_items'          => __w( 'Search Transactions', 'zerobscrm' ),
		'not_found'             => __w( 'Not found', 'zerobscrm' ),
		'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
	);
	$args = array(
		'label'                 => __w( 'Transactions', 'zerobscrm' ),
		'description'           => __w( 'Zero-BS Transactions', 'zerobscrm' ),
		'labels'                => $labels,
		'supports'              => array('taxonomies'), 		'taxonomies' 			=> array('zerobscrm_transactiontag'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => "25.5",
		'menu_icon'             => 'dashicons-cart',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => false,		
		'exclude_from_search'   => true, 		'publicly_queryable'    => false, 		'capability_type'       => 'post',
	);
	register_post_type( 'zerobs_transaction', $args );
	

	if($ZBSuseForms == "1"){
			
		$labels = array(
			'name'                  => _wx( 'Forms', 'Forms', 'zerobscrm' ),
			'singular_name'         => _wx( 'Form', 'Form', 'zerobscrm' ),
			'menu_name'             => __w( 'Forms', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Form', 'zerobscrm' ),
			'archives'              => __w( 'Form Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Company:', 'zerobscrm' ),
			'parent'    			 => __w( 'Company', 'zerobscrm' ),
			'all_items'             => __w( 'All Forms', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Form', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Form', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Form', 'zerobscrm' ),
			'update_item'           => __w( 'Update Form', 'zerobscrm' ),
			'view_item'             => __w( 'View Form', 'zerobscrm' ),
			'search_items'          => __w( 'Search Form', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Form Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Form image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Form image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Form image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Form', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Form', 'zerobscrm' ),
			'items_list'            => __w( 'Forms list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Forms list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Forms list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( 'Form', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Form', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array('title'), 			'taxonomies'            => array(),
			'hierarchical'          => false, 			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => "25.9", 			'menu_icon'             => 'dashicons-welcome-widgets-menus',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => false,
			'has_archive'           => false,		
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'page',
			'taxonomies' 			=> array('zerobscrm_formtag'),
		);
		register_post_type( 'zerobs_form', $args );
	}


			
		$labels = array(
			'name'                       => _wx( 'Log Tags', 'Log Tags', 'zerobscrm' ),
			'singular_name'              => _wx( 'Log Tag', 'Log Tag', 'zerobscrm' ),
			'menu_name'                  => __w( 'Log Tags', 'zerobscrm' ),
			'all_items'                  => __w( 'All Tags', 'zerobscrm' ),
			'parent_item'                => __w( 'Parent Tag', 'zerobscrm' ),
			'parent_item_colon'          => __w( 'Parent Tag:', 'zerobscrm' ),
			'new_item_name'              => __w( 'New Tag Name', 'zerobscrm' ),
			'add_new_item'               => __w( 'Add Tag Item', 'zerobscrm' ),
			'edit_item'                  => __w( 'Edit Tag', 'zerobscrm' ),
			'update_item'                => __w( 'Tag Item', 'zerobscrm' ),
			'view_item'                  => __w( 'View Tag', 'zerobscrm' ),
			'separate_items_with_commas' => __w( 'Separate Tags with commas', 'zerobscrm' ),
			'add_or_remove_items'        => __w( 'Add or remove Tags', 'zerobscrm' ),
			'choose_from_most_used'      => __w( 'Choose from the most used', 'zerobscrm' ),
			'popular_items'              => __w( 'Popular Tags', 'zerobscrm' ),
			'search_items'               => __w( 'Search Tags', 'zerobscrm' ),
			'not_found'                  => __w( 'Not Found', 'zerobscrm' ),
			'no_terms'                   => __w( 'No Tags', 'zerobscrm' ),
			'items_list'                 => __w( 'Tags list', 'zerobscrm' ),
			'items_list_navigation'      => __w( 'Tags list navigation', 'zerobscrm' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => false,
			'show_tagcloud'              => false,
		    'capabilities' => array(
		      'manage_terms'=> 'manage_categories',
		      'edit_terms'=> 'manage_categories',
		      'delete_terms'=> 'manage_categories',
		      'assign_terms' => 'read'
		    )
		);
		register_taxonomy( 'zerobscrm_logtag', array( 'zerobscrm_logtag' ), $args );


		$labels = array(
			'name'                  => _wx( 'Logs', 'Logs', 'zerobscrm' ),
			'singular_name'         => _wx( 'Log', 'Log', 'zerobscrm' ),
			'menu_name'             => __w( 'Logs', 'zerobscrm' ),
			'name_admin_bar'        => __w( 'Log', 'zerobscrm' ),
			'archives'              => __w( 'Log Archives', 'zerobscrm' ),
			'parent_item_colon'     => __w( 'Company:', 'zerobscrm' ),
			'parent'    			 => __w( 'Company', 'zerobscrm' ),
			'all_items'             => __w( 'All Logs', 'zerobscrm' ),
			'add_new_item'          => __w( 'Add New Log', 'zerobscrm' ),
			'add_new'               => __w( 'Add New', 'zerobscrm' ),
			'new_item'              => __w( 'New Log', 'zerobscrm' ),
			'edit_item'             => __w( 'Edit Log', 'zerobscrm' ),
			'update_item'           => __w( 'Update Log', 'zerobscrm' ),
			'view_item'             => __w( 'View Log', 'zerobscrm' ),
			'search_items'          => __w( 'Search Log', 'zerobscrm' ),
			'not_found'             => __w( 'Not found', 'zerobscrm' ),
			'not_found_in_trash'    => __w( 'Not found in Trash', 'zerobscrm' ),
			'featured_image'        => __w( 'Log Image', 'zerobscrm' ),
			'set_featured_image'    => __w( 'Set Log image', 'zerobscrm' ),
			'remove_featured_image' => __w( 'Remove Log image', 'zerobscrm' ),
			'use_featured_image'    => __w( 'Use as Log image', 'zerobscrm' ),
			'insert_into_item'      => __w( 'Insert into Log', 'zerobscrm' ),
			'uploaded_to_this_item' => __w( 'Uploaded to this Log', 'zerobscrm' ),
			'items_list'            => __w( 'Logs list', 'zerobscrm' ),
			'items_list_navigation' => __w( 'Logs list navigation', 'zerobscrm' ),
			'filter_items_list'     => __w( 'Filter Logs list', 'zerobscrm' ),
		);
		$args = array(
			'label'                 => __w( 'Log', 'zerobscrm' ),
			'description'           => __w( 'Zero-BS Log', 'zerobscrm' ),
			'labels'                => $labels,
			'supports'              => array(  'thumbnail', 'taxonomies'), 			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false, 			'public'                => true,
			'show_ui'               => false,
			'show_in_menu'          => false,
			'menu_position'         => "25.9",
			'menu_icon'             => 'dashicons-admin-users',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => false,
			'has_archive'           => false,		
			'exclude_from_search'   => true, 			'publicly_queryable'    => false, 			'capability_type'       => 'post',
			'taxonomies' 			=> array('zerobscrm_logtag'),
		    'capabilities' => array(
		        'edit_post' => 'admin_zerobs_customers',
		        'edit_posts' => 'admin_zerobs_customers',
		        'edit_others_posts' => 'admin_zerobs_customers',
		        'publish_posts' => 'admin_zerobs_customers',
		        'read_post' => 'admin_zerobs_customers',
		        'read_private_posts' => 'admin_zerobs_customers',
		        'delete_post' => 'admin_zerobs_customers'
		    )
		);
		register_post_type( 'zerobs_log', $args );

				zeroBSCRM_flushPermalinksIfReq();

}





			add_action('trash_zerobs_customer','zbsCRM_trash_customer',1,1);
	function zbsCRM_trash_customer($post_id){
	    if(!did_action('trash_post')){
	        
	        	        header("Location: edit.php?post_type=zerobs_customer&page=zbs-deletion&cid=".$post_id);
	        exit();

									
	    } else {


	    }
	}

		add_action('trash_zerobs_quote','zbsCRM_trash_quote',1,1);
	function zbsCRM_trash_quote($post_id){
	    if(!did_action('trash_post')){
	        
	        	        header("Location: edit.php?post_type=zerobs_customer&page=zbs-deletion&qid=".$post_id);
	        exit();

	    }
	}
		add_action('trash_zerobs_invoice','zbsCRM_trash_invoice',1,1);
	function zbsCRM_trash_invoice($post_id){
	    if(!did_action('trash_post')){
	        
	        	        header("Location: edit.php?post_type=zerobs_customer&page=zbs-deletion&iid=".$post_id);
	        exit();

	    }
	}
		add_action('trash_zerobs_transaction','zbsCRM_trash_transaction',1,1);
	function zbsCRM_trash_transaction($post_id){
	    if(!did_action('trash_post')){
	        
	        	        header("Location: edit.php?post_type=zerobs_customer&page=zbs-deletion&tid=".$post_id);
	        exit();

	    }
	}










function zeroBSCRM_removeViewLinksAdminBar() {
    global $wp_admin_bar,$post,$zbsCustomPostTypesToHide;
                                                             
           
	if (
				(isset($post) && in_array($post->post_type,$zbsCustomPostTypesToHide))
		||
				(isset($_GET['post_type']) && in_array($_GET['post_type'],$zbsCustomPostTypesToHide))
		){
	    	
	    		    	$wp_admin_bar->remove_menu('view');

	    		    	$wp_admin_bar->remove_menu('comments');

		}
}
add_action('wp_before_admin_bar_render', 'zeroBSCRM_removeViewLinksAdminBar');



















function zeroBSCRM_redirectCPTS() {

    global $wp_query,$post,$zbsCustomPostTypesToHide;

    if (isset($post) && in_array($post->post_type,$zbsCustomPostTypesToHide)){

    	    	if ($post->post_type == 'zerobs_quote'){

		    		    $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

		    if ($useQuoteBuilder == "1") $skipRules = true;

		}

    		    if (!isset($skipRules) && (is_archive($post->post_type) || is_singular($post->post_type))){

	        $url   = get_bloginfo('url');
	        wp_redirect( esc_url_raw( $url ), 301 );
	        exit();

	    }

	}
}

add_action ( 'template_redirect', 'zeroBSCRM_redirectCPTS', 1);











function zeroBSCRM_installDefaultContent() {

	global $zeroBSCRM_slugs, $zeroBSCRM_Settings; 
		$quoteBuilderDefaultsInstalled = zeroBSCRM_getSetting('quotes_default_templates');

	if (!is_array($quoteBuilderDefaultsInstalled)){

				$installedQuoteTemplates = array();

					$args = array (
				'post_type'              => 'zerobs_quo_template',
				'post_status'            => 'publish',
				'posts_per_page'         => 100,
				'order'                  => 'DESC',
				'orderby'                => 'post_date',

									   'meta_key'   => 'zbsdefault', 
				   'meta_value' => 1
			);

			$list = get_posts( $args );
			if (count($list) > 0) foreach ($list as $template){

				wp_delete_post($template->ID,true);

			}

				global $quoteBuilderDefaultTemplates;
		if(!isset($quoteBuilderDefaultTemplates)) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.DefaultContent.php');

				if (count($quoteBuilderDefaultTemplates) > 0) foreach ($quoteBuilderDefaultTemplates as $template){

			$newPost = wp_insert_post(array('post_title'=>$template[0],'post_content'=>$template[1],'post_status'=>'publish','post_type'=>'zerobs_quo_template','comment_status'=>'closed','meta_input'=>array('zbs_quotemplate_meta'=>array(),'zbsdefault'=>1)));

			if ($newPost > 0) $installedQuoteTemplates[] = $newPost;

		}

		  		$zeroBSCRM_Settings->update('quotes_default_templates',$installedQuoteTemplates);

	}



}




















   





		define('ZBSCRM_INC_CPT',true);