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










  
    function zeroBSCRM__WYSIWYG_register_button( $buttons ) {
     array_push($buttons, "zbsCRMForms");
     return $buttons;
  }
  function zeroBSCRM__WYSIWYG_add_plugin( $plugin_array ) {
     $plugin_array['zbsCRMForms'] = ZEROBSCRM_URL.'js/ZeroBSCRM.admin.wysiwygbar.min.js'; 
     return $plugin_array;
  }

  add_action('admin_head', 'zeroBSCRM__WYSIWYG_tc4_button');
  function zeroBSCRM__WYSIWYG_tc4_button() {
      global $typenow;
            if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
     	return;
      }
            if( ! in_array( $typenow, array( 'post', 'page' ) ) )
          return;
  	  	if ( get_user_option('rich_editing') == 'true') {
  		add_filter("mce_external_plugins", 'zeroBSCRM__WYSIWYG_add_plugin');
  		add_filter('mce_buttons', 'zeroBSCRM__WYSIWYG_register_button');
  		  	}
  }

  


  
                function zeroBSCRM__WYSIWYG_quotebuildr_register_button( $buttons ) {
           array_push($buttons, "zbsQuoteTemplates");
           return $buttons;
        }
        function zeroBSCRM__WYSIWYG_quotebuildr_add_plugin( $plugin_array ) {
           $plugin_array['zbsQuoteTemplates'] = ZEROBSCRM_URL.'js/ZeroBSCRM.admin.quotebuilder.wysiwygbar.min.js'; 
           return $plugin_array;
        }

        add_action('admin_head', 'zeroBSCRM__WYSIWYG_quotebuildr_tc4_button');
        function zeroBSCRM__WYSIWYG_quotebuildr_tc4_button() {
            global $typenow;
                        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
            }
                        if( ! in_array( $typenow, array( 'post', 'page', 'zerobs_quo_template' ) ) )
                return;

                        if ($typenow != 'zerobs_quo_template') return;

                    if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", 'zeroBSCRM__WYSIWYG_quotebuildr_add_plugin');
            add_filter('mce_buttons', 'zeroBSCRM__WYSIWYG_quotebuildr_register_button');
                      }
        }

  

  






		define('ZBSCRM_INC_WYSIWYG',true);