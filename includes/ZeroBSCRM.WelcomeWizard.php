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








        function zeroBSCRM_wizard(){
      $loc = ZEROBSCRM_WILDURL .'welcome-to-zbs/';
      wp_safe_redirect( $loc );
    }


    #} Welcome Screen 
        register_activation_hook( ZEROBSCRM_PATH.'ZeroBSCRM.php', 'zbs_welcome_screen_activate' );
    function zbs_welcome_screen_activate() {

      set_transient( '_zbs_welcome_screen_activation_redirect', true, 30 );
      
    }


    add_action( 'admin_init', 'zbs_welcome_screen_do_activation_redirect' );
    function zbs_welcome_screen_do_activation_redirect() {

            if ( ! get_transient( '_zbs_welcome_screen_activation_redirect' ) ) {
        return;
      }

            if ( is_network_admin()){         return;
      }

            

            delete_transient( '_zbs_welcome_screen_activation_redirect' );

            $loc = ZEROBSCRM_WILDURL .'welcome-to-zbs/';
      wp_safe_redirect( $loc );
      exit();

    }


    add_action('admin_menu', 'zbs_welcome_screen_pages');
    function zbs_welcome_screen_pages() {

    	global $zeroBSCRM_slugs;
    	add_dashboard_page(
    		'Welcome To Welcome Screen',
    		'Welcome To Welcome Screen',
    		'read',
    		$zeroBSCRM_slugs['welcome'],
    		'zeroBSCRM_pages_welcome_screen'
    	);
    }


    add_action( 'admin_head', 'zbs_welcome_screen_remove_menus' );
    function zbs_welcome_screen_remove_menus() {
    	global $zeroBSCRM_slugs;
        remove_submenu_page( 'index.php', $zeroBSCRM_slugs['welcome'] );
    }






		define('ZBSCRM_INC_WELCOMEWIZ',true);