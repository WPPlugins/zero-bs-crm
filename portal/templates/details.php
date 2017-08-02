<?php /* *
 * Invoices Template.
 *
 * This template can be overriden by copying this file to your-theme/zerobscrm-plugin-templates/invoices.php
 * It uses the DEFAULT styles of the Theme. Extra style packs are available from 
 * https://zerobscrm.com/extensions/
 *
 * @author 		Mike Stott
 * @package 	Customer Portal
 * @version     1.2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; add_action( 'wp_enqueue_scripts', 'zeroBS_portal_enqueue_stuff' );
get_header();


if($_POST['save'] == 1){
		$uid = get_current_user_id();
		$cID = zeroBS_getCustomerIDFromWPID($uid);
		if((int)$_POST['customer_id'] == $cID){	
			$zbsCustomerMeta = zeroBS_buildCustomerMeta($_POST);
	        update_post_meta($cID, 'zbs_customer_meta', $zbsCustomerMeta);
	        echo "<div class='zbs_alert'>" . __w("Details Updated","zerobscrm") . "</div>";
		}
}


?>
<div class="zbs-client-portal-wrap">
    <?php
                zeroBS_portalnav('details');
    ?>

    <div class='zbs-portal-wrapper'>
	<form action="#" name="zbs-update-deets" method="POST">


<?php
	global $wpdb, $zbsCustomerFields;
    $fields = $zbsCustomerFields;

	$uid = get_current_user_id();
	$cID = zeroBS_getCustomerIDFromWPID($uid);
	?>
			<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $cID; ?>" />
	<?php


	$zbsCustomer = get_post_meta($cID,'zbs_customer_meta',true);
	$haystack = array('status', 'notes','cf1');   
                    foreach ($fields as $fieldK => $fieldV){

                    	if(in_array($fieldK, $haystack)){
                    		$showField = false;
                    	}else{
                        	$showField = true;
						}
                                                if (isset($fieldV['opt']) && (!isset($zbsFieldsEnabled[$fieldV['opt']]) || !$zbsFieldsEnabled[$fieldV['opt']])) $showField = false;

                                                if ($showField) {

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


                            switch ($fieldV[0]){

                                case 'text':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control widetext" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'price':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <?php echo zeroBSCRM_getCurrencyChr(); ?> <input style="width: 130px;display: inline-block;;" type="text" name="zbscq_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control  numbersOnly" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;


                                case 'date':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-date" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'select':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
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
                                    </td></tr><br/><?php

                                    break;

                                case 'tel':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-tel" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'email':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <input type="text" name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control zbs-email" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" value="<?php if (isset($zbsCustomer[$fieldK])) echo $zbsCustomer[$fieldK]; ?>" autocomplete="off" />
                                    </td></tr><?php

                                    break;

                                case 'textarea':

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
                                    <td>
                                        <textarea name="zbsc_<?php echo $fieldK; ?>" id="<?php echo $fieldK; ?>" class="form-control" placeholder="<?php if (isset($fieldV[2])) echo $fieldV[2]; ?>" autocomplete="off"><?php if (isset($zbsCustomer[$fieldK])) echo zeroBSCRM_textExpose($zbsCustomer[$fieldK]); ?></textarea>
                                    </td></tr><?php

                                    break;

                                                                case 'selectcountry':

                                    $countries = zeroBSCRM_loadCountryList();

                                    ?><tr class="wh-large"><th><label for="<?php echo $fieldK; ?>"><?php echo $fieldV[1]; ?>:</label></th>
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


                    }




?>
	<input type="hidden" id="save" name="save" value="1"/>	
	<br/><br/>
	<input type="submit" id="submit" value="<?php _we('Submit','zerobscrm');?>"/>
	</form>


	</div>
</div>

<?php get_footer(); ?>