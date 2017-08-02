<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.2+
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 29/12/2016
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;








        

                function zeroBSCRM_addQuoteQueryVars($aVars) {
          $aVars[] = "quotehash";
          return $aVars;
        } add_filter('query_vars', 'zeroBSCRM_addQuoteQueryVars');

        function zeroBSCRM_addInVoiceID($aVars) {
          $aVars[] = "invoiceid";
          return $aVars;
        } add_filter('query_vars', 'zeroBSCRM_addInVoiceID');



   
function zeroBSCRM_quotebuilder_add_rewrite_rules( $rules ) {
  $new = array();
  $new['proposal/(.+)/(.+)/?$'] = 'index.php?zerobs_quote=$matches[1]&quotehash=$matches[2]';

  return array_merge( $new, $rules ); }
add_filter( 'rewrite_rules_array', 'zeroBSCRM_quotebuilder_add_rewrite_rules' );



function zeroBSCRM_flush_rewrite_rules()
{
    $rules = get_option( 'rewrite_rules' );
    if ( ! isset( $rules['proposal/(.+)/?(.+)/?$'] ) ) { 
        global $wp_rewrite; $wp_rewrite->flush_rules();
    }
}
add_action( 'wp_loaded','zeroBSCRM_flush_rewrite_rules' );

function zeroBSCRM_flushPermalinksIfReq() {
    if ( get_option('zbscrm_flush_permalinks') == true ) {
        flush_rewrite_rules();
        update_option('zbscrm_flush_permalinks', false);
    }
}
function zeroBSCRM_setFlushPermalinksNextLoad(){
  update_option('zbscrm_flush_permalinks', true);
}




function zeroBSCRM_quotebuilder_filter_post_type_link( $link, $post ) {
  if ( $post->post_type == 'zerobs_quote' ) {
          
      
                
                $correctHash = get_post_meta($post->ID,'zbshash',true);
        $link = str_replace('proposal/'.$post->post_name.'/','proposal/'.$post->ID.'/'.$correctHash,$link);
      
      
      
      }
  return $link;
}
add_filter( 'post_type_link', 'zeroBSCRM_quotebuilder_filter_post_type_link', 10, 2 );



function zeroBSCRM_QuoteFE_validateAccess(){

    global $post, $zeroBSQuoteAccessPermitted; $zeroBSQuoteAccessPermitted = false;

        if (isset($post) && is_object($post) && $post->post_type == 'zerobs_quote' && !is_admin()){ 
                $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

        if ($useQuoteBuilder == "1"){

                    global $wp_query; $quoteHash = '';
          if (isset($wp_query->query_vars['quotehash'])) $quoteHash = urldecode($wp_query->query_vars['quotehash']);

                    
                        if (current_user_can('edit_post', $post->ID)) {
              $zeroBSQuoteAccessPermitted = true;

                            global $zbsQuoteAdminView; $zbsQuoteAdminView = true;
            }
            if (!$zeroBSQuoteAccessPermitted && !empty($quoteHash)){

                $correctHash = get_post_meta($post->ID,'zbshash',true);

              
                if (gettype($quoteHash) == 'string') if (hash_equals($correctHash,$quoteHash)) $zeroBSQuoteAccessPermitted = true;

            }

            if (!$zeroBSQuoteAccessPermitted) {
                                zeroBSCRM_force_404();
                exit();
            }


        }


    }

}

add_action( 'wp','zeroBSCRM_QuoteFE_validateAccess' );








function zeroBSCRM_content_intercept($content) {

    global $post;

        if ($post->post_type == 'zerobs_quote'){ 
                $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

        if ($useQuoteBuilder == "1"){

                    global $zeroBSQuoteAccessPermitted; if (!isset($zeroBSQuoteAccessPermitted)) zeroBSCRM_QuoteFE_validateAccess();

            if ($zeroBSQuoteAccessPermitted === true){

                                global $zbsQuoteData; if (!isset($zbsQuoteData)) $zbsQuoteData = zeroBS_getQuote($post->ID,true);

                                                                require_once(ZEROBSCRM_WILDPATH.'proposals/single-proposal.php');

                                return;

            } else {

                            return '';

            }

        }

    }

return $content;
}
add_filter('the_content', 'zeroBSCRM_content_intercept');

function zeroBSCRM_fixtitle($title, $id) {

    global $post;
    global $zbsQuoteData; if (!isset($zbsQuoteData) && isset($post->ID)) $zbsQuoteData = zeroBS_getQuote($post->ID,true);

    $uid = get_current_user_id();
    $cID = zeroBS_getCustomerIDFromWPID($uid);


      if (gettype($cID) == 'string') if($zbsQuoteData['customerid'] != $cID){
          if($post->post_type == 'zerobs_quote'){
             $title = '';
          }
          return $title;
      }
    


        if (isset($post) && $post->post_type == 'zerobs_quote'){ 

                    global $zeroBSQuoteAccessPermitted; if (!isset($zeroBSQuoteAccessPermitted)) zeroBSCRM_QuoteFE_validateAccess();

            if ($zeroBSQuoteAccessPermitted === true){

                                global $zbsQuoteData; if (!isset($zbsQuoteData)) $zbsQuoteData = zeroBS_getQuote($post->ID,true);

                if (isset($zbsQuoteData) && isset($zbsQuoteData['meta']) && isset($zbsQuoteData['meta']['name']) && !empty($zbsQuoteData['meta']['name'])) $proposalTitle = $zbsQuoteData['meta']['name'];

                                if (!empty($proposalTitle))
                  $proposalTitle = 'Proposal: '.$proposalTitle;
                else
                  $proposalTitle = 'Proposal';
      
              return $proposalTitle;

            } else {

                                return 'Not Found';

            }

    }



  return $title;

}
add_filter('the_title', 'zeroBSCRM_fixtitle', 10, 2);







define('ZBSCRM_INC_REWRITERULES',1);