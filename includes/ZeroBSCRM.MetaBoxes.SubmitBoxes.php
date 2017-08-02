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





   




    class zeroBS__SubmitMetabox {

        static $instance;
                        private $postTypes;
        private $postTypesLabels;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            

                                                $this->postTypes = array('zerobs_invoice');        
                        $this->postTypesLabels = array(
                'zerobs_invoice' => 'Invoice'
            );
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

                    }

        public function initMetaBox(){

            if (count($this->postTypes) > 0) foreach ($this->postTypes as $pt){

                                $callBackArr = array($this,$pt);

                add_meta_box(
                    'wpzbscsub_itemdetails_'.$pt,
                    $this->postTypesLabels[$pt].' Actions',                     array( $this, 'print_meta_box' ),
                    $pt,
                    'side',
                    'low',
                    $callBackArr
                );

            }

        }
        
        public function print_meta_box( $post, $metabox ) {

                                $postType = ''; if (isset($metabox['args']) && isset($metabox['args'][1]) && !empty($metabox['args'][1])) $postType = $metabox['args'][1];

                                if (in_array($postType,array('zerobs_invoice'))){
                
                                        if (isset($post->post_status) && $post->post_status != "auto-draft"){
                    ?>
                        <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />

                
                                <?php
                                $zbs_inv_meta = get_post_meta($post->ID,'zbs_customer_invoice_meta', true); 
                            
                                if(!isset($zbs_inv_meta['status'])){
                                    $zbs_stat = 'Draft';
                                }else{
                                    $zbs_stat = $zbs_inv_meta['status'];
                                }

                                global $zbsCustomerInvoiceFields;

                               $sel='';
                                ?>
                                <div class="zbs-actions-side">
                                    <div class="row">
                                        <div class='pull-left zbs-what'>
                                            <?php _we('Status','zerobscrm'); ?>
                                        </div>
                                        <div class="action pull-right">
                                            <select id="invoice_status" name="invoice_status">
                                                <?php foreach($zbsCustomerInvoiceFields['status'][3] as $z){
                                                    if($z == $zbs_stat){$sel = 'selected'; }else{ $sel = '';}
                                                    echo '<option value="'.$z.'"'. $sel .'>'.$z.'</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>



                                <div class="clear"></div>
                         
                       
                            <div class='bottom zbs-invoice-actions-bottom'>
                                <button class='button button-primary button-large pull-right' id="zbs_invoice_save"><?php _we("Update","zerobscrm"); ?></button>
                                <?php

                                    
                                ?><div id="delete-action" class='pull-left'>
                                 <?php
                                 if ( current_user_can( "delete_post", $post->ID ) ) {
                                   if ( !EMPTY_TRASH_DAYS )
                                        $delete_text = __w('Delete Permanently', 'zerobscrm');
                                   else
                                        $delete_text = __w('Move to Trash', 'zerobscrm');
                                 ?>
                                 <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
                                 } ?>
                                </div>
                                <div class='clear'></div>
                            </div>

              


                    <style type="text/css">
                    </style>
                    <script type="text/javascript">

                        jQuery(document).ready(function(){

                        });


                    </script>
                    <?php


                    } else {

                        ?>

                    <button class='button button-primary button-large' id="zbs_invoice_save"><?php _we("Save","zerobscrm"); ?></button>

                     <?php

                        
                        

                }

            } 
        }

       
    }

    $zeroBS__SubmitMetabox = new zeroBS__SubmitMetabox( __FILE__ );

