<?php /* !
 * WH Plugin Language Support
 * V1.0
 *
 * Copyright 2015, Plugin Beast, StormGate Ltd.
 *
 * Date: 24/09/15
 */


function _wx($str,$context,$domain='zerobscrm'){

		global $whLangsupport;
	if (isset($whLangsupport) && is_array($whLangsupport) && isset($whLangsupport[$domain])) {

				$settingsStr = $whLangsupport[$domain];

		global $$settingsStr;

				if (isset($$settingsStr)){

			$whPLangSettings = $$settingsStr->getAll();

			if (is_array($whPLangSettings)){

				if (isset($whPLangSettings['whlang'])){

										$langKey = md5($str); 
					if (isset($whPLangSettings['whlang'][$langKey])) {
												if (!empty($whPLangSettings['whlang'][$langKey][0])) return $whPLangSettings['whlang'][$langKey][0]; 
					}

				}

			}

		}

	}


		return _x($str,$context,$domain);

}

function __w($str,$domain='zerobscrm'){


		global $whLangsupport;

	
	if (isset($whLangsupport) && is_array($whLangsupport) && isset($whLangsupport[$domain])) {

		
				$settingsStr = $whLangsupport[$domain];

		global $$settingsStr;

				if (isset($$settingsStr)){

			
			$whPLangSettings = $$settingsStr->getAll();

			if (is_array($whPLangSettings)){

			
				if (isset($whPLangSettings['whlang'])){

										$langKey = md5($str);

					


					if (isset($whPLangSettings['whlang'][$langKey])) {

						
												if (!empty($whPLangSettings['whlang'][$langKey][0])) return $whPLangSettings['whlang'][$langKey][0]; 
					}

				}

			}

		}

	}

		return __($str,$domain);

}

function _we($str,$domain='zerobscrm'){

		global $whLangsupport;
	if (isset($whLangsupport) && is_array($whLangsupport) && isset($whLangsupport[$domain])) {

		
				$settingsStr = $whLangsupport[$domain];

		
		global $$settingsStr;

				if (isset($$settingsStr)){

			$whPLangSettings = $$settingsStr->getAll();

			
			if (is_array($whPLangSettings)){
			
			
				if (isset($whPLangSettings['whlang'])){
			
										$langKey = md5($str);

					
					if (isset($whPLangSettings['whlang'][$langKey])){

						
												if (!empty($whPLangSettings['whlang'][$langKey][0])) { echo $whPLangSettings['whlang'][$langKey][0]; return $whPLangSettings['whlang'][$langKey][0]; }

					}

				}

			}

		}

	}

		return _e($str,$domain);

}

function zeroBSCRM_whLangLibSupport(){}







function zeroBSCRM_whLangLibLangEditPage($dom,$settingsStr,$pluginName,$pluginEmail){

	
	global $$settingsStr;


	
				if (isset($$settingsStr) && isset($_POST['goUpdate'])){

			$whPLangSettings = $$settingsStr->getAll();

						if (is_array($whPLangSettings) && isset($whPLangSettings['whlang'])){

								$langModule = $whPLangSettings['whlang'];

								$langChanges = 0;
				if (count($whPLangSettings['whlang']) > 0) foreach ($whPLangSettings['whlang'] as $langKey => $langObj){

										if (isset($_POST['whl_'.$langKey]) && $_POST['whl_'.$langKey] != $langModule[$langKey][0]) {
						$langModule[$langKey][0] = sanitize_text_field($_POST['whl_'.$langKey]);
						$langChanges++;
					}

				}

								if ($langChanges > 0){

										$$settingsStr->update('whlang',$langModule);
					$whLangUpdateStr = 'Language Overrides Successfully Updated!';

				}

			} 
		} 


	

		$retStr = '<div id="whLangLibEditPage"><form method="post"><input type="hidden" name="goUpdate" value="1" />';

				if (isset($whLangUpdateStr)){

			$retStr .= whStyles_html_msg(0,$whLangUpdateStr);

		}

				$retStr .= '<p>Here you can edit the language labels for '.$pluginName.'.<br />By setting an option below, you override the default language for the plugin, as loaded from the translation file.<br />&nbsp;&nbsp;<strong>Current WordPress Language:</strong> '.get_bloginfo("language").' (<a href="'.admin_url('options-general.php').'" target="_blank">edit</a>)</p>';
		if (!empty($pluginEmail)) $retStr .= '<p>Note: This is useful to override a few labels, but if you\'d like to translate '.$pluginName.' into another language, please do email us <a href="mailto:'.$pluginEmail.'">'.$pluginEmail.'</a></p>';


				$retStr .= '<table class="table table-bordered table-striped wtab"><thead><tr>';

						$retStr .= '<th colspan="2" class="wmid">'.__w('General Language',$dom).':</th></tr></thead><tbody>';


						if (isset($$settingsStr)){

				$whPLangSettings = $$settingsStr->getAll();

								if (is_array($whPLangSettings) && isset($whPLangSettings['whlang'])){

					
										$lc = 1;
					if (count($whPLangSettings['whlang']) > 0) foreach ($whPLangSettings['whlang'] as $langKey => $langObj){

																								
						$retStr .= '<tr><td class="whlLangKey">'.$lc.'. "'.$langObj[1].'"</td><td><span>Override:</span><br /><textarea name="whl_'.$langKey.'" class="langOverride form-control">'.$langObj[0].'</textarea><br /><span class="restoreLang">Restore Default</span><span class="originalLang">'.$langObj[1].'</span></td></tr>';


						$lc++;

					}

				} else $retStr .= '<tr><td colspan="2" style="text-align:center;padding:40px;">Language could not be loaded for "'.$dom.'" (502)</td></tr>';

			} else $retStr .= '<tr><td colspan="2" style="text-align:center;padding:40px;">Language could not be loaded for "'.$dom.'" (501)</td></tr>';


				$retStr .= '<tr><td colspan="2" style="text-align:center;padding:40px;"><button type="submit" class="button button-success">Save Language Changes</button></td></tr>';

				$retStr .= '</tbody></table>';

				$retStr .= "<script type=\"text/javascript\">jQuery(document).ready(function(){jQuery('#whLangLibEditPage .restoreLang').click(function(){var def = jQuery('span.originalLang',jQuery(this).parent()).html();var decodedDef = jQuery(\"<div/>\").html(def).text();jQuery('textarea.langOverride',jQuery(this).parent()).val(decodedDef);});});</script>";



				$retStr .= '<style type="text/css">#whLangLibEditPage{width:780px;margin:10px}#whLangLibEditPage p{padding:14px;background:#FFF}#whLangLibEditPage table.wtab tr td textarea.langOverride{width:100%;min-height:70px}#whLangLibEditPage table.wtab tr td .restoreLang{font-size:11px;text-decoration:underline;color:#6060db;float:right;margin:4px 10px 4px 14px}#whLangLibEditPage table.wtab tr td .originalLang{display:none}#whLangLibEditPage table.wtab tr td.whlLangKey {width: 35%;}#whLangLibEditPage table.wtab tr td .restoreLang:hover {cursor:pointer;}</style>';
		



		$retStr .= '</div></form>';

	return $retStr;

}