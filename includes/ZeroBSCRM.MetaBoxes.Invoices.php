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









    function zeroBS__addInvoiceMetaBoxes() {  

                add_meta_box('zerobs-customer-invoices', 'Attachments', 'zeroBS__MetaboxFilesInvoice', 'zerobs_invoice', 'normal', 'low');  

    }
    add_action('add_meta_boxes', 'zeroBS__addInvoiceMetaBoxes');  









    class zeroBS__MetaboxInvoice {

        
        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_invoice';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbsci_itemdetails',
                ' ',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) {

                global $plugin_page;
                $screen = get_current_screen();
                                $zbsCustomer = get_post_meta($post->ID, 'zbs_customer_invoice_meta', true);
                $zbsCustomerID = get_post_meta($post->ID, 'zbs_customer_invoice_customer', true);
                $zbsCompanyID = get_post_meta($post->ID, 'zbs_company_invoice_company', true);

                                $newInvoice = false; if (gettype($zbsCustomer) != "array") $newInvoice = true;

                global $zbsCustomerInvoiceFields, $zeroBSCRM_slugs;

                $fields = $zbsCustomerInvoiceFields;

                $currencyChar = zeroBSCRM_getCurrencyChr();


                                if (isset($_GET['zbsinherit'])){

                    $fromQuoteID = (int)sanitize_text_field($_GET['zbsinherit']);

                    if (!empty($fromQuoteID)){

                                                $fromQuote = get_post_meta($fromQuoteID, 'zbs_customer_quote_meta', true);
                        $fromCustID = get_post_meta($fromQuoteID, 'zbs_customer_quote_customer', true);
                        $fromQuoteVal = 0; if (isset($fromQuote['val'])) $fromQuoteVal = $fromQuote['val'];

                                                
                                                        if ((!isset($zbsCustomerID) || empty($zbsCustomerID)) && !empty($fromCustID) && $fromCustID > 0) $zbsCustomerID = $fromCustID;

                                                        if ((!isset($zbsCustomer) || !isset($zbsCustomer['val'])) && !empty($fromQuoteVal) && $fromQuoteVal > 0) {
                                if (!is_array($zbsCustomer)) $zbsCustomer = array();
                                $zbsCustomer['val'] = $fromQuoteVal;
                            }


                                                if (!empty($zbsCustomerID)) {
                         
                            $fromCustEmail = ''; 
                            $fromCustMeta = zeroBS_getCustomerMeta($zbsCustomerID);
                            if (isset($fromCustMeta['email']) && !empty($fromCustMeta['email'])) $fromCustEmail = $fromCustMeta['email'];

                        }

                    }

                } else {

                                        if (isset($zbsCustomer) && isset($zbsCustomer['quolink']) && !empty($zbsCustomer['quolink'])) $fromQuoteID = (int)$zbsCustomer['quolink'];

                }


                                if (
                                        (isset($zbsCustomer) && is_array($zbsCustomer) && (!isset($zbsCustomer['status']) || empty($zbsCustomer['status']))) ||
                                        (!isset($zbsCustomer) || !is_array($zbsCustomer))
                    ) $zbsCustomer['status'] = 'Draft';               
            ?>
                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                <?php                                          if ($newInvoice) echo '<input type="hidden" name="zbscrm_newinvoice" value="1" />';

         
                $invOffset = zeroBSCRM_getInvoiceOffset();
                $allowInvNoChange = zeroBSCRM_getSetting('invallowoverride');

                $b2b = zeroBSCRM_getSetting('companylevelcustomers');
                
                $invoicePostID = (int)$post->ID; 

                                $thisInvNo = get_post_meta($invoicePostID,'zbsid', true); 
                
                if (!isset($thisInvNo) || empty($thisInvNo)){

                                        $thisInvNo = zeroBSCRM_getNextInvoiceID();

                }

                                $zbs_inv_meta = get_post_meta($invoicePostID,'zbs_customer_invoice_meta', true); 
              
                if(!isset($zbs_inv_meta['status'])){
                    $zbs_stat = 'Draft';
                }else{
                    $zbs_stat = $zbs_inv_meta['status'];
                }

                global $zeroBSCRM_Settings;
                $invsettings = $zeroBSCRM_Settings->getAll();

                ?>
                <style>
                <?php
                if($invsettings['invtax'] == 0){
                    echo '.taxhide{ display:none!important;';
                }
                ?>
                </style>
                <script type="text/javascript">
                    var zbs_tax = <?php echo $invsettings['invtax']; ?>;
                </script>
                <div id="zbs_invoice_alerts">
                    <div id="zbs_success"><i class="fa fa-check-circle" aria-hidden="true"></i> <span></span></div>
                </div>

                <div id="zbs_invoice_actions">
                    <div class='zbs-invoice-status'>
                        <span class='<?php echo strtolower($zbs_stat); ?> statty'>
                            <?php echo $zbs_stat; ?>
                        </span>
                    </div>
                    <?php if ($newInvoice){ ?>
                    
                    <?php }else{ ?>
                    <?php
                      
                                                                                                if(zeroBSCRM_isExtensionInstalled('portal')){
                            $invprevloc = site_url('clients/invoices/' . $post->ID);
                        }else{
                            $invprevloc =  wp_nonce_url(admin_url('admin.php?zbs_invid='. $post->ID), 'zbsinvpreview');  
                        }
                        
                      ?>
                    <a href="<?php echo $invprevloc; ?>" target="_blank" class='btn-primary' id="zbs_invoice_preview"<?php if ($newInvoice) echo ' disabled="disabled"'; ?>><?php _we("Preview","zerobscrm"); ?></a>
                    <button class='btn-secondary' id="zbs_invoice_send" data-zbsinvid="<?php echo $post->ID; ?>"<?php if ($newInvoice) echo ' disabled="disabled"'; ?>><?php _we("Send","zerobscrm"); ?></button>         
                    <?php
                        
                    

                        
                                                if (zeroBSCRM_isExtensionInstalled('pdfinv')){
                            
                                                        ?><input type="button" name="zbs_invoicing_download_pdf" id="zbs_invoicing_download_pdf" class="btn-secondary" value="Download PDF" />
                            <script type="text/javascript">
                            jQuery(document).ready(function(){

                                // add your form to the end of body (outside <form>)
                                var formHTML = '<form target="_blank" method="post" id="zbs_invoicing_download_pdf_form" action="">';
                                    formHTML += '<input type="hidden" name="zbs_invoicing_download_pdf" value="1" />';
                                    formHTML += '<input type="hidden" name="zbs_invoice_id" value="<?php echo $post->ID; ?>" />';
                                    formHTML += '</form>';
                                jQuery('#wpbody').append(formHTML);

                                // on click
                                jQuery('#zbs_invoicing_download_pdf').click(function(){
                                    // submit form
                                    jQuery('#zbs_invoicing_download_pdf_form').submit();
                                });

                            });                    
                            </script>
                            <?php 

                        } else {

                            
                                                        if ($zbs_stat != "draft"){

                                                                                                $hasXd = zeroBSCRM_getCloseState('pdfinvinstall');
                                if (!$hasXd || $hasXd < time()-604800){

                                    $directInstallLink = wp_nonce_url('admin.php?page=zerobscrm-extensions&zbsinstall=pdfinv','zbscrminstallnonce');
                                    ?><div id="zbswhPDFNotice" style="position: absolute;top: -23px;right: 160px;text-align: right;width: 200px;background: #FFF;padding: 10px;border: 1px solid #428bca;z-index: 10000;border-radius: 5px;">
                                    <div class="zbsCloseThisAndLog" data-closelog="pdfinvinstall"><i class="fa fa-times-circle" aria-hidden="true"></i></div>Want PDF Invoicing?<br />(It’s a free, one-click install)<br /><a class="btn btn-success" style="text-decoration:none" href="<?php echo $directInstallLink; ?>">Install Now</a></div><?php

                                }

                            }

                        }

                        
                    ?>


                    <button class='btn-secondary btn-zbs-save' id="zbs_invoice_save" style="background: #16b716;color: #FFF;"><?php _we("Save","zerobscrm"); ?></button>
                    <?php } ?>
                </div>

                        <?php
                            $zbs_logo = '';
                            if(isset($zbs_inv_meta['logo'])){
                                $zbs_logo = $zbs_inv_meta['logo'];
                            }else{
                                                                if(isset($invsettings['invoicelogourl']) && !empty($invsettings['invoicelogourl'])){
                                    $zbs_logo = $invsettings['invoicelogourl'];
                                }else{
                                    $zbs_logo = '';
                                }
                            }
                            if($zbs_logo != ''){
                                $logo_c = 'show';
                                $logo_s = 'hide';
                            }else{
                                $logo_c = '';
                                $logo_s = '';
                                $zbs_logo = '';
                            }
                        ?>


                <!-- lets add a section to upload and attach a logo -->
                <div id="zbs_top_wrapper">
                    <div id="zbs_invoice_logos">
                        <div class="wh-logo <?php echo $logo_s; ?>">
                            <i class="fa fa-file-image-o" aria-hidden="true"></i><span class="wh-logo-text"><?php _we("+ Add your logo","zerobscrm"); ?></span>
                        </div>

                        <div class="wh-logo-set <?php echo $logo_c; ?>">
                            <img id="wh-logo-set-img" src="<?php echo $zbs_logo; ?>"/>
                            <input type="hidden" name="zbscq_logo" id="logo" value="<?php echo $zbs_logo; ?>" />
                            <div class="zbs-logo-options">
                                <span class="zbs-update"><?php _we("Update","zerobscrm");?></span>
                                <span class="zbs-remove"><?php _we("Remove","zerobscrm");?></span>
                            </div>
                        </div>
                    </div>
                    <div class='zbs-invoice-topper'>
                        <table class="form-table">
                        <tr class="wh-large zbs-invoice-number"><th><label for="no">Invoice Number:</label></th> 
                            <td>
                                <?php 

                                                                                                                                                
                                    if (isset($allowInvNoChange) && $allowInvNoChange == '1'){ ?>
                                    <input type="text" name="zbsinvid" id="no" class="zbs-normal form-control widetext" placeholder="e.g. 101"  value="<?php if (isset($thisInvNo)) echo $thisInvNo; ?>" />
                                    <?php } else {

                                                                                echo '<div class="zbs-normal">'.$thisInvNo.'</div>';
                                        echo '<input type="hidden" name="zbsinvid" value="'.$thisInvNo.'" />';

                                    } ?>   
                                    <span class="zbs-infobox" style="margin-top:3px">You can override this invoice number if you like, otherwise it takes the next unique ID value. You can control whether the invoice number can be over-ridden in the settings.</span>                 
                            </td>
                        </tr>
                        <tr class="wh-large"><th><label for="date"><?php _we("Invoice Date", "zerobscrm"); ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbscq_date" id="date" class="form-control zbs-date" placeholder="<?php 
                                            
                                                                                                                                    if (!empty($thisInvNo)) echo $thisInvNo;

                                        ?>" value="<?php if (isset($zbs_inv_meta['date'])) echo $zbs_inv_meta['date']; ?>" />
                                    <span class="zbs-infobox" style="margin-top:3px">You can select any invoice date, but when you click Send, the invoice will be sent straight away, even if you selected a future date.</span>                 
                                    </td>
                        </tr>
                        <tr class="wh-large">
                            <th><label for="Reference">Reference:</label></th>
                                    <td>
                                        <input type="text" name="zbscq_ref" id="ref" class="form-control widetext" placeholder="Such as Ref #" value="<?php if (isset($zbs_inv_meta['ref'])) echo $zbs_inv_meta['ref']; ?>" autocomplete="off" />
                                    </td>
                            </tr>
                        <tr class="wh-large"><th><label for="due">Due date:</label></th>
                        <td>
                            <select name="zbscq_due" class="form-control" style="font-size:16px;">
                           <?php
                                                        echo '<option value="" disabled="disabled"';
                            if (!isset($zbsCustomerID) || (isset($zbsCustomerID)) && empty($zbsCustomerID)) echo ' selected="selected"';
                            echo '>Select</option>';

                            $dueList = array(
                                'No due date' => -1,
                                'Due on receipt' => 0,
                                'Due in 10 days' => 10, 
                                'Due in 15 days' => 15, 
                                'Due in 30 days' => 30, 
                                'Due in 45 days' => 45, 
                                'Due in 60 days' => 60, 
                                'Due in 90 days' => 90
                                );

                            foreach ($dueList as $k => $v){
                                echo '<option value="'.$v.'"';
                                if (isset($zbs_inv_meta['due']) && $zbs_inv_meta['due'] == $v){
                                    echo ' selected="selected"';
                                }
                                echo '>'.$k.'</option>';
                            }

                           ?>
                            </select>
                        </td></tr>

                        <?php

                        $recurringInv = '';
                        $recurringInv = apply_filters('invoicing_pro_recurring', $invoicePostID);
                        if($recurringInv == $invoicePostID){
                            $recurringInv = '';
                        }
                        echo $recurringInv;
                        ?>

                     </table>
                    </div>
                </div>
                
                <div class='clear'></div>

                <div id='zbs-business-info-wrapper'>
                    <div class='business-info-toggle'>
                        <i class="fa fa-chevron-circle-right" aria-hidden="true"></i><span class='your-info-biz'><?php _we('Your business information','zerobscrm'); ?></span>
                    </div>
                    <?php 
                                                $zbs_biz_name =  zeroBSCRM_getSetting('businessname');
                        $zbs_biz_yourname =  zeroBSCRM_getSetting('businessyourname');

                        $zbs_biz_extra =  zeroBSCRM_getSetting('businessextra');

                        $zbs_biz_youremail =  zeroBSCRM_getSetting('businessyouremail');
                        $zbs_biz_yoururl =  zeroBSCRM_getSetting('businessyoururl');
                        $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']) . "&tab=invoices";
                    ?>
                    <div class="business-info">
                        <table class="table zbs-table">
                            <tbody>
                                <tr><td><?php echo $zbs_biz_name; ?></td></tr>
                                <tr><td><?php echo $zbs_biz_yourname; ?></td></tr>
                                <tr><td><?php echo nl2br(zeroBSCRM_textExpose($zbs_biz_extra)); ?></td></tr>
                                <tr class='top-pad'><td><?php echo $zbs_biz_youremail; ?></td></tr>
                                <tr><td><?php echo $zbs_biz_yoururl; ?></td></tr>
                            </tbody>
                        </table>
                        <span class="edit-or-add"><a href="<?php echo $zbs_settings_slug; ?>" target="_blank"><?php _we('Edit or add details','zerobscrm'); ?></a></span>
                    </div>
                </div>

            <?php
            echo '<input type="hidden" name="inv-ajax-nonce" id="inv-ajax-nonce" value="' . wp_create_nonce( 'inv-ajax-nonce' ) . '" />';
            ?>

                <hr/>

                <div id="billing-to">
                    <table class="form-table">
                            <tr class="wh-large">
                                <th><?php _we("Send invoice to: ", "zerobscrm"); ?><span class="zbs-infobox" style="margin-top:3px">You can send invoices to any email. You can choose who to assign the invoice to (Customer or Company) and it will show up under Manage Customers (or Manage Company)</span>   
            </th>
                                <td><input class="form-control" type="email" id="bill" name="zbscq_bill" value="<?php 
                                
                                    if (isset($zbs_inv_meta['bill'])) 
                                        echo $zbs_inv_meta['bill']; 
                                    else {

                                                                                if (isset($fromCustEmail)) echo $fromCustEmail;

                                    }

                                ?>" placeholder="Email address"/></td>
                            </tr>
                            <tr class="wh-large hide">
                                <th><?php _we("Copy to: ", "zerobscrm"); ?></th>
                                <td><input class="form-control" type="email" id="ccbill" name="zbscq_ccbill" value="<?php if (isset($zbs_inv_meta['ccbill'])) echo $zbs_inv_meta['ccbill']; ?>" placeholder="e.g. you@you.com" /></td>
                            </tr>
                    </table>
               </div>

                <div id="assign-to">
                    <div class="zbs-controls">
                        <table class="form-table">
                    <tr class="wh-large">
                     <th class='assign-label'><?php _we("Assign to (Customer): ", "zerobscrm"); ?></th>
                    <td>
                        <select name="zbsci_customer" class="form-control" style="font-size:16px;">
                       <?php
                                                echo '<option value="" disabled="disabled"';
                        if (!isset($zbsCustomerID) || (isset($zbsCustomerID)) && empty($zbsCustomerID)) echo ' selected="selected"';
                        echo '>None</option>';

                        $customerList = zeroBS_getCustomers(TRUE,10000);

                        if (count($customerList) > 0) foreach ($customerList as $customer){



                            echo '<option value="'.$customer['id'].'"';
                            if (isset($zbsCustomerID) && $customer['id'] == $zbsCustomerID) echo ' selected="selected"';
                            echo '>'.zeroBS_customerName($customer['id'],$customer['meta']).'</option>';
                        }

                       ?>
                        </select>
                    </td></tr>
                    <?php if(zeroBSCRM_getSetting('companylevelcustomers')){ ?>
                        <tr class="wh-large">
                         <th class='assign-label'><?php _we("Assign to (Company): ", "zerobscrm"); ?></th>
                        <td>
                            <select name="zbsci_company" class="form-control" style="font-size:16px;">
                           <?php
                                                        echo '<option value="" disabled="disabled"';
                            if (!isset($zbsCompanyID) || (isset($zbsCompanyID)) && empty($zbsCompanyID)) echo ' selected="selected"';
                            echo '>None</option>';

                            $companyList = zeroBS_getCompanies(TRUE,10000);
                            if (count($companyList) > 0) foreach ($companyList as $company){
                                echo '<option value="'.$company['id'].'"';
                                if (isset($zbsCompanyID) && $company['id'] == $zbsCompanyID) echo ' selected="selected"';
                                echo '>' . $company['name'] .' #' . $company['id'] . '</option>';
                            }

                           ?>
                            </select>
                        </td></tr>
                    <?php } ?>

                </table>
                    </div>
                </div>

                <div class="clear"></div>

                <table class="form-table" id="wptbpMetaBoxMainItemInv"></table>


            <?php
                $zbsInvoiceHorQ = get_post_meta($post->ID, 'zbsInvoiceHorQ', true);
            ?>

            <div id="zbs-invoice-customiser">
                <span class='header' style='float:left'>Customise: </span>
                <select class="form-control" id="invoice-customiser-type" name="invoice-customiser-type" id="invoice-customiser-type" style="width:30%;">
                    <?php 
                    $options = array( 'quantity', 'hours');
                    $output = '';
                    for( $i=0; $i<count($options); $i++ ) {
                      $output .= '<option ' 
                                 . ( $zbsInvoiceHorQ == $options[$i] ? 'selected="selected"' : '' ) . ' value='.$options[$i].'>' 
                                 . ucfirst($options[$i]) 
                                 . '</option>';
                    }
                    echo $output;
                    ?>
                </select>

            </div>

            <div id="zbs-invoice-items">
                <table class='table'>
                    <thead>
                        <th>Description</th>
                        <?php 
                        if($zbsInvoiceHorQ == 'quantity' || $zbsInvoiceHorQ == ''){ ?>
                            <th class="cen" id="zbs_inv_qoh"><?php _we("Quantity","zerobscrm"); ?></th>
                            <th class="cen" id="zbs_inv_por"><?php _we("Price","zerobscrm"); ?></th>
                        <?php }else{ ?>
                            <th class="cen" id="zbs_inv_qoh"><?php _we("Hours","zerobscrm"); ?></th>
                            <th class="cen" id="zbs_inv_por"><?php _we("Rate","zerobscrm"); ?></th>
                        <?php } ?>
                        <th class="cen taxhide">Tax</th>
                        <th>Amount</th>
                    </thead>
                   
                        <?php
                        $invlines = get_post_meta($post->ID,'zbs_invoice_lineitems',true);
                                               if($invlines != ''){
                            $i=1;
                            foreach($invlines as $invline){
                                if($i == 1){
                                    if($invsettings['invtax']){
                                        $cols = 2;
                                        $bcols = 4;
                                        $colsar = 2;

                                    }else{
                                        $cols = 1;
                                        $bcols = 3;
                                        $colsar = 1;
                                        $invline['zbsli_tax'] = 0;                                      }
                                    $zbs_extra_li  = '<td class="row-1-pad" colspan="'.$cols.'"></td>';
                                }
                                else{
                                    $zbs_extra_li = "";
                                }
                                if(!$invsettings['invtax']){
                                        $invline['zbsli_tax'] = 0; 
                                }
                                echo 
                                '<tbody class="zbs-item-block invnotem" data-tableid="'.$i.'" id="tblock'.$i.'">
                                        <tr class="top-row">
                                            <td style="width:77.5%"><input type="text" class = "zbs-item-name" name="zbsli_itemname'.$i.'" value="'.$invline['zbsli_itemname'].'" placeholder="Item Name" required/></td>
                                            <td style="width:7.5%"><input class="quan" data-zbsr="'.$i.'" name="zbsli_quan'.$i.'" type="number" value="'.$invline['zbsli_quan'].'" min="0" step="0.5" placeholder="0" style="width:100%"/></td>
                                            <td style="width:7.5%"><input class="price" data-zbsr="'.$i.'" name="zbsli_price'.$i.'" type="number" value="'.$invline['zbsli_price'].'" min="0" step="0.01" placeholder="0" style="width:100%"/></td>
                                            <td style="width:7.5%" class="taxhide"><input class="tax taxhide" data-zbsr="'.$i.'" name="zbsli_tax'.$i.'" type="number" value="'.$invline['zbsli_tax'].'" min="0" max="100" placeholder="0" style="width:75%;margin-right:6px;"/>%</td>
                                            <td style="width:7.5%" rowspan="2" class="row-amount row-amount-'.$i.'">'.$currencyChar.$invline['zbsli_rowt'].'</td>

                                            <input name="zbsli_rowt'.$i.'" type="hidden" class="hidden-subtotal hidden-row-amount-'.$i.'" value="'.$invline['zbsli_rowt'].'"/>
                                            <input name="zbsli_rowtt" type="hidden" class="inv-tax-subtot" id="row-tax-total-'.$i.'" value=""/>
                                        </tr>
                                        <tr class="bottom-row">
                                            <td colspan="'.$bcols.'" class="tapad"><textarea name="zbsli_itemdes'.$i.'" rows="1" placeholder="Enter detailed description (optional)">'.$invline['zbsli_des'].'</textarea></td>     
                                        </tr>
                                        <tr class="add-row"><td colspan="2"></td>'.$zbs_extra_li.'<td colspan="'.$colsar.'" class="zbs-row-actions remove-row" data-remrow="'.$i.'"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Remove Row</td><td colspan="2" class="zbs-row-actions zbs-add-row"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Row</td></tr><tr class="padding"></tr>
                                </tbody>';                            
                                $i++;
                            }
                        }else{
                        ?>
                        <tbody class="zbs-item-block" data-tableid="1" id="tblock1">
                                <tr class="top-row">
                                    <td style="width:40%"><input type="text" class = "zbs-item-name" name="zbsli_itemname1" value="" placeholder="Item Name" required/></td>
                                    <td style="width:7.5%"><input class="quan" data-zbsr="1" name="zbsli_quan1" type="number" value="0" min="0" step="0.5" placeholder="0" style="width:100%"/></td>
                                    <td style="width:7.5%"><input class="price" data-zbsr="1" name="zbsli_price1" type="number" value="0" min="0" step="0.01" placeholder="0" style="width:100%"/></td>
                                    <td style="width:7.5%" class="taxhide"><input class="tax taxhide" data-zbsr="1" name="zbsli_tax1" type="number" value="0" min="0" max="100" placeholder="0" style="width:75%;margin-right:6px;"/>%</td>
                                    <td style="width:7.5%" rowspan="2" class="row-amount row-amount-1"><?php echo $currencyChar; ?>0.00</td>
                                    <input name="zbsli_rowt1" type="hidden" class="hidden-subtotal hidden-row-amount-1" value=""/>
                                    <input name="zbsli_rowtt" type="hidden" class="inv-tax-subtot" id="row-tax-total-1" value=""/>
                                </tr>
                                <?php if($invsettings['invtax'] == 0){ ?>
                                    <tr class="bottom-row">
                                        <td colspan="3" class="tapad"><textarea name="zbsli_itemdes1" rows="1" placeholder="Enter detailed description (optional)"></textarea></td>
                                    </tr>
                                    <tr class="add-row"><td colspan="1"></td><td class="row-1-pad notaxpad" colspan="1"></td><td colspan="2" class="zbs-row-actions remove-row" data-remrow="1"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Remove Row</td><td colspan="2" class="zbs-row-actions zbs-add-row"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Row</td></tr><tr class="padding"></tr>
                                <?php }else{ ?>
                                    <tr class="bottom-row">
                                        <td colspan="4" class="tapad"><textarea name="zbsli_itemdes1" rows="1" placeholder="Enter detailed description (optional)"></textarea></td>
                                    </tr>
                                    <tr class="add-row"><td colspan="2"></td><td class="row-1-pad yestaxpad" colspan="2"></td><td colspan="2" class="zbs-row-actions remove-row" data-remrow="1"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Remove Row</td><td colspan="2" class="zbs-row-actions zbs-add-row"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Row</td></tr><tr class="padding"></tr>
                                <?php } ?>
                        </tbody>
                        <?php } ?>
               
                </table>
            </div>

            <?php
                $zbsInvoiceTotalsArr = get_post_meta($post->ID,"zbs_invoice_totals",true);
                          ?>
                <table id="invoice_totals">
                <?php if($invsettings['invtax'] == 0 && $invsettings['invpandp'] == 0 && $invsettings['invdis'] == 0 ){ 
                        $subtotalhide = 'hide';
                    }else{
                        $subtotalhide = '';
                   }
                ?>
                    <tr class='total-top <?php echo $subtotalhide; ?>'>
                        <td style="width:50%"></td>
                        <td colspan="3" class='bord bord-l'><?php _we("Subtotal","zerobscrm"); ?></td>
                        <td class='bord row-amount' class='bord'><span class='zbs-subtotal-value'><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ echo $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ echo "0.00"; } ?></span></td>
                        <input class="zbs_gt" type="hidden" name="zbs-subtotal-value" id="zbs-subtotal-value" value="<?php if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ echo $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ echo "0.00"; } ?>"/>
                    </tr>
                <?php if($invsettings['invdis'] == 1){ 
                            $dishide = '';
                        }else{
                            $dishide = 'hide';
                            $zbsInvoiceTotalsArr["invoice_discount_total_value"] = 0;
                            $zbsInvoiceTotalsArr["invoice_discount_total"] = 0;
                        }
                ?>

                <?php
                    $invoice_path = ZEROBSCRM_PATH . 'invoices/';

            
                    if($zbsInvoiceTotalsArr ==''){
                        $zbsInvoiceTotalsArr['invoice_discount_type'] = '%';
                    }
                ?>
                    <tr class='discount <?php echo $dishide; ?>'>
                        <td style="width:50%"></td>
                        <td class='bord bord-l'><?php _we("Discount","zerobscrm"); ?></td>
                        <td class='bord'><input type="number" name="invoice_discount_total" id="invoice_discount_total" step = "0.01" min="0" value="<?php if(!empty($zbsInvoiceTotalsArr["invoice_discount_total"])){ echo $zbsInvoiceTotalsArr["invoice_discount_total"]; }else{ echo "0.00"; } ?>"/></td>
                        <td class='bord'><select id="invoice_discount_type" name="invoice_discount_type"><option value="%" <?php if($zbsInvoiceTotalsArr['invoice_discount_type'] =='%') echo 'selected'; ?>>%</option><option value="m" <?php if($zbsInvoiceTotalsArr['invoice_discount_type'] =='m') echo 'selected'; ?>><?php echo $currencyChar; ?></option></td>
                        <td class='bord row-amount' id="zbs_discount_combi"><?php if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ echo "-" . $currencyChar . $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ echo  $currencyChar . "0.00"; } ?></td>
                        <input class="zbs_gt disc" type="hidden" name="invoice_discount_total_value" id="invoice_discount_total_value" val="<?php if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ echo $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ echo "0.00"; } ?>"/>
                    </tr>
                    <?php if($invsettings['invtax'] == 1){ 
                        $rh = 2;
                    }else{
                        $rh = 1;
                    }
                    ?>
                     <?php if($invsettings['invpandp'] == 1){      $phide = '';
                        }else{
                            $phide = 'hide';
                        }
                        ?>
                    <tr class='postage_and_pack <?php echo $phide; ?>'>
                        <td style="width:50%"></td>
                        <td class='bord bord-l'><?php _we("Postage and packaging","zerobscrm"); ?></td>
                        <td class='bord' colspan="2"><input class="zbs_gt" type="number" step = "0.01" min="0" name="invoice_postage_total" id="invoice_postage_total" value="<?php if(!empty($zbsInvoiceTotalsArr["invoice_postage_total"])){ echo $zbsInvoiceTotalsArr["invoice_postage_total"]; }else{ echo "0.00"; } ?>"/></td>
                        <td class='bord row-amount' id="pandptotal" rowspan="<?php echo $rh; ?>"><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_postage_total"])){ echo number_format($zbsInvoiceTotalsArr["invoice_postage_total"],2); }else{ echo "0.00"; } ?></td>
                    </tr>
                    <?php 

                

                        if($invsettings['invtax'] == 1){ 
                            $phide = '';
                        }else{
                            $phide = 'hide';
                        }

                        if($invsettings['invpandp'] == 0){
                            $phide = 'hide';
                        }

                        ?>
                    <tr class="tax_on_postage <?php echo $phide; ?>">
                        <td style="width:50%"></td>
                        <td class='bord bord-l'><?php _we("Tax on postage (%)","zerobscrm"); ?></td>
                        <td class='bord' colspan="2"><input type="number" name="invoice_postage_tax" id="invoice_postage_tax" value="<?php if(!empty($zbsInvoiceTotalsArr["invoice_postage_tax"])){ echo $zbsInvoiceTotalsArr["invoice_postage_tax"]; }else{ echo "0.00"; } ?>"/></td>
                    </tr>
                    <?php 
                    if(isset($zbsInvoiceTotalsArr["invoice_tax_total"])){
                        $ttclass = 'class="tax_total_1 taxhide"';
                    }else{
                        $ttclass = 'class="tax_total taxhide"';
                    } ?>
                    <?php if($invsettings['invtax'] != 0){  ?>
                    <tr <?php echo $ttclass; ?>>
                        <td style="width:50%"></td>
                        <td colspan="3" class='bord bord-l'><?php _we("Tax","zerobscrm"); ?></td>
                        <td class='bord row-amount zbs-tax-total-span'><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_tax_total"])){ echo $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ echo "0.00"; } ?></td>
                        <input class="zbs_gt" type="hidden" name="invoice_tax_total" id="invoice_tax_total" value="<?php if(!empty($zbsInvoiceTotalsArr["invoice_taxtotal"])){ echo $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ echo "0.00"; } ?>"/>
                    </tr>
                    <?php } ?>
                     <tr class="zbs_grand_total">
                        <td style="width:50%"></td>
                        <td class='bord bord-l' colspan="3"><?php _we("Total","zerobscrm"); ?></td>
                        <td class='bord row-amount' colspan="2"><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_grandt_value"])){ echo $zbsInvoiceTotalsArr["invoice_grandt_value"]; }else{ echo "0.00"; } ?></td>
                        <input type="hidden" name="invoice_grandt_value" id="zbs-grandt-value" value="<?php if(!empty($zbsInvoiceTotalsArr["invoice_grandt_value"])){ echo $zbsInvoiceTotalsArr["invoice_grandt_value"]; }else{ echo "0.00"; } ?>"/>
                    </tr>               

                </table>

                <?php

                    if(!isset($zbsInvoiceTotalsArr["invoice_grandt_value"])){
                        $zbsInvoiceTotalsArr["invoice_grandt_value"] = 0;
                    }


                    if($zbsInvoiceTotalsArr["invoice_grandt_value"] == 0){
                       echo '<table id="partials" class="hide">';
                    }else{
                        echo '<table id="partials">';
                    }
                    global $wpdb;
                                        $zbs_partials_query = $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'zbs_invoice_partials' AND meta_value = '%d'", $post->ID);
                    $zbs_partials = $wpdb->get_results($zbs_partials_query);
                    


                    $balance = $zbsInvoiceTotalsArr["invoice_grandt_value"];

                    if($zbs_partials){
                        $subtotalhide = '';
                        foreach($zbs_partials as $zbs_partial){ 
                            $trans_meta = get_post_meta($zbs_partial->post_id,'zbs_transaction_meta',true);
                            $balance = $balance - $trans_meta['total'];
                            ?>

                            <tr class='total-top <?php echo $subtotalhide; ?>'>
                                <td style="width:50%"></td>
                                <td colspan="3" class='bord bord-l'><?php _we("Payment (transaction ID:","zerobscrm"); ?><?php echo $trans_meta['orderid']; ?>)</td>
                                <td class='bord row-amount' class='bord'><span class='zbs-partial-value'><?php echo $currencyChar; ?><?php if(!empty($trans_meta['total'])){ echo number_format($trans_meta['total'],2); }else{ echo "0.00"; } ?></span></td>
                            </tr>
                    <?php 
                        } 
                    }

                    if($balance == $zbsInvoiceTotalsArr["invoice_grandt_value"]){
                        $balance_hide = 'hide';
                    }else{
                        $balance_hide = '';
                    }

                    ?>


                    <tr class='zbs_grand_total <?php echo $balance_hide; ?>'>
                        <td style="width:50%"></td>
                        <td colspan="3" class='bord bord-l'><?php _we("Amount due","zerobscrm"); ?></td>
                        <td class='bord row-amount' class='bord'><span class='zbs-subtotal-value'><?php echo $currencyChar; ?><?php  echo number_format($balance,2);  ?></span></td>
                    </tr>



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

                });

            </script>
             
            <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />


            <?php  if (!$newInvoice){ ?>
            <div id='zbs-send-test'>
                    <h4 class="modal-title" id="myModalLabel">Send test invoice</h4>

                <div id="zbs_invoice_test_alerts">
                    <div id="zbs_test_success"><i class="fa fa-check-circle" aria-hidden="true"></i> <span></span></div>
                </div>

                    <b>Email: </b><input class="form-control" type="email" id="zbs-modal-invoice-test" value=""/>
                    <button type="button" class="btn btn-primary" onclick="zbscrm_JS_sendtestinvoice()">Send</button>
            </div>
            <?php } ?>




            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            global $wpdb;

            zbs_write_log($post);
                        if ( 'publish' != $post->post_status && $post->post_type == 'zerobs_invoice'){
                $wpdb->update( $wpdb->posts, array( 'post_status' => 'publish' ), array( 'ID' => $post->ID ) );
            }


            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbsci_itemdetails'  && $post->post_type == $this->postType){

                                        $invOffset = zeroBSCRM_getInvoiceOffset();
                    $allowInvNoChange = zeroBSCRM_getSetting('invallowoverride');
                    $invID = (int)$post_id+$invOffset; if ($allowInvNoChange && isset($_POST['zbsinvid']) && !empty($_POST['zbsinvid'])) $invID = (int)$_POST['zbsinvid'];
                    update_post_meta($post_id,"zbsid",$invID);

                                        if (!empty($invID)) zeroBSCRM_setMaxInvoiceID($invID);



                    $zbsCustomerInvoiceMeta = array();

                                        $zbsInvoiceLines = array();
                    update_post_meta($post_id,"zbs_invoice_lineitems",$zbsInvoiceLines); 
                                        for($i=1; $i<=30; $i++){
                                                if(isset($_POST["zbsli_itemname".$i])){
                            $zbsInvoiceLines[$i]["zbsli_itemname"] = $_POST["zbsli_itemname" . $i];
                            $zbsInvoiceLines[$i]["zbsli_itemname"] = isset($_POST["zbsli_itemname" . $i]) ? $_POST["zbsli_itemname" . $i] : "";
                            $zbsInvoiceLines[$i]["zbsli_des"] = isset($_POST["zbsli_itemdes" . $i]) ? $_POST["zbsli_itemdes" . $i] : "";
                            $zbsInvoiceLines[$i]["zbsli_quan"] = isset($_POST["zbsli_quan" . $i]) ? $_POST["zbsli_quan" . $i] : 0;
                            $zbsInvoiceLines[$i]["zbsli_price"] = isset($_POST["zbsli_price" . $i]) ? $_POST["zbsli_price" . $i] : 0;
                            $zbsInvoiceLines[$i]["zbsli_tax"] = isset($_POST["zbsli_tax" . $i]) ? $_POST["zbsli_tax" . $i] : 0;
                            $zbsInvoiceLines[$i]["zbsli_rowt"] = isset($_POST["zbsli_rowt" . $i]) ? $_POST["zbsli_rowt" . $i] : 0;
                        }
                    }

                    update_post_meta($post_id,"zbs_invoice_lineitems",$zbsInvoiceLines);

                    
                    $zbsInvoiceTotals = array();

                    $zbsInvoiceTotals["zbs-subtotal-value"] = isset($_POST["zbs-subtotal-value"]) ? $_POST["zbs-subtotal-value"] : 0;          
                    $zbsInvoiceTotals["invoice_discount_total"] = isset($_POST["invoice_discount_total"]) ? $_POST["invoice_discount_total"] : 0;
                    $zbsInvoiceTotals["invoice_discount_type"] = isset($_POST["invoice_discount_type"]) ? $_POST["invoice_discount_type"] : "%";
                    $zbsInvoiceTotals["invoice_discount_total_value"] = isset($_POST["invoice_discount_total_value"]) ? $_POST["invoice_discount_total_value"] : 0;
                    $zbsInvoiceTotals["invoice_postage_total"] = isset($_POST["invoice_postage_total"]) ? $_POST["invoice_postage_total"] : 0;
                    $zbsInvoiceTotals["invoice_postage_tax"] = isset($_POST["invoice_postage_tax"]) ? $_POST["invoice_postage_tax"] : 0;
                    $zbsInvoiceTotals["invoice_tax_total"] = isset($_POST["invoice_tax_total"]) ? $_POST["invoice_tax_total"] : 0;
                    $zbsInvoiceTotals["invoice_grandt_value"] = isset($_POST["invoice_grandt_value"]) ? $_POST["invoice_grandt_value"] : 0;

                    update_post_meta($post_id,"zbs_invoice_totals",$zbsInvoiceTotals);

                    global $zbsCustomerInvoiceFields;

                    foreach ($zbsCustomerInvoiceFields as $fK => $fV){

                        $zbsCustomerInvoicepdMeta[$fK] = '';

                        if (isset($_POST['zbscq_'.$fK])) {

                            switch ($fV[0]){

                                case 'tel':

                                                                        $zbsCustomerInvoiceMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);
                                    preg_replace("/[^0-9 ]/", '', $zbsCustomerInvoiceMeta[$fK]);
                                    break;

                                case 'price':

                                                                        $zbsCustomerInvoiceMeta[$fK] = sanitize_text_field($_POST['invoice_grandt_value']);                                       $zbsCustomerInvoiceMeta[$fK] = preg_replace('@[^0-9\.]+@i', '-', $zbsCustomerInvoiceMeta[$fK]);
                                    $zbsCustomerInvoiceMeta[$fK] = floatval($zbsCustomerInvoiceMeta[$fK]);
                                    break;


                                case 'textarea':

                                    $zbsCustomerInvoiceMeta[$fK] = zeroBSCRM_textProcess($_POST['zbscq_'.$fK]);

                                    break;

                                default:

                                    $zbsCustomerInvoiceMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);

                                    break;


                            }

                        }


                    }


                                        $zbsInvoiceHorQ = $_POST['invoice-customiser-type'];
                    update_post_meta($post_id, 'zbsInvoiceHorQ', $zbsInvoiceHorQ);

                                                                                                                        $zbsCustomerInvoiceCustomer = -1; if (isset($_POST['zbsci_customer'])) $zbsCustomerInvoiceCustomer = (int)$_POST['zbsci_customer'];
                    if ($zbsCustomerInvoiceCustomer !== -1) update_post_meta($post_id, 'zbs_customer_invoice_customer', $zbsCustomerInvoiceCustomer);

                                        $zbsCompanyInvoiceCompany = -1; if (isset($_POST['zbsci_company'])) $zbsCompanyInvoiceCompany = (int)$_POST['zbsci_company'];
                    if ($zbsCompanyInvoiceCompany !== -1) update_post_meta($post_id, 'zbs_company_invoice_company', $zbsCompanyInvoiceCompany);
                             

                                        if (isset($_POST['zbsci_fromquote'])) {
                        $zbsCustomerInvoiceMeta['quolink'] = (int)sanitize_text_field($_POST['zbsci_fromquote']);

                                                if (!empty($zbsCustomerInvoiceMeta['quolink'])) {
                            
                                                                                    $quoteFrom = zeroBS_getQuoteByWPID($zbsCustomerInvoiceMeta['quolink']);
                            if (isset($quoteFrom['meta'])){

                                $newMeta = $quoteFrom['meta'];
                                if (!isset($quoteFrom['meta']['invlink']) || empty($quoteFrom['meta']['invlink']) || !is_array($quoteFrom['meta']['invlink'])){

                                                                        $newMeta['invlink'] = array($post_id);
                                    

                                } else {

                                                                                                                                                $newInvLink = array($post_id); if (count($newMeta['invlink']) > 0) foreach ($newMeta['invlink'] as $invLinkID) {
                                        if (!in_array($invLinkID,$newInvLink)) $newInvLink[] = $invLinkID;
                                    }
                                    $newMeta['invlink'] = $newInvLink;

                                }

                                update_post_meta($zbsCustomerInvoiceMeta['quolink'], 'zbs_customer_quote_meta', $newMeta);

                            } 
                        }
                    }

                    

                                        $zbs_status = 'Draft'; if (isset($_POST['invoice_status'])) $zbs_status =$_POST['invoice_status'];
                    $zbsCustomerInvoiceMeta['status'] = $zbs_status;


                                        
                    $zbs_recur = 0; if (isset($_POST['zbs_recur'])) $zbs_recur = (int)$_POST['zbs_recur'];
                    $zbsCustomerInvoiceMeta['zbs_recur'] = $zbs_recur;

                    $payMode = zeroBSCRM_getSetting('invpro_pay');

                    if($payMode == '1'){                           $stripe_plan = ''; if (isset($_POST['stripe_plan'])) $stripe_plan = $_POST['stripe_plan'];
                        $zbsCustomerInvoiceMeta['stripe_plan'] = $stripe_plan;
                    }

                    if($payMode == '2'){                          $paypal_period = 'M'; if (isset($_POST['paypal_period'])) $paypal_period = $_POST['paypal_period'];
                        $zbsCustomerInvoiceMeta['paypal_period'] = $paypal_period;

                       $paypal_every = 1; if (isset($_POST['paypal_every'])) $paypal_every = $_POST['paypal_every'];
                        $zbsCustomerInvoiceMeta['paypal_every'] = $paypal_every;

                    }


                                        $zbs_inv_val = preg_replace('@[^0-9\.]+@i', '-', $_POST['invoice_grandt_value']);
                    $zbsCustomerInvoiceMeta['val'] = $zbs_inv_val;
                                        update_post_meta($post_id, 'zbs_customer_invoice_meta', $zbsCustomerInvoiceMeta);


                                                            if (isset($_POST['zbscrm_newinvoice']) && $_POST['zbscrm_newinvoice'] == 1){

                        zeroBSCRM_FireInternalAutomator('invoice.new',array(
                            'id'=>$post_id,
                            'againstid' => $zbsCustomerInvoiceCustomer,
                            'invoiceMeta'=> $zbsCustomerInvoiceMeta,
                            'zbsid'=>$invID
                            ));
                        
                    }



                }
            }

            return $post;
        }
    }

    $zeroBS__MetaboxInvoice = new zeroBS__MetaboxInvoice( __FILE__ );










    function zeroBS__MetaboxFilesInvoice($post) {  
                

        $html = '';
        global $zeroBSCRM_slugs;

        
                                $zbsCustomerInvoices = get_post_meta($post->ID, 'zbs_customer_invoices', true);

        ?>
                <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxMainItemInvs">

                    <?php 

                                        if (is_array($zbsCustomerInvoices) && count($zbsCustomerInvoices) > 0){ 
                      ?><tr class="wh-large"><th><label><?php echo count($zbsCustomerInvoices).' Attachment:'; ?></label></th>
                                <td id="zbsFileWrapInvoices">
                                    <?php $fileLineIndx = 1; foreach($zbsCustomerInvoices as $quote){
                                        $file = basename($quote['file']);
                                        echo '<div class="zbsFileLine" id="zbsFileLineInvoice'.$fileLineIndx.'"><a href="'.$quote['url'].'" target="_blank">'.$file.'</a> (<span class="zbsDelFile" data-delurl="'.$quote['url'].'"><i class="fa fa-trash"></i></span>)</div>';
                                        $fileLineIndx++;

                                    } ?>
                                </td></tr><?php

                    } ?>

                    <?php 

                            wp_nonce_field(plugin_basename(__FILE__), 'zbsc_invoice_attachment_nonce');
                             
                            $html .= '<input type="file" id="zbsc_invoice_attachment" name="zbsc_invoice_attachment" value="" size="25">';
                            $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']);
                            ?><tr class="wh-large"><th><label><?php _we("Attach Files","zerobscrm");?></label><div class="zbs-infobox">You can attach as many file types as you like. Supported file formats are <span class='zbs-file-types'><?php echo zeroBS_acceptableFileTypeListStr(); ?></span>. You can manage these in <a href="<?php echo $zbs_settings_slug; ?>" target="_blank"><?php _we("Settings","zerobscrm"); ?></a>. Use this for attaching things like a PDF version of the invoice or your Terms and Conditions.</div></th>
                                <td><?php
                            echo $html;

                    ?></td></tr>

                    <tr><td colspan="2" class="zbs-normal zbs-add-memo-trigger hide">
                        <span class="zbs-plus">+</span> <?php _we("Add memo to self", "zerobscrm"); ?>
                   </td></tr>

                    <tr><td colspan="2" class="zbs-normal zbs-memo-box hide">
                        <div class="zbs-memo"><?php _we("Memo", "zerobscrm"); ?></div>
                        <textarea class="zbs-memo-ta form-control" id="notes" name="notes" placeholder="<?php _we("Add memo to self (your recipient won't see this)","zerobscrm"); ?>"></textarea>
                        <div class='zbs-memo-hide'><?php _we("Hide","zerobscrm"); ?></div>
                   </td></tr>



                    <tr><td colspan="2" class="zbs-italic">
                        It is the user's responsibility to create an invoice that is compliant with local laws and regulations, including, but not limited to, the application of the correct tax rate(s)
                    </td></tr>

                
                </table>
                <script type="text/javascript">

                    var zbsInvoicesCurrentlyDeleting = false;

                    jQuery('document').ready(function(){

                        jQuery('.zbsDelFile').click(function(){

                            if (!window.zbsInvoicesCurrentlyDeleting){

                                // blocking
                                window.zbsInvoicesCurrentlyDeleting = true;

                                var delUrl = jQuery(this).attr('data-delurl');
                                var lineIDtoRemove = jQuery(this).closest('.zbsFileLine').attr('id');

                                if (typeof delUrl != "undefined" && delUrl != ''){



                                      // postbag!
                                      var data = {
                                        'action': 'delFile',
                                        'zbsfType': 'invoices',
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

                                                jQuery('#zbsFileWrapInvoices').append('<div class="alert alert-error" style="margin-top:10px;"><strong>Error:</strong> Unable to delete this file.</div>');

                                                // Callback
                                                //if (typeof errorcb == "function") errorcb(response);
                                                //callback(response);


                                              }

                                            });

                                }

                                window.zbsInvoicesCurrentlyDeleting = false;

                            } // / blocking

                        });

                    });


                </script><?php
    }

    add_action('save_post', 'zeroBSCRM_save_invoice_file_data');
    function zeroBSCRM_save_invoice_file_data($id) {



        if(!empty($_FILES['zbsc_invoice_attachment']['name'])) {


        
        if(!wp_verify_nonce($_POST['zbsc_invoice_attachment_nonce'], plugin_basename(__FILE__))) {
          return $id;
        }            
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
          return $id;
        } 
        
        if (!zeroBSCRM_permsInvoices()){
            return $id;
        }
        
            $supported_types = zeroBS_acceptableFileTypeMIMEArr();             $arr_file_type = wp_check_filetype(basename($_FILES['zbsc_invoice_attachment']['name']));
            $uploaded_type = $arr_file_type['type'];

            if(in_array($uploaded_type, $supported_types)) {
                $upload = wp_upload_bits($_FILES['zbsc_invoice_attachment']['name'], null, file_get_contents($_FILES['zbsc_invoice_attachment']['tmp_name']));
                if(isset($upload['error']) && $upload['error'] != 0) {
                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                } else {
                    

                                                                        $zbsCustomerInvoices = get_post_meta($id,'zbs_customer_invoices', true);

                                    if (is_array($zbsCustomerInvoices)){

                                                                                $zbsCustomerInvoices[] = $upload;

                                    } else {

                                                                                $zbsCustomerInvoices = array($upload);

                                    }

                                    update_post_meta($id, 'zbs_customer_invoices', $zbsCustomerInvoices);  
                }
            }
            else {
                wp_die("The file type that you've uploaded is not an accepted file format.");
            }
        }
    }

