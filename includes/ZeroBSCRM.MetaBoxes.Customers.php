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







        global $zbsCustomerFields,$zbsCustomerQuoteFields,$zbsCustomerInvoiceFields;









    class zeroBS__Metabox {

        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_customer';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbsc_itemdetails',
                'Customer Details',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {

                global $zeroBSCRM_Settings;

                                $zbsCustomer = get_post_meta($post->ID, 'zbs_customer_meta', true);


                                $fieldHideOverrides = $zeroBSCRM_Settings->get('fieldhides');
                $zbsShowID = $zeroBSCRM_Settings->get('showid');

       
                global $zbsCustomerFields;
                $fields = $zbsCustomerFields;

                                $useSecondAddr = zeroBSCRM_getSetting('secondaddress');

                                $showAddr = zeroBSCRM_getSetting('showaddress');


                                
            
            ?>
                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                <?php wp_nonce_field( 'save_zbscustomer', 'save_zbscustomer_nce' ); ?>
                <?php ?><script type="text/javascript">var zbscrmjs_secToken = '<?php echo wp_create_nonce( "zbscrmjs-ajax-nonce" ); ?>';</script><?php ?>

                <?php 
                    if (gettype($zbsCustomer) != "array") echo '<input type="hidden" name="zbscrm_newcustomer" value="1" />';
                    
                ?>


                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">

                    <?php                     
                    if ($zbsShowID == "1") { ?>
                    <tr class="wh-large"><th><label>Customer ID:</label></th>
                    <td style="font-size: 20px;color: green;vertical-align: top;">
                        #<?php echo $post->ID; ?>
                    </td></tr>
                    <?php } ?>

    <?php  ?>
                    

                    <?php 
            

                                        global $zbsFieldsEnabled; if ($useSecondAddr == '1') $zbsFieldsEnabled['secondaddress'] = true;
                    
                                        $zbsFieldGroup = ''; $zbsOpenGroup = false;

                    foreach ($fields as $fieldK => $fieldV){

                        $showField = true;

                                                if (isset($fieldV['opt']) && (!isset($zbsFieldsEnabled[$fieldV['opt']]) || !$zbsFieldsEnabled[$fieldV['opt']])) $showField = false;


                                                if (isset($fieldHideOverrides['customer']) && is_array($fieldHideOverrides['customer'])){
                            if (in_array($fieldK, $fieldHideOverrides['customer'])){
                              $showField = false;
                            }
                        }

                                                
                                                        if (
                                $zbsOpenGroup &&
                                                                        ( 
                                        (isset($fieldV['area']) && $fieldV['area'] != $zbsFieldGroup) ||
                                                                                 !isset($fieldV['area']) && $zbsFieldGroup != ''
                                    )
                                ){

                                                                        $zbsCloseTable = true; if ($zbsFieldGroup == 'Main Address') $zbsCloseTable = false;

                                                                        echo '</table></div>';
                                    if ($zbsCloseTable) echo '</td></tr>';

                            }

                                                        if (isset($fieldV['area'])){

                                                                if ($zbsFieldGroup != $fieldV['area']){

                                                                        $zbsFieldGroup = $fieldV['area'];
                                    $fieldGroupLabel = str_replace(' ','_',$zbsFieldGroup); $fieldGroupLabel = strtolower($fieldGroupLabel);

                                                                        $zbsOpenTable = true; if ($zbsFieldGroup == 'Second Address') $zbsOpenTable = false;

                                                                        if(!$showAddr){
                                        $zbs_c = "zbs-hide-add";
                                    }else{
                                        $zbs_c = "";
                                    }

                                                                        if ($zbsOpenTable) echo '<tr class="wh-large zbs-field-group-tr '.$zbs_c.'"><td colspan="2">';

                                    echo '<div class="zbs-field-group zbs-fieldgroup-'.$fieldGroupLabel.'"><label class="zbs-field-group-label">'.$fieldV['area'].'</label>';
                                    echo '<table class="form-table wh-metatab wptbp" id="wptbpMetaBoxGroup-'.$fieldGroupLabel.'">';
                                    
                                                                        $zbsOpenGroup = true;

                                }


                            } else {

                                                                $zbsFieldGroup = '';

                            }

                                                                        



                                                if ($showField) {


                            switch ($fieldV[0]){

                                case 'text':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control widetext" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'price':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <?php echo zeroBSCRM_getCurrencyChr(); ?> <input style="width: 130px;display: inline-block;;" type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control  numbersOnly" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;


                                case 'date':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-date" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'select':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <select name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" autocomplete="off">
                                            <?php

                                                if (isset($fieldV[3]) && count($fieldV[3]) > 0){

                                                                                                        echo '<option value="" disabled="disabled"';
                                                    if (!isset($zbsCustomer[$fieldK]) || (isset($zbsCustomer[$fieldK])) && empty($zbsCustomer[$fieldK])) echo ' selected="selected"';
                                                    echo '>Select</option>';

                                                    foreach ($fieldV[3] as $opt){

                                                        echo '<option value="'.$opt.'"';
                                                        if (isset($zbsCustomer[$fieldK]) && strtolower($zbsCustomer[$fieldK]) == strtolower($opt)) echo ' selected="selected"'; 
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
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-tel" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'email':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-email" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'textarea':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <textarea name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" autocomplete="off"><?php if (isset($zbsCustomer[$fieldK])) echo zeroBSCRM_textExpose($zbsCustomer[$fieldK]); ?></textarea>
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
                                                    if (!isset($zbsCustomer[$fieldK]) || (isset($zbsCustomer[$fieldK])) && empty($zbsCustomer[$fieldK])) echo ' selected="selected"';
                                                    echo '>Select</option>';

                                                    foreach ($countries as $country){

                                                        echo '<option value="'.$country.'"';
                                                        if (isset($zbsCustomer[$fieldK]) && strtolower($zbsCustomer[$fieldK]) == strtolower($country)) echo ' selected="selected"'; 
                                                        echo '>'.$country.'</option>';

                                                    }

                                                } else echo '<option value="">'.__w('No Countries Loaded','zerobscrm').'!</option>';

                                            ?>
                                        </select>
                                    </td></tr><?php

                                    break;


                            } 




                        } 


                                                
                                                        if (
                                $zbsOpenGroup &&
                                                                        ( 
                                        (isset($fieldV['area']) && $fieldV['area'] != $zbsFieldGroup) ||
                                                                                 !isset($fieldV['area']) && $zbsFieldGroup != ''
                                    )
                                ){

                                                                        $zbsCloseTable = true; if ($zbsFieldGroup == 'Main Address') $zbsCloseTable = false;

                                                                        echo '</table></div>';
                                    if ($zbsCloseTable) echo '</td></tr>';

                            }


                                                                        




                    }


                    

                    ?>
                    
            </table>


            <style type="text/css">
                #submitdiv {
                    display:none;
                }
            </style>
            <script type="text/javascript">

                jQuery(document).ready(function(){

                    // bastard override of wp terminology:
                    jQuery('#submitdiv h2 span').html('Save');
                    if (jQuery('#submitdiv #publish').val() == 'Publish')
                        jQuery('#submitdiv #publish').val('Save');
                    jQuery('#submitdiv').show();

                    // turn off auto-complete on customer records via form attr... should be global for all ZBS record pages
                    jQuery('#post').attr('autocomplete','off');

                });


            </script>
             
            <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />


            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if (!isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbsc_itemdetails'  && $post->post_type == $this->postType){


                    

                    $zbsCustomerMeta = zeroBS_buildCustomerMeta($_POST);

                                        $zbs_stat = 'no_change';

                                                                                                    if (isset($_POST['zbscrm_newcustomer'])){
                        $zbs_new = 'new';
                    }else{
                        if($zbsCustomerMeta['status'] != $curr_meta['status']){
                                                        $zbs_stat = 'change';
                        }else{
                            $zbs_stat = 'no_change';
                        }
                        $zbs_new = 'old';
                    }
                                        update_post_meta($post_id, 'zbs_customer_meta', $zbsCustomerMeta);


                                        

                                        

                                        global $zbsCurrentPostMeta; $zbsCurrentPostMeta = $zbsCustomerMeta;

                }
            }

            return $post;
        }
    }

    $zeroBS__Metabox = new zeroBS__Metabox( __FILE__ );

        add_filter( 'admin_post_thumbnail_html', 'zeroBS_add_featured_image_instruction');
    function zeroBS_add_featured_image_instruction( $content ) {

        global $post;

        if (has_post_thumbnail( $post->ID ) ) {

            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 

            return $content .= '<p style="text-align:center;"><a href="'.$image[0].'" target="_blank">'.__w('View Customer Image','zerobscrm').'</a> <i class="fa fa-expand" aria-hidden="true"></i></p>';

        }

        return $content;


    }









    class zeroBS__MetaboxAssociated {

        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_customer';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbsc_itemdetails_associated',
                __w('Quotes, Invoices & Transactions','zerobscrm'),
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {

                $invoices = zeroBS_getInvoicesForCustomer($post->ID,true,100);
                $quotes = zeroBS_getQuotesForCustomer($post->ID,true,100);
                $transactions = zeroBS_getTransactionsForCustomer($post->ID,true,100);
            

                
                $ZBSuseQuotes = zeroBSCRM_getSetting('feat_quotes');
                $ZBSuseInvoices = zeroBSCRM_getSetting('feat_invs');

            ?>


                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">

                    <?php 

                    if ($ZBSuseQuotes == "1"){
                        if (count($quotes) > 0) {

                                ?><tr class="wh-large"><th><label><?php echo count($quotes).' Quote'; if (count($quotes) > 0) echo 's'; ?></label>
                                <br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_quote&zbsprefillcust='.$post->ID); ?>"><?php _we('Add a Quote','zerobscrm'); ?></a></small>
                                </th>
                                <td>
                                    <?php

                                        $quoteOffset = zeroBSCRM_getQuoteOffset();

                                        foreach($quotes as $quote){

                                                                                        $qStr = ''; $qID = '';
                                                                                        if (isset($quote['zbsid'])) $qID = $quote['zbsid'];
                                            if (isset($qID) && !empty($qID)) $qStr = 'QuoteRef '.$qID;                                             if (isset($quote['meta']['val'])) $qStr .= ' ('.zeroBSCRM_getCurrencyChr().$quote['meta']['val'].')';

                                                                                        echo '<a href="'.admin_url( 'post.php?post='.$quote['id'].'&action=edit').'" target="_blank">'.$qStr.'</a><br />';


                                        }
                                    ?></td></tr><?php

                        } else {

                                ?><tr class="wh-large"><th colspan="2"><label><?php _we('No Quotes Yet','zerobscrm'); ?></label><br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_quote&zbsprefillcust='.$post->ID); ?>"><?php _we('Add a Quote','zerobscrm'); ?></a></small></th></tr><?php
                        }
                    }



                    if ($ZBSuseInvoices == "1"){
                        if (count($invoices) > 0) {

                            $totalInvVal = 0;
                            $invOffset = zeroBSCRM_getInvoiceOffset();

                                        foreach($invoices as $invoice){

                                            if (isset($invoice['meta']['val'])) $totalInvVal += floatval($invoice['meta']['val']);

                                        }

                                ?><tr class="wh-large"><th><label><?php echo count($invoices).' Invoice';  if (count($invoices) > 0) echo 's'; ?></label><?php
                                if (!empty($totalInvVal)) echo '<br />Total Value: '.zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($totalInvVal);
                                ?>
                                <br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_invoice&zbsprefillcust='.$post->ID); ?>"><?php _we('Add an Invoice','zerobscrm'); ?></a></small>
                                </th>
                                <td>
                                    <?php
                                        foreach($invoices as $invoice){

                                                                                        $invStr = '';  $invID = '';
                                                                                        if (isset($invoice['zbsid'])) $invID = $invoice['zbsid'];
                                            if (!empty($invID)) $invStr .= '#'.$invID;                                             if (isset($invoice['meta']['val'])) $invStr .= ' ('.zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($invoice['meta']['val']).')';
                                            echo '<a href="'.admin_url( 'post.php?post='.$invoice['id'].'&action=edit').'" target="_blank">'.$invStr.'</a><br />';


                                        }
                                    ?></td></tr><?php

                        } else {

                                ?><tr class="wh-large"><th colspan="2"><label><?php _we('No Invoices Yet','zerobscrm'); ?></label><br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_invoice&zbsprefillcust='.$post->ID); ?>"><?php _we('Add an Invoice','zerobscrm'); ?></a></small></th></tr><?php
                        }
                    }

                    if (count($transactions) > 0) {

                        global  $zbscrmApprovedExternalSources;

                        $totalTransVal = 0;
                        foreach($transactions as $transaction){

                            if (isset($transaction['meta']['total'])) $totalTransVal += floatval($transaction['meta']['total']);

                        }

                            ?><tr class="wh-large"><th><label><?php echo count($transactions).' Transaction'; if (count($transactions) > 0) echo 's'; ?></label><?php
                            if (!empty($totalTransVal)) echo '<br />Total Value: '.zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($totalTransVal);
                            ?>
                            <br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_transaction&zbsprefillcust='.$post->ID); ?>"><?php _we('Add a Transaction','zerobscrm'); ?></a></small>
                            </th>
                            <td>
                                <?php 
                                    foreach($transactions as $transaction){

                                                                                $transStr = ''; 

                                                                                $hasIco = false;
                                            
                                                                                        if (isset($transaction['type'])) {
                                                
                                                                                                if (isset($zbscrmApprovedExternalSources[$transaction['type']]) && isset($zbscrmApprovedExternalSources[$transaction['type']]['ico'])){
                                                    
                                                                                                        $transStr .= '<span class="fa '.$zbscrmApprovedExternalSources[$transaction['type']]['ico'].'"></span> ';

                                                                                                        $hasIco = true;

                                                }
                                                

                                            }
                                            if (!$hasIco){

                                                                                                $transStr .= '<span class="fa fa-shopping-cart"></span> ';

                                            }

                                        if (isset($transaction['meta']['orderid'])) $transStr .= '#'.$transaction['meta']['orderid'];
                                        if (isset($transaction['meta']['total'])) $transStr .= ' ('.zeroBSCRM_getCurrencyChr().zeroBSCRM_prettifyLongInts($transaction['meta']['total']).')';
                                        if (isset($transaction['meta']['item']) && !empty($transaction['meta']['item'])) $transStr .= ' ('.$transaction['meta']['item'].')';
                                        echo '<a href="'.admin_url( 'post.php?post='.$transaction['id'].'&action=edit').'" target="_blank">'.$transStr.'</a><br />';


                                    }
                                ?>
                                </td></tr><?php

                    } else {

                            ?><tr class="wh-large"><th colspan="2"><label><?php _we('No Transactions Yet','zerobscrm'); ?></label><br /><small><a href="<?php echo get_admin_url('','post-new.php?post_type=zerobs_transaction&zbsprefillcust='.$post->ID); ?>"><?php _we('Add a Transaction','zerobscrm'); ?></a></small></th></tr><?php
                    }


                    ?>

                
            </table>


            <style type="text/css">
                #submitdiv {
                    display:none;
                }
            </style>
            <script type="text/javascript">

                jQuery(document).ready(function(){

                    // bastard override of wp terminology:
                    jQuery('#submitdiv h2 span').html('Save');
                    if (jQuery('#submitdiv #publish').val() == 'Publish')
                        jQuery('#submitdiv #publish').val('Save');
                    jQuery('#submitdiv').show();
                    

                });


            </script>
             


            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbsc_itemdetails_associated'  && $post->post_type == $this->postType){

                    

                }
            }

            return $post;
        }
    }

    $zeroBS__MetaboxAssociated = new zeroBS__MetaboxAssociated( __FILE__ );










function zeroBS__addCustomerMetaBoxes() {   
    add_meta_box('zerobs-customer-files', 'Other Files', 'zeroBS__MetaboxFilesOther', 'zerobs_customer', 'normal', 'low');  
}
add_action('add_meta_boxes', 'zeroBS__addCustomerMetaBoxes');  

function zeroBS__MetaboxFilesOther($post) {  
       

    $html = '';

    
                        $zbsCustomerQuotes = get_post_meta($post->ID, 'zbs_customer_files', true);

    ?>
            <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItemFiles">

                <?php 

                                
                                if (is_array($zbsCustomerQuotes) && count($zbsCustomerQuotes) > 0){ 
                  ?><tr class="wh-large"><th><label><?php echo count($zbsCustomerQuotes).' File(s):'; ?></label></th>
                            <td id="zbsFileWrapOther">
                                <?php $fileLineIndx = 1; foreach($zbsCustomerQuotes as $quote){

                                    $file = basename($quote['file']);
                                    echo '<div class="zbsFileLine" id="zbsFileLineCustomer'.$fileLineIndx.'"><a href="'.$quote['url'].'" target="_blank">'.$file.'</a> (<span class="zbsDelFile" data-delurl="'.$quote['url'].'"><i class="fa fa-trash"></i></span>)</div>';
                                    $fileLineIndx++;

                                } ?>
                            </td></tr><?php

                } ?>

                <?php 

                        wp_nonce_field(plugin_basename(__FILE__), 'zbsc_file_attachment_nonce');
                         
                        $html .= '<input type="file" id="zbsc_file_attachment" name="zbsc_file_attachment" value="" size="25">';
                        
                        ?><tr class="wh-large"><th><label>Add File:</label><br />(Optional)<br />Accepted File Types:<br /><?php echo zeroBS_acceptableFileTypeListStr(); ?></th>
                            <td><?php
                        echo $html;
                ?></td></tr>

            
            </table>
            <script type="text/javascript">

                var zbsCustomerCurrentlyDeleting = false;

                jQuery('document').ready(function(){

                    jQuery('.zbsDelFile').click(function(){

                        if (!window.zbsCustomerCurrentlyDeleting){

                            // blocking
                            window.zbsCustomerCurrentlyDeleting = true;

                            var delUrl = jQuery(this).attr('data-delurl');
                            var lineIDtoRemove = jQuery(this).closest('.zbsFileLine').attr('id');

                            if (typeof delUrl != "undefined" && delUrl != ''){



                                  // postbag!
                                  var data = {
                                    'action': 'delFile',
                                    'zbsfType': 'customer',
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

                                            jQuery('#zbsFileWrapOther').append('<div class="alert alert-error" style="margin-top:10px;"><strong>Error:</strong> Unable to delete this file.</div>');

                                            // Callback
                                            //if (typeof errorcb == "function") errorcb(response);
                                            //callback(response);


                                          }

                                        });

                            }

                            window.zbsCustomerCurrentlyDeleting = false;

                        } // / blocking

                    });

                });


            </script>

            <?php
}

add_action('save_post', 'zeroBSCRM_save_customer_file_data');
function zeroBSCRM_save_customer_file_data($id) {

    global $zbsc_justUploadedCustomer;


    if(!empty($_FILES['zbsc_file_attachment']['name']) && 
        (!isset($zbsc_justUploadedCustomer) ||
            (isset($zbsc_justUploadedCustomer) && $zbsc_justUploadedCustomer != $_FILES['zbsc_file_attachment']['name'])
        )
        ) {


    
    if(!wp_verify_nonce($_POST['zbsc_file_attachment_nonce'], plugin_basename(__FILE__))) {
      return $id;
    }        
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $id;
    }        
    
    if (!zeroBSCRM_permsCustomers()){
        return $id;
    }
    

        $zbsc_justUploadedCustomer = $_FILES['zbsc_file_attachment']['name'];



        $supported_types = zeroBS_acceptableFileTypeMIMEArr();         $arr_file_type = wp_check_filetype(basename($_FILES['zbsc_file_attachment']['name']));
        $uploaded_type = $arr_file_type['type'];

        if(in_array($uploaded_type, $supported_types)) {
            $upload = wp_upload_bits($_FILES['zbsc_file_attachment']['name'], null, file_get_contents($_FILES['zbsc_file_attachment']['tmp_name']));

            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                

                                                                $zbsCustomerFiles = get_post_meta($id,'zbs_customer_files', true);

                                if (is_array($zbsCustomerFiles)){

                                                                        $zbsCustomerFiles[] = $upload;

                                } else {

                                                                        $zbsCustomerFiles = array($upload);

                                }

                                update_post_meta($id, 'zbs_customer_files', $zbsCustomerFiles);  
            }
        }
        else {
            wp_die("The file type that you've uploaded is not an accepted file format.");
        }
    }
}






class zeroBS__CustomerPortal {
    static $instance;
    private $postType;

    public function __construct( $plugin_file ) {
        self::$instance = $this;
        $this->postType = 'zerobs_customer';
        add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
        add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
    }

    public function create_meta_box() {
        add_meta_box('wpzbsci_portal','Customer Portal', array( $this, 'print_meta_box' ), $this->postType,'side','low');
    }

    public function print_meta_box( $post, $metabox ) {
        global $plugin_page, $zeroBSCRM_Settings;
        $screen = get_current_screen();
        ?>
        <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
        <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
        <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />
        <?php
        $wp_user_id = '';
        $zbsCustomer = get_post_meta($post->ID, 'zbs_customer_meta', true);


        if (isset($zbsCustomer) && is_array($zbsCustomer) && isset($zbsCustomer['email'])){

                            $wp_user_id = get_post_meta($post->ID,'zbs_wp_user_id', true);
            if($wp_user_id == ''){
                $wp_user_id = email_exists( $zbsCustomer['email'] );
            }
        }
        ?>
        <script>
            jQuery(document).ready(function(){
                jQuery('.wp-user-generate').unbind("click").bind('click',function(e){
                    email = jQuery('#email').val();
                    if(email == ''){
                        alert("The email field is blank. Please fill in the email and save");
                        return false;
                    }
                    var t = {
                        action: "zbs_new_user",
                        email: email,
                        security: jQuery( '#newwp-ajax-nonce' ).val(),
                    }                    
                    i = jQuery.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data: t,
                        dataType: "json"
                    });
                    i.done(function(e) {
                        console.log(e);
                        if(e.success){
                            jQuery('.zbs-user-id').html(e.user_id);
                            jQuery('.no-gen').remove();
                            jQuery('.waiting-togen').html("<div class='alert alert-success'>Succes</div>User Generated");
                        }
                    }), i.fail(function(e) {
                        //error
                    });
                });
            });
        </script>
        <?php
        echo '<div class="waiting-togen">';

        if($wp_user_id){
                        echo 'WordPress User Link (ID: <span class="zbs-user-id">'. $wp_user_id .'</span>)';

            
            
            update_post_meta($post->ID,'zbs_wp_user_id', $wp_user_id);                           echo '<br/></br/>';
           
        }else if(!$wp_user_id && $zbsCustomer != ''){
            echo '<div class="no-gen">';
            echo 'No WordPress User exists with this email<br/><br/>';
            echo '<div class="button button-primary wp-user-generate">Generate WordPress User</div>';
            echo '<input type="hidden" name="newwp-ajax-nonce" id="newwp-ajax-nonce" value="' . wp_create_nonce( 'newwp-ajax-nonce' ) . '" />';
            echo '</div>';
        }else{
            echo 'Save your new Customer before Customer Portal functionality becomes available';
        }

        echo '</div>';




    }

    public function save_meta_box( $post_id, $post ) {
        global $wpdb;
        if( empty( $_POST['meta_box_ids'] ) ){ return; }
        foreach( $_POST['meta_box_ids'] as $metabox_id ){
            if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }
            if( $metabox_id == 'wpzbsci_portal'  && $post->post_type == $this->postType){

                
            }
        }

        return $post;
    }
}

if (zeroBSCRM_isExtensionInstalled('portal')) $zeroBS__CustomerPortal = new zeroBS__CustomerPortal( __FILE__ );







