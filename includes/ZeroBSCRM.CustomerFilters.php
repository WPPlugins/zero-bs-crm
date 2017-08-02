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







		function zeroBSCRM_CustomerTypeList($jsCallbackFuncStr='',$inputDefaultValue=''){

		$ret = '';
		
						$ret .= '<div class="zbstypeaheadwrap">';

						$ret .= '<input class="zbstypeahead" type="text" value="'.$inputDefaultValue.'" placeholder="Customer name or email..." data-zbsopencallback="'.$jsCallbackFuncStr.'">';	

						$ret .= '</div>';

						global $haszbscrmBHURLCustomersOut; 
			if (!isset($haszbscrmBHURLCustomersOut)){ 
				$ret .= '<script type="text/javascript">var zbscrmBHURLCustomers = "'.admin_url('admin-ajax.php').'?zbscjson=true";</script>';
				$haszbscrmBHURLCustomersOut = true;
			}

				
		return $ret;

	}

		function zeroBSCRM_cjson(){

		header('Content-Type: application/json');
		$ret = array();

		if (is_user_logged_in() && zeroBSCRM_permsCustomers()){
		
			$ret = zeroBS_getCustomers(false,10000,0,false,false,'',false,false,false);

		}

		echo json_encode($ret);

		exit();

	}
 




 


function zbs_customerFiltersGetApplied($srcArr='usepost',$requireEmail=false){

	$fieldPrefix = '';

		if (is_string($srcArr) && $srcArr == 'usepost') {
		$srcArr = $_POST;
				$fieldPrefix = 'zbs-crm-customerfilter-';

		$fromPost = true;
	}

		global $zbsCustomerFields, $zbsCustomerFiltersInEffect, $zbsCustomerFiltersPosted;
	$allZBSTags = zeroBS_integrations_getAllCategories();

		$appliedFilters = array(); $activeFilters = 0;

	

				$possibleFilters = array(

						'status' => array('str','status'),
			'namestr' => array('str','custom:fullname'),
			'source' => array('str','cf1'),
			'valuefrom' => array('float','custom:totalval'),
			'valueto' => array('float','custom:totalval'),
			'addedrange' => array('str',''), 												'hasquote' => array('bool',''),
			'hasinv' => array('bool',''),
			'hastransact' => array('bool',''),
			'postcode' => array('str','postcode')

		);
		
		foreach ($possibleFilters as $key => $filter){

			$type = $filter[0];

			if (isset($srcArr[$fieldPrefix.$key])){

				switch ($type){

					case 'str':

												if (!empty($srcArr[$fieldPrefix.$key])) {

														$appliedFilters[$key] = sanitize_text_field($srcArr[$fieldPrefix.$key]);
							$activeFilters++;
						}

						break;

					case 'float':

												if (!empty($srcArr[$fieldPrefix.$key])) {

														$no = (float)$srcArr[$fieldPrefix.$key];

														$appliedFilters[$key] = $no;
							$activeFilters++;
						}

						break;

					case 'int':

												if (!empty($srcArr[$fieldPrefix.$key])) {

														$no = (int)$srcArr[$fieldPrefix.$key];

														$appliedFilters[$key] = $no;
							$activeFilters++;
						}

						break;

					case 'bool':

																																													if (isset($srcArr[$fieldPrefix.$key])) {

							if ($srcArr[$fieldPrefix.$key] == "1"){

																$appliedFilters[$key] = true;
								$activeFilters++;

							} else if ($srcArr[$fieldPrefix.$key] == "-1"){

																$appliedFilters[$key] = false;
								$activeFilters++;

							}

						}

						break;


				}

			}


		} 
				if (isset($appliedFilters['addedrange']) && !empty($appliedFilters['addedrange'])){

						if (strpos($appliedFilters['addedrange'],'-') > 0){

				$dateParts = explode(' - ',$appliedFilters['addedrange']);
				if (count($dateParts) == 2){

										if (!empty($dateParts[0])) {
						$appliedFilters['addedfrom'] = $dateParts[0];
						$activeFilters++;
					}
					if (!empty($dateParts[1])) {
						$appliedFilters['addedto'] = $dateParts[1];
						$activeFilters++;
					}

				}

			}

		}

				if (isset($fromPost)){
			$appliedFilters['tags'] = array();
			if (isset($allZBSTags) && count($allZBSTags) > 0){
			
								foreach ($allZBSTags as $tagGroupKey => $tagGroup){
					
					if (count($tagGroup) > 0) foreach ($tagGroup as $tag){
					
												if (isset($_POST['zbs-crm-customerfilter-tag-'.$tagGroupKey.'-'.$tag->term_id])) {

														$appliedFilters['tags'][$tagGroupKey][$tag->term_id] = true;
							$activeFilters++;

						}

					}


				}


			}

		} else {

									$appliedFilters['tags'] = array();

			if (isset($srcArr['tags'])){

				$srcTags = (array)$srcArr['tags'];

				if (is_array($srcTags) && count($srcTags) > 0) foreach ($srcTags as $tagKey=>$tagObj){

					$appliedFilters['tags'][$tagKey] = (array)$tagObj;

				}


			}

		}


				if (
			$requireEmail || 
			( isset($srcArr[$fieldPrefix.'require-email']) && !empty($srcArr[$fieldPrefix.'require-email']) )
			) $appliedFilters['require_email'] = true;

						if ($activeFilters > 0) $zbsCustomerFiltersPosted = $activeFilters;

		return $appliedFilters;

}


function zbs_customerFiltersGUI($selected=array(),$echo=false,$wrapClassAdditions='',$useAJAX=false,$requireEmail=false){

			
		global $zbsCustomerFields, $zbsCustomerFiltersInEffect, $zbsCustomerFiltersPosted;
	$allZBSTags = zeroBS_integrations_getAllCategories();

	
		$appliedFilters = zbs_customerFiltersGetApplied(); 		if (isset($selected) && is_array($selected) && count($selected) > 0) $appliedFilters = $selected;


	
				$zbsCustomerFiltersHTML = '';
		$currencyChar = zeroBSCRM_getCurrencyChr();

				$zbsCustomerFiltersHTML .= '<script type="text/javascript">var zbscrmjs_secToken = \''.wp_create_nonce( "zbscrmjs-ajax-nonce" ).'\';</script>';
		
		
						$zbsClassAdditions = ''; if (!empty($wrapClassAdditions)) $zbsClassAdditions = ' '.$wrapClassAdditions;

						$zbsCustomerFiltersHTML .= '<div class="zbs-crm-customerfilters-wrap'.$zbsClassAdditions.'"><form method="post" id="zbs-crm-customerfilter-form">';

								if ($requireEmail) $zbsCustomerFiltersHTML  .= '<input type="hidden" name="zbs-crm-customerfilter-require-email" value="1" />';

				
										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-status">'.__w('Status','zerobscrm').':</label>' 
											.	'<select id="zbs-crm-customerfilter-status" name="zbs-crm-customerfilter-status">' 
											.	'<option value="">Any</option>' 
											.	'<option value="" disabled="disabled">=========</option>';

																								if (isset($zbsCustomerFields['status']) && isset($zbsCustomerFields['status'][3]) && is_array($zbsCustomerFields['status'][3])) foreach ($zbsCustomerFields['status'][3] as $statusStr){

													$zbsCustomerFiltersHTML .=	'<option value="'.$statusStr.'"';
													if (isset($appliedFilters['status']) && $appliedFilters['status'] == $statusStr) $zbsCustomerFiltersHTML .=	' selected="selected"';
													$zbsCustomerFiltersHTML .=	'>'.$statusStr.'</option>';

												}

					$zbsCustomerFiltersHTML .=	'</select>'
											.	'</div>';

										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter zbs-crm-customerfilter-namestr"><label for="zbs-crm-customerfilter-namestr">'.__w('Name Contains','zerobscrm').':</label>' 
											.	'<input type="text" id="zbs-crm-customerfilter-namestr" name="zbs-crm-customerfilter-namestr" value="';
					if (isset($appliedFilters['namestr']) && !empty($appliedFilters['namestr'])) $zbsCustomerFiltersHTML .= $appliedFilters['namestr'];
					$zbsCustomerFiltersHTML .=	'" placeholder="'.__w('e.g. Mike','zerobscrm').'" />'
											.	'</div>';

										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter zbs-crm-customerfilter-valuerange"><label for="zbs-crm-customerfilter-valuefrom">'.__w('Total Value (Range)','zerobscrm').':</label>' 
											.	'<div class="input-group"><span class="input-group-addon">'.$currencyChar.'</span><input type="text" id="zbs-crm-customerfilter-valuefrom" name="zbs-crm-customerfilter-valuefrom" value="';
					if (isset($appliedFilters['valuefrom']) && !empty($appliedFilters['valuefrom'])) $zbsCustomerFiltersHTML .= $appliedFilters['valuefrom'];
					$zbsCustomerFiltersHTML .=	'" /></div> <span class="to-label">To</span> <div class="input-group"><span class="input-group-addon">'.$currencyChar.'</span>'
											.	'<input type="text" id="zbs-crm-customerfilter-valueto" name="zbs-crm-customerfilter-valueto" value="';
					if (isset($appliedFilters['valueto']) && !empty($appliedFilters['valueto'])) $zbsCustomerFiltersHTML .= $appliedFilters['valueto'];
					$zbsCustomerFiltersHTML .=	'" /></div>'
											.	'</div>';

					


										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-addedrange">'.__w('Date Added (Range)','zerobscrm').':</label>' 
											.	'<div id="zbs-crm-customerfilter-addedrange-reportrange">'
											.	'    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>'
											.	'</div>'
																						.	'<input type="hidden" id="zbs-crm-customerfilter-addedrange" name="zbs-crm-customerfilter-addedrange" class="zbs-date-range" value="';
					if (isset($appliedFilters['addedrange']) && !empty($appliedFilters['addedrange'])) $zbsCustomerFiltersHTML .= $appliedFilters['addedrange'];
					$zbsCustomerFiltersHTML .=	'" />'
											.	'</div>';






										if (isset($allZBSTags) && count($allZBSTags) > 0){
					
										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter-group">';

						$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter zbs-full-line"><label for="zbs-crm-customerfilter-tags" class="zbs-full-line">'.__w('Has Tag(s)','zerobscrm').':</label>';

												foreach ($allZBSTags as $tagGroupKey => $tagGroup){

														if (count($tagGroup) > 0) {

																$taxonomyDeets = get_taxonomy($tagGroupKey);
								$taxonomyName = $tagGroupKey; if (isset($taxonomyDeets->labels) && isset($taxonomyDeets->labels->name)) $taxonomyName = $taxonomyDeets->labels->name;

																$zbsCustomerFiltersHTML .=	'<div class="zbs-crm-customerfilter-group zbs-panel"><label class="zbs-panel-title" for="zbs-crm-customerfilter-tags-'.$tagGroupKey.'">'.$taxonomyName.'</label>';

																		foreach ($tagGroup as $tag){

																				$fieldName = 'zbs-crm-customerfilter-tag-'.$tagGroupKey.'-'.$tag->term_id;

																				$fieldActive = false; if (isset($appliedFilters['tags'][$tagGroupKey]) && isset($appliedFilters['tags'][$tagGroupKey][$tag->term_id])){ $fieldActive = true; }

										$zbsCustomerFiltersHTML .= '<div class="zbs-crm-customerfilter-groupline"><input type="checkbox" id="'.$fieldName.'" name="'.$fieldName.'" value="1"';
										if ($fieldActive) $zbsCustomerFiltersHTML .= ' checked="checked"';
										$zbsCustomerFiltersHTML .= ' /> <label for="'.$fieldName.'">'.$tag->name.'</label></div>';

									}														

								$zbsCustomerFiltersHTML .=	'</div>';

							}

						}

												$zbsCustomerFiltersHTML .=	'</div>';

												$zbsCustomerFiltersHTML .=	'</div>';

					}


										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter-group">';

												$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-hasquote">'.__w('Has Quote','zerobscrm').':</label>' 
												.	'<input type="checkbox" id="zbs-crm-customerfilter-hasquote" name="zbs-crm-customerfilter-hasquote" value="1"';
											if (isset($appliedFilters['hasquote']) && $appliedFilters['hasquote']) $zbsCustomerFiltersHTML .= ' checked="checked"';
											$zbsCustomerFiltersHTML .= ' />'
												.	'</div>';

												$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-hasinv">'.__w('Has Invoice','zerobscrm').':</label>' 
												.	'<input type="checkbox" id="zbs-crm-customerfilter-hasinv" name="zbs-crm-customerfilter-hasinv" value="1"';
											if (isset($appliedFilters['hasinv']) && $appliedFilters['hasinv']) $zbsCustomerFiltersHTML .= ' checked="checked"';
											$zbsCustomerFiltersHTML .= ' />'
												.	'</div>';

												$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-hastransact">'.__w('Has Transaction','zerobscrm').':</label>' 
												.	'<input type="checkbox" id="zbs-crm-customerfilter-hastransact" name="zbs-crm-customerfilter-hastransact" value="1"';
											if (isset($appliedFilters['hastransact']) && $appliedFilters['hastransact']) $zbsCustomerFiltersHTML .= ' checked="checked"';
											$zbsCustomerFiltersHTML .= ' />'
												.	'</div>';

					$zbsCustomerFiltersHTML .= '</div>';
	

										if (
						isset($zbsCustomerFields['cf1']) && 
						isset($zbsCustomerFields['cf1'][0]) && 
						isset($zbsCustomerFields['cf1'][1]) && 
						$zbsCustomerFields['cf1'][0] == 'select' && 
						$zbsCustomerFields['cf1'][1] == 'Source'
					){
						$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-source">'.__w('Source','zerobscrm').':</label>' 
												.	'<select id="zbs-crm-customerfilter-source" name="zbs-crm-customerfilter-source">' 
												.	'<option value="">Any</option>' 
												.	'<option value="" disabled="disabled">=========</option>';

																										if (isset($zbsCustomerFields['cf1']) && isset($zbsCustomerFields['cf1'][3]) && is_array($zbsCustomerFields['cf1'][3])) foreach ($zbsCustomerFields['cf1'][3] as $sourceStr){

														$zbsCustomerFiltersHTML .=	'<option value="'.$sourceStr.'"';
														if (isset($appliedFilters['source']) && $appliedFilters['source'] == $sourceStr) $zbsCustomerFiltersHTML .=	' selected="selected"';
														$zbsCustomerFiltersHTML .=	'>'.$sourceStr.'</option>';

													}

						$zbsCustomerFiltersHTML .=	'</select>'
												.	'</div>';
					}

										$zbsCustomerFiltersHTML .= 	'<div class="zbs-crm-customerfilter"><label for="zbs-crm-customerfilter-postcode">'.__w('Within Postal Code','zerobscrm').':</label>' 
											.	'<input type="text" id="zbs-crm-customerfilter-postcode" name="zbs-crm-customerfilter-postcode" value="';
					if (isset($appliedFilters['postcode']) && !empty($appliedFilters['postcode'])) $zbsCustomerFiltersHTML .= $appliedFilters['postcode'];
					$zbsCustomerFiltersHTML .=	'" placeholder="'.__w('e.g. AL1 or 90012','zerobscrm').'" />'
											.	'</div>';



					

						if (!$useAJAX){
				$zbsCustomerFiltersHTML .= '<div class="zbs-crm-closing-action"><button type="submit" class="">Apply</button></div>';
			} else {
				$zbsCustomerFiltersHTML .= '<div class="zbs-crm-closing-action"><button type="button" class="zbs-ajax-customer-filters">Apply</button></div>';				
				$zbsCustomerFiltersHTML .= '<div class="zbs-crm-customerfilter-ajax-output" style="display:none"></div>';
			}

						$zbsCustomerFiltersHTML .= '</form></div>';

						$zbsCustomerFiltersHTML .= 	'<script type="text/javascript">'
									.	"</script>";
									


									

				$zbsCustomerFiltersInEffect = $appliedFilters;

				

		if ($echo) echo $zbsCustomerFiltersHTML;		
	return $zbsCustomerFiltersHTML;

}


function zbs_customerFiltersRetrieveCustomers($perPage=10,$page=1,$forcePaging=false,$forceRefresh=false){

			
		global $zbsCustomerFields, $zbsCustomerFiltersInEffect, $zbsCustomerFiltersCurrentList;


		if (
				(isset($zbsCustomerFiltersCurrentList) && is_array($zbsCustomerFiltersCurrentList) && $forceRefresh) ||
				(!isset($zbsCustomerFiltersCurrentList) || !is_array($zbsCustomerFiltersCurrentList))
		){

		
						$appliedFilters = array(); 

						if (isset($zbsCustomerFiltersInEffect) && is_array($zbsCustomerFiltersInEffect) && count($zbsCustomerFiltersInEffect) > 0) $appliedFilters = $zbsCustomerFiltersInEffect;

			
								
																																					if ($forcePaging) {
								
								if ($perPage < 0) $perPageArg = 10; else $perPageArg = (int)$perPage;

							} else {

								$perPageArg = 10000; 
							}

										$args = array (
						'post_type'              => 'zerobs_customer',
						'post_status'            => 'publish',
						'posts_per_page'         => $perPageArg,
						'order'                  => 'DESC',
						'orderby'                => 'post_date'
					);
				
					if ($forcePaging){ 
												$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
						if ($actualPage > 0) $args['offset'] = $perPageArg*$actualPage;
					}


																											

															
																							if (isset($appliedFilters['namestr']) && !empty($appliedFilters['namestr'])){

														$args['s'] = $appliedFilters['namestr'];

						}

					
												if (isset($appliedFilters['addedfrom']) && !empty($appliedFilters['addedfrom'])){

														if (!isset($args['date_query'])) $args['date_query'] = array();

														$args['date_query'][] = array(
															'column' => 'post_date_gmt',
															'after' => $appliedFilters['addedfrom'],
														);

						}
						if (isset($appliedFilters['addedto']) && !empty($appliedFilters['addedto'])){

														if (!isset($args['date_query'])) $args['date_query'] = array();

														$args['date_query'][] = array(
															'column' => 'post_date_gmt',
															'before' => $appliedFilters['addedto'],
														);

						}



										if (isset($appliedFilters['tags']) && is_array($appliedFilters['tags']) && count($appliedFilters['tags']) > 0){

												$tagQueryArrays = array();

												foreach ($appliedFilters['tags'] as $taxonomyKey => $tagItem){

							$thisTaxonomyArr = array();

														foreach ($tagItem as $tagID => $activeFlag){

																$thisTaxonomyArr[] = $tagID;

							}

							if (count($thisTaxonomyArr) > 0){

																$tagQueryArrays[] = array(
															'taxonomy' => $taxonomyKey,
															'field'    => 'term_id',
															'terms'    => $thisTaxonomyArr,
														);

							}

							


						}

												if (count($tagQueryArrays) > 0){

																$args['tax_query'] = array();

																if (count($tagQueryArrays) > 1){

									$args['tax_query']['relation'] = 'AND';

								}

																foreach ($tagQueryArrays as $tqArr) $args['tax_query'][] = $tqArr;

						}

					}

															
					
															
															$potentialCustomerList = zeroBS_getCustomers(true,10,0,true,true,'',true,$args);
										$endingCustomerList = array();

															

																				$x = 0;
					if (count($potentialCustomerList) > 0) foreach ($potentialCustomerList as $potentialCustomer){

												$includeThisCustomer = true;

																																				$botheredAboutQuotes = true; $botheredAboutInvs = true; $botheredAboutTransactions = true;

																								$fullCustomer = $potentialCustomer;

												if (isset($appliedFilters['require_email'])){

							if (!zeroBSCRM_validateEmail($fullCustomer['meta']['email'])) $includeThisCustomer = false;

						}

												if (isset($appliedFilters['status']) && !empty($appliedFilters['status'])){

														if ($appliedFilters['status'] != $fullCustomer['meta']['status']) $includeThisCustomer = false;

						}

												if (isset($appliedFilters['source']) && !empty($appliedFilters['source'])){

														if ($appliedFilters['source'] != $fullCustomer['meta']['cf1']) $includeThisCustomer = false;

						}

												if (isset($appliedFilters['postcode']) && !empty($appliedFilters['postcode'])){

														$cleanPostcode = str_replace(' ','',$fullCustomer['meta']['postcode']);
							$filterPostcode = str_replace(' ','',$appliedFilters['postcode']);

														if (substr($cleanPostcode,0,strlen($filterPostcode)) != $filterPostcode) $includeThisCustomer = false;

						}

						
														$totVal = zeroBS_customerTotalValue($potentialCustomer['id'],$fullCustomer['invoices'],$fullCustomer['transactions']);

														if (isset($appliedFilters['valuefrom']) && !empty($appliedFilters['valuefrom'])){

																if ($totVal < $appliedFilters['valuefrom']) $includeThisCustomer = false;

							}
							if (isset($appliedFilters['valueto']) && !empty($appliedFilters['valueto'])){

																if ($totVal > $appliedFilters['valueto']) $includeThisCustomer = false;

							}


												if (isset($appliedFilters['hasquote']) && $appliedFilters['hasquote'] && count($fullCustomer['quotes']) < 1) $includeThisCustomer = false;
						if (isset($appliedFilters['hasinv']) && $appliedFilters['hasinv'] && count($fullCustomer['invoices']) < 1) $includeThisCustomer = false;
						if (isset($appliedFilters['hastransact']) && $appliedFilters['hastransact'] && count($fullCustomer['transactions']) < 1) $includeThisCustomer = false;


												if ($includeThisCustomer) $endingCustomerList[] = $fullCustomer;


					}
															

					
					   					   

										$zbsCustomerFiltersCurrentList = $endingCustomerList;


		} else { 
						$endingCustomerList = $zbsCustomerFiltersCurrentList;

		}

				if (!$forcePaging){

					 	if ($perPage < 0) $perPage = 10; else $perPage = (int)$perPage;

		 			 	$thisOffset = 0;
			$actualPage = $page-1; if ($actualPage < 0) $actualPage = 0;
			if ($actualPage > 0) $thisOffset = $perPage*$actualPage;


						if (isset($thisOffset)){

								$endingCustomerList = array_slice($endingCustomerList, $thisOffset, $perPage);

			}

		}

		
				

				return $endingCustomerList;
}

function zeroBS__customerFiltersRetrieveCustomerCount(){

		global $zbsCustomerFiltersCurrentList;

	if (isset($zbsCustomerFiltersCurrentList) && is_array($zbsCustomerFiltersCurrentList)) {
		
				return count($zbsCustomerFiltersCurrentList);

	} else {

				zbs_customerFiltersRetrieveCustomers();

				return count($zbsCustomerFiltersCurrentList);

	}

}

function zeroBS__customerFiltersRetrieveCustomerCountAndTopCustomers($countToReturn=3){

		global $zbsCustomerFiltersCurrentList;

	if (isset($zbsCustomerFiltersCurrentList) && is_array($zbsCustomerFiltersCurrentList)) {
		
				return array('count'=>count($zbsCustomerFiltersCurrentList),'top'=>array_slice($zbsCustomerFiltersCurrentList,0,$countToReturn));

	} else {

				$zbsCustomersFiltered = zbs_customerFiltersRetrieveCustomers();

				return array('count'=>count($zbsCustomerFiltersCurrentList),'top'=>array_slice($zbsCustomersFiltered,0,$countToReturn));

	}

}








		define('ZBSCRM_INC_CUSTFILT',true);