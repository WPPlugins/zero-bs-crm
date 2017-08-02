<?php /* */
if ( ! defined( 'ABSPATH' ) ) exit;

            include( dirname(dirname(dirname(dirname( dirname ( __FILE__ ))) ))."/wp-config.php" ); 
    $formcss = ZEROBSCRM_URL . 'css/ZeroBSCRM.admin.frontform.css';
    $formjs = ZEROBSCRM_URL . 'js/ZeroBSCRM.leadform.js?ver=1.17';
    $formid = (int)sanitize_text_field($_GET['fid']);
    $formhandler =  esc_url( admin_url('admin-ajax.php') );
            $zbsForm = zeroBS_getForm($formid);

        if (is_array($zbsForm) && isset($zbsForm['meta'])){

?><!DOCTYPE html>
<html lang="en-US" class="no-js">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='robots' content='noindex,nofollow' />

    <title>ZBSCRM Form</title>

    <?php wp_print_scripts(); ?>
    <script type='text/javascript' src='<?php echo $formjs; ?>'></script>
    <link rel='stylesheet' href='<?php echo $formcss; ?>' type='text/css' media='all' />
    <?php 
                zbsCRM_FormHTMLHeader();
    ?>

</head><body>

<?php 

        echo zbs_simple_form_html($formid); 

?>

</body></html><?php } 

        exit();

?>