<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V2.2
 *
 * Copyright 2017, Epic Plugins, StormGate Ltd.
 *
 * Date: 29/06/2017
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;








    class zeroBS__OwnershipMetabox {

        static $instance;
                        private $postTypes;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            

                                                $this->postTypes = array('zerobs_customer','zerobs_company');        
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function initMetaBox(){

            if (count($this->postTypes) > 0) foreach ($this->postTypes as $pt){

                                $callBackArr = array($this,$pt);

                add_meta_box(
                    'wpzbscownership_itemdetails_'.$pt,
                    'Assigned To',
                    array( $this, 'print_meta_box' ),
                    $pt,
                    'side',
                    'low',
                    $callBackArr
                );

            }

        }
        
        public function print_meta_box( $post, $metabox ) {


                global $zeroBSCRM_Settings;
                $canGiveOwnership = $zeroBSCRM_Settings->get('usercangiveownership');
                $zbsPossibleOwners = array();
                $canChangeOwner = ($canGiveOwnership == "1" || current_user_can('administrator'));

                                $postType = ''; if (isset($metabox['args']) && isset($metabox['args'][1]) && !empty($metabox['args'][1])) $postType = $metabox['args'][1];

                                if (in_array($postType,array('zerobs_customer','zerobs_company'))){

                                        switch ($postType){

                        case "zerobs_customer":

                            $zbsThisOwner = zeroBS_getCustomerOwner($post->ID);

                                                        if ($canChangeOwner) $zbsPossibleOwners = zeroBS_getPossibleCustomerOwners();

                            break;
                        case "zerobs_company":

                            $zbsThisOwner = zeroBS_getCompanyOwner($post->ID);

                                                        if ($canChangeOwner) $zbsPossibleOwners = zeroBS_getPossibleCompanyOwners();

                            break;

                        default:
                            $zbsThisOwner = array();
                            break;

                    }

                                        if ($canChangeOwner || isset($zbsThisOwner['ID'])){


                                        
                ?>
                    <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                    <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                    
                    <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxOwnership">

                        <?php if (!$canChangeOwner) {


                            
                            ?><tr><td>
                                <?php echo esc_html( $zbsThisOwner['OBJ']->display_name ); ?>
                            </td></tr><?php 
                        } else {

                            
                            ?><tr><td>
                                <select class="" id="zerobscrm-owner" name="zerobscrm-owner">
                                    <option value="-1">None</option>
                                    <?php if (count($zbsPossibleOwners) > 0) foreach ($zbsPossibleOwners as $possOwner){

                                        ?><option value="<?php echo $possOwner->ID; ?>"<?php 
                                        if ($possOwner->ID == $zbsThisOwner['ID']) echo ' selected="selected"';
                                        ?>><?php echo esc_html( $possOwner->display_name ); ?></option><?php 
                                    } ?>
                                </select>
                            </td></tr><?php

                        } ?>


                    </table>


                <style type="text/css">
                </style>
                <script type="text/javascript">

                    jQuery(document).ready(function(){

                    });


                </script>
                
                <?php


                } else {

                    
                    ?><style type="text/css">#wpzbscownership_itemdetails_<?php echo $postType; ?> {display:none;}</style><?php

                                        ?>
                    <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                    <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                    <?php

                }

            } 
        }

       public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                foreach ($this->postTypes as $postType){

                    if( $metabox_id == 'wpzbscownership_itemdetails_'.$postType  && $post->post_type  == $postType){

                        $newOwner = -1; if (isset($_POST['zerobscrm-owner']) && !empty($_POST['zerobscrm-owner'])) $newOwner = (int)$_POST['zerobscrm-owner'];

                                                if (isset($_POST['zbscrm_newcustomer']) && $newOwner === -1){

                                $newOwner = get_current_user_id();

                        } 

                                                zeroBS_setOwner($post_id,$newOwner);

                        
                    }

                }
            }

            return $post;
        } 
    }

    $zeroBS__OwnershipMetabox = new zeroBS__OwnershipMetabox( __FILE__ );

