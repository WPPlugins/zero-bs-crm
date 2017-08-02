<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.2.5
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 09/01/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;







function zeroBSCRM_retrieveEmailTemplate($template='default'){

	$templatedHTML = ''; 

	if (function_exists('file_get_contents')){

				$acceptableTemplates = array('default');

		if (in_array($template, $acceptableTemplates)){

		            try {

		            			                $templatedHTML = file_get_contents(ZEROBSCRM_PATH.'html/notifications/email-'.$template.'.html');


		            } catch (Exception $e){

		                
		            }

		}

	}

	return $templatedHTML;

}





   





	 	function zeroBSCRM_quote_generateNotificationHTML($quoteID=-1,$return=true){

		    if (!empty($quoteID)){

		        $templatedHTML = ''; $html = ''; $pWrap = '<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">';

		        		        $templatedHTML = zeroBSCRM_retrieveEmailTemplate('default');

		        		        if (!empty($templatedHTML)){

		        			        	$bodyHTML = '';

		        				        		$quote = zeroBS_getQuote($quoteID,true);

		        		if (isset($quote['meta']) && is_array($quote['meta'])){

		                    		                    $zbs_biz_name =  zeroBSCRM_getSetting('businessname');
		                    $zbs_biz_yourname =  zeroBSCRM_getSetting('businessyourname');

		                    $zbs_biz_extra =  zeroBSCRM_getSetting('businessextra');

		                    $zbs_biz_youremail =  zeroBSCRM_getSetting('businessyouremail');
		                    $zbs_biz_yoururl =  zeroBSCRM_getSetting('businessyoururl');
		                    $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']) . "&tab=invoices";
		
								                		$proposalTitle = '';
	                		if (isset($quote) && isset($quote['meta']) && isset($quote['meta']['name']) && !empty($quote['meta']['name'])) $proposalTitle = $quote['meta']['name'];
	                		$proposalURL = get_the_permalink($quoteID);

		        			
		        				$bodyHTML .= $pWrap.'Hi there,'.'</p>';
		        				$bodyHTML .= $pWrap.'This is a quick notification email to let you know that your proposal';
		        				if (isset($proposalTitle) && !empty($proposalTitle)) $bodyHTML .= ' "'.$proposalTitle.'"';
				            	if (isset($zbs_biz_name) && !empty($zbs_biz_name)) $bodyHTML .= ' from '.$zbs_biz_name;
		        				$bodyHTML .= ' is ready.'.'</p>';
		        				$bodyHTML .= $pWrap.'You can view the proposal online now:'.'</p>';

		        						        				$bodyHTML .= '<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;"><tbody>';
	                            $bodyHTML .= '<tr><td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;">';
	                            $bodyHTML .= '<tbody><tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;"> <a href="'.$proposalURL.'" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">View Proposal</a> </td>';
	                            $bodyHTML .= '</tr></tbody></table></td></tr></tbody></table>';



				        					        	
				                $bizInfoTable = '';

				                    $bizInfoTable = '<table class="table zbs-table">';
				                        $bizInfoTable .= '<tbody>';
				                            $bizInfoTable .= '<tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:5px;"><strong>'.$zbs_biz_name.'</strong></td></tr>';
				                            $bizInfoTable .= '<tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:5px;">'.$zbs_biz_yourname.'</td></tr>';
				                            $bizInfoTable .= '<tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:5px;">'.$zbs_biz_extra.'</td></tr>';
				                            				                           					                        $bizInfoTable .= '</tbody>';
				                    $bizInfoTable .= '</table>';

				            				            $subLine = 'You have received this notification because a proposal has been sent to you';
				            if (isset($zbs_biz_name) && !empty($zbs_biz_name)) $subLine .= ' by '.$zbs_biz_name;
				            $subLine .= '. If you believe this was sent in error, please reply and let us know.';


				            				            
				            				            				            $html = str_replace('###TITLE###','Proposal Notification',$templatedHTML);
				            $html = str_replace('###MSGCONTENT###',$bodyHTML,$html);  
				            $html = str_replace('###FOOTERBIZDEETS###',$bizInfoTable,$html);  
				            $html = str_replace('###FOOTERUNSUBDEETS###',$subLine,$html);  
				            $html = str_replace('###POWEREDBYDEETS###','Powered by <a href="http://zerobscrm.com" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;text-decoration:none;">ZeroBSCRM.com</a>.',$html);  

				       }

		            		            if (!$return) { echo $html; exit(); }

		        }  

		        return $html;


	       	}

	    	   	return;

	} 


		function zeroBSCRM_quote_generateAcceptNotifHTML($quoteID=-1,$quoteSignedBy='',$return=true){

		    if (!empty($quoteID)){

		        $templatedHTML = ''; $html = ''; $pWrap = '<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">';

		        		        $templatedHTML = zeroBSCRM_retrieveEmailTemplate('default');

		        		        if (!empty($templatedHTML)){

		        			        	$bodyHTML = '';

		        				        		$quote = zeroBS_getQuote($quoteID,true);

		        		if (isset($quote['meta']) && is_array($quote['meta'])){
		
								                		$proposalTitle = '';
	                		if (isset($quote) && isset($quote['meta']) && isset($quote['meta']['name']) && !empty($quote['meta']['name'])) $proposalTitle = $quote['meta']['name'];
	                		$proposalURL = get_the_permalink($quoteID);
	                		$proposalEditURL = get_edit_post_link($quoteID);


		        			
		        				$bodyHTML .= $pWrap.'Hi there,'.'</p>';
		        				$bodyHTML .= $pWrap.'This is a quick notification email to let you know that your proposal';
		        				if (isset($proposalTitle) && !empty($proposalTitle)) $bodyHTML .= ' "'.$proposalTitle.'"';
		        				$bodyHTML .= ' has been accepted!';
		        				if (isset($quoteSignedBy) && !empty($quoteSignedBy)) $bodyHTML .= ' by "<a href="mailto:'.$quoteSignedBy.'">'.$quoteSignedBy.'</a>"';
		        				$bodyHTML .='</p>';
		        				$bodyHTML .= $pWrap.'Here\'s the link to view the proposal online:'.'</p>';

		        						        				$bodyHTML .= '<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;"><tbody>';
	                            $bodyHTML .= '<tr><td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;">';
	                            $bodyHTML .= '<tbody><tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;"> <a href="'.$proposalURL.'" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">'.__w('View Proposal','zerobscrm').'</a> </td>';
	                            $bodyHTML .= '</tr></tbody></table></td></tr></tbody></table>';

		        				$bodyHTML .= $pWrap.'And here\'s the link to edit the proposal:'.'</p>';

		        						        				$bodyHTML .= '<table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;"><tbody>';
	                            $bodyHTML .= '<tr><td align="left" style="font-family:sans-serif;font-size:14px;vertical-align:top;padding-bottom:15px;"><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;width:auto;">';
	                            $bodyHTML .= '<tbody><tr><td style="font-family:sans-serif;font-size:14px;vertical-align:top;background-color:#ffffff;border-radius:5px;text-align:center;background-color:#3498db;"> <a href="'.$proposalEditURL.'" target="_blank" style="text-decoration:underline;background-color:#ffffff;border:solid 1px #3498db;border-radius:5px;box-sizing:border-box;color:#3498db;cursor:pointer;display:inline-block;font-size:14px;font-weight:bold;margin:0;padding:12px 25px;text-decoration:none;text-transform:capitalize;background-color:#3498db;border-color:#3498db;color:#ffffff;">'.__w('Edit Proposal','zerobscrm').'</a> </td>';
	                            $bodyHTML .= '</tr></tbody></table></td></tr></tbody></table>';


				            				            $subLine = 'You have received this notification because your proposal has been accepted in Zero BS CRM';
				            $subLine .= '. If you believe this was sent in error, please reply and let us know.';


				            				            
				            				            				            $html = str_replace('###TITLE###','Proposal Notification',$templatedHTML);
				            $html = str_replace('###MSGCONTENT###',$bodyHTML,$html);  
				            $html = str_replace('###FOOTERBIZDEETS###',$bizInfoTable,$html);  
				            $html = str_replace('###FOOTERUNSUBDEETS###',$subLine,$html);  
				            $html = str_replace('###POWEREDBYDEETS###','Powered by <a href="http://zerobscrm.com" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;text-decoration:none;">ZeroBSCRM.com</a>.',$html);  

				       }

		            		            if (!$return) { echo $html; exit(); }

		        }  

		        return $html;


	       	}

	    	   	return;

	} 







	 	function zeroBSCRM_INVMIKECHANGE_generateNotificationHTML($quoteID=-1,$return=true){

		    if (!empty($quoteID)){

		        $templatedHTML = ''; $html = ''; $pWrap = '<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">';

		        		        $templatedHTML = zeroBSCRM_retrieveEmailTemplate('default');

		        		        if (!empty($templatedHTML)){

		        			        	$inv = array();

		        		if (is_array($inv)){

					        						        	$bodyHTML = '';


					        	
					            					            $html = str_replace('###TITLE###','Proposal Notification',$templatedHTML);
					            $html = str_replace('###MSGCONTENT###',$bodyHTML,$html);  
					            $html = str_replace('###FOOTERBIZDEETS###',$bizInfoTable,$html);  
					            $html = str_replace('###FOOTERUNSUBDEETS###',$subLine,$html);  
					            $html = str_replace('###POWEREDBYDEETS###','Powered by <a href="http://zerobscrm.com" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;text-decoration:none;">ZeroBSCRM.com</a>.',$html);  

					      }

		            		            if (!$return) { echo $html; exit(); }

		        }  

		        return $html;


	       	}

	    	   	return;

	} 








	 	function zeroBSCRM_Portal_generateNotificationHTML($pwd=-1,$return=true, $email){

		    if (!empty($pwd)){

		        $templatedHTML = ''; $html = ''; $pWrap = '<p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">';

		        		        $templatedHTML = zeroBSCRM_retrieveEmailTemplate('default');

		        		        if (!empty($templatedHTML)){

		        			        	$inv = array();

		        		if (is_array($inv)){

					        						        	$bodyHTML = '';
					        	$bizInfoTable = '';
					        	$subLine = '';

					        	$login_url = esc_url( home_url( '/clients/login' ) ); 

					        	$bodyHTML = "<div style='text-align:center'><h1>Your Client Portal</h1>";
					        	$bodyHTML .= __w("Welcome to your Client Portal. You can login to your Portal at the following link","zerobscrm");
					        	$bodyHTML .= "</br></br>";
					        	$bodyHTML .= '<a href="'.$login_url.'">'.$login_url.'</a>';  
					        	$bodyHTML .= "</br></br>";
					        	$bodyHTML .= __w("Your Login Details are", "zerobscrm");
					        	$bodyHTML .= "</br></br>";
					        	$bodyHTML .= "<strong>" . __w("Username", "zerobscrm") . "</strong>: " . $email;
					        	$bodyHTML .= "<br/>";
					        	$bodyHTML .= "<strong>" . __w("Password", "zerobscrm") . "</strong>: " . $pwd;
					        	$bodyHTML .= "</div>";

					            					            $html = str_replace('###TITLE###','Welcome to your Client Portal',$templatedHTML);
					            $html = str_replace('###MSGCONTENT###',$bodyHTML,$html);  
					            $html = str_replace('###FOOTERBIZDEETS###',$bizInfoTable,$html);  
					            $html = str_replace('###FOOTERUNSUBDEETS###',$subLine,$html);  
					            $html = str_replace('###POWEREDBYDEETS###','Powered by <a href="http://zerobscrm.com" style="color:#3498db;text-decoration:underline;color:#999999;font-size:12px;text-align:center;text-decoration:none;">ZeroBSCRM.com</a>.',$html);  

					      }

		            		            if (!$return) { echo $html; exit(); }

		        }  

		        return $html;


	       	}

	    	   	return;

	} 








		define('ZBSCRM_INC_TEMPLATING',true);