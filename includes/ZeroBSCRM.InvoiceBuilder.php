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








function zeroBSCRM_replace_invoice_submit_meta_box() 
{

                remove_meta_box('submitdiv', 'zerobs_invoice', 'core');  
                if(!class_exists('zeroBS__SubmitMetabox')) require_once(ZEROBSCRM_PATH . 'includes/ZeroBSCRM.MetaBoxes.SubmitBoxes.php');
  
}

add_action( 'admin_menu', 'zeroBSCRM_replace_invoice_submit_meta_box' );

function zeroBSCRM_invoiceBuilderColumnCount(){

        global $post;
        if (isset($post->post_status) && $post->post_status != "auto-draft") return 2;

        return 2;

}
add_filter('get_user_option_screen_layout_zerobs_invoice', 'zeroBSCRM_invoiceBuilderColumnCount' );



function zeroBSCRM_invoice_generateInvoiceHTML($invoicePostID=-1,$return=true){

    if (!empty($invoicePostID)){

        $templatedHTML = ''; $html;

        if (function_exists('file_get_contents')){

            try {

                $templatedHTML = file_get_contents(ZEROBSCRM_PATH.'html/invoices/invoice.html');


            } catch (Exception $e){

                
            }

        }   

                if (!empty($templatedHTML)){

                        $currencyChar = zeroBSCRM_getCurrencyChr();
            global $zeroBSCRM_Settings; $invsettings = $zeroBSCRM_Settings->getAll();

            
                                $zbsCustomer = get_post_meta($invoicePostID, 'zbs_customer_invoice_meta', true);
                $zbsCustomerID = get_post_meta($invoicePostID, 'zbs_customer_invoice_customer', true);

                global $zbsCustomerInvoiceFields, $zeroBSCRM_slugs;
                $fields = $zbsCustomerInvoiceFields;


                                if (
                                        (isset($zbsCustomer) && is_array($zbsCustomer) && (!isset($zbsCustomer['status']) || empty($zbsCustomer['status']))) ||
                                        (!isset($zbsCustomer) || !is_array($zbsCustomer))
                    ) $zbsCustomer['status'] = 'Draft';   
                $invOffset = zeroBSCRM_getInvoiceOffset();
                $allowInvNoChange = zeroBSCRM_getSetting('invallowoverride');

                $b2b = zeroBSCRM_getSetting('companylevelcustomers');
                
                                $zbs_inv_meta = get_post_meta($invoicePostID,'zbs_customer_invoice_meta', true); 
                

                                $thisInvNo = get_post_meta($invoicePostID,'zbsid', true); 
                


                if(!isset($zbs_inv_meta['status'])){
                    $zbs_stat = 'Draft';
                }else{
                    $zbs_stat = $zbs_inv_meta['status'];
                }
                
                

                if(isset($zbs_inv_meta['logo'])){
                    $logoURL = $zbs_inv_meta['logo'];
                }else{
                                        if($invsettings['invoicelogourl'] != ''){
                        $logoURL = $invsettings['invoicelogourl'];
                    }else{
                        $logoURL = '';
                    }
                }


                if($logoURL != ''){
                    $logoClass = 'show';
                                        $logoURL = $zbs_inv_meta['logo'];
                    $bizInfoClass = '';
                }else{
                    $logoClass = '';
                                        $logoURL = '';
                    $bizInfoClass = 'biz-up';
                }

                                                                
                $invNoStr = '';
                if (isset($allowInvNoChange) && $allowInvNoChange == '1'){ 
                    if (isset($thisInvNo)) {
                        $invNoStr = $thisInvNo; 
                    }
                 } else {
                                        $invNoStr = '<div class="zbs-normal">'.$thisInvNo.'</div>';
                } 

                $invDateStr = '';
                $inv_date = date_create($zbs_inv_meta['date']);


                $invDateStr = date_format($inv_date, 'd M Y');

                $ref = '';
                if (isset($zbs_inv_meta['ref'])) $ref = $zbs_inv_meta['ref'];

                if($zbs_inv_meta['due'] == -1){
                                    $dueDateStr = __("No due date", "zerobscrm");
                }else{
                    $due_date = date_create($zbs_inv_meta['date']);
                    $str = $zbs_inv_meta['due'] . ' days';
                    date_add($due_date, date_interval_create_from_date_string($str));
                    $dueDateStr = date_format($due_date, 'd M Y');
                }

                $bizInfoTable = '';

                                        $zbs_biz_name =  zeroBSCRM_getSetting('businessname');
                    $zbs_biz_yourname =  zeroBSCRM_getSetting('businessyourname');

                    $zbs_biz_extra =  zeroBSCRM_getSetting('businessextra');

                    $zbs_biz_youremail =  zeroBSCRM_getSetting('businessyouremail');
                    $zbs_biz_yoururl =  zeroBSCRM_getSetting('businessyoururl');
                    $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']) . "&tab=invbuilder";

                    $bizInfoTable = '<table class="table zbs-table">';
                        $bizInfoTable .= '<tbody>';
                            $bizInfoTable .= '<tr><td>'.$zbs_biz_name.'</td></tr>';
                            $bizInfoTable .= '<tr><td>'.$zbs_biz_yourname.'</td></tr>';
                            $bizInfoTable .= '<tr><td>'.$zbs_biz_extra.'</td></tr>';
                            $bizInfoTable .= '<tr class="top-pad"><td>'.$zbs_biz_youremail.'</td></tr>';
                            $bizInfoTable .= '<tr><td>'.$zbs_biz_yoururl.'</td></tr>';
                        $bizInfoTable .= '</tbody>';
                    $bizInfoTable .= '</table>';

                
                $tableHeaders = '';

                    $zbsInvoiceHorQ = get_post_meta($invoicePostID, 'zbsInvoiceHorQ', true);

                     if($zbsInvoiceHorQ == 'quantity'){ 
                        $tableHeaders = '<th style="font-weight:100" class="cen" id="zbs_inv_qoh">'.__w("Quantity","zerobscrm").'</th>';
                        $tableHeaders .= '<th style="font-weight:100" class="cen" id="zbs_inv_por">'.__w("Price","zerobscrm").'</th>';
                     }else{ 
                        $tableHeaders = '<th style="font-weight:100" class="cen" id="zbs_inv_qoh">'.__w("Hours","zerobscrm").'</th>';
                        $tableHeaders .= '<th style="font-weight:100" class="cen" id="zbs_inv_por">'.__w("Rate","zerobscrm").'</th>';
                     }


                $lineItems = '';

                    $invlines = get_post_meta($invoicePostID,'zbs_invoice_lineitems',true);

                                               if($invlines != ''){
                            $i=1;
                            foreach($invlines as $invline){
                                if($i == 1){
                                    $zbs_extra_li  = '<td class="row-1-pad" colspan="2"></td>';
                                }
                                else{
                                    $zbs_extra_li = "";
                                }
                                $lineItems .= 
                                '<tbody class="zbs-item-block" data-tableid="'.$i.'" id="tblock'.$i.'">
                                        <tr class="top-row">
                                            <td style="width:70%">'.$invline['zbsli_itemname'].'</td>
                                            <td style="width:7.5%;text-align:center;" rowspan="3" class="cen">'.$invline['zbsli_quan'].'</td>
                                            <td style="width:7.5%;text-align:center;" rowspan="3"class="cen">'. $currencyChar.$invline['zbsli_price'].'</td>
                                            <td style="width:7.5%;text-align:right;" rowspan="3" class="row-amount">' . $currencyChar. $invline['zbsli_rowt'].'</td>
                                        </tr>
                                        <tr class="bottom-row">
                                            <td colspan="4" class="tapad">'.$invline['zbsli_des'].'</td>     
                                        </tr>
                                        <tr class="add-row"></tr>
                                </tbody>';                            
                                $i++;
                            }
                        }

                $totalsTable = '';

                    $zbsInvoiceTotalsArr = get_post_meta($invoicePostID,"zbs_invoice_totals",true);

                    $totalsTable .= '<table id="invoice_totals" style="width:100%;">';
                        if($invsettings['invtax'] != 0 && $invsettings['invpandp'] != 0 && $invsettings['invdis'] != 0 ){
                        $totalsTable .= '<tr class="total-top">';
                            $totalsTable .= '<td style="width:76%"></td>';
                            $totalsTable .= '<td colspan="3" class="bord bord-l" style="text-align:right">'.__w("Subtotal","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" style="text-align:right"><span class="zbs-subtotal-value">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ $totalsTable .= $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '</span></td>';
                            $totalsTable .= '<input class="zbs_gt" type="hidden" name="zbs-subtotal-value" id="zbs-subtotal-value" val="';
                                if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ $totalsTable .= $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '"/>';
                        $totalsTable .= '</tr>';
                        }
                        if(isset($zbsInvoiceTotalsArr["invoice_discount_total_value"]) && !empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])) {
                                                                                
                        if($invsettings['invdis'] == 1){ 
                            $totalsTable .= '<tr class="discount">';
                                $totalsTable .= '<td style="width:76%"></td>';
                                $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right">'.__w("Discount","zerobscrm").'</td>';
                                $totalsTable .= '<td class="bord row-amount" id="zbs_discount_combi" style="text-align:right">';
                                    if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ $totalsTable .= "-" . $currencyChar . $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ $totalsTable .= $currencyChar . "0.00"; }
                                $totalsTable .= '</td>';
                                $totalsTable .= '<input class="zbs_gt disc" type="hidden" name="invoice_discount_total_value" id="invoice_discount_total_value" val="';
                                    if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ $totalsTable .= "0.00"; }
                                $totalsTable .= '"/>';
                            $totalsTable .= '</tr>';
                        }
                        }
                        if($invsettings['invpandp'] == 1){ 
                        $totalsTable .= '<tr class="postage_and_pack">';
                            $totalsTable .= '<td style="width:76%"></td>';
                            $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right">'.__w("Postage and packaging","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" id="pandptotal" rowspan="1" style="text-align:right">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_postage_total"])){ $totalsTable .= number_format($zbsInvoiceTotalsArr["invoice_postage_total"],2); }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '</td>';
                        $totalsTable .= '</tr>';
                        }
                        if($invsettings['invtax'] == 1){

                        if(isset($zbsInvoiceTotalsArr["invoice_tax_total"])){
                            $ttclass = 'tax_total_1';
                        }else{
                            $ttclass = 'tax_total';
                        }
                        $totalsTable .= '<tr class="'.$ttclass.'">';
                            $totalsTable .= '<td style="width:76%"></td>';
                            $totalsTable .= '<td colspan="3" class="bord bord-l" style="text-align:right">'.__w("Tax","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount zbs-tax-total-span" style="text-align:right">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_tax_total"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '</td>';
                            $totalsTable .= '<input class="zbs_gt" type="hidden" name="invoice_tax_total" id="invoice_tax_total" value="';
                                if(!empty($zbsInvoiceTotalsArr["invoice_taxtotal"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '"/>';
                        $totalsTable .= '</tr>';
                        } 
                         $totalsTable .= '<tr class="zbs_grand_total" style="line-height:30px;">';
                            $totalsTable .= '<td style="width:76%"></td>';
                            $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right;background:#f5f5f5;font-weight:800">'.__w("Total","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" colspan="2" style="text-align:right;background:#f5f5f5;font-weight:800">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_grandt_value"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_grandt_value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '</td>';
                        $totalsTable .= '</tr>';

                    $totalsTable .= '</table>';



                    $partialsTable = '';

                        if($zbsInvoiceTotalsArr["invoice_grandt_value"] == 0){
                           $partialsTable .= '<table id="partials" class="hide">';
                        }else{
                            $partialsTable .= '<table id="partials">';
                        }
                        global $wpdb;
                                                $zbs_partials_query = $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'zbs_invoice_partials' AND meta_value = '%d'", $invoicePostID);
                        $zbs_partials = $wpdb->get_results($zbs_partials_query);

                        $balance = $zbsInvoiceTotalsArr["invoice_grandt_value"];

                        if($zbs_partials){
                            $subtotalhide = '';
                            foreach($zbs_partials as $zbs_partial){ 
                                $trans_meta = get_post_meta($zbs_partial->post_id,'zbs_transaction_meta',true);
                                $balance = $balance - $trans_meta['total'];
                                

                                $partialsTable .= '<tr class="total-top '.$subtotalhide.'">';
                                    $partialsTable .= '<td style="width:50%"></td>';
                                    $partialsTable .= '<td colspan="3" class="bord bord-l">'.__w("Payment (transaction ID:","zerobscrm").$trans_meta['orderid'].')</td>';
                                    $partialsTable .= '<td class="bord row-amount"><span class="zbs-partial-value">'.$currencyChar;
                                        if(!empty($trans_meta['total'])){ $partialsTable .=  number_format($trans_meta['total'],2); }else{ $partialsTable .=  "0.00"; }
                                    $partialsTable .= '</span></td>';
                                $partialsTable .= '</tr>';
                        
                            } 
                        }

                        if($balance == $zbsInvoiceTotalsArr["invoice_grandt_value"]){
                            $balance_hide = 'hide';
                        }else{
                            $balance_hide = '';
                        }

                        $partialsTable .= '<tr class="zbs_grand_total'.$balance_hide.'">';
                            $partialsTable .= '<td style="width:50%"></td>';
                            $partialsTable .= '<td colspan="3" class="bord bord-l">'.__w("Amount due","zerobscrm").'</td>';
                            $partialsTable .= '<td class="bord row-amount"><span class="zbs-subtotal-value">'.$currencyChar.number_format($balance,2).'</span></td>';
                        $partialsTable .= '</tr>';
                        $partialsTable .= '</table>';

                                                                                                                                                                  if (!empty($potentialPayButton) && gettype($potentialPayButton) == 'string') $partialsTable .= $potentialPayButton;
                            

                        $cssURL = ZEROBSCRM_URL . 'css/ZeroBSCRM.admin.invoicepreview.min.css';

                        $html = str_replace('###CSS###',$cssURL,$templatedHTML);
            $html = str_replace('###LOGOCLASS###',$logoClass,$html);  
            $html = str_replace('###LOGOURL###',$logoURL,$html);  
            $html = str_replace('###INVNOSTR###',$invNoStr,$html);  
            $html = str_replace('###INVDATESTR###',$invDateStr,$html);  
            $html = str_replace('###REF###',$ref,$html);  
            $html = str_replace('###DUEDATE###',$dueDateStr,$html);  
            $html = str_replace('###BIZCLASS###',$bizInfoClass,$html);  
            $html = str_replace('###BIZINFOTABLE###',$bizInfoTable,$html);  
            $html = str_replace('###TABLEHEADERS###',$tableHeaders,$html);  
            $html = str_replace('###LINEITEMS###',$lineItems,$html);  
            $html = str_replace('###TOTALSTABLE###',$totalsTable,$html);    
            $html = str_replace('###PARTIALSTABLE###',$partialsTable,$html);    

                        if (!$return) { echo $html; exit(); }

        }

        return $html;

    } 

        return false;
}


function zeroBSCRM_invoice_generatePortalInvoiceHTML($invoicePostID=-1,$return=true){

    if (!empty($invoicePostID)){

        $templatedHTML = ''; $html;

        if (function_exists('file_get_contents')){

            try {

                $templatedHTML = file_get_contents(ZEROBSCRM_PATH.'html/invoices/portalinvoice.html');


            } catch (Exception $e){

                
            }

        }   

                if (!empty($templatedHTML)){

                        $currencyChar = zeroBSCRM_getCurrencyChr();
            global $zeroBSCRM_Settings; $invsettings = $zeroBSCRM_Settings->getAll();

            
                                $zbsCustomer = get_post_meta($invoicePostID, 'zbs_customer_invoice_meta', true);
                $zbsCustomerID = get_post_meta($invoicePostID, 'zbs_customer_invoice_customer', true);

                global $zbsCustomerInvoiceFields, $zeroBSCRM_slugs;
                $fields = $zbsCustomerInvoiceFields;


                                if (
                                        (isset($zbsCustomer) && is_array($zbsCustomer) && (!isset($zbsCustomer['status']) || empty($zbsCustomer['status']))) ||
                                        (!isset($zbsCustomer) || !is_array($zbsCustomer))
                    ) $zbsCustomer['status'] = 'Draft';   
                $invOffset = zeroBSCRM_getInvoiceOffset();
                $allowInvNoChange = zeroBSCRM_getSetting('invallowoverride');

                $b2b = zeroBSCRM_getSetting('companylevelcustomers');
                
                                $zbs_inv_meta = get_post_meta($invoicePostID,'zbs_customer_invoice_meta', true); 
                

                                $thisInvNo = get_post_meta($invoicePostID,'zbsid', true); 
                


                if(!isset($zbs_inv_meta['status'])){
                    $zbs_stat = 'Draft';
                }else{
                    $zbs_stat = $zbs_inv_meta['status'];
                }

                if ($zbs_stat == 'Paid') {
                    $stat_str = __w("Total Paid");
                }

                if($zbs_stat == 'Unpaid'){
                    $stat_str = __w("To Pay");
                }

                $zbsInvoiceTotalsArr = get_post_meta($invoicePostID,"zbs_invoice_totals",true);

                $topStatus = '<div class="zbs-portal-label">';
                    $topStatus .= $stat_str;
                $topStatus .= '</div>';



                $topStatus .= '<h1 class="zbs-portal-value">' . $currencyChar . number_format($zbsInvoiceTotalsArr["invoice_grandt_value"], 2) . '</h1>';
                
                if($zbs_stat == 'Paid'){
                    $topStatus .= '<div class="zbs-invoice-paid"><i class="fa fa-check"></i>' . __w("Paid") . '</div>';   
                }

                

                if(isset($zbs_inv_meta['logo'])){
                    $logoURL = $zbs_inv_meta['logo'];
                }else{
                                        if($invsettings['invoicelogourl'] != ''){
                        $logoURL = $invsettings['invoicelogourl'];
                    }else{
                        $logoURL = '';
                    }
                }


                if($logoURL != ''){
                    $logoClass = 'show';
                                        $logoURL = $zbs_inv_meta['logo'];
                    $bizInfoClass = '';
                }else{
                    $logoClass = '';
                                        $logoURL = '';
                    $bizInfoClass = 'biz-up';
                }

                                                                
                $invNoStr = '';
                if (isset($allowInvNoChange) && $allowInvNoChange == '1'){ 
                    if (isset($thisInvNo)) {
                        $invNoStr = $thisInvNo; 
                    }
                 } else {
                                        $invNoStr = '<div class="zbs-normal inv-num">'.$thisInvNo.'</div>';
                } 

                $invDateStr = '';
                $inv_date = date_create($zbs_inv_meta['date']);


                $invDateStr = date_format($inv_date, 'd M Y');

                $ref = '';
                if (isset($zbs_inv_meta['ref'])) $ref = $zbs_inv_meta['ref'];

                if($zbs_inv_meta['due'] == -1){
                                    $dueDateStr = __("No due date", "zerobscrm");
                }else{
                    $due_date = date_create($zbs_inv_meta['date']);
                    $str = $zbs_inv_meta['due'] . ' days';
                    date_add($due_date, date_interval_create_from_date_string($str));
                    $dueDateStr = date_format($due_date, 'd M Y');
                }


                $bizInfoTable = '';

                                        $zbs_biz_name =  zeroBSCRM_getSetting('businessname');
                    $zbs_biz_yourname =  zeroBSCRM_getSetting('businessyourname');

                    $zbs_biz_extra =  zeroBSCRM_getSetting('businessextra');

                    $zbs_biz_youremail =  zeroBSCRM_getSetting('businessyouremail');
                    $zbs_biz_yoururl =  zeroBSCRM_getSetting('businessyoururl');
                    $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']) . "&tab=invbuilder";

                    $bizInfoTable = '<div class="pay-to">';
                    $bizInfoTable .= '<div class="zbs-portal-label">' . __w('Pay To') . '</div>';
                        $bizInfoTable .= '<div class="zbs-portal-biz">';
                            $bizInfoTable .= '<div class="pay-to-name">'.$zbs_biz_name.'</div>';
                            $bizInfoTable .= '<div>'.$zbs_biz_yourname.'</div>';
                            $bizInfoTable .= '<div>'.nl2br($zbs_biz_extra).'</div>';
                            $bizInfoTable .= '<div>'.$zbs_biz_youremail.'</div>';
                            $bizInfoTable .= '<div>'.$zbs_biz_yoururl.'</div>';
                        $bizInfoTable .= '</div>';
                    $bizInfoTable .= '</div>';

                    $invTo = get_post_meta($zbsCustomerID, 'zbs_customer_meta', true);

                    $custInfoTable = '<div class="pay-to">';
                    $custInfoTable .= '<div class="zbs-portal-label">' . __w('Invoice To') . '</div>';
                        $custInfoTable .= '<div class="zbs-portal-biz">';
                            $custInfoTable .= '<div class="pay-to-name">'.$invTo['fname'].' ' .$invTo['lname'] . '</div>';
                            $custInfoTable .= '<div>'.$invTo['addr1'].'</div>';
                            $custInfoTable .= '<div>'.$invTo['addr2'].'</div>';
                            $custInfoTable .= '<div>'.$invTo['city'].'</div>';
                            $custInfoTable .= '<div>'.$invTo['postcode'].'</div>';
                        $custInfoTable .= '</div>';
                    $custInfoTable .= '</div>';
                
                $tableHeaders = '';

                    $zbsInvoiceHorQ = get_post_meta($invoicePostID, 'zbsInvoiceHorQ', true);

                     if($zbsInvoiceHorQ == 'quantity'){ 
                        $tableHeaders = '<th style="font-weight:100" class="cen" id="zbs_inv_qoh">'.__w("Quantity","zerobscrm").'</th>';
                        $tableHeaders .= '<th style="font-weight:100" class="cen" id="zbs_inv_por">'.__w("Price","zerobscrm").'</th>';
                     }else{ 
                        $tableHeaders = '<th style="font-weight:100" class="cen" id="zbs_inv_qoh">'.__w("Hours","zerobscrm").'</th>';
                        $tableHeaders .= '<th style="font-weight:100" class="cen" id="zbs_inv_por">'.__w("Rate","zerobscrm").'</th>';
                     }


                $lineItems = '';

                    $invlines = get_post_meta($invoicePostID,'zbs_invoice_lineitems',true);

                                               if($invlines != ''){
                            $i=1;
                            foreach($invlines as $invline){
                                if($i == 1){
                                    $zbs_extra_li  = '<td class="row-1-pad" colspan="2"></td>';
                                }
                                else{
                                    $zbs_extra_li = "";
                                }
                                $lineItems .= 
                                '<tbody class="zbs-item-block" data-tableid="'.$i.'" id="tblock'.$i.'">
                                        <tr class="top-row">
                                            <td style="width:70%">'.$invline['zbsli_itemname'].'<br/><span class="dz">'.$invline['zbsli_des'].'</span></td>
                                            <td style="width:7.5%;text-align:center;" rowspan="3" class="cen">'.$invline['zbsli_quan'].'</td>
                                            <td style="width:7.5%;text-align:center;" rowspan="3"class="cen">'. $currencyChar.$invline['zbsli_price'].'</td>
                                            <td style="width:7.5%;text-align:right;" rowspan="3" class="row-amount">' . $currencyChar. $invline['zbsli_rowt'].'</td>
                                        </tr>
                                </tbody>';                            
                                $i++;
                            }
                        }

                $totalsTable = '';
                    $totalsTable .= '<table id="invoice_totals" style="width:100%;">';
                        if($invsettings['invtax'] != 0 && $invsettings['invpandp'] != 0 && $invsettings['invdis'] != 0 ){
                        $totalsTable .= '<tr class="total-top">';
                            $totalsTable .= '<td style="width:50%"></td>';
                            $totalsTable .= '<td colspan="3" class="bord bord-l" style="text-align:right">'.__w("Subtotal","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" colspan="2" style="text-align:right"><span class="zbs-subtotal-value">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ $totalsTable .= $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '</span></td>';
                            $totalsTable .= '<input class="zbs_gt" type="hidden" name="zbs-subtotal-value" id="zbs-subtotal-value" val="';
                                if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ $totalsTable .= $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '"/>';
                        $totalsTable .= '</tr>';
                        }
                        if(isset($zbsInvoiceTotalsArr["invoice_discount_total_value"]) && !empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])) {
                                                                                
                        if($invsettings['invdis'] == 1){ 
                            $totalsTable .= '<tr class="discount">';
                                $totalsTable .= '<td style="width:50%"></td>';
                                $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right">'.__w("Discount","zerobscrm").'</td>';
                                $totalsTable .= '<td class="bord row-amount" colspan="2" id="zbs_discount_combi" style="text-align:right">';
                                    if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ $totalsTable .= "-" . $currencyChar . $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ $totalsTable .= $currencyChar . "0.00"; }
                                $totalsTable .= '</td>';
                                $totalsTable .= '<input class="zbs_gt disc" type="hidden" name="invoice_discount_total_value" id="invoice_discount_total_value" val="';
                                    if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ $totalsTable .= "0.00"; }
                                $totalsTable .= '"/>';
                            $totalsTable .= '</tr>';
                        }
                        }
                        if($invsettings['invpandp'] == 1){ 
                        $totalsTable .= '<tr class="postage_and_pack">';
                            $totalsTable .= '<td style="width:50%"></td>';
                            $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right">'.__w("Postage and packaging","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" colspan="2" id="pandptotal" rowspan="1" style="text-align:right">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_postage_total"])){ $totalsTable .= number_format($zbsInvoiceTotalsArr["invoice_postage_total"],2); }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '</td>';
                        $totalsTable .= '</tr>';
                        }
                        if($invsettings['invtax'] == 1){

                        if(isset($zbsInvoiceTotalsArr["invoice_tax_total"])){
                            $ttclass = 'tax_total_1';
                        }else{
                            $ttclass = 'tax_total';
                        }
                        $totalsTable .= '<tr class="'.$ttclass.'">';
                            $totalsTable .= '<td style="width:50%"></td>';
                            $totalsTable .= '<td colspan="3" class="bord bord-l" style="text-align:right">'.__w("Tax","zerobscrm").'</td>';
                            $totalsTable .= '<td colspan="2" class="bord row-amount zbs-tax-total-span" style="text-align:right">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_tax_total"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '</td>';
                            $totalsTable .= '<input class="zbs_gt" type="hidden" name="invoice_tax_total" id="invoice_tax_total" value="';
                                if(!empty($zbsInvoiceTotalsArr["invoice_taxtotal"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ $totalsTable .= "0.00"; }
                            $totalsTable .= '"/>';
                        $totalsTable .= '</tr>';
                        } 
                         $totalsTable .= '<tr class="zbs_grand_total" style="line-height:30px;">';
                            $totalsTable .= '<td style="width:50%"></td>';
                            $totalsTable .= '<td class="bord bord-l" colspan="3" style="text-align:right;background:#f5f5f5;font-weight:800">'.__w("Total","zerobscrm").'</td>';
                            $totalsTable .= '<td class="bord row-amount" colspan="2" style="text-align:right;background:#f5f5f5;font-weight:800">'.$currencyChar;
                                if(!empty($zbsInvoiceTotalsArr["invoice_grandt_value"])){ $totalsTable .= $zbsInvoiceTotalsArr["invoice_grandt_value"]; }else{ $totalsTable .= "0.00"; } 
                            $totalsTable .= '</td>';
                        $totalsTable .= '</tr>';

                    $totalsTable .= '</table>';



                    $partialsTable = '';

                        if($zbsInvoiceTotalsArr["invoice_grandt_value"] == 0){
                           $partialsTable .= '<table id="partials" class="hide">';
                        }else{
                            $partialsTable .= '<table id="partials">';
                        }
                        global $wpdb;
                                                $zbs_partials_query = $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'zbs_invoice_partials' AND meta_value = '%d'", $invoicePostID);
                        $zbs_partials = $wpdb->get_results($zbs_partials_query);

                        $balance = $zbsInvoiceTotalsArr["invoice_grandt_value"];

                        if($zbs_partials){
                            $subtotalhide = '';
                            foreach($zbs_partials as $zbs_partial){ 
                                $trans_meta = get_post_meta($zbs_partial->post_id,'zbs_transaction_meta',true);
                                $balance = $balance - $trans_meta['total'];
                                

                                $partialsTable .= '<tr class="total-top '.$subtotalhide.'">';
                                    $partialsTable .= '<td style="width:50%"></td>';
                                    $partialsTable .= '<td colspan="3" class="bord bord-l">'.__w("Payment (transaction ID:","zerobscrm").$trans_meta['orderid'].')</td>';
                                    $partialsTable .= '<td class="bord row-amount"><span class="zbs-partial-value">'.$currencyChar;
                                        if(!empty($trans_meta['total'])){ $partialsTable .=  number_format($trans_meta['total'],2); }else{ $partialsTable .=  "0.00"; }
                                    $partialsTable .= '</span></td>';
                                $partialsTable .= '</tr>';
                        
                            } 
                        }

                        if($balance == $zbsInvoiceTotalsArr["invoice_grandt_value"]){
                            $balance_hide = 'hide';
                        }else{
                            $balance_hide = '';
                        }

                        $partialsTable .= '<tr class="zbs_grand_total'.$balance_hide.'">';
                            $partialsTable .= '<td style="width:50%"></td>';
                            $partialsTable .= '<td colspan="3" class="bord bord-l">'.__w("Amount due","zerobscrm").'</td>';
                            $partialsTable .= '<td class="bord row-amount"><span class="zbs-subtotal-value">'.$currencyChar.number_format($balance,2).'</span></td>';
                        $partialsTable .= '</tr>';
                        $partialsTable .= '</table>';

                                                                                                                            
                $potentialPayButton = '';
                if ($zbs_stat != 'Paid') {
                    if(function_exists('zeroBSCRM_paypalbutton')){
                        $potentialPayButton = '<h2>Pay Online</h2>';
                        $stripe_or_paypal = zeroBSCRM_getSetting('invpro_pay');
                        if($stripe_or_paypal == '2'){
                            $potentialPayButton .= apply_filters('invoicing_pro_paypal_button', $invoicePostID);
                        }else if($stripe_or_paypal == '1'){
                            $potentialPayButton .= apply_filters('invoicing_pro_stripe_button', $invoicePostID);
                        }else if($stripe_or_paypal =='3'){
                            $potentialPayButton .= apply_filters('invoicing_pro_worldpay_button', $invoicePostID);
                        }
                    }else{
                        $potentialPayButton = '';
                    }
                }

                if ($zbs_stat == 'Paid') {
                    $payThanks = '<div class="deets"><h2>' . __w("Thank You", "zerobscrm") . '</h2>';
                        $payThanks .= '<div>'. nl2br(zeroBSCRM_getSetting('paythanks')) . '</div>';
                    $payThanks .= '</div>';
                }else{
                    $payThanks = '';
                }
                $payDeets = '';        
                    $payDeets = zeroBSCRM_getSetting('paymentinfo');
                    $payDetails = '<div class="deets"><h2>' . __w("Payment Details", "zerobscrm") . '</h2>';
                        $payDetails .= '<div>'.nl2br($payDeets).'</div>';
                        $payDetails .= '<div>' . __w('Payment Reference: INV', 'zerobscrm') . $invoicePostID . '</div>';
                    $payDetails .= '</div>';
                        $cssURL = ZEROBSCRM_URL . 'css/ZeroBSCRM.admin.invoicepreview.min.css';

                        $html = str_replace('###CSS###',$cssURL,$templatedHTML);
            $html = str_replace('###LOGOCLASS###',$logoClass,$html);  
            $html = str_replace('###LOGOURL###',$logoURL,$html);  
            $html = str_replace('###INVNOSTR###',$invNoStr,$html);  
            $html = str_replace('###INVDATESTR###',$invDateStr,$html);  
            $html = str_replace('###REF###',$ref,$html);  
            $html = str_replace('###DUEDATE###',$dueDateStr,$html);  
            $html = str_replace('###BIZCLASS###',$bizInfoClass,$html);  
            $html = str_replace('###BIZINFOTABLE###',$bizInfoTable,$html);  
            $html = str_replace('###TABLEHEADERS###',$tableHeaders,$html);  
            $html = str_replace('###LINEITEMS###',$lineItems,$html);  
            $html = str_replace('###TOTALSTABLE###',$totalsTable,$html);    
            $html = str_replace('###PARTIALSTABLE###',$partialsTable,$html);    
            $html = str_replace('###TOPSTATUS###', $topStatus, $html);
            $html = str_replace('###CUSTINFOTABLE###', $custInfoTable, $html);
            $html = str_replace('###PAYPALBUTTON###', $potentialPayButton, $html);
            $html = str_replace('###PAYDETAILS###', $payDetails, $html);
            $html = str_replace('###PAYTHANKS###', $payThanks, $html);
            

                        if (!$return) { echo $html; exit(); }

        }

        return $html;

    } 

        return false;


}



function zbs_invoice_pdf(){

		if ( isset($_POST['zbs_invoicing_download_pdf'])  ) {

                if (!zeroBSCRM_permsInvoices()) exit();

        global $plugin_page;

                zeroBSCRM_extension_checkinstall_pdfinv();

		#} require DOMPDF
				require_once(ZEROBSCRM_PATH . 'includes/dompdf/autoload.inc.php');


				
				

		
				$invoicePostID = -1;
		if (isset($_POST['zbs_invoice_id']) && !empty($_POST['zbs_invoice_id'])) $invoicePostID = (int)$_POST['zbs_invoice_id'];

				if (!zeroBSCRM_permsInvoices() || empty($invoicePostID)){
			die();
		}

		

		$html = zbs_invoice_html($invoicePostID);

		$options = new Dompdf\Options();
		$options->set('isRemoteEnabled', TRUE);
		$dompdf = new Dompdf\Dompdf($options);
		$contxt = stream_context_create([ 
		    'ssl' => [ 
		        'verify_peer' => FALSE, 
		        'verify_peer_name' => FALSE,
		        'allow_self_signed'=> TRUE
		    ] 
		]);
		$dompdf->setHttpContext($contxt);

		$dompdf->loadHtml($html);

		$dompdf->render();

		$upload_dir = wp_upload_dir();
				
				$zbsInvoiceDir = $upload_dir['basedir'].'\/invoices\/';

		if ( ! file_exists( $zbsInvoiceDir ) ) {
		    wp_mkdir_p( $zbsInvoiceDir );
		}


		$file_to_save = $zbsInvoiceDir.$invoicePostID.'.pdf';
				file_put_contents($file_to_save, $dompdf->output());   				header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="invoice-'.$invoicePostID.'.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($file_to_save));
		header('Accept-Ranges: bytes');
		readfile($file_to_save);

				unlink($file_to_save); 

				die();
	}
}
add_action('admin_init','zbs_invoice_pdf');


function zbs_invoice_html( $invoicePostID ){


                $thisInvNo = get_post_meta($invoicePostID,'zbsid', true); 
        

    $zbs_inv_content = null;
    ob_start();
	?>
	<html lang="en-US">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width">
		<title>Invoice <?php echo $thisInvNo; ?></title>
	</head>
	<body>
	<div>

    <?php 

 		 		$zbsInvoice = get_post_meta($invoicePostID, 'zbs_customer_invoice_meta', true);
		$zbsCustomerID = get_post_meta($invoicePostID, 'zbs_customer_invoice_customer', true);

				global $zeroBSCRM_Settings, $zbsCustomerInvoiceFields, $zeroBSCRM_slugs;
		$currencyChar = zeroBSCRM_getCurrencyChr();
		$invsettings = $zeroBSCRM_Settings->getAll();
		$fields = $zbsCustomerInvoiceFields;

				if (isset($zbsInvoice) && isset($zbsInvoice['quolink']) && !empty($zbsInvoice['quolink'])) $fromQuoteID = (int)$zbsInvoice['quolink'];


				if (
				(isset($zbsInvoice) && is_array($zbsInvoice) && (!isset($zbsInvoice['status']) || empty($zbsInvoice['status']))) ||
				(!isset($zbsInvoice) || !is_array($zbsInvoice))
		) $zbsInvoice['status'] = 'Draft';   
		$invOffset = zeroBSCRM_getInvoiceOffset();
		$allowInvNoChange = zeroBSCRM_getSetting('invallowoverride');

		$b2b = zeroBSCRM_getSetting('companylevelcustomers');
		
						$zbs_inv_meta = get_post_meta($invoicePostID,'zbs_customer_invoice_meta', true); 



				if(!isset($zbs_inv_meta['status']))
			$zbs_stat = 'Draft';
		else
			$zbs_stat = $zbs_inv_meta['status'];
		
				$sbg = ''; 		if ($zbs_stat == 'Paid') $sbg = 'green';

				$zbs_logo = '';
        if(isset($zbs_inv_meta['logo'])){
            $zbs_logo = $zbs_inv_meta['logo'];
        }else{
                        if($invsettings['invoicelogourl'] != ''){
                $zbs_logo = $invsettings['invoicelogourl'];
            }else{
                $zbs_logo = '';
            }
        }

		if ($zbs_logo != ''){
		    $logo_c = 'show';
		    $logo_s = 'hide';
		    $zbs_logo = $zbs_inv_meta['logo'];
		    $zbs_biz_shift = '';
		} else {
		    $logo_c = '';
		    $logo_s = '';
		    $zbs_logo = '';
		    $zbs_biz_shift = 'biz-up';
		}
        
        ?>



            <!-- lets add a section to upload and attach a logo -->
            <div id="zbs_top_wrapper" style='font-family:"Helvetica Neue",sans-serif;'>

            	<div style="float:right;background:#ddd;margin-bottom:20px;display:inline-block;padding:5px;">
            		<span style="font-size:25px;font-weight:900;color:white;text-transform:uppercase;display:inline-block;padding:5px;"><?php echo $zbs_stat; ?></span>
            	</div>

            	<div style="float:none;clear:both"></div>

                <div id="zbs_invoice_logos" style="width:50%;float:left">
                    <div class="wh-logo-set <?php echo $logo_c; ?>">
                        <img id="wh-logo-set-img" src="<?php echo $zbs_logo; ?>" style="max-width:50%;"/>
                    </div>
                </div>


            <div style="float:none;clear:both;margin:20px;"></div>

            <?php

            $cust_meta = get_post_meta($zbsCustomerID,'zbs_customer_meta',true);

            zbs_write_log($cust_meta);

            ?>


            <div style="float:none;clear:both"></div>


                <table style="width:50%;float:right">
                    <tbody>
                    <tr class="wh-large zbs-invoice-number">
                    	<td><label for="no"><?php _we("Invoice Number:","zerobscrm"); ?></label></td> 
                        <td style="text-align:right;">
                            <?php 


                                                                                                                                

                                if (isset($allowInvNoChange) && $allowInvNoChange == '1'){ ?>
                                        <?php if (isset($thisInvNo)) echo $thisInvNo; ?>
                                <?php } else {

                                                                        echo '<div class="zbs-normal">'.$thisInvNo.'</div>';

                                } ?>   
                                
                        </td>
                    </tr>
                    <tr>
                    	<td><label for="date"><?php _we("Invoice Date", "zerobscrm"); ?>:</label></td>
                        <td style="text-align:right;"><?php if (isset($zbs_inv_meta['date'])) echo $zbs_inv_meta['date']; ?></td>
                    </tr>
                    <tr>
                        <td><label for="Reference">Reference:</label></td>
                        <td style="text-align:right;"><?php if (isset($zbs_inv_meta['ref'])) echo $zbs_inv_meta['ref']; ?></td>
                    </tr>
                    <tr>
	                    <td><label for="due">Due date:</label></td>
                        <td style="text-align:right;">
	                       <?php

	                       	                        $dueList = array(
	                            'No due date' => -1,
	                            'Due on receipt' => 0,
	                            'Due on date specified' => 1, 
	                            'Due in 10 days' => 10, 
	                            'Due in 15 days' => 15, 
	                            'Due in 30 days' => 30, 
	                            'Due in 45 days' => 45, 
	                            'Due in 60 days' => 60, 
	                            'Due in 90 days' => 90
	                            );

	                        foreach ($dueList as $k => $v){
	                            if (isset($zbs_inv_meta['due']) && $zbs_inv_meta['due'] == $v){
	                                echo $k;
	                            }
	                        }

	                       ?>
	                    </td>
                 </tr>
                </tbody>
            </table>
            
            <div class='clear' style="clear:both"></div>
            
            <div id='zbs-business-info-wrapper' style="width:50%;float:right;">
                <h4><?php _we("To", "zerobscrm"); ?></h4>
                <div class="business-info" style="float:right;margin-top:20px;">
                    <div class="div zbs-div">
                        <div>
                            <div><div><?php echo $cust_meta['fname'] . " " . $cust_meta['lname']; ?></div></div>
                            <div><div><?php echo $cust_meta['addr1']; ?></div></div>
                            <div><div><?php echo $cust_meta['addr2'] ?></div></div>
                            <div class='top-pad'><div><?php echo $cust_meta['city'];?></div></div>
                            <div><div><?php echo $cust_meta['postcode'] ?></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id='zbs-person-info-wrapper' style="width:50%;float:left;">
            <h4><?php _we("From", "zerobscrm"); ?></h4>
                <?php 
                                        $zbs_biz_name =  zeroBSCRM_getSetting('businessname');
                    $zbs_biz_yourname =  zeroBSCRM_getSetting('businessyourname');

                    $zbs_biz_extra =  zeroBSCRM_getSetting('businessextra');

                    $zbs_biz_youremail =  zeroBSCRM_getSetting('businessyouremail');
                    $zbs_biz_yoururl =  zeroBSCRM_getSetting('businessyoururl');
                    $zbs_settings_slug = admin_url("admin.php?page=" . $zeroBSCRM_slugs['settings']) . "&tab=invbuilder";
                ?>
                <div class="business-info" style="float:left;margin-top:20px;">
                    <div class="div zbs-div">
                        <div>
                            <div><div><?php echo $zbs_biz_name; ?></div></div>
                            <div><div><?php echo $zbs_biz_yourname; ?></div></div>
                            <div><div><?php echo $zbs_biz_extra; ?></div></div>
                            <div class='top-pad'><div><?php echo $zbs_biz_youremail; ?></div></div>
                            <div><div><?php echo $zbs_biz_yoururl; ?></div></div>
                        </div>
                    </div>
      	        </div>
            </div>

            <div class='clear' style="clear:both;margin-top:20px;margin-bottom:20px;"></div>

            <?php
            echo '<input type="hidden" name="inv-ajax-nonce" id="inv-ajax-nonce" value="' . wp_create_nonce( 'inv-ajax-nonce' ) . '" />';
            ?>


            <div class="clear" style="height:30px"></div>

            <div class="form-div" id="wptbpMetaBoxMainItemInv"></div>

                <?php

                                ?>
                <div class="form-div wh-metatab wptbp" style="width:100%">


                <!--
                <div class="wh-large"><div><label for="zbsci_fromquote">From Quote ID:</label></div>
                    <div>
                        <input type="text" name="zbsci_fromquote" id="zbsci_fromquote" class="form-control widetext" placeholder="e.g. 123" value="<?php if (isset($fromQuoteID)) echo $fromQuoteID; ?>" />
                    </td>
                </div>
                -->
            
        </div>

        <?php
            $zbsInvoiceHorQ = get_post_meta($invoicePostID, 'zbsInvoiceHorQ', true);
        ?>

        <table style='width:100%'>
        	<thead style='background:#eee;font-weight:900;padding:10px;margin-top:50px'>
                <th style='text-align:left;'>Description</th>
                <?php 
                if($zbsInvoiceHorQ == 'quantity'){ ?>
                    <th id="zbs_inv_qoh"><?php _we("Quantity","zerobscrm"); ?></th>
                    <th id="zbs_inv_por"><?php _we("Price","zerobscrm"); ?></th>
                <?php }else{ ?>
                    <th id="zbs_inv_qoh"><?php _we("Hours","zerobscrm"); ?></th>
                    <th id="zbs_inv_por"><?php _we("Rate","zerobscrm"); ?></th>
                <?php } ?>
                <th><?php _we("Amount","zerobscrm"); ?></th>
            </thead>
            
            <tbody class='zbs-div-block'>
                    <?php
                    $invlines = get_post_meta($invoicePostID,'zbs_invoice_lineitems',true);
                                       if($invlines != ''){
                        $i=1;
                        foreach($invlines as $invline){
                            if($i == 1){
                                $zbs_extra_li  = '<td class="row-1-pad" colspan="2"></td>';
                            }
                            else{
                                $zbs_extra_li = "";
                            }
                            echo 
                            '<tr class="zbs-item-block" data-divid="'.$i.'" id="tblock'.$i.'">
                                    <tr class="top-row">
                                        <td style="width:70%">'.$invline['zbsli_itemname'].'</td>
                                        <td style="width:7.5%;text-align:center;" rowspan="3" class="cen">'.$invline['zbsli_quan'].'</td>
                                        <td  style="width:7.5%;text-align:center;" rowspan="3"class="cen">'. $currencyChar.$invline['zbsli_price'].'</td>
                                        <td style="width:7.5%;text-align:right;" rowspan="3" class="row-amount">' . $currencyChar. $invline['zbsli_rowt'].'</td>
                                    </tr>
                                    <tr class="bottom-row">
                                        <td colspan="4" style="font-size:12px;font-weight:100;font-style:italic;">'.$invline['zbsli_des'].'</td>     
                                    </tr>
                            </tr>';                            
                            $i++;
                        }
                    } ?>
                </tbody>
       			</table>
                <br/>


            </div>
        </div>
        </div>
        </div>

        <?php
            $zbsInvoiceTotalsArr = get_post_meta($invoicePostID,"zbs_invoice_totals",true);
                  ?>




 <div class='clear' style="clear:both;margin-top:20px;margin-bottom:20px;float:none;"></div>



        <table id="invoice_totals" style="position:fixed;bottom:200px;">
        	<tbody>
            	<?php if($invsettings['invtax'] != 0 && $invsettings['invpandp'] != 0 && $invsettings['invdis'] != 0 ){ ?>
                <tr class='total-top'>
                    <td></td>
                    <td colspan="3" class='bord bord-l' style="text-align:right"><?php _we("Subtotal","zerobscrm"); ?></td>
                    <td class='bord row-amount' class='bord' style="text-align:right"><span class='zbs-subtotal-value'><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["zbs-subtotal-value"])){ echo $zbsInvoiceTotalsArr["zbs-subtotal-value"]; }else{ echo "0.00"; } ?></span></td>
                </tr>
                <?php } ?>
                <?php if(isset($zbsInvoiceTotalsArr["invoice_discount_total_value"]) && !empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])) {
                                                        ?>
	            <?php if($invsettings['invdis'] == 1){ ?>
	                <tr class='discount'>
	                    <td></td>
	                    <td class='bord bord-l' colspan="3" style="text-align:right"><?php _we("Discount","zerobscrm"); ?></td>
	                    <td class='bord row-amount' id="zbs_discount_combi" style="text-align:right"><?php if(!empty($zbsInvoiceTotalsArr["invoice_discount_total_value"])){ echo "-" . $currencyChar . $zbsInvoiceTotalsArr["invoice_discount_total_value"]; }else{ echo $currencyChar . "0.00"; } ?></td>
	  			    </tr>
	             <?php } ?>
                <?php } ?>
                <?php if($invsettings['invpandp'] == 1){ ?>
                <tr class='postage_and_pack'>
                    <td></td>
                    <td class='bord bord-l' colspan="3" style="text-align:right"><?php _we("Postage and packaging","zerobscrm"); ?></td>
                    <td class='bord row-amount' id="pandptotal" rowspan="1" style="text-align:right"><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_postage_total"])){ echo number_format($zbsInvoiceTotalsArr["invoice_postage_total"],2); }else{ echo "0.00"; } ?></td>
                </tr>
                <?php } ?>
                <?php if($invsettings['invtax'] == 1){

                if(isset($zbsInvoiceTotalsArr["invoice_tax_total"])){
                    $ttclass = 'class="tax_total_1"';
                }else{
                    $ttclass = 'class="tax_total"';
                } ?>
                <tr <?php echo $ttclass; ?>>
                    <td></td>
                    <td colspan="3" class='bord bord-l' style="text-align:right"><?php _we("Tax","zerobscrm"); ?></td>
                    <td class='bord row-amount zbs-tax-total-span' style="text-align:right"><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_tax_total"])){ echo $zbsInvoiceTotalsArr["invoice_tax_total"]; }else{ echo "0.00"; } ?></td>
                </tr>
                <?php } ?>
                 <tr class="zbs_grand_total" style="line-height:30px;">
                    <td></td>
                    <td class='bord bord-l' colspan="3" style="text-align:right;background:#f5f5f5;font-weight:800"><?php _we("Total","zerobscrm"); ?></td>
                    <td class='bord row-amount' colspan="2" style="text-align:right;background:#f5f5f5;font-weight:800"><?php echo $currencyChar; ?><?php if(!empty($zbsInvoiceTotalsArr["invoice_grandt_value"])){ echo $zbsInvoiceTotalsArr["invoice_grandt_value"]; }else{ echo "0.00"; } ?></td>
                </tr>

                   <tr>
                    <td>
                        <?php

                                    $payDeets = '';        
                                        $payDeets = zeroBSCRM_getSetting('paymentinfo');
                                        $payDetails = '<div class="deets"><h2>' . __w("Payment Details", "zerobscrm") . '</h2>';
                                            $payDetails .= '<div>'.nl2br($payDeets).'</div>';
                                            $payDetails .= '<div>' . __w('Payment Reference: INV', 'zerobscrm') . $invoicePostID . '</div>';
                                        $payDetails .= '</div>';

                                    echo $payDeets;

                        ?>

                    </td>
                    </tr>

            </tbody>
        </table>

        <?php
                    if($zbsInvoiceTotalsArr["invoice_grandt_value"] == 0){
                       echo '<table id="partials" class="hide">';
                    }else{
                        echo '<table id="partials">';
                    }
                    global $wpdb;
                                        $zbs_partials_query = $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'zbs_invoice_partials' AND meta_value = '%d'", $invoicePostID);
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


                    <tr class='zbs_grand_total<?php echo $balance_hide; ?>' style="display:none;">
                        <td style="width:50%"></td>
                        <td colspan="3" class='bord bord-l'><?php _we("Amount due","zerobscrm"); ?></td>
                        <td class='bord row-amount' class='bord'><span class='zbs-subtotal-value'><?php echo $currencyChar; ?><?php  echo number_format($balance,2);  ?></span></td>
                    </tr>
 
                    </table>



         
    </div> <!-- end container -->
    </body>
    </html>
    <?php
    $zbs_inv_content = ob_get_contents();
    ob_end_clean();
    return $zbs_inv_content;
}




add_action( 'wp_ajax_zbs_invoice_send_invoice', 'zbs_invoice_send_invoice' );
function zbs_invoice_send_invoice(){
    check_ajax_referer( 'inv-ajax-nonce', 'security' );
	$zbs_invID = -1; $em = ''; $r = array();
	if (isset($_POST['id']) && !empty($_POST['id'])) $zbs_invID = (int)$_POST['id'];  	if (isset($_POST['em']) && !empty($_POST['em'])) $em = sanitize_text_field($_POST['em']);

        
		if (!zeroBSCRM_validateEmail($em)){
		$r['message'] = 'Not a valid email';
		echo json_encode($r);
		die();
	} else $email = $em;

		if ($zbs_invID == -1 || empty($em) || !zeroBSCRM_permsInvoices()){
		die();
	}

    
        $body = zeroBSCRM_invoice_generateInvoiceHTML($zbs_invID,true);



	$biz_name = zeroBSCRM_getSetting('businessname');
	$biz_extra = zeroBSCRM_getSetting('businessextra');

	$subject = 'You received an invoice (' . $zbs_invID . ') from ' . $biz_name;
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$attachments = array();
		$zbsCustomerInvoices = get_post_meta($zbs_invID, 'zbs_customer_invoices', true);
    if($zbsCustomerInvoices){
        foreach($zbsCustomerInvoices as $invoice){
        	$attachments[] = $invoice['file'];
        }
    }
	

	wp_mail( $email, $subject, $body, $headers, $attachments );

		$zbs_inv_meta = get_post_meta($zbs_invID,'zbs_customer_invoice_meta', true); 



	$zbs_inv_meta['status'] = 'Unpaid';
	update_post_meta($zbs_invID,'zbs_customer_invoice_meta', $zbs_inv_meta);

		$r['message'] = 'All done OK';
	echo json_encode($r);
	die(); }



add_action( 'wp_ajax_zbs_invoice_mark_paid', 'zbs_invoice_mark_paid' );
function zbs_invoice_mark_paid(){

		$zbs_invID = -1;
	if (isset($_POST['id']) && !empty($_POST['id'])) $zbs_invID = (int)$_POST['id'];  
		if ($zbs_invID < 1 || !zeroBSCRM_permsInvoices()){

		die();

	} else {

		
				$zbs_inv_meta = get_post_meta($zbs_invID,'zbs_customer_invoice_meta', true); 
		$zbs_inv_meta['status'] = 'Paid';
		update_post_meta($zbs_invID,'zbs_customer_invoice_meta', $zbs_inv_meta);
		
				$r['message'] = 'All done OK';
		echo json_encode($r);

	}
	
	die(); }

add_action( 'wp_ajax_zbs_invoice_send_test_invoice', 'zbs_invoice_send_test_invoice' );
function zbs_invoice_send_test_invoice(){

    check_ajax_referer( 'inv-ajax-nonce', 'security' );
    
	$zbs_invID = -1; $em = ''; $r = array();
	if (isset($_POST['id']) && !empty($_POST['id'])) $zbs_invID = (int)$_POST['id'];  	if (isset($_POST['em']) && !empty($_POST['em'])) $em = sanitize_text_field($_POST['em']);

        $r['em'] = $em;
    
		if (!zeroBSCRM_validateEmail($em)){
		$r['message'] = 'Not a valid email';
		echo json_encode($r);
		die();
	} else $email = $em;

		if ($zbs_invID == -1 || empty($em) || !zeroBSCRM_permsInvoices()){
		die();
	}


    
        $body = zeroBSCRM_invoice_generateInvoiceHTML($zbs_invID,true);

	$biz_name = zeroBSCRM_getSetting('businessname');
	$biz_extra = zeroBSCRM_getSetting('businessextra');

	$subject = '[Test Email] You received an invoice (' . $zbs_invID . ') from ' . $biz_name;
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$attachments = array();
		$zbsCustomerInvoices = get_post_meta($zbs_invID, 'zbs_customer_invoices', true);
    foreach($zbsCustomerInvoices as $quote){
    	$attachments[] = $quote['file'];
    }
	

	wp_mail( $email, $subject, $body, $headers, $attachments );


		$r['message'] = 'All done OK';
	echo json_encode($r);
	die(); }




add_filter( 'wp_mail_from', 'zbs_wp_mail_from' );
function zbs_wp_mail_from( $original_email_address ) {
	$f = zeroBSCRM_getSetting('invfromemail');
	if($f == ''){
	return $original_email_address;
	}else{
	return $f;    
	}
}

add_filter( 'wp_mail_from_name', 'zbs_wp_mail_from_name' );
function zbs_wp_mail_from_name( $original_email_from ) {
  	$n = zeroBSCRM_getSetting('invfromname');
  	if($n == ''){
  		return $original_email_from;
  	}else{
  		return $n;
	}
}
 









		define('ZBSCRM_INC_INVBUILDER',true);