<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.10
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 19/07/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;








        global $zbsCompanyFields,$zbsCompanyQuoteFields,$zbsCompanyInvoiceFields;







    class zeroBS__Metabox_Companies {

        static $instance;
                        private $postType;
        private $coOrgLabel;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            

                        $companyOrOrg = zeroBSCRM_getSetting('coororg');
            $this->coOrgLabel = zeroBSCRM_getCompanyOrOrg();

            $this->postType = 'zerobs_company';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbsc_companydetails',
                $this->coOrgLabel.' Details',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {


                global $zeroBSCRM_Settings;

                                $zbsCompany = get_post_meta($post->ID, 'zbs_company_meta', true);


                                $fieldHideOverrides = $zeroBSCRM_Settings->get('fieldhides');
                $zbsShowID = $zeroBSCRM_Settings->get('showid');


                global $zbsCompanyName; $zbsCompanyName = ''; if (isset($zbsCompany['name'])) $zbsCompanyName = $zbsCompany['name'];

                global $zbsCompanyFields;
                $fields = $zbsCompanyFields;

                                $useSecondAddr = zeroBSCRM_getSetting('secondaddress');

            
            ?>
                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                <?php wp_nonce_field( 'save_zbscompany', 'save_zbscompany_nce' ); ?>
                
                <?php 
                    if (gettype($zbsCompany) != "array") echo '<input type="hidden" name="zbscrm_newcompany" value="1" />';

                ?>

                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItem">

                    <?php                     
                    if ($zbsShowID == "1") { ?>
                    <tr class="wh-large"><th><label>Company ID:</label></th>
                    <td style="font-size: 20px;color: green;vertical-align: top;">
                        #<?php echo $post->ID; ?>
                    </td></tr>
                    <?php } ?>

    <?php if (has_post_thumbnail( $post->ID ) ): ?>
      <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
      <tr class="wh-large"><th><label><?php echo $this->coOrgLabel; ?> Image:</label></th>
                    <td>
                        <a href="<?php echo $image[0]; ?>" target="_blank"><img src="<?php echo $image[0]; ?>" alt="<?php echo $this->coOrgLabel; ?> Image" style="max-width:300px;border:0" /></a>
                    </td></tr>
    <?php endif; ?>
                    

                    <?php 

            

                                        global $zbsFieldsEnabled; if ($useSecondAddr == '1') $zbsFieldsEnabled['secondaddress'] = true;
                    
                                        $zbsFieldGroup = ''; $zbsOpenGroup = false;

                    foreach ($fields as $fieldK => $fieldV){

                        $showField = true;

                                                if (isset($fieldV['opt']) && (!isset($zbsFieldsEnabled[$fieldV['opt']]) || !$zbsFieldsEnabled[$fieldV['opt']])) $showField = false;


                                                if (isset($fieldHideOverrides['company']) && is_array($fieldHideOverrides['company'])){
                            if (in_array($fieldK, $fieldHideOverrides['company'])){
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

                                                                        if ($zbsOpenTable) echo '<tr class="wh-large zbs-field-group-tr"><td colspan="2">';
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
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control widetext" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCompany[$fieldK])) echo $zbsCompany[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'price':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <?php echo zeroBSCRM_getCurrencyChr(); ?> <input style="width: 130px;display: inline-block;;" type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control  numbersOnly" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCompany[$fieldK])) echo $zbsCompany[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;


                                case 'date':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-date" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCompany[$fieldK])) echo $zbsCompany[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'select':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <select name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" autocomplete="off">
                                            <?php

                                                if (isset($fieldV[3]) && count($fieldV[3]) > 0){

                                                                                                        echo '<option value="" disabled="disabled"';
                                                    if (!isset($zbsCompany[$fieldK]) || (isset($zbsCompany[$fieldK])) && empty($zbsCompany[$fieldK])) echo ' selected="selected"';
                                                    echo '>Select</option>';

                                                    foreach ($fieldV[3] as $opt){

                                                        echo '<option value="'.$opt.'"';
                                                        if (isset($zbsCompany[$fieldK]) && strtolower($zbsCompany[$fieldK]) == strtolower($opt)) echo ' selected="selected"'; 
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
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-tel" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCompany[$fieldK])) echo $zbsCompany[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'email':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-email" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCompany[$fieldK])) echo $zbsCompany[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'textarea':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                    <td>
                                        <textarea name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" autocomplete="off"><?php if (isset($zbsCompany[$fieldK])) echo zeroBSCRM_textExpose($zbsCompany[$fieldK]); ?></textarea>
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

                                                } else echo '<option value="">No Countries Loaded!</option>';

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

                    // turn off auto-complete on records via form attr... should be global for all ZBS record pages
                    jQuery('#post').attr('autocomplete','off');

                    /*// All datepickers
                    jQuery('.zbs-date').datetimepicker({
                      timepicker:false,
                      format:'d.m.Y'
                    });

                     Moved to daterangepicker 03/06/16 */
                    jQuery('.zbs-date').daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        locale: {
                            format: "DD.MM.YYYY"
                        }
                    }, 
                    function(start, end, label) {
                        //var years = moment().diff(start, 'years');
                        //alert("You are " + years + " years old.");
                    });


                    jQuery('.numbersOnly').keyup(function () {
                        var rep = this.value.replace(/[^0-9\.]/g, '');
                        if (this.value != rep) {
                           this.value = rep;
                        }
                    });


                });


            </script>
             
            <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />


            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if(!isset($_POST[ $metabox_id . '_nonce' ]) ||  ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbsc_companydetails'  && $post->post_type == $this->postType){


                    

                    $zbsCompanyMeta = zeroBS_buildCompanyMeta($_POST);

                                        update_post_meta($post_id, 'zbs_company_meta', $zbsCompanyMeta);

                                        global $zbsCurrentCompanyPostMeta; $zbsCurrentCompanyPostMeta = $zbsCompanyMeta;

                }
            }

            return $post;
        }
    }

    $zeroBS__Metabox_Companies = new zeroBS__Metabox_Companies( __FILE__ );







    class zeroBS__MetaboxCompanyAssociated {

        static $instance;
                        private $postType;
        private $coname;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_company';
            
                        global $zbsCompanyName; 
            $this->coname = zeroBSCRM_getCompanyOrOrg(); if (isset($zbsCompanyName)) $this->coname = $zbsCompanyName;

            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbsc_itemdetails_associated',
                zeroBSCRM_getContactOrCustomer().'s at '.$this->coname,
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {

                $contacts = zeroBS_getCustomers(true,1000,0,false,false,'',false,false,$post->ID);        

                
            ?>

                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItemContacts">

                    <tr class="wh-large"><th>
                        <?php

                            if (count($contacts) > 0){

                                echo '<div id="zbs-co-contacts">';

                                foreach ($contacts as $contact){

                                    echo '<div class="zbs-co-contact">';

                                                                        echo zeroBS_getCustomerIcoHTML($contact['id']);

                                    echo '<strong><a href="post.php?post='.$contact['id'].'&action=edit">'.zeroBS_customerName($contact['id'],$contact['meta'],false,false).'</a></strong><br />';

                                    echo '</div>';

                                }


                                echo '</div>';

                            } else {

                                echo '<div style="margin-left:auto;margin-right:auto;display:inline-block">No '.zeroBSCRM_getContactOrCustomer().'s found</div>';

                            }

                        ?>
                    </th></tr>
                    
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

                    
                    /*// All datepickers
                    jQuery('.zbs-date').datetimepicker({
                      timepicker:false,
                      format:'d.m.Y'
                    });

                     Moved to daterangepicker 03/06/16 */
                    jQuery('.zbs-date').daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        locale: {
                            format: "DD.MM.YYYY"
                        }
                    }, 
                    function(start, end, label) {
                        //var years = moment().diff(start, 'years');
                        //alert("You are " + years + " years old.");
                    });


                    jQuery('.numbersOnly').keyup(function () {
                        var rep = this.value.replace(/[^0-9\.]/g, '');
                        if (this.value != rep) {
                           this.value = rep;
                        }
                    });


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

                    $zbsCustomerMeta = array();


                    global $zbsCustomerFields;

                    foreach ($zbsCustomerFields as $fK => $fV){

                        $zbsCustomerMeta[$fK] = '';

                        if (isset($_POST['zbsc_'.$fK])) {

                            switch ($fV[0]){

                                case 'tel':

                                                                        $zbsCustomerMeta[$fK] = sanitize_text_field($_POST['zbsc_'.$fK]);
                                    preg_replace("/[^0-9 ]/", '', $zbsCustomerMeta[$fK]);
                                    break;

                                case 'price':

                                                                        $zbsCustomerMeta[$fK] = sanitize_text_field($_POST['zbsc_'.$fK]);
                                    $zbsCustomerMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsCustomerMeta[$fK]);
                                    $zbsCustomerMeta[$fK] = floatval($zbsCustomerMeta[$fK]);
                                    break;


                                case 'textarea':

                                    $zbsCustomerMeta[$fK] = zeroBSCRM_textProcess($_POST['zbsc_'.$fK]);

                                    break;


                                default:

                                    $zbsCustomerMeta[$fK] = sanitize_text_field($_POST['zbsc_'.$fK]);

                                    break;


                            }

                        }


                    }

                                        update_post_meta($post_id, 'zbs_customer_meta', $zbsCustomerMeta);

                }
            }

            return $post;
        }
    }

    $zeroBS__MetaboxCompanyAssociated = new zeroBS__MetaboxCompanyAssociated( __FILE__ );









        function zbsCustomer_companyMetaBox($post) {


                $companyOrOrg = zeroBSCRM_getSetting('coororg');
        $companyLabel = zeroBSCRM_getCompanyOrOrg();

        
                $companies = zeroBS_getCompanies(true,1000,0);

        if ( 'zerobs_customer' == $post->post_type && count($companies) > 0){             $coID = get_post_meta($post->ID,'zbs_company',true);
            ?>
            <label class="screen-reader-text" for="zbs_company"><?php _we($companyLabel, 'zerobscrm') ?></label><select name="zbs_company" id="zbs_company">
            <option value=""><?php _we('Select a Company', 'zerobscrm'); ?></option>
            <?php zbsCustomer_companyDropdown($coID,$companies); ?>
            </select>
            <?php
        } ?>
        <?php
    }
    add_action('add_meta_boxes','zbsCustomer_addCompanyMetaBox');
    function zbsCustomer_addCompanyMetaBox() {

                $b2bMode = zeroBSCRM_getSetting('companylevelcustomers');

        if ($b2bMode){

                        $companyOrOrg = zeroBSCRM_getSetting('coororg');
            $companyLabel = zeroBSCRM_getCompanyOrOrg();

            add_meta_box('postparentdiv', __w($companyLabel, 'zerobscrm'), 'zbsCustomer_companyMetaBox', 'zerobs_customer', 'side', 'core');

        }

    }
    function zbsCustomer_companyDropdown( $default = '', $companies ) {

      foreach ($companies as $co){
        echo "\n\t" . '<option value="'.$co['id'].'"';
        if ($co['id'] == $default) echo ' selected="selected"';
                $coName = $co['meta']['coname'];
                if (empty($coName) && isset($co['coname'])) $coName = $co['coname'];
        echo '>'.$coName.'</option>';
      }

    }

