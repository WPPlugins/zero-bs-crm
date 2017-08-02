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








    class zeroBS__ExternalSourceMetabox {

        static $instance;
                        private $postTypes;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            

                                                $this->postTypes = array('zerobs_customer','zerobs_company');        
            add_action( 'add_meta_boxes', array( $this, 'initMetaBox' ) );

                    }

        public function initMetaBox(){

            if (count($this->postTypes) > 0) foreach ($this->postTypes as $pt){

                                $callBackArr = array($this,$pt);

                add_meta_box(
                    'wpzbscext_itemdetails_'.$pt,
                    'External Sources',
                    array( $this, 'print_meta_box' ),
                    $pt,
                    'side',
                    'low',
                    $callBackArr
                );

            }

        }
        
        public function print_meta_box( $post, $metabox ) {

                                $postType = ''; if (isset($metabox['args']) && isset($metabox['args'][1]) && !empty($metabox['args'][1])) $postType = $metabox['args'][1];

                                if (in_array($postType,array('zerobs_customer','zerobs_company'))){


                                                            switch ($postType){

                        case "zerobs_customer":
                            $zbsThisObjExternals = zeroBS_getCustomerExternalSource($post->ID);
                            break;
                        case "zerobs_company":
                            $zbsThisObjExternals = zeroBS_getCompanyExternalSource($post->ID);
                            break;

                        default:
                            $zbsThisObjExternals = array();
                            break;

                    }

                    
                    if (isset($zbsThisObjExternals) && is_array($zbsThisObjExternals) && count($zbsThisObjExternals) > 0){
                
                ?>
                    <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />

                    <table class="form-table wh-metatab wptbp" id="wptbpMetaBoxExternalSource">
                        <tr>
                        <td>
                            <?php if (count($zbsThisObjExternals) > 0) foreach ($zbsThisObjExternals as $extKey => $extDeet){

                                                                                                echo '<div class="zbsExternalSource">';

                                    switch ($extKey){

                                        case 'pay': 
                                            echo '<i class="fa fa-paypal"></i> PayPal:<br /><span>'.$extDeet[1].'</span>';

                                            break;

                                                                                case 'env':

                                            echo '<i class="fa fa-envira"></i> Envato:<br /><span>'.$extDeet[1].'</span>';

                                            break;

                                        case 'form':

                                            echo '<i class="fa fa-wpforms"></i> Form Capture:<br /><span>'.$extDeet[1].'</span>';

                                            break;

                                        case 'csv':

                                            echo '<i class="fa fa-file-text"></i> CSV Import:<br /><span>'.$extDeet[1].'</span>';

                                            break;

                                        case 'gra':

                                            echo '<i class="fa fa-wpforms"></i> Gravity Forms:<br /><span>'.$extDeet[1].'</span>';

                                            break;
                                            
                                        case 'api':

                                            echo '<i class="fa fa-random"></i> API:<br /><span>'.$extDeet[1].'</span>';

                                            break;

                                        default:

                                                                                        echo '<i class="fa fa-users"></i> '.$extDeet[0].':<br /><span>'.$extDeet[1].'</span>';

                                            break;



                                    }

                                echo '</div>';

                            } ?>
                        </td></tr>
                    </table>


                <style type="text/css">
                </style>
                <script type="text/javascript">

                    jQuery(document).ready(function(){

                    });


                </script>
                 
                <input type="hidden" name="<?php echo $metabox['id']; ?>_fields[]" value="subtitle_text" />


                <?php


                } else {

                    
                    ?><style type="text/css">#wpzbscext_itemdetails_<?php echo $postType; ?> {display:none;}</style><?php

                }

            } 
        }

       
    }

    $zeroBS__ExternalSourceMetabox = new zeroBS__ExternalSourceMetabox( __FILE__ );

