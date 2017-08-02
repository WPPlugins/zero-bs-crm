<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.18
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 30/08/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;






   global $zbsExtensionsExcludeFromSettings; $zbsExtensionsExcludeFromSettings = array('pdfinv','gravforms','csvimporterlite','portal','invpro', 'cf7');







function zeroBSCRM_extensionsInstalled(){

		global $zeroBSCRM_extensionsInstalledList;
	
	return $zeroBSCRM_extensionsInstalledList;

}

function zeroBSCRM_extensionsList(){

		global $zeroBSCRM_extensionsCompleteList, $zeroBSCRM_extensionsFreeList;

		$ret = array(); foreach ($zeroBSCRM_extensionsCompleteList as $extKey => $extObj){

				$extName = $extKey; if (function_exists('zeroBSCRM_extension_name_'.$extKey) == 'function') $extName = call_user_func('zeroBSCRM_extension_name_'.$extKey);
				if ($extName == $extKey && isset($extObj['fallbackname'])) $extName = $extObj['fallbackname'];

		$ret[$extKey] = array(
			'name' => $extName,
			'installed' => zeroBSCRM_isExtensionInstalled($extKey),
			'free' => in_array($extKey,$zeroBSCRM_extensionsFreeList),			'meta' => $extObj
		);

	}
	
	return $ret;

}

function zeroBSCRM_extensionsListSegmented(){

	$exts = zeroBSCRM_extensionsList(); 

		$ret = array('free'=>array(),'paid'=>array());
	foreach ($exts as $extKey => $ext){

		if ($ext['free'])
			$ret['free'][$extKey] = $ext;
		else
			$ret['paid'][$extKey] = $ext;

	}

	return $ret;

}

function zeroBSCRM_isExtensionInstalled($extKey=''){

		global $zeroBSCRM_extensionsInstalledList;

	
	if (count($zeroBSCRM_extensionsInstalledList) > 0) foreach ($zeroBSCRM_extensionsInstalledList as $ext){
		if (!empty($ext) && $ext == $extKey) return true;
	}

		return false;

}

function zeroBSCRM_returnExtensionDetails($extKey=''){

		global $zeroBSCRM_extensionsCompleteList, $zeroBSCRM_extensionsFreeList;

	if (array_key_exists($extKey, $zeroBSCRM_extensionsCompleteList)){

		$extObj = $zeroBSCRM_extensionsCompleteList[$extKey];

				$extName = $extKey; if (function_exists('zeroBSCRM_extension_name_'.$extKey) == 'function') $extName = call_user_func('zeroBSCRM_extension_name_'.$extKey);
				if ($extName == $extKey && isset($extObj['fallbackname'])) $extName = $extObj['fallbackname'];

		return array(
			'key' => $extKey,
			'name' => $extName,
			'installed' => zeroBSCRM_isExtensionInstalled($extKey),
			'free' => in_array($extKey,$zeroBSCRM_extensionsFreeList),			'meta' => $extObj
		);

	}

		return false;

}








global $zeroBSCRM_extensionsInstalledList, $zeroBSCRM_extensionsCompleteList, $zeroBSCRM_extensionsFreeList;

$zeroBSCRM_extensionsCompleteList = array(
	
		'pay' => array(

		'fallbackname' => 'PayPal Sync', 
		'imgstr' => '<i class="fa fa-paypal" aria-hidden="true"></i>',
		'desc' => 'Retrieve all customer data from PayPal automatically.',
		'url' => 'http://zerobscrm.com/extensions/paypal-sync/',
		'colour' => '#009cde',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-paypal-sync/'

	),
	'woo' => array(

		'fallbackname' => 'WooCommerce Sync', 
		'imgstr' => '<i class="fa fa-shopping-cart" aria-hidden="true"></i>',
		'desc' => 'Retrieve all customer data from WooCommerce.',
		'url' => 'http://zerobscrm.com/extensions/woo-sync/',
		'colour' => 'rgb(216, 187, 73)',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-woosync/'

	),
	'stripesync' => array(

		'fallbackname' => 'Stripe Sync', 
		'imgstr' => '<i class="fa fa-cc-stripe" aria-hidden="true"></i>',
		'desc' => 'Retrieve all customer data from Stripe automatically.',
		'url' => 'https://zerobscrm.com/product/stripe-sync/',
		'colour' => '#5533ff',
		'helpurl' => 'https://docs.zerobscrm.com/doc/zerobs-extension-stripe-sync/'

	),
	'worldpay' => array(

		'fallbackname' => 'WorldPay Sync', 
		'imgstr' => '<i class="fa fa-credit-card" aria-hidden="true"></i>',
		'desc' => 'Create Customers from Gravity Forms (Integration).',
		'url' => 'http://zerobscrm.com/extensions/gravity-forms/',
		'colour' => '#f01e14', 
		'helpurl' => 'https://docs.zerobscrm.com/doc/zerobs-extension-worldpay-sync/'

	),
	'invpro' => array(

		'fallbackname' => 'Invoicing PRO', 
		'imgstr' => '<i class="fa fa-credit-card" aria-hidden="true"></i>',
		'desc' => 'Collect invoice payments directly from ZBS, with PayPal.',
		'url' => 'http://zerobscrm.com/extensions/invoicing-pro/',
		'colour' => '#1e0435',
		'helpurl' => 'http://docs.zerobscrm.com/doc/invoicing-pro/'

	),
	'csvimporter' => array(

		'fallbackname' => 'CSV Importer PRO', 
		'imgstr' => '<i class="fa fa-upload" aria-hidden="true"></i>',
		'desc' => 'Import existing customer data into ZBSCRM (PRO Version)',
		'url' => 'http://zerobscrm.com/extensions/simple-csv-importer/',
		'colour' => 'green',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-csv-importer/',

		'shortname' => 'CSV Imp. PRO' 
	),
	'mailcampaigns' => array(

		'fallbackname' => 'Mail Campaigns', 
		'imgstr' => '<i class="fa fa-envelope-o" aria-hidden="true"></i>',
		'desc' => 'Send emails to targeted segments of customers.',
		'url' => 'http://zerobscrm.com/extensions/mail-campaigns/',
		'colour' => 'rgb(173, 210, 152)',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-mail-campaigns/'

	),
	'salesdash' => array(

		'fallbackname' => 'Sales Dashboard', 
		'imgstr' => '<i class="fa fa-tachometer" aria-hidden="true"></i>',
		'desc' => 'The ultimate sales dashboard.',
		'url' => 'http://zerobscrm.com/extensions/sales-dashboard/',
		'colour' => 'black',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zero-bs-extension-sales-dashboard/'

	),
	'gravforms' => array(

		'fallbackname' => 'Gravity Forms', 
		'imgstr' => '<i class="fa fa-wpforms" aria-hidden="true"></i>',
		'desc' => 'Create Customers from Gravity Forms (Integration).',
		'url' => 'http://zerobscrm.com/extensions/gravity-forms/',
		'colour' => '#91a8ad', 		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-gravity-forms/'

	),

		'contactform7' => array(

		'fallbackname' => 'Contact Form 7', 
		'imgstr' => '<i class="fa fa-wpforms" aria-hidden="true"></i>',
		'desc' => 'Use Contact Form 7 to collect leads and customer info.',
		'url' => 'https://zerobscrm.com/product/contact-form-7/',
		'colour' => '#e2ca00',
		'helpurl' => 'https://docs.zerobscrm.com/doc/zerobs-extension-contact-form-7/'

	),
	'googlesync' => array(

		'fallbackname' => 'Google Contacts Sync', 
		'imgstr' => '<i class="fa fa-google" aria-hidden="true"></i>',
		'desc' => 'Retrieve all customer data from Google Contacts.',
		'url' => 'https://zerobscrm.com/product/google-contacts-sync/',
		'colour' => '#91a8ad', 
		'helpurl' => 'https://docs.zerobscrm.com/doc/zerobs-extension-google-contacts-sync/'

	),
	'groovesync' => array(

		'fallbackname' => 'Groove Sync', 
		'imgstr' => '<i class="fa fa-life-ring" aria-hidden="true"></i>',
		'desc' => 'Retrieve all customer data from Groove automatically.',
		'url' => 'https://zerobscrm.com/product/groove-sync/',
		'colour' => '#11ABCC',
		'helpurl' => 'https://docs.zerobscrm.com/doc/zerobs-extension-groove-sync/'

	),
	

		'portal' => array(

		'fallbackname' => 'Customer Portal', 
		'imgstr' => '<i class="fa fa-users" aria-hidden="true"></i>',
		'desc' => 'Add a client area to your website.',
		'colour' => '#833a3a',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/',

		'shortName' => 'Portal'

	),

	'api' => array(

		'fallbackname' => 'Zero BS API', 
		'imgstr' => '<i class="fa fa-random" aria-hidden="true"></i>',
		'desc' => 'Enable the API area of your CRM.',
		'colour' => '#000000',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/',

		'shortName' => 'API'

	),

	'quotebuilder' => array(

		'fallbackname' => 'Quote Builder', 
		'imgstr' => '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
		'desc' => 'Write and send professional proposals from ZBS.',
		'colour' => '#1fa67a',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/'

	),

	'invbuilder' => array(

		'fallbackname' => 'Invoice Builder', 
		'imgstr' => '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
		'desc' => 'Write and send professional invoices from ZBS.',
		'colour' => '#2a044a',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/'

	),

	'pdfinv' => array(

		'fallbackname' => 'PDF Invoicing', 
		'imgstr' => '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
		'desc' => 'Want PDF Invoices? Get this installed.',
		'colour' => 'green',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/'

	),

	'forms' => array(

		'fallbackname' => 'Front-end Forms', 
		'imgstr' => '<i class="fa fa-keyboard-o" aria-hidden="true"></i>',
		'desc' => 'Useful front-end forms to capture leads.',
		'colour' => 'rgb(126, 88, 232)',
		'helpurl' => 'http://docs.zerobscrm.com/documentation/',

		'shortname' => 'Forms' 
	),

		'csvimporterlite' => array(

		'fallbackname' => 'CSV Importer LITE', 
		'imgstr' => '<i class="fa fa-upload" aria-hidden="true"></i>',
		'desc' => 'Lite Version of CSV Customer Importer',
		'url' => 'http://zerobscrm.com/extensions/simple-csv-importer/',
		'colour' => 'green',
		'helpurl' => 'http://docs.zerobscrm.com/doc/zerobs-extension-csv-importer/',

		'shortname' => 'CSV Imp. LITE', 		'prover' => 'csvimporter' 
	)

); 
$zeroBSCRM_extensionsFreeList = array(
	
	'pdfinv',
	'forms',
	'quotebuilder',
	'invbuilder',
	'csvimporterlite',
	'portal',
	'api'

);

function zeroBSCRM_extension_name_pdfinv(){ return 'PDF Invoicing'; }
function zeroBSCRM_extension_name_forms(){ return 'Front-end Forms'; }
function zeroBSCRM_extension_name_quotebuilder(){ return 'Quote Builder'; }
function zeroBSCRM_extension_name_invbuilder(){ return 'Invoice Builder'; }
function zeroBSCRM_extension_name_csvimporterlite(){ return 'CSV Importer LITE'; }
function zeroBSCRM_extension_name_portal(){ return 'Customer Portal'; }
function zeroBSCRM_extension_name_api(){ return 'API'; }


function zeroBSCRM_extension_checkinstall_pdfinv(){

	$shouldBeInstalled = zeroBSCRM_getSetting('feat_pdfinv');

	if ($shouldBeInstalled == "1" && !file_exists(ZEROBSCRM_PATH.'includes/dompdf/autoload.inc.php')){

				global $zeroBSCRM_Settings;
		$zeroBSCRM_Settings->update('feat_pdfinv',-1);

				zeroBSCRM_extension_install_pdfinv();

	}

}

function zeroBSCRM_extension_install_pdfinv(){

		if (!file_exists(ZEROBSCRM_PATH.'includes/dompdf/autoload.inc.php')){

		global $zeroBSCRM_urls;

					
			set_time_limit(0); 			
						$workingDir = ZEROBSCRM_PATH.'temp'.time(); if (!file_exists($workingDir)) wp_mkdir_p($workingDir);
			$endingDir = ZEROBSCRM_PATH.'includes/dompdf'; if (!file_exists($endingDir)) wp_mkdir_p($endingDir);

			if (file_exists($endingDir) && file_exists($workingDir)){

								$libs = zeroBSCRM_retrieveFile($zeroBSCRM_urls['extdlrepo'].'pdfinv.zip',$workingDir.'/pdfinv.zip');

								if (file_exists($workingDir.'/pdfinv.zip')){

					
										$expanded = zeroBSCRM_expandArchive($workingDir.'/pdfinv.zip',$endingDir.'/');

										if (file_exists($endingDir.'/autoload.inc.php')){

												unlink($workingDir.'/pdfinv.zip');
						rmdir($workingDir);

												global $zeroBSCRM_Settings;
						$zeroBSCRM_Settings->update('feat_pdfinv',1);

												global $zeroBSCRM_extensionsInstalledList;
						if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
						$zeroBSCRM_extensionsInstalledList[] = 'pdfinv';

						return true;

					} else {

												global $zbsExtensionInstallError;
						$zbsExtensionInstallError = 'ZBS CRM was not able to extract the libraries it needs to in order to install PDF Invoices.';

					}


				} else {

										global $zbsExtensionInstallError;
					$zbsExtensionInstallError = 'ZBS CRM was not able to download the libraries it needs to in order to install PDF Invoices.';

				}


			} else {

								global $zbsExtensionInstallError;
				$zbsExtensionInstallError = 'ZBS CRM was not able to create the directories it needs to in order to install PDF Invoices.';

			}


	} else {

		

				global $zeroBSCRM_Settings;
		$zeroBSCRM_Settings->update('feat_pdfinv',1);

		return true;

	}

		return false;

}

function zeroBSCRM_extension_uninstall_pdfinv(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_pdfinv',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'pdfinv') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_forms(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_forms',1);

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'forms';

	return true;

}
function zeroBSCRM_extension_uninstall_forms(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_forms',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'forms') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_quotebuilder(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_quotes',1);

		zeroBSCRM_setFlushPermalinksNextLoad();

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'quotebuilder';

	return true;

}
function zeroBSCRM_extension_uninstall_quotebuilder(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_quotes',-1);

		zeroBSCRM_setFlushPermalinksNextLoad();

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'quotebuilder') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_invbuilder(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_invs',1);

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'invbuilder';

	return true;

}
function zeroBSCRM_extension_uninstall_invbuilder(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_invs',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'invbuilder') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_csvimporterlite(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_csvimporterlite',1);

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'csvimporterlite';

	return true;

}
function zeroBSCRM_extension_uninstall_csvimporterlite(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_csvimporterlite',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'csvimporterlite') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_portal(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_portal',1);

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'portal';

	return true;

}
function zeroBSCRM_extension_uninstall_portal(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_portal',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'portal') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}

function zeroBSCRM_extension_install_api(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_api',1);

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$zeroBSCRM_extensionsInstalledList[] = 'api';

	return true;

}
function zeroBSCRM_extension_uninstall_api(){

		global $zeroBSCRM_Settings;
	$zeroBSCRM_Settings->update('feat_api',-1);

		global $zeroBSCRM_extensionsInstalledList;
	$ret = array(); foreach ($zeroBSCRM_extensionsInstalledList as $x) if ($x !== 'api') $ret[] = $x;
	$zeroBSCRM_extensionsInstalledList = $ret;

	return true;

}



function zeroBSCRM_freeExtensionsInit(){

		global $zeroBSCRM_extensionsInstalledList;
	if (!is_array($zeroBSCRM_extensionsInstalledList)) $zeroBSCRM_extensionsInstalledList = array();
	$ZBSuseForms = zeroBSCRM_getSetting('feat_forms');
	if ($ZBSuseForms == 1) $zeroBSCRM_extensionsInstalledList[] = 'forms';
	$ZBSusePDFS = zeroBSCRM_getSetting('feat_pdfinv');
	if ($ZBSusePDFS == 1) $zeroBSCRM_extensionsInstalledList[] = 'pdfinv';
	$ZBSuse = zeroBSCRM_getSetting('feat_quotes');
	if ($ZBSuse == 1) $zeroBSCRM_extensionsInstalledList[] = 'quotebuilder';
	$ZBSuse = zeroBSCRM_getSetting('feat_invs');
	if ($ZBSuse == 1) $zeroBSCRM_extensionsInstalledList[] = 'invbuilder';
	$ZBSuse = zeroBSCRM_getSetting('feat_csvimporterlite');
	if ($ZBSuse == 1) $zeroBSCRM_extensionsInstalledList[] = 'csvimporterlite';
	$ZBSuse = zeroBSCRM_getSetting('feat_portal');
	if ($ZBSuse == 1) $zeroBSCRM_extensionsInstalledList[] = 'portal';
	$ZBSuse = zeroBSCRM_getSetting('feat_api');
	if ($ZBSuse == 1) $zeroBSCRM_extensionsInstalledList[] = 'api';


} zeroBSCRM_freeExtensionsInit();







define('ZBSCRMEXTLISTINC',true);