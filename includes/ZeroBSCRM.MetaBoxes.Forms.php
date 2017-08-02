<?php /* !
 * Zero BS CRM
 * http://www.epicplugins.com
 * V1.1.17
 *
 * Copyright 2016, Epic Plugins, StormGate Ltd.
 *
 * Date: 13/09/16
 */



    if ( ! defined( 'ZEROBSCRM_PATH' ) ) exit;








    class zeroBS__MetaboxFormFields {

        
        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_form';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbs_formfields',
                'Form Language Labels',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) { ?>
            <?php 
                

                $zbsForm = get_post_meta($post->ID, 'zbs_form_field_meta', true);

                global $zbsFormFields;
                $fields = $zbsFormFields;
                ?>

                    <table class="form-table wh-metatab wptbp">
                    <?php
                    foreach ($fields as $fieldK => $fieldV){

                                               switch ($fieldV[0]){

                            case 'text':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <input type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control widetext" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsForm[$fieldK])) echo $zbsForm[$fieldK]; ?>" />
                                </td></tr><?php

                                break;


                            case 'textarea':

                                ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo zeroBSCRM_localiseFieldLabel($fieldV[1]); ?>:</label></th>
                                <td>
                                    <textarea name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>"><?php if (isset($zbsForm[$fieldK])) echo zeroBSCRM_textExpose($zbsForm[$fieldK]); ?></textarea>
                                </td></tr><?php

                                break;


                        }



                    }


                    ?>

                    <!--
                    <tr class="wh-large"><th><label for="zbsci_fromquote">From Quote ID:</label></th>
                        <td>
                            <input type="text" name="zbsci_fromquote" id="zbsci_fromquote" class="form-control widetext" placeholder="e.g. 123" value="<?php if (isset($fromQuoteID)) echo $fromQuoteID; ?>" />
                        </td>
                    </tr>
                    -->
                
            </table>


            <div class="clear"></div>

                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>
                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />


            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbs_formfields'  && $post->post_type == $this->postType){

                    global $zbsFormFields;
                    foreach ($zbsFormFields as $fK => $fV){
                        $zbsFormFieldMeta[$fK] = '';
                        if (isset($_POST['zbscq_'.$fK])) {
                            switch ($fV[0]){
                                case 'text':
                                    $zbsFormFieldMeta[$fK] = zeroBSCRM_textProcess($_POST['zbscq_'.$fK]);
                                    break;
                                case 'textarea':
                                    $zbsFormFieldMeta[$fK] = zeroBSCRM_textProcess($_POST['zbscq_'.$fK]);
                                    break;
                                default:
                                    $zbsFormFieldMeta[$fK] = sanitize_text_field($_POST['zbscq_'.$fK]);
                                    break;
                            }
                        }
                    }
                    update_post_meta($post_id, 'zbs_form_field_meta', $zbsFormFieldMeta);
                }
            }

            return $post;
        }
    }

    $zeroBS__MetaboxFormFields = new zeroBS__MetaboxFormFields( __FILE__ );


    class zeroBS__MetaboxForm {

        
        static $instance;
                        private $postType;

        public function __construct( $plugin_file ) {
                                                   self::$instance = $this;
            
            $this->postType = 'zerobs_form';
            
            add_action( 'add_meta_boxes', array( $this, 'create_meta_box' ) );
            add_filter( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
        }

        public function create_meta_box() {

            
            add_meta_box(
                'wpzbs_formsettings',
                'Form Settings',
                array( $this, 'print_meta_box' ),
                $this->postType,
                'normal',
                'high'
            );
        }

        public function print_meta_box( $post, $metabox ) { ?>
            <?php 
                         $formcss = ZEROBSCRM_URL . 'css/ZeroBSCRM.admin.frontform.css';
             $formjs = ZEROBSCRM_URL . 'js/ZeroBSCRM.leadform.js?ver=1.17';
             $formtemp = ZEROBSCRM_WILDURL . 'form-templates/';
             $zbsfs = get_post_meta($post->ID,'zbs_form_style',true);
            ?>
            <script>
                jQuery(document).ready(function(){
                    jQuery('#wpzbs_formsettings').after('<div id="form-embed"><h1 class="welcomeh1">Embed Code</h1><h3 class="welcomeh3">Use the code below to embed this form on another site.</h3><pre id="zbs-form-pre"></pre></div>');
                    
                    zbsembed = jQuery('.embed-selected').html();
                    jQuery('#zbs-form-pre').html(zbsembed);

                    //can move to a seperate script at some point....
                    jQuery('.choice').unbind("click").bind("click",function(e){
                        jQuery('#zbs-form-pre').html(''); //clear out the HTML
                        var zbsf_pid = jQuery(this).data('pid');
                        var zbsf_style = jQuery(this).data('style');

                        jQuery('#zbs_form_style_post').val(zbsf_style);                    
                        if(jQuery('#'+zbsf_style+'_html_form .form-wrapper').hasClass('zbs-form-wrap')){
                           console.log('we have a form inside the wrapper');
                        }else{
                            var zbsf_action = jQuery('#zbs_form_action').data('zbsformaction');
                            jQuery('#'+zbsf_style+'_html_form .form-wrapper').wrap("<form action='#' class='zbs-form-wrap' method='post'></form>");
                            jQuery('#'+zbsf_style+'_html_form .form-wrapper').addClass('zbs-form-wrap');
                        }

                        var zbsf_html = jQuery('#'+zbsf_style+'_html_form .zbs_form_content_wrap').html();  //replace with proper HTML form elements
                        var zbsf_html_css_link = jQuery('#zbs_form_css').data('css');
                        var zbsf_css_link = "<link rel='stylesheet' href='"+zbsf_html_css_link +"' type='text/css' media='all' />";

                        var zbsf_html_js_link = jQuery('#zbs_form_js').data('js');
                        console.log(zbsf_html_js_link);
                        var zbsf_js_link = "<script type='text/javascript' src='>";
                        var zbsf_js_link = jQuery('.ZBSencodedJS').html();
                        console.log(zbsf_html_css_link);
                        var zbsf_html_encoded =  zbsf_html;

                        jQuery('.choice').removeClass('selected');
                        jQuery(this).addClass('selected');
                        jQuery('.zbs_shortcode_message').show();
                        jQuery('.shorty').html('[zbs_form id="'+zbsf_pid+'" style="'+zbsf_style+'"]').show();

                        jQuery('#zbs-form-pre').html(zbsf_html_encoded);
                    });

                });

            function zbs_htmlEncode(value){
              //create a in-memory div, set it's inner text(which jQuery automatically encodes)
              //then grab the encoded contents back out.  The div never exists on the page.
              return jQuery('<div/>').text(value).html();
            }


            </script>

            <?php
            $zbsForm = get_post_meta($post->ID, 'zbs_form_field_meta', true);
            ?>


            <div class='ZBSencodedJS hide'>&lt;script type='text/javascript' src='<?php echo $formjs; ?>'&gt;&lt;/script&gt;
            </div>
            <?php $zbsfs = get_post_meta($post->ID,'zbs_form_style',true); ?>
            <input type="hidden" name="zbs_form_style_post" id="zbs_form_style_post" value="<?php echo $zbsfs; ?>" />

            <h1 class="welcomeh1">Welcome to Zero BS CRM Form Creator</h1>
            <h3 class="welcomeh3">Choose your style for the form you wish to embed (click to choose)</h3>
            <p class="zbs_msg">Make sure to save the form before using the shortcode.</p>
            <div class="zbs_shortcode_message">
            <p>You can embed this form on <b>this website</b> using the shortcode below (choose your style first). To embed the form on a seperate website use the embed code in the "Embed Code" box below.</p>
            <p class="shorty">[zbs_form id="<?php echo $post->ID; ?>" style="<?php  echo $zbsfs; ?>"]</p>
            </div>

            <div id="form-chooser">
                <!-- 3 styles for now - naked, simple and content grab -->
                <div class="third" id="naked-form">
                    <div class="naked choice <?php if($zbsfs == 'naked'){ echo 'selected';} ?>" data-pid="<?php echo $post->ID; ?>" data-style="naked">
                        <div class="blobby" style="margin-bottom:13px;">
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                        </div>
                        <div class="content">
                             <div class="form-wrapper">
                                <div class="input"><?php if(!empty($zbsForm['fname'])){ echo $zbsForm['fname']; }else{ echo "First Name"; } ?></div><div class="input"><?php if(!empty($zbsForm['email'])){ echo $zbsForm['email']; }else{ echo "Email"; } ?></div><div class="send"><?php if(!empty($zbsForm['submit'])){ echo $zbsForm['submit']; }else{ echo "Submit"; } ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="blobby">
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text Lorem Ipsum m Ipsum <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                        </div>
                    </div>
                    <div class="caption">Naked Style</div>
                                    <div id="naked_html_form" class="hide">
                        <div id="zbs_form_css" data-css="<?php echo $formcss; ?>"></div>
                        <div id="zbs_form_js" data-js="<?php echo $formjs; ?>"></div>
                        <div id="zbs_form_action" data-zbsformaction="<?php echo esc_url( admin_url('admin-post.php') ); ?>"></div>
                        
    <div class='zbs_form_content_wrap <?php if($zbsfs == 'naked'){ echo 'embed-selected';} ?>'>&lt;iframe src='<?php echo $formtemp; ?>naked.php?fid=<?php echo $post->ID; ?>' height='200px' width='700px' style='border:0px!important'&gt;&lt;/iframe&gt;
    </div> <!-- end form content grab -->
                    </div>
                </div>

                <div class="third" id="cgrab-form">
                    <div class="cgrab choice <?php if($zbsfs == 'cgrab'){ echo 'selected';} ?>" data-pid="<?php echo $post->ID; ?>" data-style="cgrab">
                        <div class="blobby">
                            <p>Lorem Ipsum Text here</p>
                        </div>
                        <div class="content">
                            <h1><?php if(!empty($zbsForm['header'])){ echo $zbsForm['header']; }else{ echo "Want to find out more?"; } ?></h1>
                            <h3><?php if(!empty($zbsForm['subheader'])){ echo $zbsForm['subheader']; }else{ echo "Drop us a line. We follow up on all contacts"; } ?></h3>
                            <div class="form-wrapper">
                                <div class="input"><?php if(!empty($zbsForm['fname'])){ echo $zbsForm['fname']; }else{ echo "First Name"; } ?></div>
                                <div class="input"><?php if(!empty($zbsForm['lname'])){ echo $zbsForm['lname']; }else{ echo "Last Name"; } ?></div>
                                <div class="input"><?php if(!empty($zbsForm['email'])){ echo $zbsForm['email']; }else{ echo "Email"; } ?></div>
                                <div class="textarea"><?php if(!empty($zbsForm['notes'])){ echo $zbsForm['notes']; }else{ echo "Your Message"; } ?></div>
                                <div class="send"><?php if(!empty($zbsForm['submit'])){ echo $zbsForm['submit']; }else{ echo "Submit"; } ?></div>
                            </div>
                            <div class="clear"></div>
                            <div class="trailer"><?php if(!empty($zbsForm['spam'])){ echo $zbsForm['spam']; }else{ echo "We will not send you spam. Our team will be in touch within 24 to 48 hours Mon-Fri (but often much quicker)"; } ?></div>
                        </div>
                        <div class="clear"></div>
                        <div class="blobby">
                            <p>Lorem Ipsum <span class="br">s</span> Text Lorem Ipsum m Ipsum <span class="br">s</span> here</p>
                        </div>
                    </div>
                    <div class="caption">Content Grab</div>
                    <div id="cgrab_html_form" class="hide">
                        <div id="zbs_form_css" data-css="<?php echo $formcss; ?>"></div>
                        <div id="zbs_form_js" data-js="<?php echo $formjs; ?>"></div>
                        <div id="zbs_form_action" data-zbsformaction=""></div>
                        
    <div class='zbs_form_content_wrap <?php if($zbsfs == 'cgrab'){ echo 'embed-selected';} ?>'>&lt;iframe src='<?php echo $formtemp; ?>content.php?fid=<?php echo $post->ID; ?>' height='700px' width='700px' style='border:0px!important'&gt;&lt;/iframe&gt;
    </div> <!-- end form content grab -->
                    </div>


                </div>


                <div class="third" id="simple-form">
                    <div class="simple choice <?php if($zbsfs == 'simple'){ echo 'selected';} ?>" data-pid="<?php echo $post->ID; ?>" data-style="simple">
                        <div class="blobby">
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                        </div>
                        <div class="content">
                            <h1><?php if(!empty($zbsForm['header'])){ echo $zbsForm['header']; }else{ echo "Want to find out more?"; } ?></h1>
                            <h3><?php if(!empty($zbsForm['subheader'])){ echo $zbsForm['subheader']; }else{ echo "Drop us a line. We follow up on all contacts"; } ?></h3>
                            <div class="form-wrapper">
                                <div class="input"><?php if(!empty($zbsForm['email'])){ echo $zbsForm['email']; }else{ echo "Email"; } ?></div><div class="send"><?php if(!empty($zbsForm['submit'])){ echo $zbsForm['submit']; }else{ echo "Submit"; } ?></div>
                            </div>
                            <div class="clear"></div>
                            <div class="trailer"><?php if(!empty($zbsForm['spam'])){ echo $zbsForm['spam']; }else{ echo "We will not send you spam. Our team will be in touch within 24 to 48 hours Mon-Fri (but often much quicker)"; } ?></div>
                        </div>
                        <div class="clear"></div>
                        <div class="blobby">
                            <p>Lorem Ipsum Text here</p>
                            <p>Lorem Ipsum <span class="br">s</span> Text Lorem Ipsum m Ipsum <span class="br">s</span> here</p>
                            <p>Lorem Ipsum Text here</p>
                        </div>
                    </div>
                    <div class="caption">Simple Style</div>
                    <div id="simple_html_form" class="hide">
                        <div id="zbs_form_css" data-css="<?php echo $formcss; ?>"></div>
                        <div id="zbs_form_js" data-js="<?php echo $formjs; ?>"></div>
                        <div id="zbs_form_action" data-zbsformaction="<?php echo esc_url( admin_url('admin-post.php') ); ?>"></div>
                        
    <div class='zbs_form_content_wrap <?php if($zbsfs == 'simple'){ echo 'embed-selected';} ?>'>&lt;iframe src='<?php echo $formtemp; ?>simple.php?fid=<?php echo $post->ID; ?>' height='300px' width='700px' style='border:0px!important'&gt;&lt;/iframe&gt;
    </div> <!-- end form content grab -->
                    </div>
                </div>
      



            </div>


            <div class="clear"></div>

                <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />
                <?php wp_nonce_field( 'save_' . $metabox['id'], $metabox['id'] . '_nonce' ); ?>


                        <input type="hidden" name="meta_box_ids[]" value="<?php echo $metabox['id']; ?>" />


            <?php
        }

        public function save_meta_box( $post_id, $post ) {
            if( empty( $_POST['meta_box_ids'] ) ){ return; }
            foreach( $_POST['meta_box_ids'] as $metabox_id ){
                if( !isset($_POST[ $metabox_id . '_nonce' ]) || ! wp_verify_nonce( $_POST[ $metabox_id . '_nonce' ], 'save_' . $metabox_id ) ){ continue; }
                                if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){ continue; }

                if( $metabox_id == 'wpzbs_formsettings'  && $post->post_type == $this->postType){

                                        $zbfs = $_POST['zbs_form_style_post'];
                    update_post_meta($post->ID,'zbs_form_style', $zbfs);
                    $zbs_form_conv = get_post_meta($post->ID, 'zbs_form_conversions', true);
                    $zbs_form_views = get_post_meta($post->ID, 'zbs_form_views', true);
                    if($zbs_form_conv == ''){
                        update_post_meta($post->ID,'zbs_form_conversions',0);
                    }
                    if($zbs_form_views == ''){
                        update_post_meta($post->ID,'zbs_form_views',0);
                    }
                }
            }

            return $post;
        }
    }

    $zeroBS__MetaboxForm = new zeroBS__MetaboxForm( __FILE__ );






        define('ZBSCRM_INC_FORMSMB',true);