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




      global $zeroBSCRM_locale;


global $zeroBSCRM_localisationTextOverrides; $zeroBSCRM_localisationTextOverrides = array(

	'en-US' => array(
					array('County','State'),
					array('Postcode','Zip Code'),
					array('Mobile Telephone','Cell')
				)

);

function zeroBSCRM_localiseFieldLabel($labelStr=''){

	global $zeroBSCRM_localisationTextOverrides;

	$locale = zeroBSCRM_getLocale();

		if (isset($locale) && !empty($locale) && isset($zeroBSCRM_localisationTextOverrides[$locale])){

				$replacement = $labelStr;
		foreach ($zeroBSCRM_localisationTextOverrides[$locale] as $repArr){

			if (isset($repArr[0]) && $repArr[0] == $labelStr) $replacement = $repArr[1];

		}

				return $replacement;

	}

	return $labelStr;

}



function zeroBSCRM_getLocale(){

	global $zeroBSCRM_locale;

	if (isset($zeroBSCRM_locale)) return $zeroBSCRM_locale;

	$zeroBSCRM_locale = get_bloginfo("language"); 

	return $zeroBSCRM_locale;
}



define('ZBSCRMLOCALEINC',true);