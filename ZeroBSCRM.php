<?php /* 
Plugin Name: Zero BS CRM
Plugin URI: http://zerobscrm.com
Description: Zero BS CRM is the simplest CRM for WordPress. Self host your own Customer Relationship Manager using WP.
Version: 2.10.4
Author: <a href="https://zerobscrm.com">Zero BS CRM</a>
*/





if ( ! defined( 'ABSPATH' ) ) exit;


if (version_compare(phpversion(), '5.4', '<')) {
    
        echo '<div style="font-family: \'Open Sans\',sans-serif;">Zero BS CRM Requires PHP Version 5.4 or above, please ask web hosting provider to update your PHP!</div>';
	exit();	

}








            register_deactivation_hook(__FILE__,'zeroBSCRM_uninstall');

        register_activation_hook(__FILE__,'zeroBSCRM_wizard');

        register_activation_hook(__FILE__,'zeroBSCRM_database_check');
    
    	add_action('init', 			'zeroBSCRM_init');

		add_action('admin_init', 			'zeroBSCRM_admin_init');

	    add_action('admin_menu', 	'zeroBSCRM_admin_menu'); 









	#} Initial Vars
	global 	$zeroBSCRM_db_version,$zeroBSCRM_version;
			$zeroBSCRM_db_version 			= "1.2";
			$zeroBSCRM_version 				= "2.10.4";
			$zeroBSCRM_helpEmail			= 'hello@zerobscrm.com';

	#} Urls
	global 	$zeroBSCRM_urls;
			$zeroBSCRM_urls['home'] 				= 'http://zerobscrm.com';
			$zeroBSCRM_urls['support']				= 'http://zerobscrm.com/kb/';
			$zeroBSCRM_urls['feedback']				= 'https://zerobscrm.com/kb/submit-a-ticket/';
			$zeroBSCRM_urls['docs'] 				= 'http://docs.zerobscrm.com/documentation/';
			$zeroBSCRM_urls['productsdatatools'] 	= 'http://zerobscrm.com/data-tools/'; 
			$zeroBSCRM_urls['smm']					= 'http://demo.zbscrm.com/_o/smm.php';
			$zeroBSCRM_urls['extimgrepo']			= 'http://demo.zbscrm.com/_i/';
			$zeroBSCRM_urls['rateuswporg']			= 'https://wordpress.org/support/view/plugin-reviews/zero-bs-crm?filter=5#postform';
			$zeroBSCRM_urls['extdlrepo']			= 'http://demo.zbscrm.com/_ext/';

						$zeroBSCRM_urls['products'] 			= 'http://zerobscrm.com/extensions/'; 
			$zeroBSCRM_urls['extcsvimporterpro']	= 'https://zerobscrm.com/product/csv-importer-pro/';
			$zeroBSCRM_urls['limitedlaunch']		= 'https://zerobscrm.com/zero-bs-crm-version-2-0-is-here/';

	#} Page slugs
	global	$zeroBSCRM_slugs;
			$zeroBSCRM_slugs['dash'] 			= "zerobscrm-dash";
			$zeroBSCRM_slugs['home'] 			= "zerobscrm-plugin";
			$zeroBSCRM_slugs['app'] 			= "zerobscrm-app";
			$zeroBSCRM_slugs['settings'] 		= "zerobscrm-plugin-settings";
			$zeroBSCRM_slugs['whlang']			= "zerobscrm-whlang";
			$zeroBSCRM_slugs['customfields']	= "zerobscrm-customfields";
			$zeroBSCRM_slugs['logout']			= "zerobscrm-logout";
			$zeroBSCRM_slugs['sync']			= "zerobscrm-sync";
			$zeroBSCRM_slugs['datatools']		= "zerobscrm-datatools";
			$zeroBSCRM_slugs['welcome']			= "zerobscrm-welcome";
			$zeroBSCRM_slugs['feedback']		= "zerobscrm-feedback";
			$zeroBSCRM_slugs['extensions']		= "zerobscrm-extensions";
			$zeroBSCRM_slugs['bulktools']		= "zerobscrm-bulktools";
			$zeroBSCRM_slugs['import']			= "zerobscrm-import";
			$zeroBSCRM_slugs['export']			= "zerobscrm-export";
			$zeroBSCRM_slugs['systemstatus']	= "zerobscrm-systemstatus";
			
	

	#} Lang integration
			global $whLangsupport;
		   $whLangsupport['zerobscrm'] = 'zeroBSCRM_Settings'; 
	#} Currencies & countries
	global 	$zeroBSCRM__currencylist;

	#} Integrations - External Sources
		global 	$zbscrmApprovedExternalSources;
			$zbscrmApprovedExternalSources = array(
												'woo' => array('WooCommerce','ico'=>'fa-shopping-cart'), 				'pay' => array('PayPal','ico'=>'fa-paypal'),
				'env' => array('Envato','ico'=>'fa-envira'), 				'csv' => array('CSV Import','ico'=>'file-text'),
				'form' => array('Form Capture', 'ico' => 'fa-wpforms'),
				'jvz' => array('JV Zoo','ico'=>'fa-paypal'),
				'gra' => array('Gravity Forms', 'ico' => 'fa-wpforms'),
				'api' => array('API', 'ico' => 'fa-random'),
				'wpa' => array('WorldPay', 'ico' => 'fa-credit-card')
			);

	#}  Integrations
		global 	$zeroBSCRM_extensionsInstalledList; $zeroBSCRM_extensionsInstalledList = array();

	#} A list of applicable Mimetypes for file uploads
	global 	$zeroBSCRM_Mimes; $zeroBSCRM_Mimes = array();



	#} Define Paths
	define( 'ZEROBSCRM_PATH', plugin_dir_path(__FILE__) );
	define( 'ZEROBSCRM_URL', plugin_dir_url(__FILE__) );
		define( 'ZEROBSCRM_WILDPATH', plugin_dir_path(__FILE__).'wild/' ); 	define( 'ZEROBSCRM_WILDURL', plugin_dir_url(__FILE__).'wild/' ); 
		define ('ZBS_MENU_FULL', 1);
	define ('ZBS_MENU_SLIM', 2);
	define ('ZBS_MENU_CRMONLY', 3);

		define('ZBS_ROOTFILE',__FILE__);








	#} Required includes
	
   		if(!defined('ZBSCRM_INC_GENFUNC')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.GeneralFuncs.php');
	if(!defined('ZBSCRM_INC_CONFINIT')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Config.Init.php');
	if(!class_exists('WHWPConfigLib')) require_once(ZEROBSCRM_PATH . 'includes/wh.config.lib.php');
	if(!defined('ZBSCRM_INC_DAL')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.DAL.php');
	if(!defined('ZBSCRM_INC_DATAVAL')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.DataValidation.php');
	if(!defined('ZBSCRM_INC_DB')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Database.php');
	if(!defined('ZBSCRM_INC_ADMSTY')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.AdminStyling.php');
	if(!defined('ZBSCRM_INC_ADMPGS')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.AdminPages.php');
	if(!defined('ZBSCRM_INC_MIGRATIONS')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Migrations.php');
	if(!defined('ZBSCRMLOCALEINC')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Core.Localisation.php');
	if(!defined('ZBSCRMEXTLISTINC')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Core.Extensions.php');
	if(!defined('ZBSCRMMENUINC')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Core.Menus.php');
	if(!defined('ZBSCRM_INC_FIELDS')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Fields.php');
	if(!defined('ZBSCRM_INC_PERMS')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Permissions.php');
	if(!defined('ZBSCRM_INC_CPT')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.CPTs.php');
	if(!defined('ZBSCRM_INC_INVENTORY')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Inventory.php');
	if(!defined('ZBSCRM_INC_REWRITERULES')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.ReWriteRules.php');
	if(!defined('ZBSCRM_INC_TEMPLATING')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Templating.php');
	if(!function_exists('_whLangLibSupport')) require_once(ZEROBSCRM_PATH . 'includes/wh.langsupport.lib.php');
	if(!defined('ZBSCRM_INC_IA')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.InternalAutomator.php');
	if(!defined('ZBSCRM_INC_CRON')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.CRON.php');
	if(!defined('ZBSCRM_INC_CUSTOMER_SEARCH')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.CustomerSearch.php');

	if(!defined('ZBSCRM_INC_ACTIVITY_SEARCH')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.AdvancedSearch.php');

		if(!class_exists('zeroBS__Metabox')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Customers.php');
	if(!class_exists('zeroBS__MetaboxQuote')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Quotes.php');
	if(!class_exists('zeroBS__MetaboxInvoice')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Invoices.php');
	if(!class_exists('zeroBS__ExternalSourceMetabox')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.ExternalSources.php');
	if(!class_exists('zeroBS__Metabox_Companies')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Companies.php');
	if(!defined('ZBSCRM_INC_TRANSMB')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Transactions.php');
	if(!defined('ZBSCRM_INC_LOGSMB')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Logs.php');
	if(!defined('ZBSCRM_INC_FORMSMB')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Forms.php');
			
		if(!defined('ZBSCRM_INC_AJAX')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.AJAX.php');
	if(!defined('ZBSCRM_INC_WYSIWYG')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.WYSIWYGButtons.php');
	if(!defined('ZBSCRM_INC_CUSTFILT')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.CustomerFilters.php');
	if(!defined('ZBSCRM_INC_WELCOMEWIZ')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.WelcomeWizard.php');
	if(!defined('ZBSCRM_INC_IARECIPES')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.InternalAutomatorRecipes.php');
	if(!defined('ZBSCRM_INC_FILEUPL')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.FileUploads.php');
	if(!defined('ZBSCRM_INC_FORMS')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Forms.php');
	if(!defined('ZBSCRM_INC_INVBUILDER')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.InvoiceBuilder.php');
	if(!defined('ZBSCRM_INC_EXPORT')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Export.php');
	if(!defined('ZBSCRM_INC_SYSCHECK')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.SystemChecks.php');
	if(!defined('ZBSCRM_INC_INTEGRATE')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.IntegrationFuncs.php');
	if(!defined('ZBSCRM_INC_TRANSINSP')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.TransactionInspector.php');
	
		if(!class_exists('zeroBSCRM_Customer_List')) require_once(ZEROBSCRM_PATH  . 'includes/ZeroBSCRM.List.Customers.php');
	if(!class_exists('zeroBSCRM_Company_List')) require_once(ZEROBSCRM_PATH  . 'includes/ZeroBSCRM.List.Companies.php');
	if(!class_exists('zeroBSCRM_CustomerNoQJ_List')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.List.CustomesWithoutQJ.php');
	if(!class_exists('zeroBSCRM_Quote_List')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.List.Quotes.php');
	if(!class_exists('zeroBSCRM_Invoice_List')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.List.Invoices.php');
	if(!class_exists('zeroBSCRM_Transaction_List')) require_once(ZEROBSCRM_PATH  . 'includes/ZeroBSCRM.List.Transactions.php');









	#} Initialise Config Model
		global $zeroBSCRM_Settings, $zeroBSCRM_Conf_Setup;
	if (!isset($zeroBSCRM_Settings)) $zeroBSCRM_Settings = new WHWPConfigLib($zeroBSCRM_Conf_Setup);

	#} Unpack Custom Fields + Apply sorts
	zeroBSCRM_unpackCustomFields();
	zeroBSCRM_unpackCustomisationsToFields(); 
	if (1 == 1){ 		zeroBSCRM_applyFieldSorts();
	}

	










      function zeroBSCRM_PostInitIncludes(){

	   	
				
   		   				if (!zeroBSCRM_isExtensionInstalled('csvimporter') && zeroBSCRM_isExtensionInstalled('csvimporterlite') && !defined('ZBSCRM_INC_CSVIMPORTERLITE')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.CSVImporter.php');

				if(zeroBSCRM_isExtensionInstalled('portal') && !defined('ZBSCRM_INC_PORTAL')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.Portal.php');

				if(zeroBSCRM_isExtensionInstalled('api') && !defined('ZBSCRM_INC_API')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.API.php');


				global $zeroBSCRM_Settings;
        $usingOwnership = $zeroBSCRM_Settings->get('perusercustomers');
		if ($usingOwnership) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.Ownership.php');

	}












add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'zeroBSCRM_add_action_links' );

function zeroBSCRM_add_action_links ( $links ) {
 $mylinks = array(
 	 '<a href="https://zerobscrm.com/extensions/">Extensions</a>',
 );
return array_merge( $links, $mylinks );
}







#} Uninstall
function zeroBSCRM_uninstall(){

		$feedbackAlready = get_option('zbsfeedback');

	if (!defined('ZBSDEACTIVATINGINPROG') && $feedbackAlready == false){

								define('ZBSDEACTIVATINGINPROG',true);


				global $zeroBSCRM_Settings;	

				if (function_exists('file_get_contents')){

			try {

				$beforeYouGo = file_get_contents(ZEROBSCRM_PATH.'html/before-you-go/index.html');

				if (!empty($beforeYouGo)){

										$beforeYouGo = str_replace('###ADMINURL###',admin_url('plugins.php'),$beforeYouGo);
					$beforeYouGo = str_replace('###ADMINASSETSURL###',ZEROBSCRM_URL.'html/before-you-go/assets/',$beforeYouGo);	
					$beforeYouGo = str_replace('###AJAXURL###',admin_url('admin-ajax.php'),$beforeYouGo);	


										deactivate_plugins( plugin_basename( __FILE__ ) );

										echo $beforeYouGo; exit();

				}


			} catch (Exception $e){

				
			}

		}	

	}

}







function zeroBSCRM_admin_init(){
		wp_register_style('zerobscrmadmcss', 	plugins_url('/css/ZeroBSCRM.admin.global.min.css',__FILE__),'', 2.2 );
	wp_register_style('zerobsjsmodal', 	plugins_url('/css/jquery.modal.min.css',__FILE__),'', 1.17 );
	wp_register_script('zerobsjsmodal' ,plugins_url('/js/lib/jquery.modal.min.js',__FILE__), array('jquery'));
		wp_register_style('wh-fa-v4-4-0-corecss', plugins_url('/css/font-awesome.min.css',__FILE__) );

		wp_register_style('zerobscrmswa', plugins_url('/css/sweetalert.min.css',__FILE__) );
}


function zeroBSCRM_global_admin_styles(){
		wp_enqueue_style( 'zerobscrmadmcss' );
	wp_enqueue_style( 'zerobsjsmodal' );
	wp_enqueue_script('zerobsjsmodal');
	wp_enqueue_style(	'wh-fa-v4-4-0-corecss'	);
	wp_enqueue_style(	'zerobscrmswa'		);
}


function zeroBSCRM_add_admin_styles( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {         if (is_object($post))
        if ( 'zerobs_customer' === $post->post_type || 'zerobs_quote' === $post->post_type || 'zerobs_company' === $post->post_type || 'zerobs_invoice' === $post->post_type || 'zerobs_transaction' === $post->post_type || 'zerobs_form' === $post->post_type || 'zerobs_mailcampaign' === $post->post_type ) {     
            zeroBSCRM_global_admin_styles();
        }
    }
}
add_action( 'admin_enqueue_scripts', 'zeroBSCRM_add_admin_styles', 10, 1 );

function zeroBSCRM_add_public_scripts( $hook ) {

    global $post;

            if( is_single() && get_query_var('post_type') && 'zerobs_quote' == get_query_var('post_type') ){

    			wp_enqueue_style('zerobscrmpubquocss', plugins_url('/css/ZeroBSCRM.public.quotes.min.css',__FILE__) );

    }
    
}
add_action( 'wp_enqueue_scripts', 'zeroBSCRM_add_public_scripts', 10, 1 );












#} Initialisation - enqueueing scripts/styles
function zeroBSCRM_init(){
  
	global $zeroBSCRM_slugs, $zeroBSCRM_Settings; 
	#} Add roles 
	zeroBSCRM_addUserRoles();

	#} Retrieve settings
	$settings = $zeroBSCRM_Settings->getAll();

		$useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');
	if($useQuoteBuilder == "1" && !class_exists('zeroBSCRM_QuoteTemplate_List')) require_once(ZEROBSCRM_PATH  . 'includes/ZeroBSCRM.QuoteTemplate.list.php');

        zeroBSCRM_migrations_run($settings['migrations']);

		if (isset($_GET['zbscjson']) && is_user_logged_in() && zeroBSCRM_permsCustomers()){ exit(zeroBSCRM_cjson()); }

		if (isset($_GET['zbs_invid']) && wp_verify_nonce($_GET['_wpnonce'], 'zbsinvpreview') && is_user_logged_in() && zeroBSCRM_permsInvoices()){ exit(zeroBSCRM_invoice_generateInvoiceHTML((int)$_GET['zbs_invid'],false)); }	

	#} Catch Dashboard + redir (if override mode)
		if (isset($settings['wptakeovermode']) && $settings['wptakeovermode'] == 1) {
	
						zeroBSCRM_catchDashboard();
	
	}

		zeroBSCRM_extensions_init_install();

	#} setup post types
	zeroBSCRM_setupPostTypes();

		zeroBSCRM_installDefaultContent();
			
	#} Admin & Public req
	wp_enqueue_script("jquery");

		global $whLangsupport; $whLangsupport['zerobscrm'] = 'zeroBSCRM_Settings';

		zeroBSCRM_PostInitIncludes();

		do_action('zerobscrm_post_init');
	
	#} Public only	
	if (!is_admin()){

				if (isset($settings['killfrontend']) && $settings['killfrontend'] == 1){

									

									if (!zeroBSCRM_isLoginPage() && !zeroBSCRM_isWelcomeWizPage() && !zeroBSCRM_isAPIRequest()){

				 zeroBSCRM_stopFrontEnd();

			}
		}

				wp_enqueue_script('zerobscrmglob', plugins_url('/js/ZeroBSCRM.public.global.min.js',__FILE__), array( 'jquery' ));

						

	} else {


			
					


				wp_enqueue_script('zerobscrmadmjs', plugins_url('/js/ZeroBSCRM.admin.global.min.js',__FILE__), array( 'jquery' ));

						wp_enqueue_script('zerobscrmtajs-0-11-1', plugins_url('/js/lib/typeahead.bundle.min.js',__FILE__), array( 'jquery' ));
	
								
				wp_enqueue_script('zerobscrmswa', plugins_url('/js/lib/sweetalert.min.js',__FILE__), array( 'jquery' ));
		
				if (isset($_GET['page']) && $_GET['page'] == 'zerobscrm-plugin-settings'){

			zeroBSCRM_load_libs_js_momentdatepicker();

		}


						

				$postTypeStr = ''; if (isset($_GET['post'])) $postTypeStr = get_post_type((int)$_GET['post']);
		
				if (
				(isset($_GET['page']) && $_GET['page'] == 'manage-customers') ||
				(isset($_GET['page']) && $_GET['page'] == 'manage-customers-noqj') ||
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_customer') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_customer')
				) {

					wp_enqueue_style('zerobscrm-customeredit', 	plugins_url('/css/ZeroBSCRM.admin.customeredit.min.css',__FILE__) );

		}

				if (
				(isset($_GET['page']) && $_GET['page'] == 'manage-invoices') ||
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_invoice') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_invoice')
				) {

															wp_enqueue_script('zerobscrmbsjs', plugins_url('/js/lib/bootstrap.min.js',__FILE__), array( 'jquery' ));

					
					wp_enqueue_style('zerobscrm-invoicebuilder', 	plugins_url('/css/ZeroBSCRM.admin.invoicebuilder.min.css',__FILE__) );
					wp_enqueue_script('zerobscrm-invoicebuilderjs', plugins_url('/js/ZeroBSCRM.admin.invoicebuilder.min.js',__FILE__), array( 'jquery' ));

		}

				if (
				(isset($_GET['page']) && $_GET['page'] == 'manage-quotes') ||
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_quote') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_quote')
				) {

					wp_enqueue_style('zerobscrm-quotebuilder', 	plugins_url('/css/ZeroBSCRM.admin.quotebuilder.min.css',__FILE__) );
					wp_enqueue_script('zerobscrm-quotebuilderjs', plugins_url('/js/ZeroBSCRM.admin.quotebuilder.min.js',__FILE__), array( 'jquery' ));

		}

				if (
								(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_form') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_form')
				) {

					wp_enqueue_style('zerobscrmformcss', 	plugins_url('/css/ZeroBSCRM.admin.frontendforms.min.css',__FILE__),'', 1.17 );

		}

				if (
								(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_transaction') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_transaction')
				) {

					wp_enqueue_style('zerobscrmtranscss', 	plugins_url('/css/ZeroBSCRM.admin.transactionedit.min.css',__FILE__),'', 1.17 );

		}

				if (isset($_GET['page']) && $_GET['page'] == 'zerobscrm-plugin-settings') {


			

						if (isset($_GET['tab']) && $_GET['tab'] == 'fieldsorts'){

								wp_enqueue_script('zerobscrmadmjqui', plugins_url('/js/lib/jquery-ui.min.js',__FILE__), array( 'jquery' ));

								wp_enqueue_style('zerobscrmsortscss', 	plugins_url('/css/ZeroBSCRM.admin.sortables.min.css',__FILE__),array(),'2.10.3');

			}

		}





						

				if (
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_customer') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_customer')
				) {
						add_filter('post_updated_messages', 'zeroBSCRM_improvedPostMsgsCustomers');

				}
		if (
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_company') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_company')
				) {
						add_filter('post_updated_messages', 'zeroBSCRM_improvedPostMsgsCompanies');

				}
		if (
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_invoice') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_invoice')
				) {
						add_filter('post_updated_messages', 'zeroBSCRM_improvedPostMsgsInvoices');

				}
		if (
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_quote') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_quote')
				) {
						add_filter('post_updated_messages', 'zeroBSCRM_improvedPostMsgsQuotes');

				}
		if (
				(isset($_GET['post_type']) && $_GET['post_type'] == 'zerobs_transaction') || 
				(!empty($postTypeStr) && $postTypeStr == 'zerobs_transaction')
				) {
						add_filter('post_updated_messages', 'zeroBSCRM_improvedPostMsgsTransactions');

				}
	

	}
	
		
		zeroBSCRM_addThemeThumbnails();

		    if ($settings['perusercustomers'] && !current_user_can('administrator')){

    	
	    if (!$settings['usercangiveownership']){

	    		    	if (zeroBSCRM_is_existingcustomer_edit_page() || zeroBSCRM_is_existingcompany_edit_page()){

	    		
	    			    		if (isset($_GET['post']) && !empty($_GET['post'])) $postID = (int)$_GET['post'];

	    		if ($postID > 0){

	    				    			if (!current_user_can('administrator')){

	    					    				if (!zeroBS_checkOwner($postID,get_current_user_id())){

	    						    					
	    					$postType = 'zbs_customer'; if (isset( $_GET['post'] )) $postType = get_post_type( $_GET['post'] );

					        					        header("Location: edit.php?post_type=".$postType."&page=zbs-noaccess&id=".$postID);
					        exit();


	    				} 
	    			} 
	    		} 	    	} 	    }
		
    }

}








function zeroBSCRM__adminHeaderExpose(){

	if (zeroBSCRM_is_edit_page()){

				$outArr = zeroBS_getForms(false,1000);

				echo '<script type="text/javascript" id="wpzbscrmformsettings">var wpzbscrmroot = "'.ZEROBSCRM_URL.'";var zbsCRMFormList = '.json_encode($outArr).';</script>';

				echo '<script type="text/javascript">var zbscrmjs_globSecToken = \''.wp_create_nonce( "zbscrmjs-glob-ajax-nonce" ).'\';</script>';
	}

		echo '<script type="text/javascript" id="wpzbscrmglobal">var zbsCRMTimeZoneOffset = '.zeroBSCRM_getTimezoneOffset().';</script>';

		global $zeroBSCRM_Settings;

    $zbsMenuMode = $zeroBSCRM_Settings->get('menulayout');
    if($zbsMenuMode == 2){?>
    	<style>
    		#adminmenu .toplevel_page_sales-dash{ display:none; }
    	</style>
    <?php }


}
add_action('admin_head', 'zeroBSCRM__adminHeaderExpose'); 




#} Public Headers
function zeroBSCRM_publicHeaderExpose(){

	global $zeroBSCRM_Settings;	
		$settings = $zeroBSCRM_Settings->getAll();

	if (zeroBSCRM_isLoginPage()){ 

		if (isset($settings['loginlogourl']) && !empty($settings['loginlogourl'])){
	

						?><style type="text/css">
				.login h1 a {
					background-image: none,url(<?php echo $settings['loginlogourl']; ?>) !important;
				}
				.login #nav, .login #backtoblog {
					/*display:none;*/
				}
			</style><?php

		}

	}

}
add_action('login_head','zeroBSCRM_publicHeaderExpose');



function zeroBSCRM_exposePID(){

	global $post;

	if (isset($post) && isset($post->ID)) echo '<script type="text/javascript">var zbscrmJSFormspid = '.$post->ID.';</script>';

}








#} Load languages
function zeroBSCRM_load_textdomain() {
	load_plugin_textdomain( 'zerobscrm', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'zeroBSCRM_load_textdomain' );














	
		function zeroBSCRM_load_libs_js_momentdatepicker(){
	    add_action( 'admin_enqueue_scripts', 'zeroBSCRM_enqueue_libs_js_momentdatepicker' );
	}
	function zeroBSCRM_enqueue_libs_js_momentdatepicker(){

				wp_enqueue_script('wh-moment-v2-1-3-js',		plugins_url('/js/lib/moment.min.js',__FILE__),array('jquery'));
		wp_enqueue_script('wh-daterangepicker-v2-1-21-js',		plugins_url('/js/lib/daterangepicker.min.js',__FILE__),array('jquery'));
		
	}


		function zeroBSCRM_load_libs_js_customerfilters(){
	    add_action( 'admin_enqueue_scripts', 'zeroBSCRM_enqueue_libs_js_customerfilters' );
	}
	function zeroBSCRM_enqueue_libs_js_customerfilters(){

				wp_enqueue_script('zbs-js-customerfilters-v1',		plugins_url('/js/ZeroBSCRM.admin.customerfilters.min.js',__FILE__),array('jquery'));
	}

		function zeroBSCRM_enqueue_media_manager(){
		wp_enqueue_media();
		wp_enqueue_script( 'custom-header' );
	}
	add_action('admin_enqueue_scripts', 'zeroBSCRM_enqueue_media_manager');








		define('ZBSCRMCORELOADED',true); ?>