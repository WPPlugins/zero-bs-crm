<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.19
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 25/10/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;




        $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');
    $showPoweredBy = zeroBSCRM_getSetting('showpoweredbyquotes');




        global $zbsCustomerFields,$zbsCustomerQuoteFields,$zbsCustomerInvoiceFields;    










    class zeroBS__MetaboxQuote {

        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_quote';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbscq_itemdetails',
                'Step 1: Quote Details',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {


                                $zbsQuote = get_post_meta($post->ID, 'zbs_customer_quote_meta', true);
                $zbsCustomerID = get_post_meta($post->ID, 'zbs_customer_quote_customer', true);
                $zbsTemplateIDUsed = get_post_meta($post->ID, 'zbs_quote_template_id', true);
                                $zbsTemplated = get_post_meta($post->ID, 'templated', true);
                if (!empty($zbsTemplated)) $zbsQuote['templated'] = true;


                                if (empty($zbsCustomerID) && isset($_GET['zbsprefillcust'])) $zbsCustomerID = (int)$_GET['zbsprefillcust'];



                                global $zbsCurrentEditQuote; $zbsCurrentEditQuote = $zbsQuote;

                


                global $zbsCustomerQuoteFields;
                $fields = $zbsCustomerQuoteFields;
                
                                $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

                ?><input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" /><?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' );

                                        if (gettype($zbsQuote) != "array") echo '<input type="hidden" name="zbscrm_newquote" value="1" />';

                                        if ($useQuoteBuilder == "1" && isset($zbsQuote['templated'])) echo '<input type="hidden" name="zbscrm_templated" id="zbscrm_templated" value="1" />';

                ?>

            <?php
            echo '<input type="hidden" name="quo-ajax-nonce" id="quo-ajax-nonce" value="' . wp_create_nonce( 'quo-ajax-nonce' ) . '" />';


                
            ?>

                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">

                    <?php 
                                        ?><tr class="wh-large"><th><label for="quoteid">QuoteRef (ID):</label></th>
                    <td>
                        <div class="zbs-prominent"><?php 

                                                                                                
                        $zbsQuoteID = get_post_meta($post->ID,"zbsid",true);
                        if (!empty($zbsQuoteID)) 
                            $quoteID = (int)$zbsQuoteID;
                        else                             $quoteID = zeroBSCRM_getNextQuoteID();

                        echo $quoteID;

                        ?><input type="hidden" name="zbsquoteid" value="<?php echo $quoteID; ?>" /></div>
                    </td></tr><?php


                                        ?><tr class="wh-large"><th><label for="zbscq_customer">Customer:</label></th>
                    <td><?php

                        
                                                        $prefillStr = ''; if (isset($zbsCustomerID) && !empty($zbsCustomerID)){

                                                                                                $prefillStr = zeroBS_getCustomerNameShort($zbsCustomerID);
                                
                            }

                                                        echo zeroBSCRM_CustomerTypeList('zbscrmjs_quoteCustomerSelect',$prefillStr);

                                                        ?><input type="hidden" name="zbscq_customer" id="zbscq_customer" value="<?php echo $zbsCustomerID; ?>" /><?php

                                                        ?><script type="text/javascript">

                                function zbscrmjs_quoteCustomerSelect(cust){

                                    // pass id to hidden input
                                    jQuery('#zbscq_customer').val(cust.id);

                                    // enable/disable button if present (here is def present)
                                    jQuery('#zbsQuoteBuilderStep2').removeAttr('disabled');
                                    jQuery('#zbsQuoteBuilderStep2info').hide();


                                }

                            </script><?php
                     ?>
                    </td></tr><?php


                    foreach ($fields as $fieldK => $fieldV){


                        switch ($fieldV[0]){

                            case 'text':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control widetext" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsQuote[$fieldK])) echo $zbsQuote[$fieldK]; ?>" autocomplete="off" />
                                </td></tr><?php

                                break;

                            case 'price':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <?php echo zeroBSCRM_getCurrencyChr(); ?> <input style="width: 130px;display: inline-block;" type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control numbersOnly" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsQuote[$fieldK])) echo $zbsQuote[$fieldK]; ?>" autocomplete="off" />
                                </td></tr><?php

                                break;


                            case 'date':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-date" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsQuote[$fieldK])) echo $zbsQuote[$fieldK]; ?>" autocomplete="off" />
                                </td></tr><?php

                                break;

                            case 'select':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <select name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" autocomplete="off">
                                        <?php

                                            if (isset($fieldV[3]) && count($fieldV[3]) > 0){

                                                                                                echo '<option value="" disabled="disabled"';
                                                if (!isset($zbsQuote[$fieldK]) || (isset($zbsQuote[$fieldK])) && empty($zbsQuote[$fieldK])) echo ' selected="selected"';
                                                echo '>Select</option>';

                                                foreach ($fieldV[3] as $opt){

                                                    echo '<option value="'.$opt.'"';
                                                    if (isset($zbsQuote[$fieldK]) && $zbsQuote[$fieldK] == $opt) echo ' selected="selected"'; 
                                                    echo '>'.$opt.'</option>';

                                                }

                                            } else echo '<option value="">No Options!</option>';

                                        ?>
                                    </select>
                                </td></tr><?php

                                break;

                            case 'tel':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-tel" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsQuote[$fieldK])) echo $zbsQuote[$fieldK]; ?>" autocomplete="off" />
                                </td></tr><?php

                                break;

                            case 'email':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-email" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsQuote[$fieldK])) echo $zbsQuote[$fieldK]; ?>" autocomplete="off" />
                                </td></tr><?php

                                break;

                            case 'textarea':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <textarea name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" autocomplete="off"><?php if (isset($zbsQuote[$fieldK])) echo zeroBSCRM_textExpose($zbsQuote[$fieldK]); ?></textarea>
                                </td></tr><?php

                                break;

                                                        case 'selectcountry':

                                $countries = zeroBSCRM_loadCountryList();

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <select name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" autocomplete="off">
                                        <?php

                                                                                        if (isset($countries) && count($countries) > 0){

                                                                                                echo '<option value="" disabled="disabled"';
                                                if (!isset($zbsQuote[$fieldK]) || (isset($zbsQuote[$fieldK])) && empty($zbsQuote[$fieldK])) echo ' selected="selected"';
                                                echo '>Select</option>';

                                                foreach ($countries as $country){

                                                    echo '<option value="'.$country.'"';
                                                    if (isset($zbsQuote[$fieldK]) && strtolower($zbsQuote[$fieldK]) == strtolower($country)) echo ' selected="selected"'; 
                                                    echo '>'.$country.'</option>';

                                                }

                                            } else echo '<option value="">No Countries Loaded!</option>';

                                        ?>
                                    </select>
                                </td></tr><?php

                                break;


                        }



                    }




                                        if ($useQuoteBuilder == "1" && (gettype($zbsQuote) != "array" || !isset($zbsQuote['templated']))){

                        ?><tr class="wh-large" id="zbs-quote-builder-step-1">

                            <th colspan="2">

                                <div class="zbs-move-on-wrap">

                                    <!-- infoz -->
                                    <h3>Publish this Quote</h3>
                                    <p>Do you want to use the Quote Builder to publish this quote? (This lets you email it to a client directly, for approval)</p>

                                    <input type="hidden" name="zbs_quote_template_id_used" id="zbs_quote_template_id_used" value="<?php if (isset($zbsTemplateIDUsed) && !empty($zbsTemplateIDUsed)) echo $zbsTemplateIDUsed; ?>" />
                                    <select class="form-control" name="zbs_quote_template_id" id="zbs_quote_template_id">
                                        <option value="" disabled="disabled">Select a template:</option>
                                        <?php

                                            $templates = zeroBS_getQuoteTemplates(true,100,0);

                                                                                        
                                            if (count($templates) > 0) foreach ($templates as $template){

                                                $templateName = 'Template '.$template['id']; 
                                                if (isset($template['name']) && !empty($template['name'])) $templateName = $template['name'].' ('.$template['id'].')';

                                                echo '<option value="'.$template['id'].'"';
                                                                                                echo '>'.$templateName.'</option>';

                                            }

                                        ?>
                                        <option value="">Blank Template</option>
                                    </select>
                                    <br />
                                    <button type="button" id="zbsQuoteBuilderStep2" class="button button-primary button-large xl"<?php if (!isset($zbsCustomerID) || empty($zbsCustomerID)){ echo ' disabled="disabled"'; } ?>>Use Quote Builder</button>
                                    <?php if (!isset($zbsCustomerID) || empty($zbsCustomerID)){ ?>
                                    <p id="zbsQuoteBuilderStep2info">(You'll need to assign this Quote to a customer to use this)</p>
                                    <?php } ?>

                                </div>

                            </th>

                        </tr><?php

                    } ?>

            </table>



            <?php  


        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbscq_itemdetails'  && $post->post_type == $this->postType){

                                        $quoteOffset = zeroBSCRM_getQuoteOffset();
                    $quoteID = (int)$post_id+$quoteOffset; if (isset($_POST['zbsquoteid']) && !empty($_POST['zbsquoteid'])) $quoteID = (int)$_POST['zbsquoteid'];
                    update_post_meta($post_id,"zbsid",$quoteID);

                                        if (!empty($quoteID)) zeroBSCRM_setMaxQuoteID($quoteID);


                    $zbsCustomerQuoteMeta = array();


                    global $zbsCustomerQuoteFields;

                    foreach ($zbsCustomerQuoteFields as $fK => $fV){

                        $zbsCustomerQuoteMeta[$fK] = '';

                        if (isset($_POST['zbscq_'.$fK])) {

                            switch ($fV[0]){

                                case 'tel':

                                                                        $zbsCustomerQuoteMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);
                                    preg_replace("/[^0-9 ]/", '', $zbsCustomerQuoteMeta[$fK]);
                                    break;

                                case 'price':

                                                                        $zbsCustomerQuoteMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);
                                    $zbsCustomerQuoteMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsCustomerQuoteMeta[$fK]);
                                    $zbsCustomerQuoteMeta[$fK] = floatval($zbsCustomerQuoteMeta[$fK]);
                                    break;


                                case 'textarea':

                                    $zbsCustomerQuoteMeta[$fK] = zeroBSCRM_textProcess($_POST['zbscq_'.$fK]);

                                    break;


                                default:

                                    $zbsCustomerQuoteMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);

                                    break;


                            }

                        }


                    }

                                                                                                                        $zbsCustomerQuoteCustomer = -1; if (isset($_POST['zbscq_customer'])) $zbsCustomerQuoteCustomer = (int)$_POST['zbscq_customer'];
                    if ($zbsCustomerQuoteCustomer !== -1) update_post_meta($post_id, 'zbs_customer_quote_customer', $zbsCustomerQuoteCustomer);



                                        update_post_meta($post_id, 'zbs_customer_quote_meta', $zbsCustomerQuoteMeta);


                                        


                                                            if (isset($_POST['zbscrm_newquote']) && $_POST['zbscrm_newquote'] == 1){

                        zeroBSCRM_FireInternalAutomator('quote.new',array(
                            'id'=>$post_id,
                            'againstid' => $zbsCustomerQuoteCustomer,
                            'quoteMeta'=> $zbsCustomerQuoteMeta,
                            'zbsid'=>$quoteID
                            ));
                        
                    }

                }


                
                if( $metabox_id == 'wpzbscsub_quotecontent' && $post->post_type == $this->postType){

                    
                        
                      if (isset($_POST['zbs_quote_content'])) {

                        
                                                $data=htmlspecialchars($_POST['zbs_quote_content']);
                        update_post_meta($post_id, 'zbs_quote_content', $data );

                                                $templateID = ''; if (isset($_POST['zbs_quote_template_id_used'])) $templateID = (int)sanitize_text_field($_POST['zbs_quote_template_id_used']);
                        update_post_meta($post_id, 'zbs_quote_template_id',$templateID);
                        update_post_meta($post_id, 'templated',1);


                      }

                }

                

            }

            return $post;
        }
    }

    $zeroBS__MetaboxQuote = new zeroBS__MetaboxQuote( __FILE__ );








    class zeroBS__QuoteContentMetabox {

        static $instance;
        private $postType;
        private $postTypesLabels;

        public function __construct( $plugin_file ) {

            self::$instance = $this;

            $this->postType = 'zerobs_quote';        
                        $this->postTypesLabels = array(
                'zerobs_quote' => 'Quote'
            );
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

                    }

        public function initMetaBox(){

                add_meta_box(
                    'wpzbscsub_quotecontent',
                    'Step 2: Quote Content',                     array( $this, 'print_meta_box' ),
                    $this->postType,
                    'normal',
                    'low'
                );


        }
        public function print_meta_box( $post, $metabox ) {

                        ?><input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" /><?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' );

            
                        $content = get_post_meta($post->ID, 'zbs_quote_content' , true ) ;
            wp_editor( htmlspecialchars_decode($content), 'zbs_quote_content', array('editor_height',580)); 
        }
    }

    $zeroBS__QuoteContentMetabox = new zeroBS__QuoteContentMetabox( __FILE__ );














    class zeroBS__QuoteActionsMetabox {

        static $instance;
        private $postType;
        private $postTypesLabels;

        public function __construct( $plugin_file ) {

            self::$instance = $this;

            $this->postType = 'zerobs_quote';        
                        $this->postTypesLabels = array(
                'zerobs_quote' => 'Quote'
            );
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

                    }

        public function initMetaBox(){

                add_meta_box(
                    'wpzbscsub_quoteactions',
                    'Step 3: Publish and Send',                     array( $this, 'print_meta_box' ),
                    $this->postType,
                    'normal',
                    'low'
                );


        }
        public function print_meta_box( $post, $metabox ) {

                        global $zbsCurrentEditQuote; $zbsQuote = $zbsCurrentEditQuote;
                
                        $useQuoteBuilder = zeroBSCRM_getSetting('usequotebuilder');

                        if ($useQuoteBuilder == "1") { 

                                $zbsCustomerID = get_post_meta($post->ID, 'zbs_customer_quote_customer', true);
                $customerEmail = ''; if (!empty($zbsCustomerID)) {
                    $customerMeta = zeroBS_getCustomerMeta($zbsCustomerID);
                    if (isset($customerMeta['email'])) $customerMeta = $customerMeta['email'];

                }

                ?><input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" /><?php

                                if (gettype($zbsQuote) != "array" || !isset($zbsQuote['templated'])){

                        ?>
                            <div class="zbs-move-on-wrap" style="padding-top:30px;">

                                <!-- infoz -->
                                <h3>Publish this Quote</h3>
                                <p>When you've finished writing your Quote, save it here before sending on to your customer:</p>

                                <button type="button" id="zbsQuoteBuilderStep3" class="button button-primary button-large xl">Save Quote</button>

                            </div>

                        <?php

                } else {

                    
                        ?>

                            <div class="zbs-move-on-wrap" style="padding-top:30px;">
                                <?php 
                                                                        echo '<script type="text/javascript">var zbscrmjs_secToken = \''.wp_create_nonce( "zbscrmjs-ajax-nonce" ).'\';</script>';
                                ?>

                                <!-- infoz -->
                                <h3>Email or Share</h3>
                                <p>Great! Your Quote has been published. You can now email it to your customer, or share the link directly:</p>

                                <div class="zbsEmailOrShare">
                                    <h4>Email to Customer:</h4>
                                    <!-- todo -->                                    
                                    <p><input type="text" class="form-control" id="zbsQuoteBuilderEmailTo" value="<?php echo $customerEmail; ?>" placeholder="e.g. customer@yahoo.com" data-quoteid="<?php echo $post->ID; ?>" /></p>
                                    <p><button type="button" id="zbsQuoteBuilderSendNotification" class="button button-primary button-large">Send Quote</button></p>
                                    <p class="small" id="zbsQuoteBuilderEmailToErr" style="display:none">An Email Address to send to is required!</p>
                                </div>
                                <div class="zbsEmailOrShare">
                                    <h4>Share the Link or <a href="<?php echo get_the_permalink($post->ID); ?>" target="_blank">preview</a>:</h4>
                                    <p><input type="text" class="form-control" id="zbsQuoteBuilderURL" value="<?php echo get_the_permalink($post->ID); ?>" /></p>
                                    <!--<p class="small">Note: Anyone who has this link can view this proposal.</p>-->
                                </div>                               

                            </div>

                        <?php

                }

            } 
        }
        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbscsub_quoteactions'  && $post->post_type == $this->postType){

                    
                }
            }

            return $post;
        }
    }

    $zeroBS__QuoteActionsMetabox = new zeroBS__QuoteActionsMetabox( __FILE__ );









    function zeroBS__addQuoteMetaBoxes() {  

                add_meta_box('zerobs-customer-quotes', 'Quote Files (optional)', 'zeroBS__MetaboxFilesQuotes', 'zerobs_quote', 'normal', 'low');  

    }
    add_action('add_meta_boxes', 'zeroBS__addQuoteMetaBoxes');  










function zeroBS__MetaboxFilesQuotes($post) {  
        

    $html = '';

    
                        $zbsCustomerQuotes = get_post_meta($post->ID, 'zbs_customer_quotes', true);

    ?>
            <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItemQuotes">

                <?php 

                                if (is_array($zbsCustomerQuotes) && count($zbsCustomerQuotes) > 0){ 
                  ?><tr class="wh-large"><th><label><?php echo count($zbsCustomerQuotes).' Quote(s):'; ?></label></th>
                            <td id="zbsFileWrapQuotes">
                                <?php $fileLineIndx = 1; foreach($zbsCustomerQuotes as $quote){

                                    $file = basename($quote['file']);
                                    echo '<div class="zbsFileLine" id="zbsFileLineQuote'.$fileLineIndx.'"><a href="'.$quote['url'].'" target="_blank">'.$file.'</a> (<span class="zbsDelFile" data-delurl="'.$quote['url'].'"><i class="fa fa-trash"></i></span>)</div>';
                                    $fileLineIndx++;

                                } ?>
                            </td></tr><?php

                } ?>

                <?php 

                        wp_nonce_field(plugin_basename(__FILE__), 'zbsc_quote_attachment_nonce');
                         
                        $html .= '<input type="file" id="zbsc_quote_attachment" name="zbsc_quote_attachment" value="" size="25">';
                        
                        ?><tr class="wh-large"><th><label>Add Quote:</label><br />(Optional)<br />Accepted File Types:<br /><?php echo zeroBS_acceptableFileTypeListStr(); ?></th>
                            <td><?php
                        echo $html;

                ?></td></tr>

            
            </table>
            <script type="text/javascript">

                var zbsQuotesCurrentlyDeleting = false;

                jQuery('document').ready(function(){

                    // turn off auto-complete on records via form attr... should be global for all ZBS record pages
                    jQuery('#post').attr('autocomplete','off');

                    jQuery('.zbsDelFile').click(function(){

                        if (!window.zbsQuotesCurrentlyDeleting){

                            // blocking
                            window.zbsQuotesCurrentlyDeleting = true;

                            var delUrl = jQuery(this).attr('data-delurl');
                            var lineIDtoRemove = jQuery(this).closest('.zbsFileLine').attr('id');

                            if (typeof delUrl != "undefined" && delUrl != ''){



                                  // postbag!
                                  var data = {
                                    'action': 'delFile',
                                    'zbsfType': 'quotes',
                                    'zbsDel':  delUrl, // could be csv, never used though
                                    'zbsCID': <?php echo $post->ID; ?>,
                                    'sec': window.zbscrmjs_secToken
                                  };

                                  // Send it Pat :D
                                  jQuery.ajax({
                                          type: "POST",
                                          url: ajaxurl, // admin side is just ajaxurl not wptbpAJAX.ajaxurl,
                                          "data": data,
                                          dataType: 'json',
                                          timeout: 20000,
                                          success: function(response) {

                                            // visually remove
                                            //jQuery(this).closest('.zbsFileLine').remove();
                                            jQuery('#' + lineIDtoRemove).remove();


                                            // Callback
                                            //if (typeof cb == "function") cb(response);
                                            //callback(response);

                                          },
                                          error: function(response){

                                            jQuery('#zbsFileWrapQuotes').append('<div class="alert alert-error" style="margin-top:10px;"><strong>Error:</strong> Unable to delete this file.</div>');

                                            // Callback
                                            //if (typeof errorcb == "function") errorcb(response);
                                            //callback(response);


                                          }

                                        });

                            }

                            window.zbsQuotesCurrentlyDeleting = false;

                        } // / blocking

                    });

                });


            </script><?php
}

add_action('save_post', 'zbsc_save_quote_data');
function zbsc_save_quote_data($id) {


    if(!empty($_FILES['zbsc_quote_attachment']['name'])) {

    
    if(!wp_verify_nonce($_POST['zbsc_quote_attachment_nonce'], plugin_basename(__FILE__))) {
      return $id;
    }        
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $id;
    }        

    
    if (!zeroBSCRM_permsQuotes()){
        return $id;
    }
    


        $supported_types = zeroBS_acceptableFileTypeMIMEArr();         $arr_file_type = wp_check_filetype(basename($_FILES['zbsc_quote_attachment']['name']));
        $uploaded_type = $arr_file_type['type'];

        if(in_array($uploaded_type, $supported_types)) {
            $upload = wp_upload_bits($_FILES['zbsc_quote_attachment']['name'], null, file_get_contents($_FILES['zbsc_quote_attachment']['tmp_name']));
            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                

                                                                $zbsCustomerQuotes = get_post_meta($id,'zbs_customer_quotes', true);

                                if (is_array($zbsCustomerQuotes)){

                                                                        $zbsCustomerQuotes[] = $upload;

                                } else {

                                                                        $zbsCustomerQuotes = array($upload);

                                }

                                update_post_meta($id, 'zbs_customer_quotes', $zbsCustomerQuotes);  
            }
        }
        else {
            wp_die("The file type that you've uploaded is not an accepted file format.");
        }
    }
}








    class zeroBS__QuoteStatusMetabox {

        static $instance;
        private $postTypes;

        public function __construct( $plugin_file ) {
        
            self::$instance = $this;

                        $this->postTypes = array('zerobs_quote');        
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

        }

        public function initMetaBox(){

            if (count($this->postTypes) > 0) foreach ($this->postTypes as $pt){

                                $callBackArr = array($this,$pt);

                add_meta_box(
                    'wpzbscquote_status',
                    'Quote Public Status',
                    array( $this, 'print_meta_box' ),
                    $pt,
                    'side',
                    'low',
                    $callBackArr
                );

            }

        }

        public function print_meta_box( $post, $metabox ) {

            global $useQuoteBuilder;

                        $zbsQuote = get_post_meta($post->ID, 'zbs_customer_quote_meta', true);
                                $zbsTemplated = get_post_meta($post->ID, 'templated', true);
                if (!empty($zbsTemplated)) $zbsQuote['templated'] = true;

                                    if ($useQuoteBuilder == "1" && (is_array($zbsQuote) && isset($zbsQuote['templated']))) { 

                    if (isset($zbsQuote) && is_array($zbsQuote) && isset($zbsQuote['accepted'])){

                                                $acceptedDate = date(zeroBSCRM_getTimeFormat().' '.zeroBSCRM_getDateFormat(),$zbsQuote['accepted'][0]);
                        $acceptedBy = $zbsQuote['accepted'][1];
                        $acceptedIP = $zbsQuote['accepted'][2];
                
                ?>

                        <table class="wh-metatab-side wptbp" id="wptbpMetaBoxQuoteStatus">
                            <tr><td style="text-align:center;color:green"><strong><?php _we('Accepted','zerobscrm'); ?> <?php echo $acceptedDate; ?></strong></td></tr>
                            <?php if (!empty($acceptedBy)) { ?><tr><td style="text-align:center"><?php _we('By: ','zerobscrm'); ?> <a href="mailto:<?php echo $acceptedBy; ?>" target="_blank"<?php if (!empty($acceptedIP)) { echo ' title="IP address:'.$acceptedIP.'"'; } ?>><?php echo $acceptedBy; ?></a></td></tr><?php } ?>                            
                        </table>   

                <?php


                } else {

                    ?>

                        <table class="wh-metatab-side wptbp" id="wptbpMetaBoxQuoteStatus">
                            <tr>
                            <td style="text-align:center"><strong><?php _we('Not Yet Accepted','zerobscrm'); ?></td></tr>
                        </table>  

                    <?php

                }

            } else { 
                                ?><style type="text/css">#wpzbscquote_status {display:none;}</style><?php 

            }

        }


    }

    $zeroBS__QuoteStatusMetabox = new zeroBS__QuoteStatusMetabox( __FILE__ );

