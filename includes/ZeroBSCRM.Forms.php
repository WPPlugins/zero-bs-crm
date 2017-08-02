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






add_action( 'manage_zerobs_form_posts_custom_column' , 'zbs_form_custom_columns', 10, 2 );

function zbs_form_custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'zbs_views':
			echo get_post_meta( $post_id, 'zbs_form_views', true ); 
			break;

		case 'zbs_conversions':
			$conversions = get_post_meta( $post_id, 'zbs_form_conversions', true ); 
			if($conversions == ''){
				echo 0;
			}else{
				echo $conversions;
			}
			break;
		case 'zbs_conversion_rate':
			$conversions = get_post_meta( $post_id, 'zbs_form_conversions', true ); 
			$views = get_post_meta($post_id, 'zbs_form_views', true);
			if($conversions == ''){
				$conversions = 0;
			}
			if($views == 0){
				$rate = 0;
			}else{
				$rate = round(100*$conversions / $views,1);
			}
			echo $rate . "%";
			break;
	}
}



function add_zbs_form_columns($columns) {
	unset($columns['date']);
    return array_merge($columns, 
              array(
					'zbs_conversion_rate' => __w('Conversion Rate'),
					'zbs_conversions' =>__w( 'Conversions'),
              		'zbs_views' => __w('Views')
                    ));
}
add_filter('manage_zerobs_form_posts_columns' , 'add_zbs_form_columns');


function get_zbs_form_template($single_template) {
     global $post;

     if ($post->post_type == 'zerobs_form') {

          $single_template =  ZEROBSCRM_WILDURL . 'form-templates/simple.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_zbs_form_template' );

function zbs_form_func($atts){
	extract( shortcode_atts( array(
		'id' => 'true',
		'style' => 'simple'
	), $atts ) );
	switch($atts['style']){
		case 'simple':
			echo zbs_simple_form_html($atts['id']);
			zeroBSCRM_exposePID();
		break;
		case 'naked':
			echo zbs_naked_form_html($atts['id']);
			zeroBSCRM_exposePID();
		break;
		case 'cgrab':
			echo zbs_content_form_html($atts['id']);
			zeroBSCRM_exposePID();
		break;
		default:
			echo 'ZeroBS CRM Forms: You have not entered a style in the shortcode';
		break;
	}
}
add_shortcode('zbs_form','zbs_form_func');



add_action( 'widgets_init', function() { register_widget( 'ZBS_Form_Widget' ); } );
class ZBS_Form_Widget extends WP_Widget {
 
    
    public function __construct() {
        parent::__construct(
            'zbs_form_widget',             'ZBS Forms',             array( 'description' => __w( 'Embed a lead capture form to your website', 'zerobscrm' ), )         );
    }
 
    
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
 		$style = $instance['style'];
 		$id = $instance['id'];

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        if ( ! empty( $style) && ! empty($id)) {
			switch($style){
				case 'Simple':
					echo zbs_simple_form_html($id);
				break;
				case 'Naked':
					echo zbs_naked_form_html($id);
				break;
				case 'Contact':
					echo zbs_content_form_html($id);
				break;
				default:
					echo 'You have not entered a style in the widget';
				break;
			}
        }
        echo $after_widget;
    }
 
    
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __w( 'Contact us', 'zerobscrm' );
        }
        if ( isset( $instance[ 'style' ] ) ) {
            $style = $instance[ 'style' ];
        }
        else {
            $style = __w( 'Simple', 'zerobscrm' );
        }
        if ( isset( $instance[ 'id' ] ) ) {
            $id = $instance[ 'id' ];
        }
        else {
            $id = 0;
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _we( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

		<p>
	      <label for="<?php echo $this->get_field_id('text'); ?>">Style: 
	        <select class='widefat' id="<?php echo $this->get_field_id('style'); ?>"
	                name="<?php echo $this->get_field_name('style'); ?>" type="text">
	          <option value='Naked'<?php echo ($style=='Naked')?'selected':''; ?>>
	            Naked
	          </option>
	          <option value='Simple'<?php echo ($style=='Simple')?'selected':''; ?>>
	            Simple
	          </option> 
	          <option value='Contact'<?php echo ($style=='Contact')?'selected':''; ?>>
	            Contact
	          </option> 
	        </select>                
	      </label>
	     </p>

        <p>
        <label for="<?php echo $this->get_field_name( 'id' ); ?>"><?php _we( 'Form ID:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
        </p>


        <?php
    }
 
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['style'] = ( !empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';
	 	$instance['id'] = ( !empty( $new_instance['id'] ) ) ? strip_tags( $new_instance['id'] ) : '';
        return $instance;
    }
 
} 

function zbsCRM_FormHTMLHeader(){

	$reCaptcha = zeroBSCRM_getSetting('usegcaptcha');
	$reCaptchaKey = zeroBSCRM_getSetting('gcaptchasitekey');
	$reCaptchaSecret = zeroBSCRM_getSetting('gcaptchasitesecret');

	if ($reCaptcha && !empty($reCaptchaKey) && !empty($reCaptchaSecret)){

				echo "<script src='https://www.google.com/recaptcha/api.js'></script>";

				echo '<script type="text/javascript">var zbscrmReCaptcha = true;</script>';

	}

}
add_action('wp_head', 'zbsCRM_FormHTMLHeader');


function zbs_simple_form_html($formid){

		$content = '';
	if (!empty($formid)){

						$formcss = ZEROBSCRM_URL . 'css/ZeroBSCRM.public.frontendforms.min.css';
		$formjs = ZEROBSCRM_URL . 'js/ZeroBSCRM.public.leadform.min.js?ver=1.17';
		$formhandler =  esc_url( admin_url('admin-ajax.php') );
				$zbsForm = zeroBS_getForm($formid);

				if (is_array($zbsForm) && isset($zbsForm['meta'])){

						$zbsFormMeta = $zbsForm['meta'];

						$reCaptchaHTML = '';
			$reCaptcha = zeroBSCRM_getSetting('usegcaptcha');
			$reCaptchaKey = zeroBSCRM_getSetting('gcaptchasitekey');
			if ($reCaptcha && !empty($reCaptchaKey)) $reCaptchaHTML = '<div class="g-recaptcha" data-sitekey="'.$reCaptchaKey.'"></div>';


						$hideZBSpower = zeroBSCRM_getSetting('showpoweredby');

		    $content = null;    
		    ob_start();

			?>
			<script type='text/javascript' src='<?php echo $formjs; ?>'></script>
			<link rel='stylesheet' href='<?php echo $formcss; ?>' type='text/css' media='all' />
			<div class="zbscrmFrontEndForm" id="zbs_form_<?php echo $formid; ?>">
			    <div id="zbs_form_ajax_action" data-zbsformajax="<?php echo $formhandler; ?>"></div>
			    <div class="embed">
			        <div class="simple" style="border:0px !important">
			            <div class="content">
			                <h1><?php if(!empty($zbsFormMeta['header'])){ echo $zbsFormMeta['header']; }else{ echo "Want to find out more?"; } ?></h1>
			                <h3><?php if(!empty($zbsFormMeta['subheader'])){ echo $zbsFormMeta['subheader']; }else{ echo "Drop us a line. We follow up on all contacts"; } ?></h3>
			                <div class="form-wrapper zbsFormWrap">
			                    <input class="input" type="text" id="zbs_email" name="zbs_email" placeholder="<?php if(!empty($zbsFormMeta['email'])){ echo $zbsFormMeta['email']; }else{ echo "Email Address"; } ?>" value=""/>
			                        <input class="input" type="hidden" id="zbs_hpot_email" name="zbs_hpot_email" value=""/>
			                        <input class="input" type="hidden" class="zbs_form_view_id" id="zbs_form_view_id" name="zbs_form_id" value="<?php echo $formid; ?>" />
			                        <input class="input" type="hidden" id="zbs_form_style" name="zbs_form_style" value="zbs_simple" />
			                        <input type="hidden" name="action" value="zbs_lead_form">
			                        <div class="zbscrmReCaptcha"><?php echo $reCaptchaHTML; ?></div>
			                        <input class="send" type="submit" value="<?php if(!empty($zbsFormMeta['submit'])){ echo $zbsFormMeta['submit']; }else{ echo "Submit"; } ?>"/>
			                        <div class="clear"></div>
			                        <div class="trailer"><?php if(!empty($zbsFormMeta['spam'])){ echo $zbsFormMeta['spam']; }else{ echo "We will not send you spam. Our team will be in touch within 24 to 48 hours Mon-Fri (but often much quicker)"; } ?></div>
			                    </div>
			                <div class="zbsForm_success"><?php if(!empty($zbsFormMeta['success'])){ echo $zbsFormMeta['success']; }else{ echo "Thanks. We will be in touch."; } ?></div>
			              	<?php if($hideZBSpower){ ?>
			                <div class="zbs_poweredby" style="font-size:11px;">powered by: <a href="http://zerobscrm.com/" target="_blank">Zero BS CRM</a></div>
			            	<?php } ?>
			            </div>
			        </div>
			    </div>
			</div>
			<?php 
			$content = ob_get_contents();
			ob_end_clean();
		}

	}

	return $content;

}
function zbs_naked_form_html($formid){

		$content = '';
	if (!empty($formid)){

						$formcss = ZEROBSCRM_URL . 'css/ZeroBSCRM.public.frontendforms.min.css';
		$formjs = ZEROBSCRM_URL . 'js/ZeroBSCRM.public.leadform.min.js?ver=1.17';
		$formhandler =  esc_url( admin_url('admin-ajax.php') );
				$zbsForm = zeroBS_getForm($formid);

				if (is_array($zbsForm) && isset($zbsForm['meta'])){

						$zbsFormMeta = $zbsForm['meta'];

						$reCaptchaHTML = '';
			$reCaptcha = zeroBSCRM_getSetting('usegcaptcha');
			$reCaptchaKey = zeroBSCRM_getSetting('gcaptchasitekey');
			if ($reCaptcha && !empty($reCaptchaKey)) $reCaptchaHTML = '<div class="g-recaptcha" data-sitekey="'.$reCaptchaKey.'"></div>';

						$hideZBSpower = zeroBSCRM_getSetting('showpoweredby');

		    $content = null;    
		    ob_start();

				?>
				<script type='text/javascript' src='<?php echo $formjs; ?>'></script>
				<link rel='stylesheet' href='<?php echo $formcss; ?>' type='text/css' media='all' />
				<div class="zbscrmFrontEndForm" id="zbs_form_<?php echo $formid; ?>">
				    <div id="zbs_form_ajax_action" data-zbsformajax="<?php echo $formhandler; ?>"></div>
				    <div class="embed">
				        <div class="naked" style="border:0px !important">
				            <div class="content">
				                <div class="form-wrapper zbsFormWrap">
				                	<input class="input" type="text" id="zbs_fname" name="zbs_fname" placeholder="<?php if(!empty($zbsFormMeta['fname'])){ echo $zbsFormMeta['fname']; }else{ echo "First Name"; } ?>" value=""/>
				                    <input class="input" type="text" id="zbs_email" name="zbs_email" placeholder="<?php if(!empty($zbsFormMeta['email'])){ echo $zbsFormMeta['email']; }else{ echo "Email Address"; } ?>" value=""/>
				                        <input class="input" type="hidden" id="zbs_hpot_email" name="zbs_hpot_email" value=""/>
				                        <input class="input" type="hidden" class="zbs_form_view_id" id="zbs_form_view_id" name="zbs_form_id" value="<?php echo $formid; ?>" />
				                        <input class="input" type="hidden" id="zbs_form_style" name="zbs_form_style" value="zbs_naked" />
				                        <input type="hidden" name="action" value="zbs_lead_form">
			                        	<div class="zbscrmReCaptcha"><?php echo $reCaptchaHTML; ?></div>
				                        <input class="send" type="submit" value="<?php if(!empty($zbsFormMeta['submit'])){ echo $zbsFormMeta['submit']; }else{ echo "Submit"; } ?>"/>
				                        <div class="clear"></div>
				                    </div>
			                <div class="zbsForm_success"><?php if(!empty($zbsFormMeta['success'])){ echo $zbsFormMeta['success']; }else{ echo "Thanks. We will be in touch."; } ?></div>
			              	<?php if($hideZBSpower){ ?>
			                <div class="zbs_poweredby" style="font-size:11px;">powered by: <a href="http://zerobscrm.com/" target="_blank">Zero BS CRM</a></div>
			            	<?php } ?>
				            </div>
				        </div>
				    </div>
				</div>
				<?php 
			$content = ob_get_contents();
			ob_end_clean();

		}

	}

	return $content;
}
function zbs_content_form_html($formid){

		$content = '';
	if (!empty($formid)){

						$formcss = ZEROBSCRM_URL . 'css/ZeroBSCRM.public.frontendforms.min.css';
		$formjs = ZEROBSCRM_URL . 'js/ZeroBSCRM.public.leadform.min.js?ver=1.17';
		$formhandler =  esc_url( admin_url('admin-ajax.php') );
				$zbsForm = zeroBS_getForm($formid);

				if (is_array($zbsForm) && isset($zbsForm['meta'])){

						$zbsFormMeta = $zbsForm['meta'];

						$reCaptchaHTML = '';
			$reCaptcha = zeroBSCRM_getSetting('usegcaptcha');
			$reCaptchaKey = zeroBSCRM_getSetting('gcaptchasitekey');
			if ($reCaptcha && !empty($reCaptchaKey)) $reCaptchaHTML = '<div class="g-recaptcha" data-sitekey="'.$reCaptchaKey.'"></div>';

						$hideZBSpower = zeroBSCRM_getSetting('showpoweredby');

		    $content = null;    
		    ob_start();

				?>
				<script type='text/javascript' src='<?php echo $formjs; ?>'></script>
				<link rel='stylesheet' href='<?php echo $formcss; ?>' type='text/css' media='all' />
				<div class="zbscrmFrontEndForm" id="zbs_form_<?php echo $formid; ?>">
				    <div id="zbs_form_ajax_action" data-zbsformajax="<?php echo $formhandler; ?>"></div>
				    <div class="embed">
				        <div class="cgrab" style="border:0px !important">
				            <div class="content">
				                <h1><?php if(!empty($zbsFormMeta['header'])){ echo $zbsFormMeta['header']; }else{ echo "Want to find out more?"; } ?></h1>
				                <h3><?php if(!empty($zbsFormMeta['subheader'])){ echo $zbsFormMeta['subheader']; }else{ echo "Drop us a line. We follow up on all contacts"; } ?></h3>
				                <div class="form-wrapper zbsFormWrap">
				                    <input class="input" type="text" id="zbs_fname" name="zbs_fname" placeholder="<?php if(!empty($zbsFormMeta['fname'])){ echo $zbsFormMeta['fname']; }else{ echo "First Name"; } ?>" value=""/>
				                    <input class="input" type="text" id="zbs_lname" name="zbs_lname" placeholder="<?php if(!empty($zbsFormMeta['lname'])){ echo $zbsFormMeta['lname']; }else{ echo "Last Name"; } ?>" value=""/>
				                    <input class="input" type="text" id="zbs_email" name="zbs_email" placeholder="<?php if(!empty($zbsFormMeta['email'])){ echo $zbsFormMeta['email']; }else{ echo "Email Address"; } ?>" value=""/>
				                    <textarea class="textarea" id="zbs_notes" name="zbs_notes" placeholder="<?php if(!empty($zbsFormMeta['notes'])){ echo $zbsFormMeta['notes']; }else{ echo "Your Message"; } ?>"></textarea>
				                    <input class="input" type="hidden" id="zbs_hpot_email" name="zbs_hpot_email" value=""/>
				                    <input class="input" type="hidden" class="zbs_form_view_id" id="zbs_form_view_id" name="zbs_form_id" value="<?php echo $formid; ?>" />
				                    <input class="input" type="hidden" id="zbs_form_style" name="zbs_form_style" value="zbs_cgrab" />
				                    <input type="hidden" name="action" value="zbs_lead_form">
			                        <div class="zbscrmReCaptcha"><?php echo $reCaptchaHTML; ?></div>
				                    <input class="send" type="submit" value="<?php if(!empty($zbsFormMeta['submit'])){ echo $zbsFormMeta['submit']; }else{ echo "Submit"; } ?>"/>
				                    <div class="clear"></div>
				                    <div class="trailer"><?php if(!empty($zbsFormMeta['spam'])){ echo $zbsFormMeta['spam']; }else{ echo "We will not send you spam. Our team will be in touch within 24 to 48 hours Mon-Fri (but often much quicker)"; } ?></div>
				                    </div>
				                <div class="zbsForm_success"><?php if(!empty($zbsFormMeta['success'])){ echo $zbsFormMeta['success']; }else{ echo "Thanks. We will be in touch."; } ?></div>
				              	<?php if($hideZBSpower){ ?>
				                <div class="zbs_poweredby" style="font-size:11px;">powered by: <a href="http://zerobscrm.com/" target="_blank">Zero BS CRM</a></div>
				            	<?php } ?>
				            </div>
				        </div>
				    </div>
				</div>
				<?php 
			$content = ob_get_contents();
			ob_end_clean();

		}

	}

	return $content;
}









		define('ZBSCRM_INC_FORMS',true);